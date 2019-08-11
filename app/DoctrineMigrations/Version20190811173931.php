<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190811173931 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750ECB944F1A');
        $this->addSql('DROP INDEX IDX_48DB750ECB944F1A ON placement');
        $this->addSql('ALTER TABLE placement CHANGE student_id person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750E217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_48DB750E217BBB47 ON placement (person_id)');
        $this->addSql('ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C96F95A435');
        $this->addSql('DROP INDEX IDX_D7D174C96F95A435 ON wish');
        $this->addSql('ALTER TABLE wish CHANGE simstudent sim_id INT NOT NULL');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C9F81AF80C FOREIGN KEY (sim_id) REFERENCES simulation (id)');
        $this->addSql('CREATE INDEX IDX_D7D174C9F81AF80C ON wish (sim_id)');
        $this->addSql('ALTER TABLE simulation DROP FOREIGN KEY FK_CBDA467BCB944F1A');
        $this->addSql('DROP INDEX IDX_CBDA467BCB944F1A ON simulation');
        $this->addSql('ALTER TABLE simulation CHANGE student_id person_id INT NOT NULL');
        $this->addSql('ALTER TABLE simulation ADD CONSTRAINT FK_CBDA467B217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_CBDA467B217BBB47 ON simulation (person_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750E217BBB47');
        $this->addSql('DROP INDEX IDX_48DB750E217BBB47 ON placement');
        $this->addSql('ALTER TABLE placement CHANGE person_id student_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750ECB944F1A FOREIGN KEY (student_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_48DB750ECB944F1A ON placement (student_id)');
        $this->addSql('ALTER TABLE simulation DROP FOREIGN KEY FK_CBDA467B217BBB47');
        $this->addSql('DROP INDEX IDX_CBDA467B217BBB47 ON simulation');
        $this->addSql('ALTER TABLE simulation CHANGE person_id student_id INT NOT NULL');
        $this->addSql('ALTER TABLE simulation ADD CONSTRAINT FK_CBDA467BCB944F1A FOREIGN KEY (student_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_CBDA467BCB944F1A ON simulation (student_id)');
        $this->addSql('ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C9F81AF80C');
        $this->addSql('DROP INDEX IDX_D7D174C9F81AF80C ON wish');
        $this->addSql('ALTER TABLE wish CHANGE sim_id simstudent INT NOT NULL');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C96F95A435 FOREIGN KEY (simstudent) REFERENCES simulation (id)');
        $this->addSql('CREATE INDEX IDX_D7D174C96F95A435 ON wish (simstudent)');
    }
}
