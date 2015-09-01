<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150901225255 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE member_info (id INT AUTO_INCREMENT NOT NULL, membership_id INT DEFAULT NULL, question_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_37011D0B1FB354CD (membership_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, payment SMALLINT NOT NULL, method VARCHAR(255) NOT NULL, payed_on DATETIME NOT NULL, expired_on DATETIME NOT NULL, studentId_id INT DEFAULT NULL, INDEX IDX_86FFD285DC83BA90 (studentId_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, more VARCHAR(255) NOT NULL, rank SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE member_info ADD CONSTRAINT FK_37011D0B1FB354CD FOREIGN KEY (membership_id) REFERENCES membership (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285DC83BA90 FOREIGN KEY (studentId_id) REFERENCES student (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE member_info DROP FOREIGN KEY FK_37011D0B1FB354CD');
        $this->addSql('DROP TABLE member_info');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE member_question');
    }
}
