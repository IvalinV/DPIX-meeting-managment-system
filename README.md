## Prequisites

**_NOTE:_** The project is running on Laravel Sail, which requires to have **docker installed**.


**_NOTE:_** If you have any issues starting the container, it could be related to permissions issue and you need to add your user to the `docker` group.
## Instalation
Before proceding with the commands into the container:

`composer install`

to install all the composer packages, including **Laravel Sail**.

Before starting the containers copy `.env.example` and renamed it to `.env` from it **laravel Sail** will load the DB credentials. In it the email `MAIL_` variables should be set, to allow the system to sent emails to the `citizen` when suggesting new meeting date and time.

To fire it up you need to run `./vendor/bin/sail up` or `./vendor/bin/sail up -d` to build the containers.

If you have any issues with the database port, you can chage it in the `docker-composer.yml`
`ports:
    - '${FORWARD_DB_PORT:-3306}:3306'`

After the container is up-and-running run the following commands:

- `./vendor/bin/sail composer install`
- `./vendor/bin/sail artisan migrate --seed`
- `./vendor/bin/sail npm install && npm run dev`
## Usage

After the system is running, you can navigate trough it, the main routes are:

### Citizen

In order to use `citizen` account you need to choose one from the `citizens` table using the email and for auth using `password` as the password.

#### Main links

- `/citizen/register`
- `/citizen/login`
- `/meeting/request`
- `/citizen/{id}/meetings`

### Lawyer
Chose one from the database, copy the email and use `password` as password.
#### Main links

- `/lawyer/register`
- `/lawyer/login`
- `/lawyer/{id}/meetings`
