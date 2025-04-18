# Beanstalk message bus - laravel package

## Install 
```sh 
composer require rezets19/laravel-beanstalk-bus
```

## Start listener
```sh  
php artisan bus:listen {queue}
```

## Rise event
```sh
php artisan bus:event
```

## Worker for systemd
```sh  
php artisan bus:worker {queue}
```

## Docs
[Beanstalk message bus standalone package](https://github.com/rezets19/beanstalk-bus)
