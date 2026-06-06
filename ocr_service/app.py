from fastapi import FastAPI, Header, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import easyocr
import time
import base64
import numpy as np
import cv2
import re

app = FastAPI()

# Configuración de Seguridad para restringir el consumo externo
API_KEY_SECRET = "Andrufar2026_Secure_OCR_Token_#!"

# Habilitar CORS para permitir solicitudes del panel administrador (Laravel) y frontend
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

print("Cargando modelo OCR... (Esto toma unos segundos la primera vez)")
# Carga inicial del modelo EasyOCR para español ('es') e inglés ('en')
# Se desactiva la aceleración GPU por compatibilidad en servidores sin tarjeta gráfica dedicada
reader = easyocr.Reader(['es', 'en'], gpu=False, model_storage_directory='./models', download_enabled=True)
print("¡Modelo OCR listo para escanear a toda velocidad!")

class ImagenRequest(BaseModel):
    """
    Esquema de validación para las solicitudes entrantes al microservicio.
    Contiene la imagen codificada en formato Base64.
    """
    imagen: str

def extraer_lote(texto, fragmentos=[]):
    """
    Analiza una cadena de texto y sus fragmentos para detectar códigos de lote usando expresiones regulares.
    
    Parámetros:
        texto (str): Cadena completa de texto consolidado por el OCR.
        fragmentos (list): Lista de fragmentos o palabras individuales identificadas en la imagen.
        
    Retorna:
        str o None: El código de lote formateado si se detecta con éxito, de lo contrario None.
    """
    texto_upper = texto.upper()

    # Patrones comunes de identificación de lotes en empaques farmacéuticos
    patrones = [
        r'\bL\s*:\s*([A-Z0-9\-]{4,15})',
        r'\bL\s+([0-9\-]{4,15})',
        r'\bLOTE\s*N[°º0oO]?\s*[:\-]?\s*([A-Z0-9\-]{4,15})',
        r'\bLOTE\s*[:\-]\s*([A-Z0-9\-]{4,15})',
        r'\bLOTE\s*:\s*([A-Z0-9\-]{4,15})',
        r'\bLOTE\s+([A-Z0-9\-]{4,15})',
        r'\bLOT\s*[:\-]?\s*([A-Z0-9\-]{4,15})',
        r'\bBATCH\s*N?[°º]?\s*[:\-]?\s*([A-Z0-9\-]{4,15})',
    ]

    # 1. Buscar coincidencia en el texto completo consolidado
    for patron in patrones:
        match = re.search(patron, texto_upper)
        if match:
            lote = match.group(1).strip()
            lote = re.sub(r'\s+', '', lote)  # Limpiar espacios internos
            if len(lote) >= 4:
                return lote

    # 2. Buscar fragmento por fragmento si la cadena consolidada falló
    for frag in fragmentos:
        frag_upper = frag.upper()
        for patron in patrones:
            match = re.search(patron, frag_upper)
            if match:
                lote = match.group(1).strip()
                lote = re.sub(r'\s+', '', lote)
                if len(lote) >= 4:
                    return lote

    # Fallback 1: Buscar números de 6 a 10 dígitos (típicos de lotes numéricos puros)
    matches = re.findall(r'\b(\d{6,10})\b', texto_upper)
    for m in matches:
        return m

    # Fallback 2: Buscar palabras alfanuméricas de 5 a 15 caracteres omitiendo palabras clave comunes
    matches = re.findall(r'\b([A-Z0-9]{5,15})\b', texto_upper)
    palabras_ignorar = {
        'EXP', 'FAB', 'MFG', 'MED', 'REG', 'TAB', 'CAP', 'MG', 'ML',
        'LOTE', 'VENCE', 'FECHA', 'PROD', 'LABORATORIO', 'PRODUCTO'
    }
    for m in matches:
        if m not in palabras_ignorar and not m.isalpha():
            lote = re.sub(r'[^A-Z0-9]', '', m)
            if len(lote) >= 4:
                return lote

    return None

