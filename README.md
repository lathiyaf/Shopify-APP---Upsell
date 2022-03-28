# Shopify Royal cart App

### Installation
Install the dependencies and devDependencies.

For both environment
```sh
$ composer install
$ cp .env.example .env 
$ nano .env // set all credentials(ex: database, shopify api key and secret, mail credentials)
$ php artisan migrate
```

For development environments...

```sh
$ npm install
$ npm run dev
```
For production environments...

```sh
$ npm install --production
$ npm run prod
```
create superviser

Extra commands

```sh
$ php artisan db:seed
$ php artisan storage:link
$ sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl restart [superviser-name]
```
### Used Shopify Tools

* Bootstrap
* JQuery
* Admin rest-api
* Shopify GraphQL api

