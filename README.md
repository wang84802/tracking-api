# Track api

## Api spec

https://contin-it.postman.co/collections/3546425-dd7a60f7-9c55-8351-7a24-8e1ab9347f61?workspace=6dbf38ff-a7be-4db7-83a1-b1b9e8c14ab5

## Aftership api spec

https://docs.aftership.com/api/4/trackings/

## How to deploy

`Required: SSL (for aftership SDK)`

$ composer install #`current version: Lumen 5.5.2`

$ cp .env.example .env #`and modified .env`

$ chmod -R ug+rwx storage bootstrap
