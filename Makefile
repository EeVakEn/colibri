include .env
db: docker-db

docker-db:
	docker exec -it $(PROJECT_PREFIX)_db /bin/bash


php:
	docker exec -it  --user $(WWWUSER) $(PROJECT_PREFIX)_app /bin/bash
