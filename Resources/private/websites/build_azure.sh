#!/bin/bash

exitWithMessageOnError () {
    if [ ! $? -eq 0 ]; then
        echo "An error has occured during web site deployment."
        echo $1
        exit 1
    fi
}

cd "$DEPLOYMENT_SOURCE"

if [ ! -f composer.phar ];
then
    echo "You need to commit a 'composer.phar' file in the root of your project to enable composer deployment."
    exit 1
fi

PHP_PATH="D:\Program Files (x86)\PHP\v5.4"
PATH="$PATH;$PHP_PATH";
OPERATION="install"

"D:\Program Files (x86)\PHP\v5.4\php.exe" composer.phar $OPERATION --prefer-dist -v
