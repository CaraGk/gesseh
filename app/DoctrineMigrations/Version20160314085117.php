<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160314085117 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO parameter (name, value, active, activates_at, label, category, type, more) VALUES (\'header_show\', \'both\', 1, now(), \'Afficher le logo ou le titre en entête ?\', \'General\', 3, \'a:4:{s:4:"none";s:5:"Aucun";s:4:"logo";s:4:"Logo";s:5:"title";s:5:"Titre";s:4:"both";s:8:"Les deux";}\')');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql("DELETE FROM parameter WHERE name = 'header_show'");
    }
}
