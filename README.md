# laravel58-client-vendor-request-job
simple client request send to vendor via mai based on laravel5.8 + mysql + event/listener/jobs

# Introduction
This project only provide a basic structure to show case api call to trigger event > listener > dispatch jobs. There's no JWT validation involved.

### Flow
1. Client makes Requests -> Waits for 5 seconds -> Sends Request to Vendor via Mail
2. 

Client make api call to update payment -> send payment details to vendor via mail

### Mail Setup
Mail set to `log` in this project. To see the sent mail, please go to `storage/logs/laravel.log` to see the mail content. You can set it up smtp or other driver. Update `config/mail.php` accordingly.

### To run the queue
Open terminal and navigate to the project folder and run `php artisan queue:listen` before you make any api request.


# To start the project (with docker and docker-compose)
If you already have docker and docker-compose. Open your terminal and go to the project folder.

1. run `docker-compose build`
2. run `docker-compose up`
3. run `docker ps` to get the container id of `app`, and run `docker exec -it {container id} sh` to go into the container.
4. run `php artisan migrate` to install the database
5. run `php artisan queue:listen` to start the queue worker.
6. go to postman with `localhost` or `127.0.0.0` to access the endpoints.


# To start the project without docker
You will need to have mysql 5.7, composer and other required tools and extensions pre-installed before you can start this project.
Check here for the requirement: (https://laravel.com/docs/5.8#installation)

1. go to project folder and open `config/database.php` and change the root password and database name accordingly.
2. open terminal and nagivate to the project folder, run `composer install` to install all the required files
3. run `php artisan migrate` to install the database
4. run `php artisan serve` to start the service.
5. Open another terminal in the same project folder and run `php artisan queue:listen` to start the queue worker.
6. go to postman with `localhost` or `127.0.0.0` to access the endpoints.


# The endpoints

## POST api/requests
client_name (required)
vendor_email (required, valid email)

sample response:
```
{
    "client_name": "aaa",
    "vendor_email": "vendor@email.com",
    "status": "new",
    "updated_at": "2019-09-08 09:46:22",
    "created_at": "2019-09-08 09:46:22",
    "id": 1
}
```
Please make sure you have started the queue worker. Email will be send (in this project, inside log) after 5 seconds. You will need the id for the next endpoint.

## PATCH api/requests/{id}/payment
payment_method (required, string)
transaction_references (required, string)

sample response:
```
{
    "msg": "Update successfully."
}
```

Email will be send immediately with updated status: payment_done, payment method, transaction reference, payment date (UTC)
