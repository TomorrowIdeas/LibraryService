# Library Service

## Installing PHP

You will need PHP v7.4+ installed along with `composer`.

### Installing PHP on Windows 10

```bash
choco install php
```

### Installing PHP on MacOS
```bash
brew install php@7.4
```

## Setting up the service

### Clone or fork the repo
```bash
git clone git@github.com:TomorrowIdeas/LibraryService
```

### Install dependencies

```bash
composer install
```

You will need `composer` installed. If you do not have composer installed, you can follow these [directions](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos) to get it for your system.

### Run migrations

```bash
make migrate
```

Running the migrations will create the database schemas/tables.

### Seed the database

```bash
make seed
```

Seeding the database will create fake/mock data.

### Start the HTTP service

```bash
php -S localhost:8000 -t app/Http/public/
```

## Creating a new migration
```bash
vendor/bin/phinx create MyNewMigrationFile
```

A migration stub will be created in the `database/migrations` folder. You can now open it up and begin creating your migration. Don't forget to run the migrations again to get your newly created schema updates into the database.

If your new schema will require mock data, you can edit the `database/seeds/DefaultSeeder.php` file.

## Scrubbing the database

If you would like to scrub the database and start clean, simply delete the database file.

```bash
rm database/database.sqlite3
```

Don't forget to run migrations again and seed your database.

## Running tests
```bash
make test
```

## Running static analysis
```bash
make analyze
```