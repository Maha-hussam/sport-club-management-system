# Sport Club Management System

## Overview

The Sport Club Management System is a Laravel web application that manages club users through role-based authentication, dashboards, and player management features.

The application supports two roles: Trainer and Player. Trainers can manage player accounts, while players have access to their own dashboard.

### Features
# Authentication and Authorization
* Homepage with club information and navigation.
* User registration, login , and logout.
* Two user roles: Trainer and Player.
* Role-based redirection after login.
* Middleware-based access control.
* Server-side validation.
* Secure password hashing using Laravel.
* MySQL database managed with Docker.

# Player Management
* View the list of registered players.
* Add new player accounts.
* Edit player information.
* Change a player's password when needed.
* Delete player accounts.
* Validate submitted data using Laravel Form Requests.
* Prevent duplicate email addresses.
* Restrict player management routes to authenticated trainers.
* Prevent trainers from managing accounts that do not have the Player role.
* Use Laravel resource routing for player management.

Player records use the existing users table and its role column. No separate players table is required.

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
* Can view their account information.
* Can access Player Management.
* Can list, add, edit, and delete player accounts.
* Cannot use Player Management to edit or delete Trainer accounts.
* Cannot access Player-only dashboard routes.

### Player

* Can register or log in as a Player.
* Is redirected to the Player Dashboard.
* Can view their account information.
* Cannot access Trainer-only dashboard or Player Management routes.

Unauthorized access is rejected by role middleware, returning a 403 Forbidden response for routes protected by the middleware. Attempts to manage a user who is not a player return 404 Not Found.

### Validation and Password Security
* Required fields are validated on the server.
* Email addresses must be valid and unique.
* Password confirmation is required when creating an account or changing a password.
* Passwords are hashed by Laravel before storage.
* The edit form allows the trainer to leave the password fields blank to retain the existing password.
* The player role is assigned by the server when creating a player; it is not selected through the management form.

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

# Homepage

Homepage — Part 1

Homepage — Part 2

# Authentication

Registration Page

Login Page

# Dashboards

Trainer Dashboard

Player Dashboard

# Player Management

Players List

Add Player

Edit Player

Player Updated Successfully

Player Deleted Successfully


## Project Status

# Phase 1 — Sport Club Management System

The project includes application setup, database configuration, authentication, user registration and login, role-based dashboards for Trainers and Players, input validation, and authorization.

# Player Management — Implemented

Trainers can view, add, edit, and delete player accounts. Only users with the Trainer role can access player management features, and Trainers cannot manage other Trainer accounts. The feature uses Laravel resource routing, Form Requests, and role-based access control.

Future phases may include match management and assigning players to matches.