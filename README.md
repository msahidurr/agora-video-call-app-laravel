# agora-video-call-app-laravel App

## Installation

Clone the repo locally:

```sh
git clone https://github.com/msahidurr/agora-video-call-app-laravel.git && cd agora-video-call-app-laravel
```

Install PHP dependencies:

```sh
composer install && npm install && npm run build
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

Add agora app id and app certificate in `.env` file:

```sh
AGORA_APP_ID=
AGORA_APP_CERTIFICATE=
```

If you are using web p2p call then you can integrate pusher credentials:

```sh
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=
```

Or, if you are using web to mobile application then add the firebase_credentials.json on `.env` file:

```sh
FIREBASE_CREDENTIALS=storage/app/firebase/firebase_credentials.json
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
