<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141023114440 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO parameter (name, value, active, activates_at, label, category, type) VALUES ('eval_block_unevaluated', true, 1, now(), 'Interdire l\'accès aux évaluations si des stages sont non évalués', 'Module Evaluation', 2)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM parameter WHERE name='eval_block_unevaluated'");
    }
}
