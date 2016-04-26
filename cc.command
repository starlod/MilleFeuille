#!/bin/sh

cd `dirname $0`

compass compile

php app/console assets:install web --symlink
php app/console assetic:dump
php app/console cache:clear --no-warmup
