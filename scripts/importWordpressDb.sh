#!/bin/bash
docker exec -it o2n_wordpress_db mysql -u root mydb < /db/wp_dump.sql
docker exec o2n_wordpress_db sh -c 'exec mysql -u wordpress -ppassword wordpress' < config/wp_dump.sql