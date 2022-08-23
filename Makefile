start:
	php -S localhost:8080 -t public public/index.php

test:
	composer exec --verbose phpunit tests