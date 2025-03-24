# Istruzioni per installare il progetto

- Clone the project using the comand "git clone https://github.com/lerajla/independent_countries_population.git"

- Copy .env.example a .env

- Exectue the following commands:

    - composer install
    - php artisan cache:clear-all
    - if the application key is not generated automatically, run php artisan key:generate
    - php artisan database:create
    - php artisan migrate
    - php artisan migrate:refresh --seed
    - php artisan cache:clear-all

To run the application in the local environment you need to launch the php artisan serve command
