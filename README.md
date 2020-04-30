[![Build Status](https://travis-ci.com/eKristensen/beer.svg?branch=master)](https://travis-ci.com/eKristensen/beer)
[![codecov](https://codecov.io/gh/eKristensen/beer/branch/master/graph/badge.svg)](https://codecov.io/gh/eKristensen/beer)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2-yellow.svg)](https://www.php-fig.org/psr/psr-2/)
[![StyleCI](https://github.styleci.io/repos/123131937/shield?branch=master)](https://github.styleci.io/repos/123131937)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eKristensen/beer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eKristensen/beer/?branch=master)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FeKristensen%2Fbeer.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2FeKristensen%2Fbeer?ref=badge_shield)
[![Dependabot Status](https://api.dependabot.com/badges/status?host=github&repo=eKristensen/beer)](https://dependabot.com)
[![Known Vulnerabilities](https://snyk.io//test/github/eKristensen/beer/badge.svg?targetFile=package.json)](https://snyk.io//test/github/eKristensen/beer?targetFile=package.json)
[![Maintainability](https://api.codeclimate.com/v1/badges/75076132d6d2c1b33b04/maintainability)](https://codeclimate.com/github/eKristensen/beer/maintainability)
[![Coverage Status](https://coveralls.io/repos/github/eKristensen/beer/badge.svg?branch=master)](https://coveralls.io/github/eKristensen/beer?branch=master)

# Beer and other stuff accounting - Kitchen O, P. O. Pedersen Kollegiet

System used to keep track of how drunk we get.

# Enviroment requirements

Ubuntu Server 20.04 LTS recommended, but any server capable of running the following will work:

* Web-server Eg. nginx (see sample config below) or apache
* PHP 7.4 or later
* MySQL or MariaDB server

It is recommended to setup lets encrypt on your webserver. Please look at [Certbot](https://certbot.eff.org/)

# Install instructions

This is a laravel project. Some steps from https://laravel.com/docs/5.8/installation do still apply here. Webserver must have PHP 7.2 or newer and point to the public folder.

On the webserver install dependencies with this command:

<code>composer install --no-dev</code> 

After installation copy the .env.example file and configure access to the mysql database you've setup:

<code>cp .env.example .env</code>

To use the application you must set a application key, do that with:

<code>php artisan key:generate</code>

When in the project root.

## Create admin user

In the root of the project enter PHP artisan tinker:

    php artisan tinker

In here, create the admin user with the following commands:

    $user = new User();
    $user->name = "Your name";
    $user->email = "your-email@example.org";
    $user->password = Hash::make('your-super-secure-password');
    $user->save();

Exit the shell with

    exit();

# Security risks

If you use this system on your own then please not that there is no limitation to who can place a order. Anybody who can access the website can buy anything on behalf of anyone. The webserver should be protected so that the purchase site not is public. If you get a leak, it is possible to remove imposters, since the IP for each purchase is noted.

You're welcome to fix this. Please see [Contributing](CONTRIBUTING.md).

# Code formatter

There is no single way to do it. But [PSR-2](https://www.php-fig.org/psr/psr-2/) is the target.

Combine php cs fixer and php cs: https://github.com/Symplify/EasyCodingStandard

## One way to do it:

<code>php-cs-fixer-v2 fix --rules=@PSR2 .</code>

## Another way:

https://github.com/php-fig-rectified/psr2r-sniffer

https://github.com/squizlabs/PHP_CodeSniffer/

Install that code sniffer with: <code>composer global require "squizlabs/php_codesniffer=*"</code>

Analyze the code with:
<code>phpcs --standard=PSR2 --ignore=/vendor/*,/bootstrap/*,/storage/framework/views*,*.blade.php,*.js,*.css .</code>

Try to fix stuff with:
<code>phpcbf --standard=PSR2 --ignore=/vendor/*,/bootstrap/*,/storage/framework/views*,*.blade.php,*.js,*.css .</code>

# PHP Linter

https://github.com/overtrue/phplint

# Javascript testing

Travis is also testing javascript with vue unit tests

* https://dev.to/tuandm/how-to-test-vue-components-with-laravel-mix-and-mocha-3kgc
* https://vuejs.org/v2/cookbook/index.html
* https://vuejs.org/v2/guide/unit-testing.html
* https://laracasts.com/series/testing-vue/episodes/1
* https://vue-test-utils.vuejs.org/

## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2FeKristensen%2Fbeer.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2FeKristensen%2Fbeer?ref=badge_large)

# Nginx sample configuration

Baisc config with nginx, https redirect and the server files in <code>/var/www/beer</code>

    server {
        listen      80;
        server_name example.com;
        location / {
            return 301 https://$host$request_uri;
        }
    }

    server {
        listen 443 ssl; # managed by Certbot
        ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem; # managed by Certbot
        ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem; # managed by Certbot
        include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
        ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot

        root /var/www/beer/public;

        index index.php index.html index.htm;

        server_name example.com;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/run/php/php7.4-fpm.sock;
        }
    }

Add this to protect the server from outsider if running locally:


        allow   192.168.1.0/24;
        deny    all;

# Client setup

Example with an Raspberry Pi with Rasbian minimal desktop.

change this file <code>home/pi/.config/lxsession/LXDE-pi/autostart</code>

<code>
@lxpanel --profile LXDE-pi
@pcmanfm --desktop --profile LXDE-pi
@xscreensaver -no-splash
@point-rpi
@xset s noblank
@chromium-browser --app=https://beer.8r.dk/rooms --start-fullscreen
</code>

# Credits

Converted from [Bootstarp](https://getbootstrap.com/) to [Bulma](https://bulma.io/) with help from https://github.com/laravel-frontend-presets/bulma
