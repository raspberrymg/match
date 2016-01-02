##Application setup

1.  Templates

    The application includes the ability to customize the content of public pages and e-mail messages.  You will want to review and modify all of these before going live.  The items that can be modified are:
    
    *   About Us content
    *   Contact Us page content
    *   Expiring opportunity e-mail
    *   Headline phrase
    *   Home page content
    *   New opportunity e-mail
    *   New organization e-mail
    *   Non-profit page content
    *   Organization activation e-mail
    *   Organization name
    *   Password reset e-mail
    *   Registration confirmation e-mail
    *   Volunteer page content
    
    Each of these is accessible in the Templates dropdown in the Admin menu. You can sign in as admin using the credentials (*admin\_user* and *admin\_password*) created during installation to access the Admin menu.
    
    The most important templates are __Organization name__ and __Headline phrase__ because they appear on all pages accessed by anonymous users. You will also see that several of the templates contain properties derived from the database.  These are distinguished by pairs of curly braces {{ ... }}. It is strongly recommended that you do not modify their contents.
    
2.  Criteria

    If you have not installed the sample criteria you **must** also define Focus and Skill criteria. They are required fields for both Volunteers and Organizations. Failure to create criteria will cause validation errors when volunteers or organizations attempt to register. Criteria can be created in the Admin menu, Criteria, Focus and Criteria, Skill menus.
    