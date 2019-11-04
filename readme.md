# What this repo is about

##### This is a website based on the Udemy Course 'Master Laravel PHP in 2019 for Beginners and Intermediate' By Piotr Jura. It is a forum covering just about everything about Laravel (it even includes a simple API). It hasn't been deployed yet but is easily reproduced locally.

# How to clone and repreduce this project

##### Cloning and repreducing this project should be straight forward with XAMPP

##### Create db's on your localhost/phpadmin called laravel and laravel_testing using utf8mb4_unicode_ci

##### Run 'php artisan passport:install --force'

##### The .env file:

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=[YOUR APP KEY]
APP_DEBUG=true
APP_URL=http://localhost/LaravelPJFirstProject/public

LOG_CHANNEL=stack

COUNTER_TIMEOUT=300

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

DB_DATABASE_TESTING=laravel_testing

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=[YOUR REDIS HOST]
REDIS_PASSWORD=[YOUR REDIS PASSWORD]
REDIS_PORT=[YOUR REDIS PORT]
REDIS_CACHE_DB=0
```
