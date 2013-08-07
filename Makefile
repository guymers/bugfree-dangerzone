COMPOSER_BIN := composer
BUGFREE_BIN := ./bin/bugfree
PHPCS_BIN := ./vendor/bin/phpcs
PHPUNIT_BIN := ./vendor/bin/phpunit

depends: vendor

cleandepends: cleanvendor vendor

vendor: composer.json
	@$(COMPOSER_BIN) --dev update
	@touch vendor

cleanvendor:
	@rm -rf composer.lock
	@rm -rf vendor

codingstyle: depends
	@echo " --- Coding Style ---"
	@$(PHPCS_BIN) --standard=PSR2 src
	@echo

lint: depends
	@echo " --- Lint ---"
	@$(BUGFREE_BIN) lint src
	@echo

test: lint
	@echo " --- Unit tests ---"
	@$(PHPUNIT_BIN)
	@echo

codecoverage: lint
	@echo " --- Code Coverage ---"
	@$(PHPUNIT_BIN) --coverage-html coverage
	@echo