def extraer_fecha(texto, fragmentos=[]):
    """
    Analiza y detecta fechas de vencimiento (vence, exp, caducidad) en el texto de la imagen.
    Realiza la conversión de nombres de meses en español o inglés a su equivalente numérico.
    
    Parámetros:
        texto (str): Cadena completa de texto del OCR.
        fragmentos (list): Lista de fragmentos detectados.
        
    Retorna:
        str o None: Fecha formateada como MM/YYYY o DD/MM/YYYY, o None en caso contrario.
    """
    texto_upper = texto.upper()

    # Mapeo de meses abreviados comunes a su equivalente en dos dígitos
    meses = {
        'ENE': '01', 'FEB': '02', 'MAR': '03', 'ABR': '04', 'MAY': '05', 'JUN': '06',
        'JUL': '07', 'AGO': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DIC': '12',
        'JAN': '01', 'APR': '04', 'AUG': '08', 'DEC': '12',
    }

    # Expresiones regulares para capturar fechas con texto indicador o formatos numéricos
    patrones = [
        # Ejemplo: EXP: JUN 2026
        r'\b(?:EXP|VENCE|CAD|CADUCIDAD|EXPIRY|USE\s*BY)\s*[:\-]?\s*([A-Z]{3})\s+(\d{4})',
        # Ejemplo: EXP: JUN/2026 o EXP: JUN-2026
        r'\b(?:EXP|VENCE|VENCIMIENTO|CAD|CADUCIDAD)\.?\s*:\s*([A-Z]{3})[\/\-](\d{4})',
        # Ejemplo: EXP: 03-2029 o V: 03/2029
        r'\b(?:EXP|V|VENCE|VENCIMIENTO|CAD|CADUCIDAD|USE\s*BY|EXPIRY)\.?\s*[:\-]?\s*(\d{2})[\/\-](\d{4})',
        # Ejemplo: EXP: 03/06/2029
        r'\b(?:EXP|VENCE|VENCIMIENTO|CAD|CADUCIDAD)\.?\s*[:\-]?\s*(\d{2})[\/\-](\d{2})[\/\-](\d{4})',
        # Formatos simples sin palabras clave: MM/YYYY o MM-YYYY
        r'\b(\d{2})[\/\-](\d{4})\b',
        # Formatos simples sin palabras clave: DD/MM/YYYY o DD-MM-YYYY
        r'\b(\d{2})[\/\-](\d{2})[\/\-](\d{4})\b',
    ]

    def procesar_match(match):
        """
        Función auxiliar para normalizar los grupos capturados por la expresión regular.
        """
        grupos = match.groups()
        # Si capturó nombre de mes y año (ej. JUN, 2026)
        if len(grupos) == 2 and grupos[0].upper() in meses:
            mes_num = meses[grupos[0].upper()]
            año = grupos[1]
            return f"{mes_num}/{año}"
        # Si capturó números de fecha estructurados
        elif len(grupos) >= 2:
            fecha = '/'.join(grupos)
            fecha = fecha.replace('-', '/')
            return fecha
        return None

    # 1. Buscar en el texto completo consolidado
    for patron in patrones:
        match = re.search(patron, texto_upper)
        if match:
            resultado = procesar_match(match)
            if resultado:
                return resultado

    # 2. Buscar en cada fragmento del OCR por separado
    for frag in fragmentos:
        frag_upper = frag.upper()
        for patron in patrones:
            match = re.search(patron, frag_upper)
            if match:
                resultado = procesar_match(match)
                if resultado:
                    return resultado

    return None

