match
=====

A Symfony project created on October 3, 2015, 8:04 am.

###After installation:
Once you are able to see the page with __Welcome to Symfony...__ at _http://{your site}/app\_dev.php_ you need to create the database and add an admin user. Run the following commands from your project directory:

    $ php app/console doctrine:database:create
    $ php app/console doctrine:schema:create
    $ php app/console match:admin:create
    $ php app/console doctrine:fixtures:load -n
