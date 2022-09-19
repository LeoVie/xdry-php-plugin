php_version=8.1

.PHONY: setup_environment
setup_environment:
ifndef php_version
	$(error php_version is not set)
endif
	make setup_ci_images php_version=$(php_version)
	make install

.PHONY: setup_ci_images
setup_ci_images:
ifndef php_version
	$(error php_version is not set)
endif
	make build_composer_image php_version=$(php_version)
	make build_phpstan_image
	make build_phpunit_image php_version=$(php_version)
	make build_psalm_image php_version=$(php_version)

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

.PHONY: build_composer_image
build_composer_image:
ifndef php_version
	$(error php_version is not set)
endif
	cd docker && docker build . -f composer.Dockerfile -t xdry-php-plugin/composer:latest --build-arg PHP_VERSION=$(php_version) && cd -

.PHONY: composer
composer:
ifndef command
	$(error command is not set)
endif
	docker run -v $(shell pwd):/app xdry-php-plugin/composer:latest $(command)

.PHONY: install
install:
	make composer command="install"

.PHONY: build_phpunit_image
build_phpunit_image:
ifndef php_version
	$(error php_version is not set)
endif
	cd docker && docker build . -f phpunit.Dockerfile -t xdry-php-plugin/phpunit:latest --build-arg PHP_VERSION=$(php_version) && cd -

.PHONY: unit
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