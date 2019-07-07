[![Build Status](https://travis-ci.com/eKristensen/beer.svg?branch=master)](https://travis-ci.com/eKristensen/beer)
[![codecov](https://codecov.io/gh/eKristensen/beer/branch/master/graph/badge.svg)](https://codecov.io/gh/eKristensen/beer)
[![Known NPM Vulnerabilities](https://snyk.io//test/github/eKristensen/beer/badge.svg?targetFile=package.json)](https://snyk.io//test/github/eKristensen/beer?targetFile=package.json)
[![Known PHP Vulnerabilities](https://snyk.io//test/github/eKristensen/beer/badge.svg?targetFile=composer.lock)](https://snyk.io//test/github/eKristensen/beer?targetFile=composer.lock)


# Beer and other stuff accounting - Kitchen O, P. O. Pedersen Kollegiet

System used to keep track of how drunk we get.

# Security risks

If you use this system on your own then please not that there is no limitation to who can place a order. Anybody who can access the website can buy anything on behalf of anyone. The webserver should be protected so that the purchase site not is public. If you get a leak, it is possible to remove imposters, since the IP for each purchase is noted.

You're welcome to make a pull request that fixes this. :)

# Code formatter
<code>php-cs-fixer-v2 fix --rules=@PSR2 .</code>
