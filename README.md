# OB EliteDrive

OB EliteDrive is a premium car rental website featuring a luxurious automotive brand design, a cinematic hero section, an animated fleet section, and a dynamic booking system.

## Prerequisites

- A local web server environment like [XAMPP](https://www.apachefriends.org/index.html), WAMP, or MAMP.
- PHP 7.4 or higher
- MySQL / MariaDB

## Installation & Setup

Follow these simple steps to run the application on your local machine:

### 1. Clone the Repository
Clone or download this repository and place the `ob-elitedrive` folder inside your local web server's root directory:
- For XAMPP: `C:\xampp\htdocs\ob-elitedrive`
- For WAMP: `C:\wamp\www\ob-elitedrive`
- For MAMP: `Applications/MAMP/htdocs/ob-elitedrive`

### 2. Start Your Local Server
Open your XAMPP Control Panel (or your specific control panel) and start the **Apache** and **MySQL** services.

### 3. Initialize the Database
This project includes an automated setup script that creates the database (`ob_rentaltours`), provisions the required table structures (`cars`, `bookings`, `contacts`), and inserts initial testing dummy data.

Open your web browser and navigate to the setup script:
```text
http://localhost/ob-elitedrive/setup.php
```
*(Note: It uses the default XAMPP credentials: user `root` with a blank password. If your local MySQL has a password configured, please update those credentials in both `setup.php` and `includes/db.php` first).*

### 4. Run the Application
Once the setup script completes without errors, navigate to the local URL to view and test the website:
```text
http://localhost/ob-elitedrive/
```

### 5. Manual Database Setup (Optional)
If you prefer not to use the automated `setup.php` script, you can manually set up the database via `phpMyAdmin`:
1. Go to `http://localhost/phpmyadmin`.
2. Create a new database named `ob_rentaltours` with collation `utf8mb4_unicode_ci`.
3. Import the provided `database.sql` file into the newly created database.

## Project Structure

- `api/` - Backend endpoints for handling bookings and contact form submissions.
- `assets/` - Contains styles (CSS), scripts (JS), images, and video assets.
- `includes/` - Reusable PHP components like DB connection (`db.php`), header, and footer.
- `index.php` - Main entry and homepage for the application.
- `setup.php` - Database initialization script.
- `database.sql` - Raw SQL dump of the database schema and dummy data.

## Built With
- **Frontend**: HTML5, Vanilla CSS3 (Custom animations & styling), Vanilla JS
- **Backend**: PHP 8.x
- **Database**: MySQL with PDO (Secure database connection and queries)
