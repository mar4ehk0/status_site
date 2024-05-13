MAKEFLAGS += --silent

include ./docker/.env
export ENV_FILE = ./docker/.env
export UID=$(shell id -u)
export GID=$(shell id -g)
export USER_NAME=$(shell id -un)
export DOCKER_COMPOSE = docker-compose -f ./docker/docker-compose.yml

.PHONY: *
SHELL=/bin/bash -o pipefail

COLOR="\033[32m%-18s\033[0m %s\n"

.PHONY: help
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z0-9_-]+:.*?## / {printf "  "${COLOR}, $$1, $$2}' ${MAKEFILE_LIST}

.PHONY: build_production
build_production: ## Builds for production containers
	docker build -t ${SYSTEM_NAME_APP} --network=host -f docker/php/production/Dockerfile . --build-arg SRC_PATH=${SRC_PATH}

.PHONY: start_production
start_production: ## Start production containers
	@${MAKE} build_production
	docker run --network=host --rm --name ${SYSTEM_NAME_APP} -v ${PWD}/app/storage:${SRC_PATH}/storage -v ${PWD}/app/var/log:${SRC_PATH}/var/log  -d ${SYSTEM_NAME_APP}
	docker exec -ti ${SYSTEM_NAME_APP} composer install --no-dev

.PHONY: stop_production
stop_production: ## Stop production containers
	docker stop ${SYSTEM_NAME_APP}

.PHONY: build_development
build_development: ## Builds for development containers
	${DOCKER_COMPOSE} build

.PHONY: start_development
start_development: ## Start development containers
	@${MAKE} stop_development
	@${MAKE} build_development
	@${MAKE} docker_compose_up_development
	docker exec -ti ${SYSTEM_NAME_APP} composer install

.PHONY: stop_development
stop_development: ## Stop development containers
	${DOCKER_COMPOSE} down -v --remove-orphans

.PHONY: docker_compose_up_development
docker_compose_up_development:
	${DOCKER_COMPOSE} up -d --remove-orphans

.PHONY: build_development_no-cache
build_development_no-cache:
	${DOCKER_COMPOSE} build --no-cache

.PHONY: shell sh
sh: shell
shell: ## Start shell into backend container
	@printf ${COLOR} 'Login to backend container';
	docker exec -ti ${SYSTEM_NAME_APP} bash

.PHONY: lint
lint: ## Checks Code Style PHP
	docker exec -ti ${SYSTEM_NAME_APP} composer lint

.PHONY: cs-fix
cs-fix: ## Fixes Code Style PHP
	docker exec -ti ${SYSTEM_NAME_APP} composer fix

.PHONY: pstan
pstan: ## Runs PSTAN
	docker exec -ti ${SYSTEM_NAME_APP} vendor/bin/phpstan

.PHONY: test
test: ## Runs php test
	docker exec -ti ${SYSTEM_NAME_APP} composer test

.PHONY: logs
logs: ## Show logs
	docker logs -f ${SYSTEM_NAME_APP}

.PHONY: shell_root
shell_root:
	docker exec -ti --user root ${SYSTEM_NAME_APP} bash

# Global
.DEFAULT_GOAL := help
