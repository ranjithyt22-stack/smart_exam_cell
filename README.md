# Smart Exam Cell

Smart Exam Cell is a lightweight PHP and MySQL application for managing students, faculty, courses, enrollments, exams, and results in a college or university environment. It provides role-based access for administrators, faculty members, and students through a simple browser-based interface.

## Features

- Role-based login for `admin`, `faculty`, and `student`
- Admin dashboard with quick counts and recent results
- Student management with create and delete actions
- Faculty management with create and delete actions
- Course management with create and delete actions
- Student enrollment management
- Exam scheduling and invigilator assignment
- Result entry, update, and deletion with audit logging
- Faculty dashboard showing invigilated exams and related results
- Student dashboard showing enrolled courses, upcoming exams, and results

## Technology Stack

- PHP 8+
- MySQL / MariaDB
- HTML, CSS, and vanilla PHP templates
- Session-based authentication

## Project Structure

- `db.sql` - database schema and sample seed data
- `seed.php` - demo data generator for users, faculty, students, and courses
- `inc/config.php` - database connection and session bootstrap
- `inc/functions.php` - shared auth helpers
- `public/` - web entry points and dashboard pages
- `public/styles.css` - application styling

## Prerequisites

- PHP installed and available from the command line or a local web server stack such as XAMPP, WAMP, MAMP, or Laragon
- MySQL or MariaDB
- A web server configured to serve the `public/` directory as the document root, or a local setup that can access the PHP files directly

## Setup

1. Create the database and tables.

   Import `db.sql` into MySQL:

   ```bash
   mysql -u root -p < db.sql
   ```

   If you are using phpMyAdmin or another GUI, import the same file there.

2. Update the database connection if needed.

   Edit `inc/config.php` and adjust the host, username, password, and database name for your environment.

3. Seed demo accounts and sample data.

   Run:

   ```bash
   php seed.php
   ```

   The seed script creates an admin account and additional sample faculty and student records.

4. Start the application.

   Point your browser to the `public/` directory through your local server. If you are using PHP's built-in server from the project root, you can run:

   ```bash
   php -S localhost:8000 -t public
   ```

   Then open:

   ```
   http://localhost:8000
   ```

## Demo Credentials

After running `seed.php`, you can sign in with:

- Admin: `admin` / `Admin@123`
- Faculty accounts: `faculty1`, `faculty2`, ... with passwords `FacPass1`, `FacPass2`, ...
- Student accounts: `student1`, `student2`, ... with passwords `StudPass1`, `StudPass2`, ...

## How It Works

- The landing page redirects authenticated users to the correct dashboard based on role.
- Admin users can manage master data and operational records from the admin dashboard.
- Faculty users can review their assigned exams and see results tied to those exams.
- Student users can view enrolled courses, upcoming exams, and published results.

## Notes

- `db.sql` includes sample records for a quick first run, but `seed.php` is the preferred way to generate a fuller demo dataset.
- The application currently uses simple server-side PHP pages rather than a framework.
- Passwords are stored using `password_hash()` and verified with `password_verify()`.

## Troubleshooting

- If login fails, confirm the database credentials in `inc/config.php` and make sure the `users` table contains the seeded records.
- If pages redirect to login unexpectedly, verify that PHP sessions are enabled and the web server can write session data.
- If you see database connection errors, confirm that MySQL is running and the `smart_exam_cell` database exists.

## License

No license file is included in this repository. Add one if you plan to distribute or reuse the project.
