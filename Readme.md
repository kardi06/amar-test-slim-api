# Slim Loan Application API

This is a simple loan application API built with the Slim framework.

## Getting Started

To use the app, follow these steps:

### 1. Clone the Repository

Clone the GitHub repository to your local machine:

```bash
git clone https://github.com/kardi06/amar-test-slim-api.git
cd your-repo
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Run the Application
Start the PHP built-in server with the following command:

```bash
php -S localhost:8080 -t public/
```

The app will be running on http://localhost:8080.

## Using the Endpoints

You can interact with the API using tools like Postman and other

### 1. Base Endpoint

```bash
Method: GET
URL: http://localhost:8080/
```

This will return a simple text message to verify the app is running.

### 2. Get Loan Data

```bash
Method: GET
URL: http://localhost:8080/api/loan
```

This endpoint will retrieve all the loan applications data.

### 3. Apply for a Loan

```bash
Method: POST
URL: http://localhost:8080/api/loan
```

You need to send the following parameters in the body of the request (as JSON or form-data):
```bash
name : Full name, must include at least a first and last name.
ktp : KTP ID, must be exactly 16 digits long.
loan_amount : Amount requested, must be between 1000 and 10000.
loan_period : Duration of the loan in months.
loan_purpose : Purpose of the loan (e.g., vacation, renovation, electronics, wedding, rent, car, investment).
date_of_birth : Date of birth in format DD-MM-YYYY.
sex : Gender, either male or female.
```

The response will include any validation errors or a success message.

## Running Unit Tests

To run the tests, use the following command:

```bash
vendor/bin/phpunit
```

You can also run specific tests like this:
```bash
vendor/bin/phpunit --filter methodName
```