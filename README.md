# Registration and Login system in vanilla php

## About Project:
I have created user registration in vanilla PHP with the login script. The landing (index) page shows a login form with a signup link. The registered user can enter their login details with the login form. Once done, he can get into the dashboard after successful authentication.

If the user has no account, he can click the signup option to create a new one.

The user registration form requests a username, email, password, and profile image upload option from the user. On submission, the PHP code allows registration if the email does not already exist.

This code has client-side validation for validating the entered user details have used jQuery for that. And also includes the server-side uniqueness validation. The user email is the base to check uniqueness before adding the users to the MySQL database.

## File Structure:
assets: This folder contains css files.
lib: This folder contains the database connection file.
Model: This folder contains a database table model file.
sql: This folder contains MySQL script file to create a table.
vendor: This folder contains jquery files and imgs.
login.php: For login form & logic.
user-registration.php: For registration form & logic
