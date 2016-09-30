<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160930170933 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL, CHANGE username_canonical username_canonical VARCHAR(180) NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE email_canonical email_canonical VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON user (confirmation_token)');
        $this->addSql('ALTER TABLE membership ADD method_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD28519883967 FOREIGN KEY (method_id) REFERENCES payum_gateway (id)');
        $this->addSql('CREATE INDEX IDX_86FFD28519883967 ON membership (method_id)');

        $this->addSql('INSERT INTO payum_gateway (gateway_name, factory_name) VALUES ("offline", "offline")');
        $this->addSql('INSERT INTO payum_gateway (gateway_name, factory_name) VALUES ("paypal", "paypal_express_checkout")');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec('DROP PROCEDURE IF EXISTS copy_gateway;');
        $this->connection->exec('
            CREATE PROCEDURE copy_gateway()
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE var_gateway_name VARCHAR(255);
                DECLARE var_gateway_id INT;
                DECLARE gt_cursor CURSOR FOR SELECT id, gateway_name FROM payum_gateway;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                OPEN gt_cursor;

                read_loop: LOOP
                    FETCH gt_cursor INTO var_gateway_id, var_gateway_name;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;

                    SET @query = CONCAT("UPDATE membership SET method_id = ", var_gateway_id, " WHERE method LIKE \'", var_gateway_name, "\'");
                    INSERT INTO debug (value1, value2, value3) VALUES (var_gateway_id, var_gateway_name, @query);
                    PREPARE stmt FROM @query;
                    EXECUTE stmt;
                END LOOP;

                CLOSE gt_cursor;
            END;
            CALL copy_gateway();
        ');
        $this->connection->exec('DROP PROCEDURE IF EXISTS copy_gateway;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD28519883967');
        $this->addSql('DROP INDEX IDX_86FFD28519883967 ON membership');
        $this->addSql('ALTER TABLE membership DROP method_id');
        $this->addSql('DROP INDEX UNIQ_8D93D649C05FB297 ON user');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, CHANGE username_canonical username_canonical VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, CHANGE email email VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, CHANGE email_canonical email_canonical VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci');
        $this->addSql('DELETE FROM payum_gateway');
    }
}
