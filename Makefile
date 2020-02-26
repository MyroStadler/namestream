-include .env

NO_COLOR=$(shell tput sgr0 -T xterm)
RED=$(shell tput bold -T xterm)$(shell tput setaf 1 -T xterm)
GREEN=$(shell tput bold -T xterm)$(shell tput setaf 2 -T xterm)
YELLOW=$(shell tput bold -T xterm)$(shell tput setaf 3 -T xterm)
BLUE=$(shell tput bold -T xterm)$(shell tput setaf 4 -T xterm)

#GIT_BRANCH=$(shell git rev-parse --abbrev-ref HEAD)

default: install

.PHONY: install
install:
	@echo '${BLUE}Verifying${NO_COLOR}'
	@test -f ./.env || (echo ${RED}Please create .env from .env.dist${NO_COLOUR}; exit 1)
#	@test -f ./docker-compose.yml || (echo ${RED}Please create docker-compose.yml from docker-compose.yml.dist${NO_COLOUR}; exit 1)
#	@echo '${BLUE}Building docker containers${NO_COLOR}'
#	@docker volume create --name=neo4j_data
#	@docker-compose up -d --force-recreate --remove-orphans
	@echo '${BLUE}Composer install${NO_COLOR}'
	@composer install --no-scripts --prefer-dist --quiet
#	@docker-compose exec -T --user $$(id -u ${USER}):$$(id -g ${USER}) app sh -c "composer install --no-scripts --prefer-dist --quiet"
	@echo '${BLUE}Dumping autoload${NO_COLOR}'
	@composer dump-autoload --no-scripts --quiet
#	@docker-compose exec -T --user $$(id -u ${USER}):$$(id -g ${USER}) app sh -c "composer dump-autoload --no-scripts --quiet"

.PHONY: test
test:
	@echo '${BLUE}Running PHPUnit Tests${NO_COLOR}'
	./vendor/bin/phpunit tests

