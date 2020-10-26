dev:
	php -S localhost:8000 -t app/Http/public/

run:
	php app/Http/main.php

analyze:
	vendor/bin/psalm --show-info=true

test:
	vendor/bin/phpunit

shell:
	php limber shell

migrate:
	php limber migrate

seed:
	vendor/bin/phinx seed:run