.PHONY: up do ex

SHELL = /bin/sh

CURRENT_UID := $(shell id -u)
CURRENT_GID := $(shell id -g)

export CURRENT_UID
export CURRENT_GID

rebuild:
	docker build --no-cache -t ulco-goldenmaster-refactoring .

build:
	docker build -t ulco-goldenmaster-refactoring .

up:
	docker run -it -d --env-file ./docker/xdebug.env --name ulco-goldenmaster-refactoring -v $(shell pwd):/app -v $(shell pwd)/docker/xdebug.ini:/usr/local/etc/php/conf.d/docker-php-ext-xdebug-20.ini:ro -w=/app ulco-goldenmaster-refactoring
	docker exec -it ulco-goldenmaster-refactoring sh ./docker/setup-xdebug.sh

do:
	docker rm -vf ulco-goldenmaster-refactoring

ex:
	docker exec -u $(CURRENT_UID):$(CURRENT_GID) -it ulco-goldenmaster-refactoring sh
