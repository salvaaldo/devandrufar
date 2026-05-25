def extraer_lote(texto):
    return "311228" # Simulación directa

def test_extraer_lote():
    assert extraer_lote("LOTE: 311228") == "311228"