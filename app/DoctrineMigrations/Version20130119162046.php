<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130119162046 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE hospital (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, web VARCHAR(255) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, hospital_id INT DEFAULT NULL, sector_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, head VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, number SMALLINT DEFAULT NULL, INDEX IDX_CD1DE18A63DBB69 (hospital_id), INDEX IDX_CD1DE18ADE95C867 (sector_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE period (id INT AUTO_INCREMENT NOT NULL, begin DATE NOT NULL, end DATE NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE placement (id INT AUTO_INCREMENT NOT NULL, period_id INT DEFAULT NULL, student_id INT DEFAULT NULL, department_id INT DEFAULT NULL, INDEX IDX_48DB750EEC8B7ADE (period_id), INDEX IDX_48DB750ECB944F1A (student_id), INDEX IDX_48DB750EAE80F5DF (department_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE grade (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, rank INT NOT NULL, is_active TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, grade_id INT DEFAULT NULL, surname VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, phone VARCHAR(18) DEFAULT NULL, ranking SMALLINT DEFAULT NULL, graduate SMALLINT DEFAULT NULL, anonymous TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), INDEX IDX_B723AF33FE19A1A8 (grade_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE parameter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(50) NOT NULL, active TINYINT(1) DEFAULT NULL, activates_at DATETIME DEFAULT NULL, expires_at DATETIME DEFAULT NULL, label VARCHAR(255) NOT NULL, category VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, UNIQUE INDEX UNIQ_2A9791105E237E06 (name), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE sector_rule (id INT AUTO_INCREMENT NOT NULL, grade_id INT DEFAULT NULL, sector INT DEFAULT NULL, relation VARCHAR(10) NOT NULL, INDEX IDX_90B6D32DFE19A1A8 (grade_id), INDEX IDX_90B6D32D4BA3D9E8 (sector), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE simul_period (id INT AUTO_INCREMENT NOT NULL, period_id INT DEFAULT NULL, begin DATE NOT NULL, end DATE NOT NULL, UNIQUE INDEX UNIQ_BB16A74BEC8B7ADE (period_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE wish (id INT AUTO_INCREMENT NOT NULL, department INT DEFAULT NULL, simstudent INT NOT NULL, rank INT NOT NULL, INDEX IDX_D7D174C9CD1DE18A (department), INDEX IDX_D7D174C96F95A435 (simstudent), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE simulation (id INT NOT NULL, student_id INT NOT NULL, department INT DEFAULT NULL, extra SMALLINT DEFAULT NULL, active TINYINT(1) NOT NULL, INDEX IDX_CBDA467BCB944F1A (student_id), INDEX IDX_CBDA467BCD1DE18A (department), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, placement_id INT DEFAULT NULL, evalcriteria_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, created_ad DATETIME NOT NULL, INDEX IDX_1323A5752F966E9D (placement_id), INDEX IDX_1323A575B87679D7 (evalcriteria_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE eval_form (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE eval_criteria (id INT AUTO_INCREMENT NOT NULL, evalform_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, more VARCHAR(255) DEFAULT NULL, rank SMALLINT NOT NULL, INDEX IDX_F3886C92BF951A59 (evalform_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE eval_sector (id INT AUTO_INCREMENT NOT NULL, sector_id INT DEFAULT NULL, form_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_52F3BF4FDE95C867 (sector_id), INDEX IDX_52F3BF4F5FF69B7D (form_id), PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A63DBB69 FOREIGN KEY (hospital_id) REFERENCES hospital(id)");
        $this->addSql("ALTER TABLE department ADD CONSTRAINT FK_CD1DE18ADE95C867 FOREIGN KEY (sector_id) REFERENCES sector(id)");
        $this->addSql("ALTER TABLE placement ADD CONSTRAINT FK_48DB750EEC8B7ADE FOREIGN KEY (period_id) REFERENCES period(id)");
        $this->addSql("ALTER TABLE placement ADD CONSTRAINT FK_48DB750ECB944F1A FOREIGN KEY (student_id) REFERENCES student(id)");
        $this->addSql("ALTER TABLE placement ADD CONSTRAINT FK_48DB750EAE80F5DF FOREIGN KEY (department_id) REFERENCES department(id)");
        $this->addSql("ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES user(id)");
        $this->addSql("ALTER TABLE student ADD CONSTRAINT FK_B723AF33FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade(id)");
        $this->addSql("ALTER TABLE sector_rule ADD CONSTRAINT FK_90B6D32DFE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade(id)");
        $this->addSql("ALTER TABLE sector_rule ADD CONSTRAINT FK_90B6D32D4BA3D9E8 FOREIGN KEY (sector) REFERENCES sector(id)");
        $this->addSql("ALTER TABLE simul_period ADD CONSTRAINT FK_BB16A74BEC8B7ADE FOREIGN KEY (period_id) REFERENCES period(id)");
        $this->addSql("ALTER TABLE wish ADD CONSTRAINT FK_D7D174C9CD1DE18A FOREIGN KEY (department) REFERENCES department(id)");
        $this->addSql("ALTER TABLE wish ADD CONSTRAINT FK_D7D174C96F95A435 FOREIGN KEY (simstudent) REFERENCES simulation(id)");
        $this->addSql("ALTER TABLE simulation ADD CONSTRAINT FK_CBDA467BCB944F1A FOREIGN KEY (student_id) REFERENCES student(id)");
        $this->addSql("ALTER TABLE simulation ADD CONSTRAINT FK_CBDA467BCD1DE18A FOREIGN KEY (department) REFERENCES department(id)");
        $this->addSql("ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5752F966E9D FOREIGN KEY (placement_id) REFERENCES placement(id)");
        $this->addSql("ALTER TABLE evaluation ADD CONSTRAINT FK_1323A575B87679D7 FOREIGN KEY (evalcriteria_id) REFERENCES eval_criteria(id)");
        $this->addSql("ALTER TABLE eval_criteria ADD CONSTRAINT FK_F3886C92BF951A59 FOREIGN KEY (evalform_id) REFERENCES eval_form(id)");
        $this->addSql("ALTER TABLE eval_sector ADD CONSTRAINT FK_52F3BF4FDE95C867 FOREIGN KEY (sector_id) REFERENCES sector(id)");
        $this->addSql("ALTER TABLE eval_sector ADD CONSTRAINT FK_52F3BF4F5FF69B7D FOREIGN KEY (form_id) REFERENCES eval_form(id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A63DBB69");
        $this->addSql("ALTER TABLE placement DROP FOREIGN KEY FK_48DB750EAE80F5DF");
        $this->addSql("ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C9CD1DE18A");
        $this->addSql("ALTER TABLE simulation DROP FOREIGN KEY FK_CBDA467BCD1DE18A");
        $this->addSql("ALTER TABLE placement DROP FOREIGN KEY FK_48DB750EEC8B7ADE");
        $this->addSql("ALTER TABLE simul_period DROP FOREIGN KEY FK_BB16A74BEC8B7ADE");
        $this->addSql("ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5752F966E9D");
        $this->addSql("ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18ADE95C867");
        $this->addSql("ALTER TABLE sector_rule DROP FOREIGN KEY FK_90B6D32D4BA3D9E8");
        $this->addSql("ALTER TABLE eval_sector DROP FOREIGN KEY FK_52F3BF4FDE95C867");
        $this->addSql("ALTER TABLE student DROP FOREIGN KEY FK_B723AF33FE19A1A8");
        $this->addSql("ALTER TABLE sector_rule DROP FOREIGN KEY FK_90B6D32DFE19A1A8");
        $this->addSql("ALTER TABLE placement DROP FOREIGN KEY FK_48DB750ECB944F1A");
        $this->addSql("ALTER TABLE simulation DROP FOREIGN KEY FK_CBDA467BCB944F1A");
        $this->addSql("ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395");
        $this->addSql("ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C96F95A435");
        $this->addSql("ALTER TABLE eval_criteria DROP FOREIGN KEY FK_F3886C92BF951A59");
        $this->addSql("ALTER TABLE eval_sector DROP FOREIGN KEY FK_52F3BF4F5FF69B7D");
        $this->addSql("ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A575B87679D7");
        $this->addSql("DROP TABLE hospital");
        $this->addSql("DROP TABLE department");
        $this->addSql("DROP TABLE period");
        $this->addSql("DROP TABLE placement");
        $this->addSql("DROP TABLE sector");
        $this->addSql("DROP TABLE grade");
        $this->addSql("DROP TABLE student");
        $this->addSql("DROP TABLE user");
        $this->addSql("DROP TABLE parameter");
        $this->addSql("DROP TABLE sector_rule");
        $this->addSql("DROP TABLE simul_period");
        $this->addSql("DROP TABLE wish");
        $this->addSql("DROP TABLE simulation");
        $this->addSql("DROP TABLE evaluation");
        $this->addSql("DROP TABLE eval_form");
        $this->addSql("DROP TABLE eval_criteria");
        $this->addSql("DROP TABLE eval_sector");
    }
}
