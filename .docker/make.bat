@echo off

IF "%1"=="" (
    echo:
    echo Usage:
    echo    make.bat env-up
    echo    make.bat env-down
    echo    make.bat env-restart
    echo    make.bat clean-assets
    echo    make.bat markup-be-build
    echo    make.bat markup-fe-build
    echo    make.bat migrate-up
    echo    make.bat migrate-down
    echo    make.bat php-attach
)

if "%1" == "env-up" (
    docker-compose -p tms up --remove-orphans --build --detach
    docker-compose -p tms ps
)

if "%1" == "env-down" (
    docker-compose -p tms stop
)

if "%1" == "env-restart" (
    docker-compose -p tms restart
)

if "%1" == "clean-assets" (
    docker-compose -p tms exec php bash -c "find backend/web/assets -maxdepth 1 ! -path backend/web/assets -type d -exec rm -rf {} \;"
    docker-compose -p tms exec php bash -c "find frontend/web/assets -maxdepth 1 ! -path frontend/web/assets -type d -exec rm -rf {} \;"
)

if "%1" == "markup-be-build" (
    docker-compose -p tms exec node bash -c 'cd backend && npm install && gulp --version && gulp build'
    make clean-assets
)

if "%1" == "markup-fe-build" (
    docker-compose -p tms exec node bash -c 'cd frontend && npm install && gulp --version && gulp build'
    make clean-assets
)

if "%1" == "migrate-up" (
    docker-compose -p tms exec php ./yii migrate --interactive=0
)

if "%1" == "migrate-down" (
    docker-compose -p tms exec php ./yii migrate/down --interactive=0
)

if "%1" == "php-attach" (
    docker-compose -p tms exec php bash
)
