<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160823153725 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285FD913E4D');
        $this->addSql('DROP INDEX UNIQ_86FFD2858789B572 ON membership');
        $this->addSql('ALTER TABLE membership ADD amount NUMERIC(2, 0) NOT NULL, ADD payment_id VARCHAR(255) DEFAULT NULL, DROP payment_instruction_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86FFD2854C3A3BB ON membership (payment_id)');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec('UPDATE membership SET amount = payment WHERE true');
        $this->connection->exec('UPDATE membership SET method = \'paypal\' WHERE method = 2');
        $this->connection->exec('UPDATE membership SET method = \'offline\' WHERE method = 1');
    }

    /**
     * @param Schema $schema
     */
    public function preDown(Schema $schema)
    {
        $this->connection->exec('UPDATE membership SET payment = amount WHERE true');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_86FFD2854C3A3BB ON membership');
        $this->addSql('ALTER TABLE membership ADD payment_instruction_id INT DEFAULT NULL, DROP amount, DROP payment_id');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285FD913E4D FOREIGN KEY (payment_instruction_id) REFERENCES payment_instructions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_86FFD2858789B572 ON membership (payment_instruction_id)');
    }
}
