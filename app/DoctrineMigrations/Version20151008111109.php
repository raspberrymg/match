<?php

namespace app\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151008111109 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, add_date DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_34DCD17692FC23A8 (username_canonical), UNIQUE INDEX UNIQ_34DCD176A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE admin_outbox (id INT AUTO_INCREMENT NOT NULL, recipient INT NOT NULL, message_type VARCHAR(255) DEFAULT NULL, user_type VARCHAR(255) DEFAULT NULL, oppId INT DEFAULT NULL, orgId INT DEFAULT NULL, function VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, event VARCHAR(255) DEFAULT NULL, eventDate DATE DEFAULT NULL, location VARCHAR(45) DEFAULT NULL, starttime VARCHAR(10) DEFAULT NULL, personId INT DEFAULT NULL, INDEX IDX_3BAE0AA7A20C4B1C (personId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE focus (id INT AUTO_INCREMENT NOT NULL, focus VARCHAR(45) DEFAULT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opportunity (id INT AUTO_INCREMENT NOT NULL, oppName VARCHAR(66) DEFAULT NULL, add_date DATE DEFAULT NULL, lastUpdate DATETIME DEFAULT NULL, minAge INT DEFAULT NULL, active TINYINT(1) DEFAULT NULL, group_ok TINYINT(1) DEFAULT NULL, expireDate DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, orgId INT DEFAULT NULL, INDEX IDX_8389C3D73A8AF33E (orgId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE opp_skill (oppId INT NOT NULL, skillId INT NOT NULL, INDEX IDX_402CB22889EA8E40 (oppId), INDEX IDX_402CB228EDA4D49F (skillId), PRIMARY KEY(oppId, skillId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organization (id INT AUTO_INCREMENT NOT NULL, orgName VARCHAR(65) DEFAULT NULL, address VARCHAR(50) DEFAULT NULL, city VARCHAR(50) DEFAULT NULL, state VARCHAR(50) DEFAULT NULL, zip VARCHAR(10) DEFAULT NULL, phone VARCHAR(50) DEFAULT NULL, website VARCHAR(50) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, temp TINYINT(1) NOT NULL, add_date DATETIME DEFAULT NULL, background TINYINT(1) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, areacode INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE org_focus (orgId INT NOT NULL, focusId INT NOT NULL, INDEX IDX_9C8DB98B3A8AF33E (orgId), INDEX IDX_9C8DB98B3308C119 (focusId), PRIMARY KEY(orgId, focusId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search (id INT AUTO_INCREMENT NOT NULL, focus_id INT DEFAULT NULL, org_id INT DEFAULT NULL, opp_id INT DEFAULT NULL, skill_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_B4F0DBA751804B42 (focus_id), INDEX IDX_B4F0DBA7F4837C1B (org_id), INDEX IDX_B4F0DBA7438D405D (opp_id), INDEX IDX_B4F0DBA75585C142 (skill_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, skill VARCHAR(45) DEFAULT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE staff (id INT NOT NULL, orgId INT DEFAULT NULL, INDEX IDX_426EF3923A8AF33E (orgId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer (id INT NOT NULL, receive_email TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol_focus (volId INT NOT NULL, focusId INT NOT NULL, INDEX IDX_79573DA1F95C666E (volId), INDEX IDX_79573DA13308C119 (focusId), PRIMARY KEY(volId, focusId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vol_skill (volId INT NOT NULL, skillId INT NOT NULL, INDEX IDX_45AA933FF95C666E (volId), INDEX IDX_45AA933FEDA4D49F (skillId), PRIMARY KEY(volId, skillId)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7A20C4B1C FOREIGN KEY (personId) REFERENCES person (id)');
        $this->addSql('ALTER TABLE opportunity ADD CONSTRAINT FK_8389C3D73A8AF33E FOREIGN KEY (orgId) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE opp_skill ADD CONSTRAINT FK_402CB22889EA8E40 FOREIGN KEY (oppId) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE opp_skill ADD CONSTRAINT FK_402CB228EDA4D49F FOREIGN KEY (skillId) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE org_focus ADD CONSTRAINT FK_9C8DB98B3A8AF33E FOREIGN KEY (orgId) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE org_focus ADD CONSTRAINT FK_9C8DB98B3308C119 FOREIGN KEY (focusId) REFERENCES focus (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA751804B42 FOREIGN KEY (focus_id) REFERENCES focus (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA7F4837C1B FOREIGN KEY (org_id) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA7438D405D FOREIGN KEY (opp_id) REFERENCES opportunity (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA75585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF3923A8AF33E FOREIGN KEY (orgId) REFERENCES organization (id)');
        $this->addSql('ALTER TABLE staff ADD CONSTRAINT FK_426EF392BF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volunteer ADD CONSTRAINT FK_5140DEDBBF396750 FOREIGN KEY (id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vol_focus ADD CONSTRAINT FK_79573DA1F95C666E FOREIGN KEY (volId) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE vol_focus ADD CONSTRAINT FK_79573DA13308C119 FOREIGN KEY (focusId) REFERENCES focus (id)');
        $this->addSql('ALTER TABLE vol_skill ADD CONSTRAINT FK_45AA933FF95C666E FOREIGN KEY (volId) REFERENCES volunteer (id)');
        $this->addSql('ALTER TABLE vol_skill ADD CONSTRAINT FK_45AA933FEDA4D49F FOREIGN KEY (skillId) REFERENCES skill (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7A20C4B1C');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF392BF396750');
        $this->addSql('ALTER TABLE volunteer DROP FOREIGN KEY FK_5140DEDBBF396750');
        $this->addSql('ALTER TABLE org_focus DROP FOREIGN KEY FK_9C8DB98B3308C119');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA751804B42');
        $this->addSql('ALTER TABLE vol_focus DROP FOREIGN KEY FK_79573DA13308C119');
        $this->addSql('ALTER TABLE opp_skill DROP FOREIGN KEY FK_402CB22889EA8E40');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA7438D405D');
        $this->addSql('ALTER TABLE opportunity DROP FOREIGN KEY FK_8389C3D73A8AF33E');
        $this->addSql('ALTER TABLE org_focus DROP FOREIGN KEY FK_9C8DB98B3A8AF33E');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA7F4837C1B');
        $this->addSql('ALTER TABLE staff DROP FOREIGN KEY FK_426EF3923A8AF33E');
        $this->addSql('ALTER TABLE opp_skill DROP FOREIGN KEY FK_402CB228EDA4D49F');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA75585C142');
        $this->addSql('ALTER TABLE vol_skill DROP FOREIGN KEY FK_45AA933FEDA4D49F');
        $this->addSql('ALTER TABLE vol_focus DROP FOREIGN KEY FK_79573DA1F95C666E');
        $this->addSql('ALTER TABLE vol_skill DROP FOREIGN KEY FK_45AA933FF95C666E');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE admin_outbox');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE focus');
        $this->addSql('DROP TABLE opportunity');
        $this->addSql('DROP TABLE opp_skill');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE org_focus');
        $this->addSql('DROP TABLE search');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE staff');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE vol_focus');
        $this->addSql('DROP TABLE vol_skill');
    }
}
