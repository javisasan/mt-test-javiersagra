VERSION = $(shell git describe --tags --always --dirty)
BRANCH = $(shell git rev-parse --abbrev-ref HEAD)
CONTAINER = mt-php

.PHONY: help shell test cover

all: help

help:
	@echo
	@echo "VERSION: $(VERSION)"
	@echo "BRANCH: $(BRANCH)"
	@echo
	@echo "usage: make <command>"
	@echo
	@echo "commands:"
	@echo "    help             - show this help"
	@echo "    dev              - compose up dev environment and apply JSON fixtures"
	@echo "    nodev            - compose down dev environment"
	@echo "    shell            - enter the container"
	@echo "    unit             - run unit tests"
	@echo "    e2e              - run end to end tests"
	@echo "    test             - run ALL tests (unit and end 2 end)"
	@echo "    cache            - execute cache:clear"
	@echo "    tree             - show git log tree"
	@echo "    purge            - removes ALL docker containers, images and volumes in dev machine"
	@echo

dev:
	@docker compose up -d
	@docker exec $(CONTAINER) bin/console doctrine:database:create
	@docker exec $(CONTAINER) bin/console doctrine:schema:create
	@docker exec $(CONTAINER) bin/console product:import-from-json
	@docker exec $(CONTAINER) bin/console discounts:import-from-json

nodev:
	@docker compose down

shell:
	@docker exec -ti $(CONTAINER) bash

unit:
	@docker exec $(CONTAINER) ./vendor/bin/phpunit --testsuite unit --stop-on-failure --colors=always

e2e:
	@docker exec $(CONTAINER) ./vendor/bin/phpunit --testsuite e2e --stop-on-failure --colors=always

test:
	@docker exec $(CONTAINER) ./vendor/bin/phpunit --testsuite unit --stop-on-failure --colors=always
	@docker exec $(CONTAINER) ./vendor/bin/phpunit --testsuite e2e --stop-on-failure --colors=always

cache:
	@docker exec $(CONTAINER) php bin/console cache:clear

tree:
	git log --graph --oneline --decorate

purge:
	@sh clean-all.sh
