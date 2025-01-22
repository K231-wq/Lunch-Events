# Lunch-Events

## Project Overview
Lunch-Events is a PHP-based web application that allows users to register, log in, create events, and manage invitations. The project uses MySQL for the database and Bootstrap for styling.

## Installation Instructions
1. Clone the repository:
   git clone https://github.com/yourusername/lunch-events.git

2. Navigate to the project directory:
## using vs code
1. install php server extension
2. serve the php server in SignIn.php
3. start xampp apache and mysql
4. create two database register and allevents
2. You can register and then login to the home
## cd lunch-events

3. Set up the database:

    Create a MySQL database named events.
    Import the database schema from database/schema.sql.
4. Configure the database connection:

    Update the database connection details in the PHP files (e.g., create.php, delete.php, etc.).
5. Start the PHP server:

## php -S localhost:8000

## Usage Instructions

1. Open the application in your browser:
# http://localhost:8000
2. Register a new account on the registration page.
3. Log in with your credentials.
4. Create a new event on the create event page.
5. Manage your invitations and RSVPs on the respective pages.

## Code Documentation
## Main Files
    create.php: Handles event creation.
    delete.php: Handles event deletion.
    invite.php: Displays user invitations.
    logout.php: Handles user logout.
    profile.php: Displays user profile.
    rsvp.php: Displays user RSVPs.
    update.php: Handles event updates.
    Register.php: Handles user registration.
    SignIn.php: Handles user login.

## Database Schema

1. register: Stores user information (UniqueId, Name, Email, Password).
2. allevents: Stores event information (id, UniqueId, name, address, time,

## API Endpoints
(Not applicable for this project)