##Application setup

### Optional parameters

During installation you defined a set of mandatory parameter (e.g., `admin_username`. The Match application includes a set of optional criteria  that are defined in `.../app/config/match.yml`. Review these parameters and set them according to your organization's needs.

* _org\_name_: The name of your organization.  The text entered here appears in the top left corner of every page.
* _skill\_required_: With the default value `true`, skill tags are used as filters when searching for opportunities or volunteers. If you do not intend to use skill tags for opportunity requirements or volunteer capabilities set this to `false`.
* _focus\_required_: With the default value `true`, focus tags are used as filters when searching for opportunities or volunteers. If you do not intend to use focus tags for organization  or volunteer interests set this to `false`.
* _expiring\_alerts_: Unless otherwise set, opportunities expire one year after entry. With the default value `true`, the admin home page will show all opportunities set to expire in the following month. The admin user can then cause an alert to be sent to each organization with a reminder that their opportunities are about to expire. When set to false expiring opportunities do not appear on the admin home page.
* _opportunity\_email_: With the default value `true`, the admin home page will show all incoming opportunities for which email "blasts" (email to registered volunteers with focus and skill criteria matching organization and opportunity tags) have not been sent. Email is sent per opportunity as BCC to each matched volunteer. When set to false the admin will not be able to alert volunteers.
* _search\_email_: With the default value `true`, a successful search for opportunities from the Volunteer page will include a link to send an email to the organization. Note that the email is sent to each staff member of the organization without showing staff members' email addresses.

### Customize templates

You will want to customize at least some of the templates in the application.  For example, the _Contact Us_ and _About Us_ pages are blank. First, a word about how templates are structured.  Virtually all templates inherit the styling and structure of `...app/Resources/views/base.html.twig`. You can get valuable information about Twig templates at Symfony's [introduction to templating](http://symfony.com/doc/current/book/templating.html). All the templates used by the application can be found at `...app/Resources`.  Here's a tree view of its directory structure:


    ├───FOSUserBundle
    │   └───views
    │       ├───ChangePassword
    │       ├───Profile
    │       ├───Registration
    │       ├───Resetting
    │       └───Security
    ├───less
    ├───translations
    ├───TwigBundle
    │   └───views
    │       └───Exception
    └───views
        ├───Admin
        │   └───Dashboard
        ├───Criteria
        ├───default
        ├───Email
        ├───Event
        ├───MainMenu
        ├───Opportunity
        ├───Organization
        ├───Person
        ├───Staff
        └───Volunteer

The `views` directory contains the primary templates for the application.  The most common public templates are found in `.../views/MainMenu` For a listing of the templates in the views directory see [Appendix 1](views_contents.md).