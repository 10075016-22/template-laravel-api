# Template API usign Laravel V. 11

## Integrated technologies

This api has the following integrated technologies

- [Laravel 11](https://laravel.com/docs/11.x/releases)
- [Laravel-permission](https://spatie.be/docs/laravel-permission/v6/introduction)
- [JWT-Authentication](https://github.com/tymondesigns/jwt-auth)

## Setting Up the Project

1. Run the following command to install the project dependencies using Composer:

```
composer install
```
This command will download and install all the required dependencies for the project.

2. Create a Copy of the .env.example File

Create a copy of the .env.example file and rename it to .env. This file will contain environment-specific settings for the project. It is important to pay close attention to the following fields for the configuration of the database

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database_name
DB_USERNAME=database_user
DB_PASSWORD=database_password
```

_In linux you can use the following command to create a copy of the file_

```
cp .env.example .env
```

3. Generate the Application Key

Run the following command to generate a new application key:

```
php artisan key:generate
```
4. Run Database Migrations with Default Data

Run the following command to execute the database migrations and seed the database with default data:

```
php artisan migrate:fresh --seed
```

This command will create the database tables and populate them with default data.

5. Generate the JWT Secret

Finally, run the following command to generate a secret key for JSON Web Tokens (JWT):

```
php artisan jwt:secret
```

This command will generate a new secret key and store it in the .env file.


That's it! Your project should now be set up and ready to use.


Now all we have to do is execute the following command to run it locally

```
php artisan serve
```

## API Documentation

### Postman
To perform various queries to the API endpoints, you can use the following .json files:

- [Collections](./documentation/postman/api.json)
- [Environment](./documentation/postman/environment.json)

If you're unsure how to use these JSON files, please refer to this guide: [Importing and exporting Overview](https://learning.postman.com/docs/getting-started/importing-and-exporting/importing-and-exporting-overview/)

The goal is to import both the collection and the environment to enable you to conduct different tests more efficiently.

For the login endpoint, the following script is added:

```js
// post-response
var data =  pm.response.json();
pm.environment.set ("token", data['access_token']);
```

This script allows you to save the session token, enabling you to access all endpoints that require authentication.