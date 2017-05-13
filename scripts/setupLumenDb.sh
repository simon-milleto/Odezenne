#!/bin/bash
docker exec -i o2n_lumen php artisan migrate:install
docker exec -i o2n_lumen php artisan migrate