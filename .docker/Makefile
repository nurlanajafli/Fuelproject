env-up:
	docker compose -p tms up --remove-orphans --build --detach
	docker compose -p tms ps
env-down:
	docker compose -p tms stop
env-restart:
	docker compose -p tms restart
database-logs:
	docker compose -p tms logs -f database
php-logs:
	docker compose -p tms logs -f php
php-exec:
	docker compose -p tms exec php bash
ps:
	docker compose -p tms ps
clean-assets:
	docker compose -p tms exec php find backend/web/assets -maxdepth 1 ! -path backend/web/assets -type d -exec rm -rf {} \;
	docker compose -p tms exec php find frontend/web/assets -maxdepth 1 ! -path frontend/web/assets -type d -exec rm -rf {} \;
markup-be-build:
	docker compose -p tms exec node bash -c 'cd backend && npm install && gulp --version && gulp build'
	make clean-assets
markup-fe-build:
	docker compose -p tms exec node bash -c 'cd frontend && npm install && gulp --version && gulp build'
	make clean-assets
migrate-up:
	docker compose -p tms exec php ./yii migrate --interactive=0
migrate-down:
	docker compose -p tms exec php ./yii migrate/down --interactive=0
