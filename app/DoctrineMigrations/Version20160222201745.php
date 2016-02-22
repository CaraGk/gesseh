<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160222201745 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18ADE95C867');
        $this->addSql('DROP INDEX IDX_CD1DE18ADE95C867 ON department');
        $this->addSql('ALTER TABLE department DROP sector_id, DROP head');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE department ADD sector_id INT DEFAULT NULL, ADD head VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18ADE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('CREATE INDEX IDX_CD1DE18ADE95C867 ON department (sector_id)');
    }

    /**
     * @param Schema $schema
     */
    public function postDown(Schema $schema)
    {
        $this->connection->exec('
            DROP PROCEDURE IF EXISTS copy_accreditation;
            CREATE PROCEDURE copy_accreditation()
            BEGIN
                DECLARE accreditation_exit INT DEFAULT FALSE;
                DECLARE var_head, var_username VARCHAR(255);
                DECLARE var_role LONGTEXT;
                DECLARE var_sector_id, var_department_id, var_accreditation_id, var_user_id INT;
                DECLARE accreditation_cursor CURSOR FOR SELECT id, supervisor, sector_id, department_id, user_id FROM accreditation;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET accreditation_exit = TRUE;
                SET var_user_id = 0;
                SET var_role = \'a:1:{i:0;s:12:"ROLE_TEACHER";}\';

                OPEN accreditation_cursor;
                department_loop: LOOP
                    FETCH accreditation_cursor INTO var_accreditation_id, var_head, var_sector_id, var_department_id, var_user_id;
                    IF accreditation_exit THEN
                        LEAVE accreditation_loop;
                    END IF;

                    UPDATE department SET sector = var_sector_id, head = var_head WHERE id = var_department_id;
                    DELETE FROM `user` WHERE id = var_user_id;
                END LOOP;
                CLOSE accreditation_cursor;
            END;
            CALL copy_accreditation();
            DROP PROCEDURE IF EXISTS copy_accreditation;
        ');
    }
}
