For WINDOWS users:

run command BEFORE CHECKOUT project:
git config --global core.autocrlf false

Add the following lines to your hosts file (/etc/hosts in Linux/Mac OS):

```
127.0.0.1   backend.tms.loc
127.0.0.1   frontend.tms.loc
127.0.0.1   garage.tms.loc
127.0.0.1   v1.tms.loc
127.0.0.1   markup-backend.tms.loc
127.0.0.1   markup-frontend.tms.loc
127.0.0.1   pgadmin.tms.loc
```

### Start/Stop/Restart/Attach/Logs

> If you work on Windows use **make.bat** command instead of **make** in the commands below.

Start

```shell script
cd .docker
make env-up
```

Stop

```shell script
cd .docker
make env-down
```

Restart

```shell script
cd .docker
make env-restart
```

Attach
> Attach local standard input, output to a container in which php:7.4-fpm
> is working. Required to install Composer packages, generating and executing database migrations etc.

```shell script
cd .docker
make php-attach
```

Logs

```shell script
cd .docker
make php-logs
```

### gii/giiant

http://backend.tms.loc/gii

http://frontend.tms.loc/gii

### pgAdmin

http://pgadmin.tms.loc:8000

Email/Password: admin@jafton.com/password

DB Password: zo0mL1oN

### redis Commander

http://localhost:8100

### debug

Install "Xdebug helper" Google Chrome extension.

### rbac

./yii rbac/init

#### PhpStorm

File->Settings->Languages & Frameworks->PHP->Debug

Xdebug section:

Debug port = 9003

Force break at first line when no path mapping specified = off

Force break at first line when a script is outside the project = off

Pre-configuration section:

click "Start Listening" button

### OAuth2

./yii migrate --migrationPath=@vendor/filsh/yii2-oauth2-server/src/migrations

### Create User 
./yii user/create
don't forget after user creation assign all permissions in http://backend.tms.loc/permission
if error during add permissions means you forgot to run ./yii rbac/init