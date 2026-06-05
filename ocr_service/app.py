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

# Configuración de Seguridad
API_KEY_SECRET = "Andrufar2026_Secure_OCR_Token_#!"

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_methods=["*"],
    allow_headers=["*"],
)

print("Cargando modelo OCR... (Esto toma unos segundos la primera vez)")
reader = easyocr.Reader(['es', 'en'], gpu=False, model_storage_directory='./models', download_enabled=True)
print("¡Modelo OCR listo para escanear a toda velocidad!")

class ImagenRequest(BaseModel):
    imagen: str

# =========================
#  EXTRAER LOTE (MEJORADO)
# =========================
def extraer_lote(texto, fragmentos=[]):
    texto_upper = texto.upper()

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

    # Buscar en texto completo
    for patron in patrones:
        match = re.search(patron, texto_upper)
        if match:
            lote = match.group(1).strip()
            lote = re.sub(r'\s+', '', lote)
            if len(lote) >= 4:
                return lote

    # Buscar fragmento por fragmento
    for frag in fragmentos:
        frag_upper = frag.upper()
        for patron in patrones:
            match = re.search(patron, frag_upper)
            if match:
                lote = match.group(1).strip()
                lote = re.sub(r'\s+', '', lote)
                if len(lote) >= 4:
                    return lote

    # Fallback: número de 6-10 dígitos
    matches = re.findall(r'\b(\d{6,10})\b', texto_upper)
    for m in matches:
        return m

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

# =========================
# EXTRAER FECHA (MEJORADO)
# =========================
def extraer_fecha(texto, fragmentos=[]):
    texto_upper = texto.upper()

    meses = {
        'ENE': '01', 'FEB': '02', 'MAR': '03', 'ABR': '04', 'MAY': '05', 'JUN': '06',
        'JUL': '07', 'AGO': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DIC': '12',
        'JAN': '01', 'APR': '04', 'AUG': '08', 'DEC': '12',
    }

    patrones = [
        # EXP: JUN 2026 (espacio entre mes y año) NUEVO
        r'\b(?:EXP|VENCE|CAD|CADUCIDAD|EXPIRY|USE\s*BY)\s*[:\-]?\s*([A-Z]{3})\s+(\d{4})',
        # EXP: JUN/2026 o EXP: JUN-2026
        r'\b(?:EXP|VENCE|VENCIMIENTO|CAD|CADUCIDAD)\.?\s*:\s*([A-Z]{3})[\/\-](\d{4})',
        # EXP: 03-2029 o V: 03/2029
        r'\b(?:EXP|V|VENCE|VENCIMIENTO|CAD|CADUCIDAD|USE\s*BY|EXPIRY)\.?\s*[:\-]?\s*(\d{2})[\/\-](\d{4})',
        # EXP: 03/06/2029
        r'\b(?:EXP|VENCE|VENCIMIENTO|CAD|CADUCIDAD)\.?\s*[:\-]?\s*(\d{2})[\/\-](\d{2})[\/\-](\d{4})',
        # Fecha sola MM/YYYY
        r'\b(\d{2})[\/\-](\d{4})\b',
        # Fecha sola DD/MM/YYYY
        r'\b(\d{2})[\/\-](\d{2})[\/\-](\d{4})\b',
    ]

    def procesar_match(match):
        grupos = match.groups()
        if len(grupos) == 2 and grupos[0].upper() in meses:
            mes_num = meses[grupos[0].upper()]
            año = grupos[1]
            return f"{mes_num}/{año}"
        elif len(grupos) >= 2:
            fecha = '/'.join(grupos)
            fecha = fecha.replace('-', '/')
            return fecha
        return None

    # Buscar en texto completo
    for patron in patrones:
        match = re.search(patron, texto_upper)
        if match:
            resultado = procesar_match(match)
            if resultado:
                return resultado

    # Buscar fragmento por fragmento
    for frag in fragmentos:
        frag_upper = frag.upper()
        for patron in patrones:
            match = re.search(patron, frag_upper)
            if match:
                resultado = procesar_match(match)
                if resultado:
                    return resultado

    return None

# =========================
#  PREPROCESAMIENTO EN CASCADA
# =========================
def preprocesar_imagen(img):
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
    gray = cv2.resize(gray, None, fx=3, fy=3, interpolation=cv2.INTER_CUBIC)  # ← subimos de 2x a 3x
    
    # V1: Invertida primero — texto claro sobre fondo oscuro
    _, otsu = cv2.threshold(gray, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)
    v1 = cv2.bitwise_not(otsu)

    # V2: CLAHE — mejora contraste local
    clahe = cv2.createCLAHE(clipLimit=3.0, tileGridSize=(8,8))
    eq = clahe.apply(gray)
    _, v2 = cv2.threshold(eq, 0, 255, cv2.THRESH_BINARY + cv2.THRESH_OTSU)

    # V3: Adaptativo sobre imagen equalizada
    eq2 = cv2.equalizeHist(gray)
    v3 = cv2.adaptiveThreshold(eq2, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY, 11, 2)

    return [v1, v2, v3]

# =========================
#  ENDPOINT PRINCIPAL
# =========================
@app.post("/detectar")
async def detectar(request: ImagenRequest, x_api_key: str = Header(None)):
    if x_api_key != API_KEY_SECRET:
        raise HTTPException(status_code=403, detail="Acceso denegado: API Key inválida")

    try:
        img_data = request.imagen
        if ',' in img_data:
            img_data = img_data.split(',')[1]

        img_bytes = base64.b64decode(img_data)
        img_array = np.frombuffer(img_bytes, np.uint8)
        img = cv2.imdecode(img_array, cv2.IMREAD_COLOR)

        if img is None:
            return {"success": False, "mensaje": "Imagen inválida"}

        # Obtener las 3 versiones del preprocesamiento
        imagenes = preprocesar_imagen(img)

        lote_encontrado = None
        fecha_encontrada = None
        textos_detectados = []
        confianzas = []
        duracion_ms = 0
        version_usada = 0

        start_time = time.perf_counter()

        #  Intentar V1 primero, luego V2, luego V3
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

            # Guardar si encontró más info que el intento anterior
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

            # Si ya encontró ambos, no seguir intentando
            if lote_encontrado and fecha_encontrada:
                version_usada = i + 1
                break

        end_time = time.perf_counter()
        duracion_ms = round((end_time - start_time) * 1000, 2)

        texto_final = ' '.join(textos_detectados)
        confianza_promedio = np.mean(confianzas) if confianzas else 0

        # Diagnóstico en consola
        print(f"=== DIAGNÓSTICO ===")
        print(f"Versión de preprocesamiento usada: V{version_usada}")
        print(f"Texto completo leído: '{texto_final}'")
        print(f"Fragmentos: {textos_detectados}")
        print(f"Lote extraído: '{lote_encontrado}'")
        print(f"Fecha extraída: '{fecha_encontrada}'")
        print(f"Duración: {duracion_ms}ms")
        print(f"==================")

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
    return {"status": "online", "service": "Andrufar OCR Intelligence Optimizada"}