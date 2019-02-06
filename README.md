# Channel 2.0

Update workflow for `rlje-wp` repo using `Composer` as a dependecy manager.

** Please commit all the codes generated from running `composer install` or `composer update` on your local enviroment **

## How to generate rlje-wp using Composer

run `composer install` if `composer.lock` is not available.

run `composer update` if `composer.lock` is available.


## How to clean the rlje-wp codebase to get new codebase in case of file version corruption

remove `wordpress/` folder
remove `vendor/` folder
remove `composer.lock` file to allow new codebase generation

run `composer self-update` to update to current version (optional)

run `composer clear-cache` to clear all composer cache

run `composer install` to regenerate new codebase from `composer.json`