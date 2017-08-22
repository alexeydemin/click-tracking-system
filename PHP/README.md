# PHP Laravel Test
The code is essentially a default laravel setup, with some database migrations added.  A docker-compose.yml file can be used to setup an environment.  

Once the environment is setup, the home page has more instructions to follow.

Please document how long this test takes you.

* use "docker-compose up" to launch the docker container
* Enter the running instance and enure "composer install" has been run
* The default environment uses:
    * DB_CONNECTION=mysql
    * DB_HOST=localhost
    * DB_PORT=3306
    * DB_DATABASE=homestead
    * DB_USERNAME=homestead
    * DB_PASSWORD=secret
* Once you have the environment created, the default page (http://localhost:port) has more information to follow

If you prefer you can use a homestead environment:

3. Inside the test/php folder `composer install`.
4. Once composer is done, execute `php vendor/bin/homestead make`.
5. Configure the **Homestead.yaml** file with your own preferences.
6. Execute `vagrant up`.
7. Before continue, the `.env` file must be configured so first let's create a copy of the default one through `cp .env.example .env`.
8. Now generate the application key through `php artisan key:generate`.
9. Edit the **.env** file with your own preferences (database, services, etc.).
