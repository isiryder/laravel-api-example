# Example Laravel API

Example API written in Laravel with books, authors and libraries. Frontend generated with Laravel Blade templates and uses Jquery and Bootstrap.

## Prerrequisites

This site has been developed using PHP 8.0.9, MySQL 8.0.26 and Composer. See instructions below:
https://www.php.net/manual/en/install.php
https://dev.mysql.com/downloads/installer/
https://getcomposer.org/doc/00-intro.md
https://laravel.com/docs/8.x
https://laravel.com/docs/8.x

Alternatively, you could use Laravel Sail (with Docker):
https://laravel.com/docs/8.x/sail
https://getcomposer.org/doc/00-intro.md
https://www.docker.com/products/docker-desktop


## Installation

Using sail (PHP version 7.4+ required in local machine)

```bash
composer require laravel/sail
git clone git@github.com:isiryder/laravel-api-example.git
cd laravel-api-example
vi .env # paste content that is further below
php artisan sail:install
php artisan sail:publish
sail up
sail composer install
sail artisan migrate
```

Content of .env:
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:1nO80liLEa+MMmH3Et5c/1jM+2bBHKOKV9phIcqN12c=
APP_DEBUG=true
APP_URL=http://example-app2.test

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=example_app2
DB_USERNAME=sail
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=memcached

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://meilisearch:7700
```

## Usage

```python
import foobar

# returns 'words'
foobar.pluralize('word')

# returns 'geese'
foobar.pluralize('goose')

# returns 'phenomenon'
foobar.singularize('phenomena')
```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)