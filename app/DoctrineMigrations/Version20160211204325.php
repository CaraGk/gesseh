<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160211204325 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department DROP number, DROP cluster');
        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750EAE80F5DF');
        $this->addSql('ALTER TABLE placement DROP FOREIGN KEY FK_48DB750EEC8B7ADE');
        $this->addSql('DROP INDEX IDX_48DB750EEC8B7ADE ON placement');
        $this->addSql('DROP INDEX IDX_48DB750EAE80F5DF ON placement');
        $this->addSql('ALTER TABLE placement DROP department_id, DROP period_id');
    }

    /**
     * @var Schema $schema
     */
    public function postDown(Schema $schema)
    {
        $this->connection->exec('
            DROP PROCEDURE IF EXISTS copy_repartition;
            CREATE PROCEDURE copy_repartition()
            BEGIN
                DECLARE repartition_exit INT DEFAULT FALSE;
                DECLARE var_number SMALLINT;
                DECLARE var_cluster VARCHAR(255);
                DECLARE var_period_id, var_department_id, var_repartition_id INT;
                DECLARE repartition_cursor CURSOR FOR SELECT id, department_id, period_id, number, cluster FROM repartition ORDER BY period_id;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET repartition_exit = TRUE;

                OPEN repartition_cursor;
                repartition_loop: LOOP
                    FETCH repartition_cursor INTO var_repartition_id, var_department_id, var_period_id, var_number, var_cluster;
                    IF repartition_exit THEN
                        LEAVE repartition_loop;
                    END IF;

                    UPDATE department SET number = var_number, cluster = var_cluster WHERE id = var_department_id;
                    UPDATE placement SET period_id = var_period_id, department_id = var_department_id WHERE repartition_id = var_repartition_id;
                END LOOP;
                CLOSE repartition_cursor;
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

        $this->addSql('ALTER TABLE department ADD number SMALLINT DEFAULT NULL, ADD cluster VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE placement ADD department_id INT DEFAULT NULL, ADD period_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750EAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE placement ADD CONSTRAINT FK_48DB750EEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('CREATE INDEX IDX_48DB750EEC8B7ADE ON placement (period_id)');
        $this->addSql('CREATE INDEX IDX_48DB750EAE80F5DF ON placement (department_id)');
    }
}
