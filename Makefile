.PHONY: install
install:
	composer install

.PHONY: static-analysis
static-analysis:
	composer phpstan

.PHONY: unit
unit:
	composer phpunit

.PHONY: mutation-testing
mutation-testing:
	composer infection

test: static-analysis unit mutation-testing