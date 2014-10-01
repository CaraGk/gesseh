<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140927151445 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO parameter (name, value, active, activates_at, label, category, type) VALUES ('eval_moderate', true, 1, now(), 'Activer la modération a priori', 'Evaluation', 2)");
        $this->addSql("UPDATE parameter set category='Module Evaluation' WHERE category='Evaluation'");
        $this->addSql("UPDATE parameter set category='Module Simulation' WHERE category='Simulation'");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM parameter WHERE name='eval_moderate'");
        $this->addSql("UPDATE parameter set category='Evaluation' WHERE category='Module Evaluation'");
        $this->addSql("UPDATE parameter set category='Simulation' WHERE category='Module Simulation'");
    }
}
