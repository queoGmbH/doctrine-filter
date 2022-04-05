.PHONY: install
install:
	composer install

.PHONY: static-analysis
static-analysis:
	rm -rf tests/Dummy/TestProxy
	php link_phpstan_baseline.php
	composer phpstan

.PHONY: unit
unit:
	composer phpunit

.PHONY: mutation-testing
mutation-testing:
	composer infection

test: static-analysis unit mutation-testing