<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170911174519 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO parameter (name, value, active, activates_at, label, category, type, more) VALUES (\'reg_name\', \'Association\', 1, now(), \'Le nom commun de la structure\', \'Module Adhesion\', 1, null)');
        $this->addSql('INSERT INTO parameter (name, value, active, activates_at, label, category, type, more) VALUES (\'reg_fullname\', \'Association de gestion des stages\', 1, now(), \'Le nom complet de la structure\', \'Module Adhesion\', 1, null)');
        $this->addSql('INSERT INTO parameter (name, value, active, activates_at, label, category, type, more) VALUES (\'reg_logo\', \'Uploads/logo.png\', 1, now(), \'Le lien vers le logo de la structure\', \'Module Adhesion\', 1, null)');
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
