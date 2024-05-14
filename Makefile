ifneq ($(if $(MAKECMDGOALS),$(words $(MAKECMDGOALS)),1),1)
.SUFFIXES:
TARGET := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),,$(firstword $(MAKECMDGOALS)))
PARAMS := $(if $(findstring :,$(firstword $(MAKECMDGOALS))),$(MAKECMDGOALS),$(wordlist 2,100000,$(MAKECMDGOALS)))
.PHONY: ONLY_ONCE
ONLY_ONCE:
	$(MAKE) $(TARGET) COMMAND_ARGS="$(PARAMS)"
%: ONLY_ONCE
	@:
else

DCE_API=docker compose exec -it api

.PHONY: start
start: ## Запускает окружение
	@docker-compose up -d

.PHONY: stop
stop: ## Останавливает окружение
	@docker-compose down

.PHONY: composer
composer:
	@$(DCE_API) sh -c "composer $(COMMAND_ARGS)"

.PHONY: c
c: ## Работа с консолью Symfony. Пример: make -- c c:c
	@$(DCE_API) sh -c "php bin/console $(COMMAND_ARGS)"

.PHONY: ecs
ecs:
	@$(DCE_API) sh -c "vendor/bin/ecs check src --fix"

.PHONY: phpunit
phpunit:
	@echo -n > var/log/test.log
	@docker-compose run --rm api sh -c "php vendor/bin/phpunit --no-coverage $(COMMAND_ARGS)"

.PHONY: cc
cc: ## Очистка кеша
	@$(DCE_API) sh -c "php bin/console c:c $(COMMAND_ARGS)"

.PHONY: dc
dc: ## Удалить контейнеры
	@docker-compose down -v

.PHONY: rmc
rmc: ## Удалить весь кеш rm -rf
	@rm -rf ./var/cache
endif
