.PHONY: install
install:
	composer install

.PHONY: unit
unit:
	composer phpunit