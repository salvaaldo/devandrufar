# test_ocr.py
from logica_ocr import extraer_lote, extraer_fecha

def test_lote():
    assert extraer_lote("cualquier cosa") == "TEST_LOTE"

def test_fecha():
    assert extraer_fecha("cualquier cosa") == "TEST_FECHA"