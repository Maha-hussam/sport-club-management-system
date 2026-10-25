# Sport Club Management System

## Overview

The Sport Club Management System is a Laravel web application that manages sports club users through role-based authentication, dashboards, player management, and match management features.

The application supports two user roles: Trainer and Player. Trainers can manage player accounts, create and manage matches, and assign players to matches. Players can access their own dashboard, view their personal information, and browse matches assigned to them.

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

# Match Management
* View the list of matches.
* Create new matches with a name, date, time, location, and optional description.
* Edit existing match information.
* View detailed information about a match.
* Delete matches.
* Assign one or more registered players to a match.
* Update player assignments for existing matches.
* Support multiple players per match and multiple matches per player.
* Restrict match management operations to authenticated trainers.
* Validate match information and player assignments before saving.
* Use Laravel Eloquent relationships to manage player–match associations.

Match management uses a dedicated matches table and a pivot table to associate matches with players. Player accounts continue to use the existing users table.

# Player Dashboard
* Redirect authenticated players to their Player Dashboard after login.
* Display a welcome message, player profile information, the total number of assigned matches, and upcoming matches.
* Allow players to view their personal information, including first name, last name, email, and registration date.
* Keep the player profile read-only.
* Display all matches assigned to the authenticated player.
* Show match names, dates, times, locations, and descriptions.
* Allow players to open assigned matches and view their details.
* Retrieve player matches using Laravel Eloquent relationships.
* Restrict player dashboard, profile, and match routes according to the authenticated user's role.
* Prevent players from accessing matches that have not been assigned to them.
* Prevent unauthorized access to other players' information through URL or request parameter manipulation.

## Technologies Used

* PHP
* Laravel
* Laravel Breeze
* MySQL
* Docker and Docker Compose
* Laravel Sail
* Blade
* Tailwind CSS and Vite
* Laravel Eloquent ORM
* Laravel Form Requests

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

The migrations create the database tables required by the application, including the match management tables when their migrations are present.

**Warning:** `migrate:fresh` drops existing tables and data. Use it only when you intentionally want to reset the database.

### 7. Build Frontend Assets

 If the frontend assets need to be rebuilt, run the commands supported by the project's Node.js and Vite setup.

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
* Can view all matches.
* Can create, edit, view, and delete matches.
* Can assign players to matches and update player assignments.
* Cannot access Player-only dashboard routes.

### Player

* Can register or log in as a Player.
* Is redirected to the Player Dashboard after login.
* Can view a welcome message, personal information, the total number of assigned matches, and upcoming matches.
* Can view their own profile, including first name, last name, email, and registration date.
* Cannot edit personal information through the profile page.
* Can view all matches assigned to their account.
* Can view match names, dates, times, locations, and descriptions.
* Can open match details only for matches assigned to them.
* Cannot access Trainer-only dashboard, Player Management, or Match Management routes.
* Cannot access another player's personal information or unassigned matches by modifying URLs or request parameters.

Unauthorized access to routes protected by role middleware is rejected with a 403 Forbidden response. Attempts to manage a user who is not a player return 404 Not Found.

### Validation and Password Security

# Authentication and Player Management

* Required fields are validated on the server.
* Email addresses must be valid and unique.
* Password confirmation is required when creating an account or changing a password.
* Passwords are hashed by Laravel before storage.
* The edit form allows the trainer to leave the password fields blank to retain the existing password.
* The player role is assigned by the server when creating a player; it is not selected through the management form.

# Match Management

* Match names, dates, times, and locations must be provided.
* The description is optional.
* Match data must pass server-side validation before being saved.
* Player assignments must reference valid existing player accounts.
* Only users with the Player role can be assigned to matches.
* Match creation, editing, deletion, and player assignment are restricted to authenticated trainers.
* Authorization must be enforced on the server, not only by hiding interface elements.

# Player Dashboard

1. Player Dashboard

The Player Dashboard provides authenticated players with an overview of their account and assigned matches.

The dashboard displays:

* A welcome message for the logged-in player.
* The player's profile information.
* The total number of matches assigned to the player.
* Upcoming matches assigned to the player.

2. My Profile

The My Profile page displays the authenticated player's personal information:

* First Name
* Last Name
* Email
* Registration Date

The profile is read-only. Players cannot update their personal information through this page.

3. My Matches

The My Matches page displays all matches assigned to the authenticated player.

Each match includes:

* Match Name
* Match Date
* Match Time
* Location
* Description

Player matches are retrieved using Laravel Eloquent relationships.

4. Match Details

Players can open an assigned match to view its details, including the match name, date, time, location, and description.

Players must not be able to access matches that have not been assigned to their accounts.

5. Authorization and Access Control
* Player-specific routes are restricted to authenticated users with the Player role.
* Trainers cannot access Player-specific pages.
* Players can view only their own dashboard and profile.
* Match queries are restricted to matches assigned to the authenticated player.
* Server-side authorization prevents unauthorized access through modified URLs or request parameters.

6. User Interface

The Player Dashboard functionality includes the following pages:

* Player Dashboard
* My Profile
* My Matches
* Match Details

## Database Design

# Users

The existing users table stores both Trainers and Players. The role column distinguishes between the two roles.

No separate players table is required.

# Matches

The matches table stores match information, including:

* Match name.
* Match date.
* Match time.
* Location.
* Optional description.
* Timestamps.

# Match–Player Relationship

A pivot table associates matches with users who have the Player role.

The relationship is many-to-many:

* A match can have multiple players.
* A player can participate in multiple matches.

Laravel Eloquent's belongsToMany relationship should be used on both the Match and User models to manage this association.

The pivot table should include the appropriate foreign keys and constraints. Its name and column names must match the actual migrations and Eloquent relationship definitions.

## Match Management Pages

# Matches List

Displays the registered matches and provides navigation to view match details, create a match, edit a match, or delete a match.

# Create Match

Allows trainers to enter the match name, date, time, location, and optional description.

# Edit Match

Allows trainers to update an existing match's information.

## Match Details and Player Assignment

Displays the match information and its assigned players. Trainers can assign additional players or update the existing assignments.

Player assignment should use the many-to-many Eloquent relationship so that assignments can be managed without manually manipulating pivot-table records throughout the application.

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

1. Homepage and Authentication

Homepage

Registration Page

Login Page

2. Role-Based Dashboards

Trainer Dashboard

Player Dashboard

3. Player Management

Players List

Add Player

Edit Player

Player Updated Successfully

Player Deleted Successfully

4. Match Management

Matches List

Create Match

Edit Match

Match Details and Player Assignment

Match Created Successfully

Player Assignment

## Project Status

# Phase 1 — Sport Club Management System

The project includes application setup, database configuration, authentication, user registration and login, role-based dashboards for Trainers and Players, input validation, and authorization.

# Player Management — Implemented

Trainers can view, add, edit, and delete player accounts. Only users with the Trainer role can access player management features, and Trainers cannot manage other Trainer accounts. The feature uses Laravel resource routing, Form Requests, and role-based access control.

# Match Management — Implemented

Trainers can create, view, edit, and delete matches. They can also assign players to matches and update player assignments using the many-to-many relationship between players and matches.

# Player Dashboard — Implemented

Players can access their dashboard, view their read-only profile, browse their assigned matches, and open match details. The dashboard displays the player's information, the total number of assigned matches, and upcoming matches. Player-specific routes and match access are restricted according to the authenticated user's role and match assignments.