def preprocesar_imagen(img):
    """
    Aplica una cascada de 3 variantes de filtros OpenCV sobre la imagen para maximizar la legibilidad
    del texto bajo distintas condiciones de luz y contraste de fondo.
    
    Parámetros:
        img: Imagen en formato BGR de OpenCV.
        
    Retorna:
        list: Lista con las 3 imágenes procesadas [v1, v2, v3].
            - v1 (Otsu Invertido): Ideal para textos claros impresos sobre fondo oscuro.
            - v2 (CLAHE): Mejora el contraste local ideal para superficies brillantes o empaques plásticos.
            - v3 (Umbral Adaptativo): Resuelve problemas de iluminación no uniforme y sombras.
    """
    # Escala de grises y redimensionado de 3x con interpolación cúbica para mejorar nitidez de caracteres pequeños
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    gray = cv2.resize(gray, None, fx=3, fy=3, interpolation=cv2.INTER_CUBIC)

    # V1: Umbralización de Otsu e inversión binaria (Texto claro sobre fondo oscuro)
    _, otsu = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    v1 = cv2.bitwise_not(otsu)

    # V2: Ecualización de Contraste Histograma Adaptativo Local (CLAHE) y posterior binarización Otsu
    clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8,8))
    eq = clahe.apply(gray)
    _, v2 = cv2.threshold(eq, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

    # V3: Umbralización Adaptativa Gaussiana sobre histograma ecualizado globalmente
    eq2 = cv2.equalizeHist(gray)
    v3 = cv2.adaptiveThreshold(eq2, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 11, 2)

    return [v1, v2, v3]

@app.post("/detectar")
async def detectar(request: ImagenRequest, x_api_key: str = Header(None)):
    """
    Endpoint principal para realizar el análisis de reconocimiento de texto sobre la imagen provista.
    
    El flujo de ejecución es:
    1. Validar la clave de API recibida en la cabecera HTTP.
    2. Decodificar la imagen en Base64 y convertirla a una matriz de OpenCV.
    3. Obtener los 3 filtros de imagen en cascada (Otsu Invertida, CLAHE, Adaptativa).
    4. Ejecutar el lector de OCR secuencialmente sobre cada filtro hasta conseguir extraer el Lote y Fecha de Vencimiento.
    5. Calcular la confianza media y el nivel de legibilidad de la lectura.
    6. Retornar los metadatos de la detección en formato JSON.
    """
    # Comprobación de seguridad
    if x_api_key != API_KEY_SECRET:
        raise HTTPException(status_code=403, detail="Acceso denegado: API Key inválida")

    try:
        # Decodificar imagen desde cadena base64
        img_data = request.imagen
        if ',' in img_data:
            img_data = img_data.split(',')[1]

        img_bytes = base64.b64decode(img_data)
        img_array = np.frombuffer(img_bytes, np.uint8)
        img = cv2.imdecode(img_array, cv2.IMREAD_COLOR)

        if img is None:
            return {"success": False, "mensaje": "Imagen inválida"}

        # Generar las 3 imágenes preprocesadas (cascada de filtros)
        imagenes = preprocesar_imagen(img)

        lote_encontrado = None
        fecha_encontrada = None
        textos_detectados = []
        confianzas = []
        duracion_ms = 0
        version_usada = 0

        start_time = time.perf_counter()

        # Ejecutar OCR iterando secuencialmente por cada filtro
        for i, img_proc in enumerate(imagenes):
            resultados = reader.readtext(
                img_proc,
                detail=1,
                paragraph=False,
                width_ths=0.7,
                text_threshold=0.3,
                canvas_size=720
            )

            textos_temp = []
            confianzas_temp = []

            for (coords, texto_fragmento, confianza) in resultados:
                confianzas_temp.append(confianza)
                if confianza >= 0.25:
                    textos_temp.append(texto_fragmento)

            texto_temp = ' '.join(textos_temp)
            lote_temp = extraer_lote(texto_temp, textos_temp)
            fecha_temp = extraer_fecha(texto_temp, textos_temp)

            # Conservar los resultados del filtro si mejoraron la lectura de lote o fecha
            if lote_temp and not lote_encontrado:
                lote_encontrado = lote_temp
                textos_detectados = textos_temp
                confianzas = confianzas_temp
                version_usada = i + 1

            if fecha_temp and not fecha_encontrada:
                fecha_encontrada = fecha_temp
                if not textos_detectados:
                    textos_detectados = textos_temp
                    confianzas = confianzas_temp
                    version_usada = i + 1

            # Si ya se encontraron ambos campos críticos, se rompe el ciclo para ahorrar recursos y tiempo
            if lote_encontrado and fecha_encontrada:
                version_usada = i + 1
                break

        end_time = time.perf_counter()
        duracion_ms = round((end_time - start_time) * 1000, 2)

        texto_final = ' '.join(textos_detectados)
        confianza_promedio = np.mean(confianzas) if confianzas else 0

        # Diagnóstico por consola del servidor Python
        print(f"=== DIAGNÓSTICO ===")
        print(f"Versión de preprocesamiento usada: V{version_usada}")
        print(f"Texto completo leído: '{texto_final}'")
        print(f"Fragmentos: {textos_detectados}")
        print(f"Lote extraído: '{lote_encontrado}'")
        print(f"Fecha extraída: '{fecha_encontrada}'")
        print(f"Duración: {duracion_ms}ms")
        print(f"==================")

        # Clasificación del nivel de fiabilidad/legibilidad de la lectura en base a la confianza media del OCR
        if confianza_promedio >= 0.80:
            nivel, estado = "Alta", "Verificado"
        elif confianza_promedio >= 0.45:
            nivel, estado = "Media", "Sujeto a revisión"
        else:
            nivel, estado = "Baja", "No legible"

        return {
            "success": True,
            "lote": lote_encontrado,
            "fecha": fecha_encontrada,
            "confianza": round(float(confianza_promedio), 2),
            "confianza_nivel": nivel,
            "confianza_estado": estado,
            "metadata": {
                "motor": "EasyOCR 1.7 (Modo Optimo)",
                "procesamiento": f"Cascada V{version_usada}/3",
                "duracion_ms": duracion_ms
            }
        }

    except Exception as e:
        return {"success": False, "mensaje": str(e)}

@app.get("/")
async def root():
    """
    Ruta raíz para comprobar el estado de salud del servidor OCR FastAPI.
    """
    return {"status": "online", "service": "Andrufar OCR Intelligence Optimizada"}