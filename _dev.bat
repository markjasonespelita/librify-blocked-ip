@echo off
REM =============================================
REM  Dynamic Project Starter: Git Bash + CMD + WSL + XAMPP
REM  Place this file in your project root
REM =============================================

REM Get folder where this .bat file is located
set "PROJECT_ROOT=%~dp0"
if "%PROJECT_ROOT:~-1%"=="\" set "PROJECT_ROOT=%PROJECT_ROOT:~0,-1%"

echo Starting development environment in %PROJECT_ROOT%...
echo.

REM --------------------------
REM Open Git Bash
REM --------------------------
start "" "C:\Program Files\Git\git-bash.exe" --cd="%PROJECT_ROOT%"

REM --------------------------
REM Open CMD
REM --------------------------
start "" cmd /k "cd /d %PROJECT_ROOT%"

REM --------------------------
REM Open WSL Ubuntu (optional)
REM --------------------------
REM Comment out if not needed
REM start "" wsl -d Ubuntu -e bash -c "cd $(wslpath '%PROJECT_ROOT%'); exec bash"

REM --------------------------
REM Start XAMPP Control Panel
REM --------------------------
start "" "C:\xampp\xampp-control.exe"

echo All terminals and XAMPP started!
