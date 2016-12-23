
symfony_blog
============
autor 
-----

m.baraniecki@gmail.com

instalacja
----------



```bash
$ git clone https://github.com/mmbar/symfony_blog.git
$ cd symfony_blog
$ composer install 

# skonfiguruj parametry bazy danych w ./config/parameters.yml

$ php bin/console doctrine:database:drop --force
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```
