.PHONY: build_image
ifndef tag
	$(error tag is not set)
endif
build_image:
	docker build -f docker/project/Dockerfile -t leovie/xdry-php-plugin:$(tag) . --no-cache

.PHONY: build_and_push_image
ifndef tag
	$(error tag is not set)
endif
build_and_push_image: build_image
	docker push leovie/xdry-php-plugin:$(tag)

.PHONY: build_phpunit_image
build_phpunit_image:
ifndef php_version
	$(error php_version is not set)
endif
	cd docker && docker build . -f phpunit.Dockerfile -t xdry-php-plugin/phpunit:latest --build-arg PHP_VERSION=$(php_version) && cd -

unit:
	docker run -v ${PWD}:/app --rm xdry-php-plugin/phpunit:latest

.PHONY: build_psalm_image
build_psalm_image:
ifndef php_version
	$(error php_version is not set)
endif
	cd docker && docker build . -f psalm.Dockerfile -t xdry-php-plugin/psalm:latest --build-arg PHP_VERSION=$(php_version) && cd -

.PHONY: psalm
psalm:
	docker run -v ${PWD}:/app --rm xdry-php-plugin/psalm:latest

.PHONY: build_phpstan_image
build_phpstan_image:
	cd docker && docker build . -f phpstan.Dockerfile -t xdry-php-plugin/phpstan:latest && cd -

.PHONY: phpstan
phpstan:
	docker run -v ${PWD}:/app --rm xdry-php-plugin/phpstan:latest analyse -c /app/build/config/phpstan.neon

.PHONY: test
test: phpstan psalm unit