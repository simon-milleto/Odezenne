#!/usr/bin/env bash
# Install Composer dependencies for Lumen
docker exec -it o2n_lumen composer install
# Run unit tests
docker exec -it o2n_lumen ./vendor/phpunit/phpunit/phpunit