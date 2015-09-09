<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150909154024 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE member_info ADD memberquestion_id INT DEFAULT NULL, DROP question_id');
        $this->addSql('ALTER TABLE member_info ADD CONSTRAINT FK_37011D0B9622182E FOREIGN KEY (memberquestion_id) REFERENCES member_question (id)');
        $this->addSql('CREATE INDEX IDX_37011D0B9622182E ON member_info (memberquestion_id)');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285DC83BA90');
        $this->addSql('DROP INDEX IDX_86FFD285DC83BA90 ON membership');
        $this->addSql('ALTER TABLE membership CHANGE studentid_id student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_86FFD285CB944F1A ON membership (student_id)');
        $this->addSql("INSERT INTO parameter (name, value, active, activates_at, label, category, type) VALUES ('reg_active', '1', 1, now(), 'Activer le module d\'adhésion', 'Module Adhesion', 2)");
        $this->addSql("INSERT INTO parameter (name, value, active, activates_at, label, category, type) VALUES ('reg_auto', '1', 1, now(), 'Autoriser les étudiants à s\'enregistrer directement sur le site', 'Module Adhesion', 2)");
        $this->addSql("INSERT INTO parameter (name, value, active, activates_at, label, category, type) VALUES ('reg_payment', '0', 1, now(), 'Montant de la cotisation', 'Module Adhesion', 1)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE member_info DROP FOREIGN KEY FK_37011D0B9622182E');
        $this->addSql('DROP INDEX IDX_37011D0B9622182E ON member_info');
        $this->addSql('ALTER TABLE member_info ADD question_id INT NOT NULL, DROP memberquestion_id');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285CB944F1A');
        $this->addSql('DROP INDEX IDX_86FFD285CB944F1A ON membership');
        $this->addSql('ALTER TABLE membership CHANGE student_id studentId_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285DC83BA90 FOREIGN KEY (studentId_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_86FFD285DC83BA90 ON membership (studentId_id)');
        $this->addSql("DELETE FROM parameter WHERE name='reg_active'");
        $this->addSql("DELETE FROM parameter WHERE name='reg_auto'");
    }
}
