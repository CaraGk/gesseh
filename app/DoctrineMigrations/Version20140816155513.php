<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140816155513 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE eval_criteria ADD required TINYINT(1) NOT NULL, ADD moderate TINYINT(1) NOT NULL");
        $this->addSql("ALTER TABLE parameter CHANGE value value VARCHAR(50) NOT NULL");
        $this->addSql("UPDATE eval_criteria SET required=true, moderate=false");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE eval_criteria DROP required, DROP moderate");
        $this->addSql("ALTER TABLE parameter CHANGE value value VARCHAR(50) DEFAULT NULL");
    }
}
