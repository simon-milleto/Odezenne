#!/usr/bin/env bash
# Install Front-end dependencies for View.js
docker exec -it o2n_client npm install
# Install Composer dependencies for Lumen
docker exec -it o2n_lumen composer install