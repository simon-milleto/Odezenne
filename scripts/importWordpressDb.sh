#!/bin/bash
docker exec -i o2n_wordpress_db mysql -u wordpress -ppassword wordpress < config/wp_dump.sql