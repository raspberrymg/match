##Optional configurations

####Criteria

You can install the [sample criteria](sample_criteria.md) with the following command:

        $ php app/console doctrine:fixture:load --fixtures=src\Truckee\MatchBundle\DataFixtures\Criteria -n
        
####Sample data

You can also start with a set of sample data.  Installing the sample data includes the [sample criteria](sample_criteria.md). The sample data include:

* Volunteer account
    * Name: Harry Volunteer
    * Email: hvolunteer@bogus.info
    * Password: 123Abcd
* Non-profit account
    * Name: Joe Glenshire
    * Email: jglenshire@bogus.info
    * Password: 123Abcd
    * Organization: Glenshire Marmot Fund

The command to install the sample data is

        php app/console doctrine:fixtures:load --fixtures=src/Truckee/MatchBundle/DataFixtures/Test -n
        
####PostgreSQL configuration

Download the [zip file](https://github.com/truckee/volunteer/archive/master.zip) rather than using `...composer create-project...`  Before running `$ php composer.phar install` perform the following:

*   This application uses date functions `month()` and `year()` which are not available in the default installation of PostgreSQL.  There is a Doctrine extension that creates these functions for PostgreSQL.  

    Add to `config.json`:

        "require": {
        ...
        "oro/doctrine-extensions": "dev-master"
        }
        
*   The application also uses the `soundex()` function.  It is available as an extension to PostgreSQL.  It can easily be added in your PostgreSQL installation with the SQL statement `create extension fuzzystrmatch;`

*   Modify `app\config\parameters.yml` as below:

        database_driver:    pdo_pgsql
        database_host:      localhost
        database_port:      5432
        database_name:      match
        database_user:      postgre

    Change the parameters as necessary to match your installation.