## Prequisites

The project is running on Laravel Sail, which requires to have **docker installed**. To start it you need to run `./vendor/bin/sail up` to build the containers.

**_NOTE:_** If you have any issues starting the container, it could be related to permissions issue and you need to add your user to the `docker` group.

## Instalation
After the container is up-and-running run the following commands:

- `./vendor/bin/sail composer install`
- `./vendor/bin/sail artisan migrate --seed`
- `./vendor/bin/sail npm install && npm run dev`

## Usage

After the system is installed and running, you can navigate trough it

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
