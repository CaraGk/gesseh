<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190811121817 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176FE19A1A8 FOREIGN KEY (grade_id) REFERENCES grade (id)');
        $this->addSql('ALTER TABLE person RENAME INDEX uniq_b723af33a76ed395 TO UNIQ_34DCD176A76ED395');
        $this->addSql('ALTER TABLE person RENAME INDEX idx_b723af33fe19a1a8 TO IDX_34DCD176FE19A1A8');
        $this->addSql('ALTER TABLE parameter ADD activates_at DATETIME DEFAULT NULL, ADD expires_at DATETIME DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter DROP activates_at, DROP expires_at');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176FE19A1A8');
        $this->addSql('ALTER TABLE person RENAME INDEX uniq_34dcd176a76ed395 TO UNIQ_B723AF33A76ED395');
        $this->addSql('ALTER TABLE person RENAME INDEX idx_34dcd176fe19a1a8 TO IDX_B723AF33FE19A1A8');
    }
}
