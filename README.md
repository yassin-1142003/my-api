HEAD
# my-api
# User Authentication API

This project is a simple API for user authentication featuring registration, login, email verification, and forgot password functionality. It is built using PHP with PDO for database connections and PHPMailer for sending emails.

## Features

- **User Registration:** Allows new users to register and receive an email verification link.
- **User Login:** Enables users to log in with their email and password.
- **Email Verification:** Verifies the user's email address via a unique token.
- **Forgot Password:** Sends a password reset link to the user's email.
- **Reset Password:** Updates the user's password when provided with a valid token.

## Requirements

- PHP 7.4 or later
- MySQL (via XAMPP or any other server)
- Composer (for dependency management)
- PHPMailer (installed via Composer)

## Installation

1. **Clone the Repository:**

   ```bash
   git clone [repository-url]
   cd [repository-folder]

2. Install Dependencies:

Run the following command in your project root (where the composer.json file is located):

composer install


3. Configure the Database:

Create a MySQL database (e.g., named users).

Import the provided SQL file (if available) or create the necessary tables manually.

Update the database credentials in config/db.php:

<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

class Db {
    private $host = 'localhost';
    private $dbname = 'users';  // Replace with your database name
    private $username = 'root';  // Replace with your username (usually "root" for XAMPP)
    private $password = '';      // Leave blank if no password is set
    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

// Create a database connection object
$db = new Db();
$pdo = $db->getConnection();
echo "DB connection successful!";
?>



4. Configure SMTP for PHPMailer:

Update your SMTP settings in the files handling email sending (for example, in auth/forgot_password.php or auth/register.php):

// Example configuration for PHPMailer
$mail->isSMTP();
$mail->Host       = 'smtp.example.com';      // Your SMTP server
$mail->SMTPAuth   = true;
$mail->Username   = 'your_email@example.com';  // Your SMTP username
$mail->Password   = 'your_email_password';       // Your SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port       = 587;



Folder Structure

A suggested folder structure for the project:

/htdocs
│
└───/api_project
    │   README.md
    │   composer.json
    │
    ├───/config
    │       db.php
    │
    ├───/auth
    │       register.php
    │       login.php
    │       forgot_password.php
    │       reset_password.php
    │       verify_email.php
    │
    └───/vendor
            ... (Composer dependencies)

Note: If you prefer to keep your authentication files (like forgot password, login, register, and verify email) outside the auth folder and directly under htdocs, update the file paths accordingly in your code.

API Endpoints

1. User Registration

Endpoint: /auth/register.php

Method: POST

Description: Registers a new user and sends an email verification link.

Parameters: name, email, password


2. User Login

Endpoint: /auth/login.php

Method: POST

Description: Logs in the user.

Parameters: email, password


3. Forgot Password

Endpoint: /auth/forgot_password.php

Method: POST

Description: Sends a password reset link to the user's email.

Parameters: email


4. Reset Password

Endpoint: /auth/reset_password.php

Method: POST

Description: Resets the user's password using a valid token.

Parameters: token, new_password


5. Email Verification

Endpoint: /auth/verify_email.php

Method: GET

Description: Verifies the user's email using the token provided in the URL.

Parameters: token (query parameter)


Running the Project

1. Start XAMPP:

Ensure Apache and MySQL services are running.



2. Access the Project:

Open your browser and navigate to:

http://localhost/api_project/



3. Testing Endpoints:

Use a tool like Postman to test your API endpoints.




Troubleshooting

Error Reporting:
Ensure error reporting is enabled during development to catch any issues. Remember to disable it in production.

File Paths:
Verify that all file paths are correct, especially after moving files or changing the folder structure.

Database Connection:
Double-check your database credentials in config/db.php if connection errors occur. 4b628a9 (initial commit with API and auth system)
