# agora-video-call-app-laravel App

## Installation

Clone the repo locally:

```sh
git clone https://github.com/msahidurr/agora-video-call-app-laravel.git && cd agora-video-call-app-laravel
```

Install PHP dependencies:

```sh
composer install
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create an SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Test user:

```sh
email: testone@example.com
password: 123456
```

```sh
email: testtwo@example.com
password: 123456
```

```sh
email: testthree@example.com
password: 123456
```
## Support Me

If you find my work helpful, you can support me by buying me a coffee!

[![Buy Me a Coffee](https://img.shields.io/badge/-Buy%20Me%20a%20Coffee-orange?style=flat&logo=buymeacoffee)](https://buymeacoffee.com/msahidurr)
