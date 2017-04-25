.PHONY: cs test

cs:
	./vendor/bin/php-cs-fixer fix src
	./vendor/bin/php-cs-fixer fix test

test:
	./vendor/bin/phpunit --configuration test/Unit/phpunit.xml

