# Laravel Project

## Introduction
This is a Laravel-based application that facilitates interactions between students and teachers through a system of courses, assignments, and submissions. The app can be run using Docker or locally with PHP's built-in server, depending on your setup. It contains four main models: Users, Courses, Assignments, and Submissions. The users are classified into two roles: `student` and `teacher`.

## Table of Contents
- Installation
  - Docker Setup
  - Local Setup (Without Docker)
- Usage
  - Running the Application
  - Running Tests
- Features
- Models
- Dependencies
- Troubleshooting
- Contributors
- License

## Installation

### Docker Setup
To run the application using Docker, follow these steps:

1. Clone the repository to your local machine.
2. Ensure Docker and Docker Compose are installed on your system.

3. Make sure to copy .env.example as .env (default env for docker) Linux Command or you can copy paste it manually: 

   ```
   cp .env.example .env
   ```

4. Run the following command in the root directory of the project:

   ```
   docker-compose up --build
   ```

This will build the necessary Docker containers and run the application.

Note: Please make sure that port 3306 isn't used by another app, Or you can change the port from the docker-compose file along with env file.

### Local Setup (Without Docker)
If you prefer not to use Docker, you can run the application locally. Make sure you have PHP, Composer, and MySQL (or any compatible database) installed.

1. Clone the repository to your local machine.
2. Set up the `.env` file with your database credentials (You can use the same as .env.example file as defualt for docker). Example:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

3. Run the following commands to install dependencies and set up the database:

   ```
   composer install
   php artisan migrate
   ```

4. Start the local development server:

   ```
   php artisan serve
   ```

## Usage

### Running the Application
- **With Docker**: Run the app by executing `docker-compose up --build`.
- **Without Docker**: Ensure your `.env` file is configured correctly, run `php artisan migrate`, and start the server using `php artisan serve`.

### Running Tests
You can run the tests using the following command:

```
php artisan test
```

This will execute the test suite defined for the application.

### API Docs
You can access the API docs after running the app using the URL:

```
http://localhost:8000/docs
```

This will execute the test suite defined for the application.

## Features
- User management with roles: `student` and `teacher`.
- Create and manage courses, assignments, and submissions.
- Student-specific and teacher-specific functionalities.

## Models
The application has four main models:
1. **Users**: Represents both students and teachers. Users must be created with a specific role (`student` or `teacher`).
2. **Courses**: Represents the courses that users (teachers and students) interact with.
3. **Assignments**: Represents the tasks assigned to students by teachers within a course.
4. **Submissions**: Represents the submissions made by students for assignments.

## Dependencies
- PHP 8.x or later
- Laravel 9.x or later
- MySQL or any other supported database
- Docker (for containerized environment)
- Composer (for dependency management)

## Troubleshooting
- **Database Issues**: Ensure your `.env` file has the correct database credentials and that the database service is running.
- **Missing Migrations**: If you encounter issues related to database schema, ensure you've run `php artisan migrate`.
- **Permission Issues**: If using Docker, ensure that your local user has the necessary permissions for file and folder access.

## License
This project is licensed under the MIT License. See the LICENSE file for details.

--- 
