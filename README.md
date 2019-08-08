## MVC Pattern

### Structure application

```text
app             Source application
|- controler    Controllers
|- core         Core application
|- models       Models application
|- views        Views application
|- widgets      Widgets application
config          Configuration application
db              DB migration
|- migration    Structure
|- seeds        Data
web             Web root
|-assets        Web assets
```

### Install dependants

#### Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

#### Install plugin bower & npm package

```bash
composer global require "fxp/composer-asset-plugin:~1.4.5"
```

#### Install dependents

```bash
composer install
```

### Config

Edit file `.env`

### Migrations and seeds data

```bash
./vendor/bin/phinx migrate
./vendor/bin/phinx seed:run
```

### Usage

Admin panel

Login `admin`, password `qwerty`
