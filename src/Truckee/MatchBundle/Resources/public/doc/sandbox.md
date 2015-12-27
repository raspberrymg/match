##Sandbox Mode

The sandbox mode feature allows an organization to explore the capabilities of the Volunteer Volunteer application inside a controlled environment.  Because access to the sandbox requires signing in, the application can be installed on an existing website without concern over unauthorized access.  [Creating at least one sandbox user](#sandbox) is required.

Many of the features of the application require sending and receiving e-mails.  In sandbox mode all e-mails are sent to a configurable e-mail address.  This allows the use of fictitious e-mail addresses without generating failed delivery messages.

During standard installation the application creates an admin user and initializes several page and e-mail templates.  Optionally available is a set of Focus and Skill criteria or a set of criteria along with some sample data.

###Installing sandbox mode

###[__Initial steps__](basic_installation.md) (Required)

Edit _...app/config/security.yml_ to remove the comment (#) as appears below in the unedited version.

        access_control:
            - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/_wdt, role: IS_AUTHENTICATED_ANONYMOUSLY }
            - { path: ^/_profiler, role: IS_AUTHENTICATED_ANONYMOUSLY }        
            #- { path: ^/, role: ROLE_USER }        
            - { path: ^/admin/, role: ROLE_ADMIN }
            - { path: ^/efconnect, role: ROLE_USER }
            - { path: ^/elfinder, role: ROLE_USER }

Note that the line `- { path: ^/, role: ROLE_USER }` **MUST** have the comment (#) restored if you ever leave sandbox mode. This line determines whether authentication is required to access the site.

###Create sandbox user

To create a sandbox user use the following command:

    $ php app/console truckee:user:create username firstname lastname email password sandbox
    
Alternatively, you can use the command interactively, starting with

    $ php app/console truckee:user:create
    
You will then be prompted for each parameter.
        
###[Application setup](app_setup.md) (Required)
        
###[Optional configurations](optional.md)
