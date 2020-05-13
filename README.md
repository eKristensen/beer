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

Ubuntu Server 20.04 LTS or Fedora 32+ is recommended, but any server capable of running the following will work:

* Web-server Eg. nginx (see sample config below) or apache
* PHP 7.4 or later
* MySQL or MariaDB server

It is recommended to setup lets encrypt on your webserver. Please look at [Certbot](https://certbot.eff.org/)

# Install instructions

This is a laravel project. Some steps from https://laravel.com/docs/5.8/installation do still apply here. Webserver must have PHP 7.4 or newer and point to the public folder.

On the webserver install dependencies with this command:

<code>composer install --no-dev</code> 

After installation copy the .env.example file and configure access to the mysql database you've setup:

<code>cp .env.example .env</code>

To use the application you must set a application key, do that with:

<code>php artisan key:generate</code>

When in the project root.

## Javascript

As pr May 13, 2020 this github repo no longer contains the compiled javascript code. Please compile on your own with npm. Run to get JavaScript working:

    npm install
    npm run prod

Another easy way to update is with

    make deploy-update

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

# Cron job

For automatic updates you could use the <code>beer-update.sh</code> script in this repo with this crontab to update every day at 5am:

    0 5 * * * sh /home/popadmin/homepage-update.sh >/dev/null 2>&1

Please not any issues will be disrecarded. Keep an eye on the upstream code. The intention is only to have working code on the master branch, then this won't be any issue.

# Nginx sample configuration (Ubuntu)

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

# Nginx sample configuration (Fedora)

Setup of php-fpm is slightly different for Fedora. Use the following as inspiration.

First /etc/nginx/nginx.conf

    # For more information on configuration, see:
    #   * Official English Documentation: http://nginx.org/en/docs/
    #   * Official Russian Documentation: http://nginx.org/ru/docs/

    user nginx;
    worker_processes auto;
    error_log /var/log/nginx/error.log;
    pid /run/nginx.pid;

    # Load dynamic modules. See /usr/share/doc/nginx/README.dynamic.
    include /usr/share/nginx/modules/*.conf;

    events {
        worker_connections 1024;
    }

    http {
        log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                          '$status $body_bytes_sent "$http_referer" '
                          '"$http_user_agent" "$http_x_forwarded_for"';

        access_log  /var/log/nginx/access.log  main;

        sendfile            on;
        tcp_nopush          on;
        tcp_nodelay         on;
        keepalive_timeout   65;
        types_hash_max_size 4096;
        server_tokens off;

        include             /etc/nginx/mime.types;
        default_type        application/octet-stream;

        ##
        # Virtual Host Configs
        ##

        include /etc/nginx/conf.d/*.conf;
        include /etc/nginx/sites-enabled/*;

    }

Next the beer site:

    server {
        listen      80;
        server_name beer.8r.dk;
        location / {
            return 301 https://$host$request_uri;
        }
    }

    server {
        listen 443 ssl; # managed by Certbot
        ssl_certificate /etc/letsencrypt/live/beer.8r.dk/fullchain.pem; # managed by Certbot
        ssl_certificate_key /etc/letsencrypt/live/beer.8r.dk/privkey.pem; # managed by Certbot
        include /etc/letsencrypt/options-ssl-nginx.conf; # managed by Certbot
        ssl_dhparam /etc/letsencrypt/ssl-dhparams.pem; # managed by Certbot

        allow   192.168.1.0/24;
        deny    all;

        root /var/www/beer/public;

        index index.php index.html index.htm index.nginx-debian.html;

        server_name beer.8r.dk;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.(php|phar)(/.*)?$ {
            fastcgi_split_path_info ^(.+\.(?:php|phar))(/.*)$;

            fastcgi_intercept_errors on;
            fastcgi_index  index.php;
            include        fastcgi_params;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            fastcgi_param  PATH_INFO $fastcgi_path_info;
            fastcgi_pass   unix:/run/php-fpm/www.sock;
        }
    }

# Client setup

Example with an Raspberry Pi with Rasbian minimal desktop.

change this file <code>/home/pi/.config/lxsession/LXDE-pi/autostart</code>

    @lxpanel --profile LXDE-pi
    @pcmanfm --desktop --profile LXDE-pi
    @xscreensaver -no-splash
    @point-rpi
    @xset s noblank
    @chromium-browser --app=https://beer.8r.dk/rooms --start-fullscreen

Website: https://www.raspberrypi.org/forums/viewtopic.php?t=132637

# Credits

Converted from [Bootstarp](https://getbootstrap.com/) to [Bulma](https://bulma.io/) with help from https://github.com/laravel-frontend-presets/bulma
