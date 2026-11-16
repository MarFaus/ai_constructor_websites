@echo off
chcp 65001 >nul
setlocal enabledelayedexpansion
cd /d "%~dp0"
title ИИ-конструктор сайтов
echo ==============================================
echo   ИИ-конструктор сайтов — запуск
echo ==============================================

rem --- PHP: сначала папка php\ рядом с проектом, потом системный
set "PHP=php"
if exist "%~dp0php\php.exe" set "PHP=%~dp0php\php.exe"

"%PHP%" -v >nul 2>&1
if errorlevel 1 (
  echo [ОШИБКА] PHP не найден.
  echo Установите PHP 8.3 или новее и добавьте его в PATH
  echo либо положите распакованный PHP в папку php рядом с этим файлом.
  echo Скачать: https://windows.php.net/download/
  pause
  exit /b 1
)

"%PHP%" -r "exit(version_compare(PHP_VERSION,'8.3.0','>=')?0:1);"
if errorlevel 1 (
  echo [ОШИБКА] Нужен PHP 8.3 или новее. Ваша версия:
  "%PHP%" -v
  pause
  exit /b 1
)

rem --- нужные расширения (проверяет сам PHP)
set "MISSING="
for /f "usebackq delims=" %%M in (`"%PHP%" -r "foreach(['pdo_sqlite','sqlite3','mbstring','openssl','fileinfo','ctype','tokenizer'] as $e){if(!extension_loaded($e))echo $e,' ';}"`) do set "MISSING=%%M"
if defined MISSING (
  echo [ОШИБКА] В php.ini не включены расширения: !MISSING!
  echo Какой php.ini используется:
  "%PHP%" --ini
  echo Откройте этот php.ini и уберите точку с запятой перед нужными extension=...
  echo Если php.ini нет, скопируйте php.ini-development в php.ini.
  pause
  exit /b 1
)

rem --- папка vendor (библиотеки). Если её нет, ставим через Composer
if not exist "vendor\autoload.php" (
  echo Папка vendor не найдена, устанавливаю библиотеки...
  where composer >nul 2>&1
  if not errorlevel 1 (
    call composer install --no-dev --no-interaction
  ) else (
    if not exist "composer.phar" "%PHP%" -r "copy('https://getcomposer.org/download/latest-stable/composer.phar','composer.phar');"
    "%PHP%" composer.phar install --no-dev --no-interaction
  )
  if not exist "vendor\autoload.php" (
    echo [ОШИБКА] Не удалось установить библиотеки. Проверьте интернет и повторите.
    pause
    exit /b 1
  )
)

rem --- первый запуск: .env, ключ, база
if not exist ".env" (
  echo Первый запуск: создаю настройки...
  copy /y ".env.example" ".env" >nul
  "%PHP%" artisan key:generate --force
)
if not exist "database\database.sqlite" type nul > "database\database.sqlite"

"%PHP%" artisan migrate --force
if errorlevel 1 (
  echo [ОШИБКА] Не удалось подготовить базу данных.
  pause
  exit /b 1
)

echo.
echo Сайт открывается: http://127.0.0.1:8000
echo Чтобы остановить сервер, закройте это окно или нажмите Ctrl+C.
echo.
start "" http://127.0.0.1:8000
"%PHP%" artisan serve --host=127.0.0.1 --port=8000
pause
