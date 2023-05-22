## Features

The Laravel Product Management REST APIs provide the following features:

- Product CRUD Operations: The APIs allow you to perform CRUD (Create, Read, Update, Delete) operations on products.
- Validation and Error Handling: Input validation is implemented to ensure data integrity, and appropriate error handling is in place to provide informative responses.
- Pagination and Sorting: APIs support pagination and sorting to efficiently manage large datasets.
- Search and Filtering: APIs offer search and filtering capabilities to retrieve specific products based on various criteria.
- Image Upload: APIs support image uploading for product.

## Installation

To set up the Laravel Product Management REST APIs project, follow these steps:

- Clone the repository to your local development environment.
- Navigate to the project directory.
- Run composer install to install the project dependencies.
- Rename the .env.example file to .env and configure your environment variables, including the database settings.
- Generate a unique application key by running php artisan key:generate.
- Run php artisan migrate to create the necessary database tables.
- Optionally, run php artisan db:seed to populate the database with sample data.
- Start the development server by running php artisan serve.

## Usage
 
 To use the Laravel Product Management REST APIs, follow these guidelines:

- Create an user by using **/api/register** endpoint.
- Obtain an access token by authenticating with valid credentials via the **/api/login** endpoint.
- Include the access token in the Authorization header for subsequent requests as a **bearer token**.
- Make requests to the desired endpoints using tools like Postman or cURL.
- Ensure to include the **Accept: application/json** header in your requests to indicate that you expect a JSON response.
