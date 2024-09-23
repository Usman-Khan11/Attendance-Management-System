# Attendance Management System

A Laravel-based Attendance Management System designed to manage and track employee attendance.

## Features

-   Admin and User Authentication
-   Role-based access (Admin, Employee)
-   Mark attendance (Present, Absent, Leave)
-   View attendance history
-   Attendance reports generation
-   Export attendance data to CSV/Excel

## Prerequisites

-   PHP >= 8.x
-   Composer
-   MySQL or other supported database
-   Node.js & NPM

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/Usman-Khan11/Attendance-Management-System.git
    ```

2. Navigate to the project folder:

    ```bash
    cd attendance-management
    ```

3. Install dependencies:

    ```bash
    composer install
    npm install && npm run dev
    ```

4. Copy the `.env.example` file to `.env`:

    ```bash
    cp .env.example .env
    ```

5. Generate the application key:

    ```bash
    php artisan key:generate
    ```

6. Configure the `.env` file with your database credentials and other settings.

7. Run migrations to set up the database:

    ```bash
    php artisan migrate
    ```

8. Seed the database (optional):

    ```bash
    php artisan db:seed
    ```

9. Start the development server:
    ```bash
    php artisan serve
    ```

## Usage

-   Admin can manage users, track attendance, and generate reports.
-   Employees can mark attendance and view their records.

## Contributing

Feel free to contribute to this project by submitting pull requests or reporting issues.

## License

This project is licensed under the MIT License.
