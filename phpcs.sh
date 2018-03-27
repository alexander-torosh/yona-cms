#!/usr/bin/env bash
#docker run --rm -i --volume "$(pwd):/app:rw" -u $(id -u):$(id -g) yonacms_php:latest php /app/vendor/bin/phpcs "$@"
docker run --rm \
    --volume $(pwd):/app \
    herloct/phpcs --standard=PSR1,PSR2 /app/src