██████╗ ███████╗██╗   ██╗     ██████╗ ██████╗ ██████╗ ███████╗    ███████╗██╗ ██████╗ ███╗   ███╗ █████╗ 
██╔══██╗██╔════╝██║   ██║    ██╔════╝██╔═══██╗██╔══██╗██╔════╝    ██╔════╝██║██╔════╝ ████╗ ████║██╔══██╗
██║  ██║█████╗  ██║   ██║    ██║     ██║   ██║██║  ██║█████╗      ███████╗██║██║  ███╗██╔████╔██║███████║
██║  ██║██╔══╝  ╚██╗ ██╔╝    ██║     ██║   ██║██║  ██║██╔══╝      ╚════██║██║██║   ██║██║╚██╔╝██║██╔══██║
██████╔╝███████╗ ╚████╔╝     ╚██████╗╚██████╔╝██████╔╝███████╗    ███████║██║╚██████╔╝██║ ╚═╝ ██║██║  ██║
╚═════╝ ╚══════╝  ╚═══╝       ╚═════╝ ╚═════╝ ╚═════╝ ╚══════╝    ╚══════╝╚═╝ ╚═════╝ ╚═╝     ╚═╝╚═╝  ╚═╝
                                                                                                         

A CAPSTONE ELibrary Project 79% / 100% Done

Devs Zen & Luther

Update 1.8.8

Changelogs

- Turned passwords into hashed
- Automatically hashed passwords when creating an account on admin's create account page
- Changed the font color of "Browse" and "Announcements" into mustard yellow
- Added About page (non editable)
- Added Available Books section at index homepage

!!Important please if you do not have the database library_system, please do the following in your phpadmin

For making the library system.

Step 1. Step 1. Press the New in the sidebar of PhpMyAdmin and type the library_system then press/click create button on the right side and yes you may name it whatever name you'd like but be advised that you will have to rename every "library_system" to the name you put in phpMyAdmin, to every code in this file or codes.

Step 2. Paste the following to SQL

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('employee', 'student') NOT NULL,
    email VARCHAR(255) NOT NULL,
    name VARCHAR(255) DEFAULT NULL,
    profile_picture VARCHAR(255) DEFAULT 'assets/images/default-profile.jpg'
);

Next, time to insert some accounts

INSERT INTO users (username, password, role) 
VALUES ('zen', 'password123', 'student'),
('admin', 'password', 'employee');

!! IMPORTANT: Passwords are now hashed for security. After inserting the above accounts, run http://localhost/capstoneproject/hash_passwords.php in your browser to hash them. Then delete the file. There are now a create account page on the admin's dashboard.

(this btw is an example, you may put other username or password anything you like and role.)

Then, paste the following into the SQL

CREATE TABLE books (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    genre VARCHAR(100) DEFAULT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    availability ENUM('Available', 'Unavailable') DEFAULT 'Available',
    number_of_copies INT(11) DEFAULT 1,
    published_in YEAR(4) NOT NULL,
    cover_image VARCHAR(255) DEFAULT 'assets/images/default-book.jpg'
);

This table will store information about the books in the library.

CREATE TABLE rentals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY, -- Primary key with auto-increment
    book_id INT(11) NOT NULL,              -- Foreign key for the book
    rented_by VARCHAR(255) NOT NULL,       -- Name of the person renting the book
    rental_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, -- Rental date with default current timestamp
    return_date TIMESTAMP NULL DEFAULT NULL, -- Return date, nullable
    status ENUM('Rented', 'Returned') NOT NULL DEFAULT 'Rented', -- Status of the rental
    date_rented DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP -- Date rented with default current timestamp
);

This table will track which student rented which book and when.

!!IMPORTANT
--In this version, you will have to manually add the books in the admin dashboard's Add A Book feature

And Viola! this php/html/css codes should function normally as intended




**If Error, then =

-- Add the `date_rented` column to the `rentals` table
ALTER TABLE rentals
ADD COLUMN date_rented DATETIME DEFAULT CURRENT_TIMESTAMP;

-- Update existing rows to set a default value for `date_rented` if needed
UPDATE rentals
SET date_rented = NOW()
WHERE date_rented IS NULL;
