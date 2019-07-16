[![Build Status](https://travis-ci.com/eKristensen/beer.svg?branch=master)](https://travis-ci.com/eKristensen/beer)
[![codecov](https://codecov.io/gh/eKristensen/beer/branch/master/graph/badge.svg)](https://codecov.io/gh/eKristensen/beer)
[![Minimum PHP Version](http://img.shields.io/badge/php-%3E%3D%207.2-8892BF.svg)](https://php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2-yellow.svg)](https://www.php-fig.org/psr/psr-2/)
[![StyleCI](https://github.styleci.io/repos/123131937/shield?branch=master)](https://github.styleci.io/repos/123131937)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eKristensen/beer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eKristensen/beer/?branch=master)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2FeKristensen%2Fbeer.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2FeKristensen%2Fbeer?ref=badge_shield)
[![Code Coverage](https://scrutinizer-ci.com/g/eKristensen/beer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/eKristensen/beer/?branch=master)
[![GuardRails badge](https://badges.guardrails.io/eKristensen/beer.svg?token=a8b10a6c14e10ac8c0c450b1c0af5ae2cc146cb95722d7e15ee2bf5655d3a199)](https://dashboard.guardrails.io/default/gh/eKristensen/beer)

# Beer and other stuff accounting - Kitchen O, P. O. Pedersen Kollegiet

System used to keep track of how drunk we get.

# Install instructions

This is a laravel project. Some steps from https://laravel.com/docs/5.8/installation do still apply here. Webserver must have PHP 7.2 or newer and point to the public folder.

On the webserver install dependencies with this command:

<code>composer install --no-dev</code> 

After installation copy the .env.example file and configure access to the mysql database you've setup:

<code>cp .env.example .env</code>

To use the application you must set a application key, do that with:

<code>php artisan key:generate</code>

When in the project root.

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
