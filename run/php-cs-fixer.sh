#!/bin/bash

docker run --rm -ti -v ${PWD}:/opt/project \
   -w /opt/project \
   poppinga/node_18_17-php_8_2-xdebug:latest \
   vendor/bin/php-cs-fixer fix \
   --config development-configuration/php-fixer-config.php \
   -v --using-cache=no

