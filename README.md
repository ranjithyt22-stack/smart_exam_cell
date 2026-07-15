# Banking System

A Spring Boot-based banking management system with a lightweight web frontend, REST APIs for account operations, and role-based admin actions. The project runs locally with an embedded H2 database by default and can also be pointed at MySQL for a more traditional setup.

## Features

- User registration and login
- User dashboard with balance and transaction history
- Deposit, withdrawal, and transfer operations
- Interest calculation for individual accounts
- Admin endpoints for listing, creating, and deleting users
- Admin control for updating user interest rates
- Contact form submission and admin message review
- Static frontend pages for login, registration, dashboard, calculators, and admin views

## Tech Stack

- Java 17
- Spring Boot 3.3.5
- Spring Web
- Spring Data JPA
- Spring JDBC
- H2 Database for local development
- MySQL connector for external database usage
- Maven

## Project Structure

```text
BankingSystem/
├── backend/
│   └── src/main/
│       ├── java/com/bank/
│       └── resources/
├── database/
├── frontend/
└── pom.xml
```

Key areas:

- `backend/src/main/java/com/bank/controller` contains the REST and MVC controllers.
- `backend/src/main/java/com/bank/service` contains business logic for accounts, users, and interest.
- `backend/src/main/java/com/bank/model` contains the core domain model.
- `frontend` contains the static pages served by Spring Boot.
- `database/schema.sql` contains the MySQL schema and sample seed data.

## Prerequisites

- Java 17 or newer
- Maven 3.9+ recommended
- Optional: MySQL 8+ if you want to use the MySQL profile instead of the embedded H2 database

## Run Locally

### 1. Start with the default H2 database

The application is configured to use a file-based H2 database out of the box, so the app should start without any extra database setup.

```bash
mvn spring-boot:run
```

The application starts on `http://localhost:8080`.

Useful local endpoints:

- H2 console: `http://localhost:8080/h2-console`
- Login page: `http://localhost:8080/login.html`

### 2. Use MySQL instead of H2

1. Create the database and seed data from `database/schema.sql`.
2. Set your MySQL credentials through environment variables or by editing `backend/src/main/resources/application-mysql.properties`.
3. Switch the active Spring profile to MySQL.

Example:

```bash
mvn spring-boot:run -Dspring-boot.run.profiles=mysql
```

## Demo Data

The schema file seeds a simple demo setup with the following accounts:

- `admin / admin123` with role `ADMIN`
- `user / user123` with role `USER`

It also creates sample accounts and transaction history for demonstration.

## Main API Endpoints

### Authentication

- `POST /auth/register` - register a new user
- `POST /auth/login` - authenticate a user

### Account Operations

These endpoints expect the logged-in user id in the `X-User-Id` header.

- `GET /api/accounts/dashboard` - fetch account summary and transactions
- `POST /api/accounts/deposit` - deposit funds
- `POST /api/accounts/withdraw` - withdraw funds
- `POST /api/accounts/transfer` - transfer funds to another account
- `POST /api/accounts/interest` - calculate interest immediately for the user

### Admin Operations

These endpoints expect `X-User-Role: ADMIN`.

- `GET /admin/users` - list users
- `POST /admin/add-user` - add a user
- `DELETE /admin/delete-user/{id}` - delete a user
- `PUT /admin/users/{id}/interest-rate` - update an account interest rate
- `GET /admin/messages` - view contact form messages

### Contact

- `POST /api/contact` - submit a contact message

## Frontend Pages

The project includes static pages for the user flow and calculators:

- `index.html`
- `login.html`
- `register.html`
- `dashboard.html`
- `admin.html`
- `loan-calculator.html`
- `interest-calculator.html`

## Notes

- The application uses simple header-based role checks for some endpoints.
- Interest calculation is available on demand from the dashboard and also through a scheduled background task.
- Passwords are stored as plain text in this demo project, so it should not be used as-is for production.

## Build

```bash
mvn clean package
```

The packaged application will be available under `target/` after the build completes.
