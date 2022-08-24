#!/bin/bash
./yii_test migrate --interactive=0 --migrationPath=@vendor/filsh/yii2-oauth2-server/src/migrations
./yii_test migrate --interactive=0 --migrationPath=@yii/rbac/migrations
./yii_test migrate --interactive=0
cd garage
php ../vendor/bin/codecept build
php ../vendor/bin/codecept run

