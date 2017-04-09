#!/bin/bash
docker exec -i o2n_lumen_db sh -c 'exec mysql -u homestead -psecret -Nse "show tables" homestead | while read table; do mysql -u homestead -psecret -e "drop table $table" homestead; done'
docker exec -i o2n_lumen php artisan migrate:install
docker exec -i o2n_lumen php artisan migrate