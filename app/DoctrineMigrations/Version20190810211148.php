<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190810211148 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33FE19A1A8');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285CB944F1A');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B645CB944F1A');
        $this->addSql('CREATE TABLE fee (id INT AUTO_INCREMENT NOT NULL, structure_id INT DEFAULT NULL, title VARCHAR(20) NOT NULL COLLATE utf8_unicode_ci, amount SMALLINT NOT NULL, help LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, is_default TINYINT(1) NOT NULL, is_counted TINYINT(1) NOT NULL, INDEX IDX_964964B52534008B (structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student RENAME person');
        $this->addSql('CREATE TABLE structure (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, slug VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, address LONGTEXT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', area VARCHAR(100) DEFAULT NULL COLLATE utf8_unicode_ci, logo VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, activated TINYINT(1) DEFAULT NULL, fullname VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, email VARCHAR(150) DEFAULT NULL COLLATE utf8_unicode_ci, areamap LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', url VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, phone VARCHAR(15) DEFAULT NULL COLLATE utf8_unicode_ci, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_6F0137EA5E237E06 (name), UNIQUE INDEX UNIQ_6F0137EA989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fee ADD CONSTRAINT FK_964964B52534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE member_question ADD structure_id INT DEFAULT NULL, ADD required TINYINT(1) DEFAULT NULL, ADD short VARCHAR(40) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE member_question ADD CONSTRAINT FK_412AEAA42534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_412AEAA42534008B ON member_question (structure_id)');
        $this->addSql('DROP INDEX IDX_86FFD285CB944F1A ON membership');
        $this->addSql('ALTER TABLE membership ADD structure_id INT DEFAULT NULL, ADD fee_id INT DEFAULT NULL, ADD status VARCHAR(10) NOT NULL COLLATE utf8_unicode_ci, ADD ref VARCHAR(50) DEFAULT NULL COLLATE utf8_unicode_ci, ADD privacy TINYINT(1) NOT NULL, CHANGE amount amount SMALLINT NOT NULL, CHANGE student_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2852534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285AB45AECA FOREIGN KEY (fee_id) REFERENCES fee (id)');
        $this->addSql('CREATE INDEX IDX_86FFD285217BBB47 ON membership (person_id)');
        $this->addSql('CREATE INDEX IDX_86FFD2852534008B ON membership (structure_id)');
        $this->addSql('CREATE INDEX IDX_86FFD285AB45AECA ON membership (fee_id)');
        $this->addSql('ALTER TABLE parameter ADD structure_id INT DEFAULT NULL, DROP activates_at, DROP expires_at');
        $this->addSql('ALTER TABLE parameter ADD CONSTRAINT FK_2A9791102534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_2A9791102534008B ON parameter (structure_id)');
        $this->addSql('ALTER TABLE payum_gateway ADD structure_id INT DEFAULT NULL, ADD label VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE payum_gateway ADD CONSTRAINT FK_3BC0BD532534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_3BC0BD532534008B ON payum_gateway (structure_id)');
        $this->addSql('DROP INDEX IDX_5399B645CB944F1A ON receipt');
        $this->addSql('ALTER TABLE receipt ADD structure_id INT DEFAULT NULL, CHANGE student_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B6452534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_5399B645217BBB47 ON receipt (person_id)');
        $this->addSql('CREATE INDEX IDX_5399B6452534008B ON receipt (structure_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

    }
}
