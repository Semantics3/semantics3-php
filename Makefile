.PHONY: cs

cs:
	./vendor/bin/php-cs-fixer fix src
	./vendor/bin/php-cs-fixer fix test
