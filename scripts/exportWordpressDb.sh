#!/bin/bash
docker exec o2n_wordpress_db sh -c 'exec mysqldump --add-drop-database -u wordpress -ppassword wordpress' > config/wp_dump.sql