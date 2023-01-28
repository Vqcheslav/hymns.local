arg=$(filter-out $@,$(MAKECMDGOALS))
start:
	docker compose up $(arg)
stop:
	docker compose stop
migration:
	php bin/console make:migration
migrate:
	php bin/console doctrine:migrations:migrate $(arg)
clear-metadata:
	php bin/console doctrine:cache:clear-metadata
	php bin/console doctrine:migrations:sync-metadata-storage