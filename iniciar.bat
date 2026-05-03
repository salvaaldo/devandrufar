@echo off
echo ========================================
echo    INICIANDO SISTEMA ANDRUFAR 4
echo ========================================

echo [1/3] Iniciando Laravel...
start "Laravel" cmd /k "cd /d C:\xampp\htdocs\andrufar4 && php artisan serve"

timeout /t 2 /nobreak >nul

echo [2/3] Iniciando Vite...
start "Vite" cmd /k "cd /d C:\xampp\htdocs\andrufar4 && npm run dev"

timeout /t 2 /nobreak >nul

echo [3/3] Iniciando OCR Python...
start "OCR Service" cmd /k "cd /d C:\xampp\htdocs\andrufar4\ocr_service && venv\Scripts\activate && uvicorn app:app --host 0.0.0.0 --port 5000 --reload"

timeout /t 3 /nobreak >nul

echo ========================================
echo    Sistema iniciado correctamente!
echo    Abre: http://localhost:8000
echo ========================================

start http://localhost:8000

pause