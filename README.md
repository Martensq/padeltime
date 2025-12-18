# PadelTime — Padel Court Booking App (Symfony)

PadelTime is a full-stack web application built with **Symfony 7** to manage a padel club website and handle **court bookings**.  
It includes a public-facing website and an admin dashboard to manage courts, settings and reservations.

> Note: This project is designed to run locally (PHP + MySQL).

## Features

- Public pages (club info, pricing, opening hours)
- Booking workflow (date / time / duration)
- Admin dashboard (manage courts, settings, bookings)
- User accounts and roles (Admin / User)
- Database persistence with Doctrine ORM + migrations
- Fixtures included to bootstrap the application (club settings + super admin)

## Tech Stack

- Symfony 7 (PHP)
- Doctrine ORM + Migrations + Fixtures
- MySQL (local via Wamp)
- Twig + Bootstrap (UI)

## Getting Started (Local)

### Prerequisites
- PHP
- Composer
- MySQL (Wamp)

### Installation

`composer install`

### Environment

Create a `.env.local` file and set:

`DATABASE_URL="mysql://root:@127.0.0.1:3306/padeltime?serverVersion=8.0.0&charset=utf8mb4"`

`MAILER_DSN=null://null`

### Database setup

`symfony console doctrine:database:create`

`symfony console doctrine:migrations:migrate`

`symfony console doctrine:fixtures:load`

### Run the app

`symfony server:start`

Open: `http://localhost:8000`

## Default Admin Account (Fixtures)

After loading fixtures, a super admin user is created.

- Email: `padeltime@gmail.com`
- Password: `Padeltime1`

> You can change these values in `src/DataFixtures/SuperAdminFixtures.php`.

## Demo

![Demo](https://github.com/Martensq/padeltime/blob/main/docs/demo.gif?raw=true)

## Troubleshooting

### Cache write errors on Windows / OneDrive
If Symfony cannot write to `var/cache`, move the project outside OneDrive-synced folders (recommended).

### PHP deprecation warnings (PHP 8.4)
Some deprecations may appear due to running an older Symfony app on a newer PHP version.
They can be hidden in local dev by adjusting `error_reporting`.