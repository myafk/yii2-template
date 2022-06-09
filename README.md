
INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer install
~~~

## Instruction to init

### One row command

~~~
php init \
    && php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0 \
    && php yii migrate --migrationPath=@vendor/lajax/yii2-translate-manager/migrations --interactive=0 \
    && php yii migrate --interactive=0 \
    && php yii system/rbac/init \
    && php yii system/init
~~~

### Init

~~~
php init
~~~

### Migrations

RBAC , Translate Manager , Common
~~~
php yii migrate --migrationPath=@yii/rbac/migrations
php yii migrate --migrationPath=@vendor/lajax/yii2-translate-manager/migrations
php yii migrate
~~~

### RBAC

~~~
php yii system/rbac/init
~~~

### Login

Base roles:
~~~
root:root-test
admin:admin-test
manager:manager-test
~~~

### TEST data
~~~
php yii system/init
~~~
