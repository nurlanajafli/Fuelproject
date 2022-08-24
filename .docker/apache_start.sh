echo "xdebug.client_host = $(ip route|awk '/default/ { print $3 }')" > $PHP_INI_DIR/conf.d/xdebug_auto.ini
eval $(ssh-agent)
ssh-add ~/.ssh/id_ed25519
composer install
php init --env=Development --overwrite=No
./yii migrate --interactive=0 --migrationPath=@vendor/filsh/yii2-oauth2-server/src/migrations
./yii migrate --interactive=0 --migrationPath=@yii/rbac/migrations
./yii migrate --interactive=0
find backend/web/assets -maxdepth 1 ! -path backend/web/assets -type d -exec rm -rf {} \;
find frontend/web/assets -maxdepth 1 ! -path frontend/web/assets -type d -exec rm -rf {} \;
set -m
apache2-foreground & supercronic /tmp/crontab
fg %1
