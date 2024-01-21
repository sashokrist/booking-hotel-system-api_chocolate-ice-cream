
## About Booking Hotel System API

To install and run the project follow these steps:

run in console: 
- git@github.com:sashokrist/booking-hotel-system-api_chocolate-ice-cream.git
- cd /booking-hotel-system-api_chocolate-ice-cream
- composer install
- cp .env.example .env
- php artisan key:generate
- Set database credentials in .env
- php artisan migrate
- php artisan serve

Setup credentials for sending emails in .env

MAIL_MAILER=smtp

MAIL_HOST=sandbox.smtp.mailtrap.io

MAIL_PORT=2525

MAIL_USERNAME=*********

MAIL_PASSWORD=*********

MAIL_ENCRYPTION=null

MAIL_FROM_ADDRESS="test@test.com"

MAIL_FROM_NAME="${APP_NAME}"

Endpoints:

all routes have the prefix: /api.

without authentication:

  POST /register
  
  POST /login

with authentication:

POST /logout

GET /rooms

POST /rooms

POST /rooms/1

DELETE /rooms/1

GET /bookings

POST /bookings/

DELETE /bookings/1

GET /customers

POST /customers

POST /payment

You must register and log in using Postman to create a user and authenticate, copy the created token, and use UI to test API:

In the console run the command:

- php artisan l5-swagger:generate 
  
- http://localhost:8000/api/documentation.

<img width="598" alt="register" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/6252d632-11eb-405e-88b9-65aabc99ac9a">

<img width="608" alt="login" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/551621bf-981c-4d1b-a332-a0ad2e47d820">

<img width="946" alt="authorize" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/0d1ee59e-c7ce-456e-bed7-530bfa84aefc">

<img width="874" alt="authorize_screen" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/42a6070b-6b6a-417c-a603-ff0f0df0dac4">

<img width="857" alt="docUI" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/830be1a4-097d-4d51-bebf-8ff5866e6684">

Test Email

<img width="530" alt="create booking email" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/ce7452bb-ff1c-4d6a-beac-7a5e8f651dae">

<img width="548" alt="booking cancel" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/19361f44-39a9-4df1-b2a4-b2afc288b57b">

Unit Tests:

<img width="677" alt="tests" src="https://github.com/sashokrist/booking-hotel-system-api_chocolate-ice-cream/assets/11788009/1f494072-db3a-4a0b-8fbd-823662182595">






  
