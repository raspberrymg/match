DROP TABLE IF EXISTS "admin";
CREATE TABLE admin (id INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "admin" VALUES(1);
DROP TABLE IF EXISTS "admin_outbox";
CREATE TABLE admin_outbox (id INTEGER NOT NULL, recipient INTEGER NOT NULL, message_type VARCHAR(255) DEFAULT NULL, user_type VARCHAR(255) DEFAULT NULL, oppId INTEGER DEFAULT NULL, orgId INTEGER DEFAULT NULL, function VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id));
DROP TABLE IF EXISTS "elfinder_file";
CREATE TABLE elfinder_file (id INTEGER NOT NULL, parent_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, content BLOB NOT NULL, size INTEGER NOT NULL, mtime INTEGER NOT NULL, mime VARCHAR(255) NOT NULL, "read" VARCHAR(255) NOT NULL, "write" VARCHAR(255) NOT NULL, locked VARCHAR(255) NOT NULL, hidden VARCHAR(255) NOT NULL, width INTEGER NOT NULL, height INTEGER NOT NULL, PRIMARY KEY(id));
DROP TABLE IF EXISTS "event";
CREATE TABLE event (id INTEGER NOT NULL, event VARCHAR(255) DEFAULT NULL, eventDate DATE DEFAULT NULL, location VARCHAR(45) DEFAULT NULL, starttime VARCHAR(10) DEFAULT NULL, personId INTEGER DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_3BAE0AA7A20C4B1C FOREIGN KEY (personId) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
DROP TABLE IF EXISTS "focus";
CREATE TABLE focus (id INTEGER NOT NULL, focus VARCHAR(45) DEFAULT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id));
INSERT INTO "focus" VALUES(1,'All',1);
INSERT INTO "focus" VALUES(2,'Animal Welfare',1);
INSERT INTO "focus" VALUES(3,'Seniors',1);
INSERT INTO "focus" VALUES(4,'Arts and Culture',1);
INSERT INTO "focus" VALUES(5,'Civic and Public Benefit',1);
INSERT INTO "focus" VALUES(6,'Education',1);
INSERT INTO "focus" VALUES(7,'Environment and Conservation',1);
INSERT INTO "focus" VALUES(8,'Health',1);
INSERT INTO "focus" VALUES(9,'Human Services',1);
INSERT INTO "focus" VALUES(10,'Recreation',1);
INSERT INTO "focus" VALUES(11,'Youth Development',1);
DROP TABLE IF EXISTS "opp_skill";
CREATE TABLE opp_skill (oppId INTEGER NOT NULL, skillId INTEGER NOT NULL, PRIMARY KEY(oppId, skillId), CONSTRAINT FK_402CB22889EA8E40 FOREIGN KEY (oppId) REFERENCES opportunity (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_402CB228EDA4D49F FOREIGN KEY (skillId) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "opp_skill" VALUES(1,2);
INSERT INTO "opp_skill" VALUES(2,4);
INSERT INTO "opp_skill" VALUES(2,14);
DROP TABLE IF EXISTS "opportunity";
CREATE TABLE opportunity (id INTEGER NOT NULL, oppName VARCHAR(66) DEFAULT NULL, add_date DATE DEFAULT NULL, lastUpdate DATETIME DEFAULT NULL, minAge INTEGER DEFAULT NULL, active BOOLEAN DEFAULT NULL, group_ok BOOLEAN DEFAULT NULL, expireDate DATE DEFAULT NULL, description CLOB DEFAULT NULL, orgId INTEGER DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_8389C3D73A8AF33E FOREIGN KEY (orgId) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "opportunity" VALUES(1,'Feeder',date('now'),NULL,0,1,0,date('now','+1 month'),'Make sure the critters don''t go hungry',1);
INSERT INTO "opportunity" VALUES(2,'Defeatherer',date('now'),NULL,0,1,0,date('now','+1 month'),'Take the fuzzy stuff off!',3);
DROP TABLE IF EXISTS "org_focus";
CREATE TABLE org_focus (orgId INTEGER NOT NULL, focusId INTEGER NOT NULL, PRIMARY KEY(orgId, focusId), CONSTRAINT FK_9C8DB98B3A8AF33E FOREIGN KEY (orgId) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9C8DB98B3308C119 FOREIGN KEY (focusId) REFERENCES focus (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "org_focus" VALUES(1,2);
INSERT INTO "org_focus" VALUES(2,3);
INSERT INTO "org_focus" VALUES(3,2);
INSERT INTO "org_focus" VALUES(3,11);
DROP TABLE IF EXISTS "organization";
CREATE TABLE organization (id INTEGER NOT NULL, orgName VARCHAR(65) DEFAULT NULL, address VARCHAR(50) DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, state VARCHAR(50) DEFAULT NULL, zip VARCHAR(10) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, website VARCHAR(50) DEFAULT NULL, active BOOLEAN DEFAULT NULL, "temp" BOOLEAN NOT NULL, add_date DATETIME DEFAULT NULL, background BOOLEAN DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, areacode INTEGER DEFAULT NULL, PRIMARY KEY(id));
INSERT INTO "organization" VALUES(1,'Glenshire Marmot Fund','PO Box 999','Truckee','CA','96160',NULL,'www.glenshiremarmots.org',1,0,date('now'),NULL,'jglenshire@bogus.info',NULL);
INSERT INTO "organization" VALUES(2,'Glenshire Marmite Fund','PO Box 999','Truckee','CA','96160',NULL,'www.melanzanemarmots.org',1,1,NULL,NULL,'jmelanzane@bogus.info',NULL);
INSERT INTO "organization" VALUES(3,'Turkeys R Us','PO Box 876','Truckee','CA','96160',NULL,NULL,1,0,date('now'),0,NULL,NULL);
DROP TABLE IF EXISTS "person";
CREATE TABLE person (id INTEGER NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles CLOB NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, add_date DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id));
INSERT INTO "person" VALUES(1,'admin','admin','admin@bogus.info','admin@bogus.info',1,'phvs3jrf0pccskg88s0co0occw8sss0','T9WgQwE52AUYdit6fh6360zfJX1MUVF+zybyOooXrZrWVgd+4CxhmEsO7VOyzi0S9nmT6yorCKFLoKEhAkTELA==','2015-09-18 05:32:46',0,0,NULL,NULL,NULL,'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}',0,NULL,'Benny','Borko',NULL,'admin');
INSERT INTO "person" VALUES(2,'jglenshire','jglenshire','jglenshire@bogus.info','jglenshire@bogus.info',1,'n1h6l64u29cso0wwsoogsg0kck4wo','av5YsbnlhFYU4Q2lJRGW29tOgMrI1h870kfpKRWg7/kchInUWOLfNce1Xd0Coa6W8LqsM5nZbjn55rjoak+u6Q==',NULL,0,0,NULL,NULL,NULL,'a:1:{i:0;s:10:"ROLE_STAFF";}',0,NULL,'Joe','Glenshire',NULL,'staff');
INSERT INTO "person" VALUES(3,'hvolunteer','hvolunteer','hvolunteer@bogus.info','hvolunteer@bogus.info',1,'15ka057xfi00kcssgscsw8owkcsg4kw','R4upYGXFimyZ7TYiM9x4kAaPc0w1IpuS0D+DqWSfTMDdUeOpsuOPUtDCl6UGsgxZ3fFOVilG1aY+HVPu93fJ5w==',NULL,0,0,NULL,NULL,NULL,'a:0:{}',0,NULL,'Harry','Volunteer',NULL,'volunteer');
INSERT INTO "person" VALUES(4,'jmelanzane','jmelanzane','jmelanzane@bogus.info','jmelanzane@bogus.info',1,'j47kmva8g408gkokw88o8g8ss08o0wo','GRXo4PGsjHCVj0B18lSChDfgNWr5pqs+stFRxfJY5ADNPH1QYGak4u+qOfopb2uo5uJfO/tdVpZIrGghoX9S2Q==',NULL,0,0,NULL,NULL,NULL,'a:1:{i:0;s:10:"ROLE_STAFF";}',0,NULL,'Joe','Melanzane',NULL,'staff');
INSERT INTO "person" VALUES(5,'bborko','bborko','bborko@bogus.info','bborko@bogus.info',1,'1czziko8raysg8gscs04co4sowsgk0w','SbZueET9yC5dKi2tMvrArvkDzeKwbnYdt/N62hb9lgxmMtGSqgHZZ7r/P0X6zKMIKeH7xZaqdzesi5u/rHa6EQ==','2015-09-18 05:33:32',0,0,NULL,NULL,NULL,'a:1:{i:0;s:10:"ROLE_STAFF";}',0,NULL,'Benny','Borko','2015-09-18 05:31:05','staff');
DROP TABLE IF EXISTS "sandbox";
CREATE TABLE sandbox (id INTEGER NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_E6EAF167BF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE);
DROP TABLE IF EXISTS "search";
CREATE TABLE search (id INTEGER NOT NULL, focus_id INTEGER DEFAULT NULL, org_id INTEGER DEFAULT NULL, opp_id INTEGER DEFAULT NULL, skill_id INTEGER DEFAULT NULL, type VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_B4F0DBA751804B42 FOREIGN KEY (focus_id) REFERENCES focus (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4F0DBA7F4837C1B FOREIGN KEY (org_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4F0DBA7438D405D FOREIGN KEY (opp_id) REFERENCES opportunity (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4F0DBA75585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
DROP TABLE IF EXISTS "skill";
CREATE TABLE skill (id INTEGER NOT NULL, skill VARCHAR(45) DEFAULT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id));
INSERT INTO "skill" VALUES(1,'All',1);
INSERT INTO "skill" VALUES(2,'Administrative Support',1);
INSERT INTO "skill" VALUES(3,'Board Member',1);
INSERT INTO "skill" VALUES(4,'Computers & IT',1);
INSERT INTO "skill" VALUES(5,'Construction/Handy Man',1);
INSERT INTO "skill" VALUES(6,'Driving',1);
INSERT INTO "skill" VALUES(7,'Fundraising/Grant Writing',1);
INSERT INTO "skill" VALUES(8,'Health Care',1);
INSERT INTO "skill" VALUES(9,'Management',1);
INSERT INTO "skill" VALUES(10,'Marketing/PR',1);
INSERT INTO "skill" VALUES(11,'Customer Service',1);
INSERT INTO "skill" VALUES(12,'Mentoring/Tutoring',1);
INSERT INTO "skill" VALUES(13,'Web/Graphics Design',1);
INSERT INTO "skill" VALUES(14,'Legal Services',1);
INSERT INTO "skill" VALUES(15,'Accounting/Bookkeeping',1);
DROP TABLE IF EXISTS "staff";
CREATE TABLE staff (id INTEGER NOT NULL, orgId INTEGER DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_426EF3923A8AF33E FOREIGN KEY (orgId) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_426EF392BF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "staff" VALUES(2,1);
INSERT INTO "staff" VALUES(4,2);
INSERT INTO "staff" VALUES(5,3);
DROP TABLE IF EXISTS "template";
CREATE TABLE template (id INTEGER NOT NULL, name VARCHAR(45) NOT NULL, source CLOB NOT NULL, last_modified DATETIME NOT NULL, description CLOB DEFAULT NULL, PRIMARY KEY(id));
INSERT INTO "template" VALUES(1,'headline','Custom headline','2015-09-18 05:29:17','Headline phrase');
INSERT INTO "template" VALUES(2,'org_name','Organization name','2015-09-18 05:29:17','Organization name');
INSERT INTO "template" VALUES(3,'reset_password','<p>Hello {{ user.firstName }}!</p> <p>To reset your password - please visit <a href="{{ confirmationUrl }}">this link</a>.</p><p> Regards, Your friends at {{ org_name }}</p>','2015-09-18 05:29:17','Password reset e-mail');
INSERT INTO "template" VALUES(4,'reg_confirm','Hello <b>{{ user.firstName }} {{ user.lastName }}</b>!

    <p>Thank you for joining us.
        Please confirm your registration by visiting <a href="{{ confirmationUrl }}">this link</a>
    </p>

    <p>
    Thanks.
    <br>
    {{ org_name }}</p>','2015-09-18 05:29:17','Registration confirmation e-mail');
INSERT INTO "template" VALUES(5,'new_opp','<p>We have just listed a new volunteer opportunity from {{ opportunity.organization.orgName }}.</p>

    <ul>
        <li>Opportunity: {{ opportunity.oppName }}</li>
        <li>Description: {{ opportunity.description }}</li>
    </ul>

    <p>Your friends at {{ org_name }}, the Volunteer Volunteer people!</p>','2015-09-18 05:29:17','New opportunity e-mail');
INSERT INTO "template" VALUES(6,'expiring_opp','<p>{{ expiring.orgName }} has the listings shown below at this website that are set to expire next month. Expired listings cease to appear on the website. If you would like these opportunities to remain active, please contact us at your earliest convenience.</p>
<p>{% for opp in expiring.oppData %}</p>
<p>Opportunity: {{ opp.oppName }}; Expiration: {{ opp.expireDate|date("m/d/Y") }}</p>
<p>{% endfor %} </p>
<p>Thank you for listing your volunteer opportunities with us. We hope we have been effective in helping you meet your volunteer needs. If there is anything we can do in the future to assist your organization please let us know.</p>
<p>Thank you.<br /> The {{ org_name }} team</p>','2015-09-18 05:29:17','Expiring opportunity e-mail');
INSERT INTO "template" VALUES(7,'new_org','<p>A new organization is in town:</p>
    <p>{{ organization.orgName }}</p>
    <p></p>Check it out!
    <p>From, yourself</p>','2015-09-18 05:29:17','New organization e-mail');
INSERT INTO "template" VALUES(8,'activated_org','<p>We have just activated {{ organization.orgName }}.  You may now login
and create an opportunity.  Once an opportunity is created we can
the alert volunteers whose interests match those of your organization
about the new opportunity.</p>

<p>Thank you very much for participating in our volunteer matching program.  
Please do not hestitate to let us know if there''s anything else we can
do to assist in meeting your volunteer needs.</p>

<p>The {{ org_name }} team.</p>','2015-09-18 05:29:17','Organization activation e-mail');
INSERT INTO "template" VALUES(9,'home_page','<p>This website is designed for use by community foundations. It can assist their local non-profit organizations in recruiting volunteers as well as help community member find volunteer opportunities that meet the member''s interests and skills.</p> <p>Modify <i>Home page content</i> in the admin, templates menu with your own content</p>','2015-09-18 05:29:17','Home page content');
INSERT INTO "template" VALUES(10,'about_us','<p>Modify <i>About Us page content</i> in the admin, templates menu with your own content</p>','2015-09-18 05:29:17','About Us content');
INSERT INTO "template" VALUES(11,'contact_us','<p>Modify <i>Contact Us page content</i> in the admin, templates menu with your own content</p>','2015-09-18 05:29:17','Contact Us page content');
INSERT INTO "template" VALUES(12,'non_profit','<p>Registering your organization allows you to post volunteer opportunities as they arise. We recommend using a valid generic e-mail address such as coordinator@your_non-profit.org for registering.&nbsp; You will receive two e-mail notifications: the first when you confirm your e-mail address, the second when the site administrator approves the organization&#39;s registration.&nbsp; After that you may log in and create opportunities.</p><p>Modify <i>Non-profit page content</i> in the admin, templates menu with your own content</p>','2015-09-18 05:29:17','Non-profit page content');
INSERT INTO "template" VALUES(13,'volunteer_page','<p>Register as a volunteer to receive e-mail notices of new opportunities. You can always log in to change your profile where you can stop delivery if you wish.</p><p>Modify <i>Volunteer page content</i> in the admin, templates menu with your own content</p>','2015-09-18 05:29:17','Volunteer page content');
DROP TABLE IF EXISTS "vol_focus";
CREATE TABLE vol_focus (volId INTEGER NOT NULL, focusId INTEGER NOT NULL, PRIMARY KEY(volId, focusId), CONSTRAINT FK_79573DA1F95C666E FOREIGN KEY (volId) REFERENCES volunteer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_79573DA13308C119 FOREIGN KEY (focusId) REFERENCES focus (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "vol_focus" VALUES(3,2);
DROP TABLE IF EXISTS "vol_skill";
CREATE TABLE vol_skill (volId INTEGER NOT NULL, skillId INTEGER NOT NULL, PRIMARY KEY(volId, skillId), CONSTRAINT FK_45AA933FF95C666E FOREIGN KEY (volId) REFERENCES volunteer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_45AA933FEDA4D49F FOREIGN KEY (skillId) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "vol_skill" VALUES(3,2);
DROP TABLE IF EXISTS "volunteer";
CREATE TABLE volunteer (id INTEGER NOT NULL, receive_email BOOLEAN DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_5140DEDBBF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE);
INSERT INTO "volunteer" VALUES(3,1);
CREATE INDEX IDX_3BAE0AA7A20C4B1C ON event (personId);
CREATE INDEX IDX_402CB22889EA8E40 ON opp_skill (oppId);
CREATE INDEX IDX_402CB228EDA4D49F ON opp_skill (skillId);
CREATE INDEX IDX_426EF3923A8AF33E ON staff (orgId);
CREATE INDEX IDX_45AA933FEDA4D49F ON vol_skill (skillId);
CREATE INDEX IDX_45AA933FF95C666E ON vol_skill (volId);
CREATE INDEX IDX_79573DA13308C119 ON vol_focus (focusId);
CREATE INDEX IDX_79573DA1F95C666E ON vol_focus (volId);
CREATE INDEX IDX_8389C3D73A8AF33E ON opportunity (orgId);
CREATE INDEX IDX_9C8DB98B3308C119 ON org_focus (focusId);
CREATE INDEX IDX_9C8DB98B3A8AF33E ON org_focus (orgId);
CREATE INDEX IDX_B4F0DBA7438D405D ON search (opp_id);
CREATE INDEX IDX_B4F0DBA751804B42 ON search (focus_id);
CREATE INDEX IDX_B4F0DBA75585C142 ON search (skill_id);
CREATE INDEX IDX_B4F0DBA7F4837C1B ON search (org_id);
CREATE UNIQUE INDEX UNIQ_34DCD17692FC23A8 ON person (username_canonical);
CREATE UNIQUE INDEX UNIQ_34DCD176A0D96FBF ON person (email_canonical);
CREATE INDEX parent_id ON elfinder_file (parent_id);
CREATE UNIQUE INDEX parent_name ON elfinder_file (parent_id, name);
