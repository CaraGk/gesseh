<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160224154256 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accreditation DROP INDEX UNIQ_3BF9D0D8A76ED395, ADD INDEX IDX_3BF9D0D8A76ED395 (user_id)');
        $this->addSql("INSERT INTO parameter (name, value, active, activates_at, label, category, type) VALUES ('eval_block_min', '3', 1, NOW(), 'Nombre d\'évaluations minimales pour l\'affichage aux non-étudiants', 'Module Evaluation', 1)");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accreditation DROP INDEX IDX_3BF9D0D8A76ED395, ADD UNIQUE INDEX UNIQ_3BF9D0D8A76ED395 (user_id)');
        $this->addSql('DELETE FROM paramenter WHERE name = "eval_block_min"');
    }
}
