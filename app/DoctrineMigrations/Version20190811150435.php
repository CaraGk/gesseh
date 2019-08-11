<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190811150435 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE repartition ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repartition ADD CONSTRAINT FK_82B791A02534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_82B791A02534008B ON repartition (structure_id)');
        $this->addSql('ALTER TABLE period ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE period ADD CONSTRAINT FK_C5B81ECE2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_C5B81ECE2534008B ON period (structure_id)');
        $this->addSql('ALTER TABLE sector ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E82534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_4BA3D9E82534008B ON sector (structure_id)');
        $this->addSql('ALTER TABLE accreditation ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE accreditation ADD CONSTRAINT FK_3BF9D0D82534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_3BF9D0D82534008B ON accreditation (structure_id)');
        $this->addSql('ALTER TABLE hospital ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hospital ADD CONSTRAINT FK_4282C85B2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_4282C85B2534008B ON hospital (structure_id)');
        $this->addSql('ALTER TABLE grade ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE grade ADD CONSTRAINT FK_595AAE342534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_595AAE342534008B ON grade (structure_id)');
        $this->addSql('ALTER TABLE person ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD1762534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_34DCD1762534008B ON person (structure_id)');
        $this->addSql('ALTER TABLE wish ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wish ADD CONSTRAINT FK_D7D174C92534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_D7D174C92534008B ON wish (structure_id)');
        $this->addSql('ALTER TABLE simulation ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE simulation ADD CONSTRAINT FK_CBDA467B2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_CBDA467B2534008B ON simulation (structure_id)');
        $this->addSql('ALTER TABLE sector_rule ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sector_rule ADD CONSTRAINT FK_90B6D32D2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_90B6D32D2534008B ON sector_rule (structure_id)');
        $this->addSql('ALTER TABLE evaluation ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evaluation ADD CONSTRAINT FK_1323A5752534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_1323A5752534008B ON evaluation (structure_id)');
        $this->addSql('ALTER TABLE eval_form ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eval_form ADD CONSTRAINT FK_1BABA6B2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_1BABA6B2534008B ON eval_form (structure_id)');
        $this->addSql('ALTER TABLE eval_sector ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eval_sector ADD CONSTRAINT FK_52F3BF4F2534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_52F3BF4F2534008B ON eval_sector (structure_id)');
        $this->addSql('ALTER TABLE partner ADD structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E162534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('CREATE INDEX IDX_312B3E162534008B ON partner (structure_id)');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec('INSERT INTO structure (id, name, fullname, slug, activated, address, email, url, phone, area, areamap) VALUES (1, "site", "site", "site", 1, "", "", "", "", "", "")');
        $this->connection->exec('UPDATE repartition SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE period SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE sector SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE accreditation SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE hospital SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE grade SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE person SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE wish SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE simulation SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE sector_rule SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE eval_form SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE evaluation SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE eval_sector SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE partner SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE parameter SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE fee SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE payum_gateway SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE member_question SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE membership SET structure_id = "1" WHERE true');
        $this->connection->exec('UPDATE receipt SET structure_id = "1" WHERE true');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accreditation DROP FOREIGN KEY FK_3BF9D0D82534008B');
        $this->addSql('DROP INDEX IDX_3BF9D0D82534008B ON accreditation');
        $this->addSql('ALTER TABLE accreditation DROP structure_id');
        $this->addSql('ALTER TABLE eval_form DROP FOREIGN KEY FK_1BABA6B2534008B');
        $this->addSql('DROP INDEX IDX_1BABA6B2534008B ON eval_form');
        $this->addSql('ALTER TABLE eval_form DROP structure_id');
        $this->addSql('ALTER TABLE eval_sector DROP FOREIGN KEY FK_52F3BF4F2534008B');
        $this->addSql('DROP INDEX IDX_52F3BF4F2534008B ON eval_sector');
        $this->addSql('ALTER TABLE eval_sector DROP structure_id');
        $this->addSql('ALTER TABLE evaluation DROP FOREIGN KEY FK_1323A5752534008B');
        $this->addSql('DROP INDEX IDX_1323A5752534008B ON evaluation');
        $this->addSql('ALTER TABLE evaluation DROP structure_id');
        $this->addSql('ALTER TABLE grade DROP FOREIGN KEY FK_595AAE342534008B');
        $this->addSql('DROP INDEX IDX_595AAE342534008B ON grade');
        $this->addSql('ALTER TABLE grade DROP structure_id');
        $this->addSql('ALTER TABLE hospital DROP FOREIGN KEY FK_4282C85B2534008B');
        $this->addSql('DROP INDEX IDX_4282C85B2534008B ON hospital');
        $this->addSql('ALTER TABLE hospital DROP structure_id');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E162534008B');
        $this->addSql('DROP INDEX IDX_312B3E162534008B ON partner');
        $this->addSql('ALTER TABLE partner DROP structure_id');
        $this->addSql('ALTER TABLE period DROP FOREIGN KEY FK_C5B81ECE2534008B');
        $this->addSql('DROP INDEX IDX_C5B81ECE2534008B ON period');
        $this->addSql('ALTER TABLE period DROP structure_id');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD1762534008B');
        $this->addSql('DROP INDEX IDX_34DCD1762534008B ON person');
        $this->addSql('ALTER TABLE person DROP structure_id');
        $this->addSql('ALTER TABLE repartition DROP FOREIGN KEY FK_82B791A02534008B');
        $this->addSql('DROP INDEX IDX_82B791A02534008B ON repartition');
        $this->addSql('ALTER TABLE repartition DROP structure_id');
        $this->addSql('ALTER TABLE sector DROP FOREIGN KEY FK_4BA3D9E82534008B');
        $this->addSql('DROP INDEX IDX_4BA3D9E82534008B ON sector');
        $this->addSql('ALTER TABLE sector DROP structure_id');
        $this->addSql('ALTER TABLE sector_rule DROP FOREIGN KEY FK_90B6D32D2534008B');
        $this->addSql('DROP INDEX IDX_90B6D32D2534008B ON sector_rule');
        $this->addSql('ALTER TABLE sector_rule DROP structure_id');
        $this->addSql('ALTER TABLE simulation DROP FOREIGN KEY FK_CBDA467B2534008B');
        $this->addSql('DROP INDEX IDX_CBDA467B2534008B ON simulation');
        $this->addSql('ALTER TABLE simulation DROP structure_id');
        $this->addSql('ALTER TABLE wish DROP FOREIGN KEY FK_D7D174C92534008B');
        $this->addSql('DROP INDEX IDX_D7D174C92534008B ON wish');
        $this->addSql('ALTER TABLE wish DROP structure_id');
    }
}
