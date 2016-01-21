<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160121155331 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE repartition (id INT AUTO_INCREMENT NOT NULL, period_id INT DEFAULT NULL, department_id INT DEFAULT NULL, number SMALLINT DEFAULT NULL, cluster VARCHAR(100) DEFAULT NULL, INDEX IDX_82B791A0EC8B7ADE (period_id), INDEX IDX_82B791A0AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE repartition ADD CONSTRAINT FK_82B791A0EC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE repartition ADD CONSTRAINT FK_82B791A0AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE placement ADD repartition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750E826605A6 FOREIGN KEY (repartition_id) REFERENCES repartition (id)');
        $this->addSql('CREATE INDEX IDX_48DB750E826605A6 ON placement (repartition_id)');
    }

    /**
     * @var Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec('
            DROP PROCEDURE IF EXISTS copy_repartition;
            CREATE PROCEDURE copy_repartition()
            BEGIN
                DECLARE department_exit INT DEFAULT FALSE;
                DECLARE var_number SMALLINT;
                DECLARE var_cluster VARCHAR(255);
                DECLARE var_period_id, var_department_id, var_repartition_id INT;
                DECLARE department_cursor CURSOR FOR SELECT id, number, cluster FROM department;
                DECLARE period_cursor CURSOR FOR SELECT id FROM period;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET department_exit = TRUE;

                OPEN department_cursor;
                department_loop: LOOP
                    FETCH department_cursor INTO var_department_id, var_number, var_cluster;
                    IF department_exit THEN
                        LEAVE department_loop;
                    END IF;

                    OPEN period_cursor;
                    period_block: BEGIN
                        DECLARE period_exit INT DEFAULT FALSE;
                        DECLARE CONTINUE HANDLER FOR NOT FOUND SET period_exit = TRUE;
                        period_loop: LOOP
                            FETCH period_cursor INTO var_period_id;
                            IF period_exit THEN
                                LEAVE period_loop;
                            END IF;

                            INSERT INTO repartition (number, cluster, department_id, period_id) VALUES (var_number, var_cluster, var_department_id, var_period_id);
                            SET var_repartition_id = LAST_INSERT_ID();
                            UPDATE placement SET repartition_id = var_repartition_id WHERE department_id = var_department_id AND period_id = var_period_id;
                        END LOOP;
                    END period_block;
                    CLOSE period_cursor;
                END LOOP;
                CLOSE department_cursor;
            END;
            CALL copy_repartition();
            DROP PROCEDURE IF EXISTS copy_repartition;
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750E826605A6');
        $this->addSql('DROP TABLE repartition');
        $this->addSql('DROP INDEX IDX_48DB750E826605A6 ON placement');
        $this->addSql('ALTER TABLE placement DROP repartition_id');
    }
}
