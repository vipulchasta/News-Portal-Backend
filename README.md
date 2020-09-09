# NewsPortal

## Cloning RESTful server

Run `git clone https://github.com/vipulchasta/News-Portal-Backend.git` for cloning server source code.

## Navigate to project directory

Run `cd News-Portal-Backend`.

## Downloading dependencies

Run `composer install` or `composer.phar install` for downloading dependencies locally.

## Database Setup

Update .env file with database username and password -> by default confugured as
    db_name:  news_portal
    username: root
    password: Blank

Create news_portal using phpmyadmin.

Run `php artisan migrate` for creating required tables.

## Running server

Run `php artisan serve` for running the REST server. 

Navigate to `http://localhost:8000/` for verification, if not able to access run `php artisan key:generate` then run `php artisan serve`.


## Further help

To get more help on the project eMail -> chasta.vipul@gmail.com
