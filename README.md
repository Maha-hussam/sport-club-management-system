# Sport Club Management System

## Overview

The Sport Club Management System is a Laravel web application that supports user registration, login, and role-based dashboards for trainers and players.

### Features

* Homepage with club information and navigation.
* User registration and login.
* User logout.
* Two user roles: Trainer and Player.
* Role-based dashboard redirection.
* Middleware-based dashboard authorization.
* Server-side validation.
* Secure password hashing using Laravel.
* MySQL database managed with Docker.

## Technologies Used

* PHP
* Laravel
* Laravel Breeze
* MySQL
* Docker and Docker Compose
* Laravel Sail
* Blade
* Tailwind CSS and Vite

## Requirements

* Docker Desktop with Docker Compose.
* Git (optional, for cloning the repository).

PHP, Composer, and MySQL can be used through the Docker environment.

## Installation and Setup

### 1. Get the Project
Clone the repository or open the downloaded project directory.

If using Git:

```bash
git clone <repository-url>
cd sport-club
```

### 2. Install Dependencies

If the project dependencies have not been installed, run the following command from the project root:

```bash
docker run --rm -v "${PWD}:/app" -w /app composer:2 install
```

### 3. Configure Environment Variables

Ensure that the `.env` file exists. If it does not, copy `.env.example` to `.env` .

Configure the database connection to match the MySQL service in the Docker Compose file:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sport_club
DB_USERNAME=sail
DB_PASSWORD=password
```

These values are examples for local development. Use matching credentials in the Docker Compose configuration. Do not commit .env or real credentials to version control.

### 4. Start Docker Services

Start the application and database containers:

```bash
docker compose up -d
```

Check the container status:

```bash
docker compose ps
```
Wait until the MySQL service is healthy before continuing.

### 5. Generate the Application Key

For a new installation without an existing application key, run:

```bash
docker compose exec laravel.test php artisan key:generate
```
Do not regenerate the key unnecessarily for an existing installation.

### 6. Run Database Migrations

Run:

```bash
docker compose exec laravel.test php artisan migrate
```

Check migration status:

```bash
docker compose exec laravel.test php artisan migrate:status
```

The migrations create the database tables required by the application.

**Warning:** `migrate:fresh` drops existing tables and data. Use it only when you intentionally want to reset the database.

### 7. Build Frontend Assets

 be rebuilt, run the commands supported by the project's Node.js and Vite setup.

For example, when Node.js and npm are available inside the application container:

```bash
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build
```

### 8. Open the Application

When using the provided Docker Compose port configuration, open:

http://localhost

## User Roles and Dashboards

### Trainer

* Can register or log in as a Trainer.
* Is redirected to the Trainer Dashboard.
* Can view their name, email, and role.
* Access to the Player Dashboard is restricted by role middleware.

### Player

* Can register or log in as a Player.
* Is redirected to the Player Dashboard.
* Can view their name, email, and role.
* Access to the Trainer Dashboard is restricted by role middleware.

Unauthorized dashboard access returns a 403 Forbidden response.

## Useful Commands

Start the application:

```bash
docker compose up -d
```

Stop the application:

```bash
docker compose down
```

Check running containers:

```bash
docker compose ps
```

List application routes:

```bash
docker compose exec laravel.test php artisan route:list
```

Check migration status:

```bash
docker compose exec laravel.test php artisan migrate:status
```

Run pending migrations:

```bash
docker compose exec laravel.test php artisan migrate
```

Build frontend assets:

```bash
docker compose exec laravel.test npm run build
```

## Screenshots

The following screenshots demonstrate the main pages and role-based dashboards of the Sport Club Management System.

1. Homepage

Homepage — Part 1

!Homepage Part 1

Homepage — Part 2

!Homepage Part 2

2. Registration Page

!Registration Page

3. Login Page

!Login Page

4. Trainer Dashboard

!Trainer Dashboard

5. Player Dashboard

!Player Dashboard

## Project Status

This is Phase 1 of the Sport Club Management System. It focuses on project setup, authentication, user roles, dashboards, validation, and authorization. Player management, match management, and other management features are planned for future phases.