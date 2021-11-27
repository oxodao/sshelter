# @TODO: Make something to generate the SSL keys on init

help:
	@echo -e '               \e[104m~~ Sshelter Makefile ~~\e[0m'
	@grep -E '^[a-zA-Z0-9_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

run: ## Starts the containers
	@docker-compose up -d
	@test -f .initialized || (test ! -f .initialized && make init && touch .initialized)

init:
	@docker-compose exec php composer install
	@$(MAKE) gen-jwt
	@$(MAKE) reset

reset: ## Reset the database
	@docker-compose exec php bin/console doc:sch:drop --force
	@docker-compose exec php bin/console doc:sch:create
	#@docker-compose exec php bin/console doc:mig:mig
	@docker-compose exec php bin/console doc:fix:load --no-interaction

gen-jwt: ## Generate the jwt keys
	@docker-compose exec php bin/console lexik:jwt:generate-keypair --overwrite --no-interaction
