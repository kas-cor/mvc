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

```bash
composer install
```

### Config DB

```bash
cp config/db.sample.php config/db.php
```

Change `config/db.php`

### Config migration

```bash
./vendor/bin/phinx init
```

Change `phinx.yml`

### Migrations and seeds data

```bash
./vendor/bin/phinx migrate
./vendor/bin/phinx seed:run
```

### Usage

Admin panel

Login `admin`, password `qwerty`
