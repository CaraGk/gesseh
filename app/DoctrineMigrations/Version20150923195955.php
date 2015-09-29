<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150923195955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD more LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE member_question CHANGE more more LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285FD913E4D');
        $this->addSql('DROP INDEX uniq_86ffd285fd913e4d ON membership');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86FFD2858789B572 ON membership (payment_instruction_id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285FD913E4D FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id)');
        $this->addSql('INSERT INTO parameter (name, value, active, activates_at, label, category, type, more) VALUES (\'reg_date\', CURDATE(), true, NOW(), \'Date anniversaire des adhésions\', \'Module Adhesion\', 1, null)');
        $this->addSql('INSERT INTO parameter (name, value, active, activates_at, label, category, type, more) VALUES (\'reg_periodicity\', \'+ 1 year\', true, NOW(), \'Périodicité des adhésions\', \'Module Adhesion\', 3, \'a:6:{s:9:"+ 1 month";s:6:"1 mois";s:10:"+ 2 months";s:6:"2 mois";s:10:"+ 6 months";s:6:"6 mois";s:8:"+ 1 year";s:4:"1 an";s:9:"+ 2 years";s:5:"2 ans";s:9:"+ 3 years";s:5:"3 ans";}\')');
        $this->addSql('INSERT INTO member_question (name, type, more, rank) VALUES (\'Inscrit à un DESC\', 8, \'a:12:{s:5:"Aucun";s:5:"Aucun";s:10:"Andrologie";s:10:"Andrologie";s:12:"Cancerologie";s:12:"Cancerologie";s:16:"Hemo transfusion";s:16:"Hemo transfusion";s:21:"Maladies infectieuses";s:21:"Maladies infectieuses";s:17:"Medecine du sport";s:17:"Medecine du sport";s:18:"Medecine d urgence";s:18:"Medecine d urgence";s:19:"Medecine vasculaire";s:19:"Medecine vasculaire";s:9:"Nutrition";s:9:"Nutrition";s:13:"Pharmacologie";s:13:"Pharmacologie";s:27:"Douleur et soins palliatifs";s:27:"Douleur et soins palliatifs";s:9:"Geriatrie";s:9:"Geriatrie";}\', 11)');
        $this->addSql('INSERT INTO member_question (name, type, more, rank) VALUES (\'Intéressé par un DESC\', 8, \'a:12:{s:5:"Aucun";s:5:"Aucun";s:10:"Andrologie";s:10:"Andrologie";s:12:"Cancerologie";s:12:"Cancerologie";s:16:"Hemo transfusion";s:16:"Hemo transfusion";s:21:"Maladies infectieuses";s:21:"Maladies infectieuses";s:17:"Medecine du sport";s:17:"Medecine du sport";s:18:"Medecine d urgence";s:18:"Medecine d urgence";s:19:"Medecine vasculaire";s:19:"Medecine vasculaire";s:9:"Nutrition";s:9:"Nutrition";s:13:"Pharmacologie";s:13:"Pharmacologie";s:27:"Douleur et soins palliatifs";s:27:"Douleur et soins palliatifs";s:9:"Geriatrie";s:9:"Geriatrie";}\', 12)');
        $this->addSql('INSERT INTO member_question (name, type, more, rank) VALUES (\'Recevoir la newsletter FAYR-GP\', 5, \'a:2:{s:3:"oui";s:3:"Oui";s:3:"non";s:3:"Non";}\', 13)');
        $this->addSql('INSERT INTO member_question (name, type, more, rank) VALUES (\'Bénéficier de la responsabilité civile GPM\', 5, \'a:2:{s:3:"oui";s:3:"Oui";s:3:"non";s:3:"Non";}\', 14)');
        $this->addSql('INSERT INTO member_question (name, type, more, rank) VALUES (\'Bénéficier de la prévoyance GPM\', 5, \'a:2:{s:3:"Oui";s:3:"oui";s:3:"Non";s:3:"non";}\', 15)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD2858789B572');
        $this->addSql('ALTER TABLE member_question CHANGE more more VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX uniq_86ffd2858789b572 ON membership');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86FFD285FD913E4D ON membership (payment_instruction_id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2858789B572 FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id)');
        $this->addSql('ALTER TABLE parameter DROP more');
        $this->addSql("DELETE FROM parameter WHERE name='reg_date'");
        $this->addSql("DELETE FROM parameter WHERE name='reg_periodicity'");
        $this->addSql("DELETE FROM member_question WHERE name='Inscrit à un DESC'");
        $this->addSql("DELETE FROM member_question WHERE name='Intéressé par un DESC'");
        $this->addSql("DELETE FROM member_question WHERE name='Recevoir la newsletter FAYR-GP'");
        $this->addSql("DELETE FROM member_question WHERE name='Bénéficier de la responsabilité civile GPM'");
        $this->addSql("DELETE FROM member_question WHERE name='Bénéficier de la prévoyance GPM'");
    }
}
