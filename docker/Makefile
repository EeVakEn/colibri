PROJECT_PREFIX=colibri
up: docker-up
stop: docker-stop
down: docker-down
restart: down up
php: docker-php
db: docker-database
nginx: docker-nginx

docker-up:
	sudo systemctl stop nginx
	docker-compose up -d

docker-stop:
	docker stop $$(docker ps -a -q)

docker-down:
	docker-compose down --remove-orphans

docker-php:
	docker exec -it $(PROJECT_PREFIX)_php /bin/bash

docker-database:
	docker exec -it $(PROJECT_PREFIX)_db /bin/bash

docker-nginx:
	docker exec -it $(PROJECT_PREFIX)_nginx /bin/bash


