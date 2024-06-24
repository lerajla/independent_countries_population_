# Istruzioni per installare il progetto

- Clonare il progetto usando il comando "git clone https://github.com/lerajla/independent_countries_population.git"

- Copiare .env.example a .env

- Lanciare i comandi nel seguente ordine:

    - composer install
    - php artisan cache:clear-all
    - nel caso l'application key non viene generato in automatico, lanciare php artisan key:generate
    - php artisan database:create
    - php artisan migrate
    - php artisan migrate:refresh --seed
    - php artisan cache:clear-all

Per far girare l'applicativo nel ambiente locale serve lanciare il comando php artisan serve
