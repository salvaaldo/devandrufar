import sys
import os

# Fuerza a Python a mirar en la carpeta actual para encontrar 'app.py'
sys.path.insert(0, os.path.abspath(os.path.dirname(__file__)))

# Ahora importamos directamente 'app' (ya que estamos dentro de ocr_service)
from app import extraer_lote, extraer_fecha

def test_extraer_lote_valido():
    texto = "Este es un medicamento con LOTE: 311228 y fecha"
    lote = extraer_lote(texto)
    assert lote == "311228"

def test_extraer_fecha_valida():
    texto = "Producto vencimiento EXP: 05/2027"
    fecha = extraer_fecha(texto)
    assert fecha == "05/2027"

def test_lote_no_encontrado():
    texto = "Texto sin informacion relevante"
    lote = extraer_lote(texto)
    assert lote is None