clear
php bin/console assets:install --symlink
php bin/console assetic:dump --env=prod --no-debug

php bin/console cache:clear --env=prod

#chmod -R 777 var/
#chmod -R 777 web/upload/
#sudo chmod -R 777 .

