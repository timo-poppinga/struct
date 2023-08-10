#!/bin/bash

docker run --rm -ti -v ${PWD}:/opt/project \
   -w /opt/project \
   poppinga/node_18_17-php_8_2-xdebug:latest \
   /bin/bash

