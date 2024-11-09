# Autoloading với Composer

## 1. Project folder structure
    .
    ├── composer.json
    ├── index.php
    └── src
       ├── Controllers
       │   └── HomeController.php
       ├── Models
       │   └── User.php
       └── Views
           └── home.php

## 2. The composer.json file
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}

## 3. Run command
    composer dump-autoload

## 4. The index.php file

    <?php
    require __DIR__ . '/vendor/autoload.php';

    use App\Controllers\HomeController;

    $homeCtrl = new HomeController;

    print_r($homeCtrl->actionIndex());

# Autoload optimizing
    Với các quy tắc PSR-0, PSR-4, autoloader cần phải check sự tồn tại của class file trong filesystem trước khi load class

## 1. Enable the class map generation mode
Thiết lập trong file composer.json

    "config": {
        "optimize-autoloader": true
    }

Chạy lệnh:

    composer install -o
    composer install --optimize-autoloader

    composer update -o
    composer update --optimize-autoloader

    composer dump-autoload -o
    composer dump-autoload --optimize
