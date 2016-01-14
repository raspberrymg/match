## Initial Installation

#### Please perform all steps before launching the site.
Sure, there are quite a few steps, but the result is hopefully worthwhile. I've tried to be as thorough as possible - it should go smoothly.

* Prerequisites: [Minimum Symfony requirements](http://symfony.com/doc/current/reference/requirements.html)

* The installation process will assume that the database name you enter already exists. If it does not an error will occur but "__Don't panic!__" Instructions on using Symfony to create the database appear in [Final Steps](#final-steps).

*  If you are using MySQL, create the project using composer as below.  If you prefer to use PostgreSQL, see [Optional configurations](optional.md) for necessary modifications to it and this application before proceeding with `$ php composer.phar install`:
  
        $ php composer.phar create-project truckee/match /path/to/webroot ~1.0

    or by downloading and extracting the [zip file](https://github.com/truckee/match/archive/master.zip).  Run `$ php composer.phar install` from the directory into which the application was installed (e.g., _../match_).
    
* Note that the document root of the application will be the _web_ directory (e.g., _.../path/to/webroot/web_). Directives for nginx servers are [here](http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html#nginx).

* During installation you will receive a number of prompts for configuration values. These parameters and their default values follow.  Below the parameters is a clarification of some of them.

        database_driver:    pdo_mysql
        database_host:      127.0.0.1
        database_port:      ~
        database_name:      match
        database_user:      root
        database_password:  ~

        mailer_transport:   smtp
        mailer_host:        127.0.0.1
        mailer_user:        ~
        mailer_password:    ~

        locale:             en
        secret:             ThisTokenIsNotSoSecretChangeIt

        admin_username:     ~
        admin_password:     aRealPasswordPlease
        admin_email:        admin@yoursite.com
        admin_first_name:   ~
        admin_last_name:    ~
        
    * _database\_user_: User must have database privileges to all objects within the named database.
    
    * _mailer\_host_, etc: These parameters are required to send any email. If necessary, it is possible to configure gmail for sending email as described [here](http://symfony.com/doc/current/cookbook/email/gmail.html)
    
    * _mailer\_user_: Log in name of user authorized to send mail. May be the same as admin_user.
    
    * _admin\_username_, etc: These are required parameters. The password must have at least six characters, at least one lower case, at least one upper case, and at least one numeral.

    * _admin\_email_: __CRITICAL: replace with valid admin email address.__  Receives error message emails from the application. This is also the address that receives all email sent while in sandbox mode.

* Once all parameters are entered the installation process will perform a `cache:clear` process. If you need to change any of the parameters do so in _...app/config/parameters.yml_.
        
### Permissions

Change permissions of the installation as per [Setting up Permissions](http://symfony.com/doc/current/book/installation.html#book-installation-permissions). In addition, allow global read/write privileges on web/images/pages - this is where images may be uploaded and then linked to pages.
        
### <a name="final-steps">Final steps</a>###

* (Optional) If the database named in the parameters does not yet exist, run the following at the shell prompt:

        $ php app/console doctrine:database:create

* Create the schema with

        $ php app/console doctrine:schema:create

*   For the basic configuration (admin user) the following is required:

        $ php app/console doctrine:fixtures:load -n
        
*   The site requires the following command to be run before launching for the first time.

        $ php app/console cache:clear --env=prod --no-debug
        
Next: [__Application setup__](app_setup.md).
