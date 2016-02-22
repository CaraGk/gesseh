<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160212194601 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accreditation (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, sector_id INT DEFAULT NULL, user_id INT DEFAULT NULL, supervisor VARCHAR(100) NOT NULL, INDEX IDX_3BF9D0D8AE80F5DF (department_id), INDEX IDX_3BF9D0D8DE95C867 (sector_id), UNIQUE INDEX UNIQ_3BF9D0D8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accreditation ADD CONSTRAINT FK_3BF9D0D8AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE accreditation ADD CONSTRAINT FK_3BF9D0D8DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('ALTER TABLE accreditation ADD CONSTRAINT FK_3BF9D0D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec('
            DROP PROCEDURE IF EXISTS copy_accreditation;
            CREATE PROCEDURE copy_accreditation()
            BEGIN
                DECLARE department_exit INT DEFAULT FALSE;
                DECLARE var_head, var_username VARCHAR(255);
                DECLARE var_role LONGTEXT;
                DECLARE var_sector_id, var_department_id, var_accreditation_id, var_user_id INT;
                DECLARE department_cursor CURSOR FOR SELECT id, head, sector_id FROM department;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET department_exit = TRUE;
                SET var_user_id = 0;
                SET var_role = \'a:1:{i:0;s:12:"ROLE_TEACHER";}\';

                OPEN department_cursor;
                department_loop: LOOP
                    FETCH department_cursor INTO var_department_id, var_head, var_sector_id;
                    IF department_exit THEN
                        LEAVE department_loop;
                    END IF;

                    SET var_username = CONCAT(\'supervisor_\', var_user_id, \'@example.fr\');
                    INSERT INTO `user` (username, username_canonical, email, email_canonical, enabled, roles) VALUES (var_username, var_username, var_username, var_username, false, var_role);
                    SET var_user_id = LAST_INSERT_ID();
                    INSERT INTO accreditation (supervisor, department_id, sector_id, user_id) VALUES (var_head, var_department_id, var_sector_id, var_user_id);
                    SET var_accreditation_id = LAST_INSERT_ID();
                END LOOP;
                CLOSE department_cursor;
            END;
            CALL copy_accreditation();
            DROP PROCEDURE IF EXISTS copy_accreditation;
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE accreditation');
    }
}
