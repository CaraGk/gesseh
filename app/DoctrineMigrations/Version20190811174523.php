<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190811174523 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter DROP activates_at, DROP expires_at');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20160825214618")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20160826220514")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20160829180021")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20160929195514")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20160930102821")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20161113145951")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20161121125206")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20161125084335")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20161208070600")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170218062755")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170304161841")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170316093620")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170317071504")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170317113526")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170625091305")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170625094530")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170625104228")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170625110211")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170728171852")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20170914154343")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20180206163355")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20180207161819")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20181016161948")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20181028205116")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20181119220054")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20190517065735")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20190811173211")');
        $this->connection->exec('INSERT INTO migration_versions (version) VALUES ("20190811173219")');
        $this->connection->exec('UPDATE membership SET amount = amount*100 WHERE 1');
        $this->connection->exec("INSERT INTO parameter (name, value, active, label, category, type, more) VALUES ('placement_site_active', '1', 1, 'Activer le module de gestion des stages', 'Module Stage', 2, null)");
        $this->connection->exec('UPDATE parameter SET name = "eval_site_active" WHERE name = "eval_active"');
        $this->connection->exec('UPDATE parameter SET name = "simul_site_active" WHERE name = "simul_active"');
        $this->connection->exec('UPDATE parameter SET name = "eval_site_limit" WHERE name = "eval_limit"');
        $this->connection->exec('UPDATE parameter SET name = "eval_site_moderate" WHERE name = "eval_moderate"');
        $this->connection->exec('UPDATE parameter SET name = "eval_site_unevaluated" WHERE name = "eval_block_unevaluated"');
        $this->connection->exec('UPDATE parameter SET name = "eval_site_nonmember" WHERE name = "eval_block_nonmember"');
        $this->connection->exec('UPDATE parameter SET name = "eval_site_min" WHERE name = "eval_block_min"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_active" WHERE name = "reg_active"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_auto" WHERE name = "reg_auto"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_payment" WHERE name = "reg_payment"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_date" WHERE name = "reg_date"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_periodicity" WHERE name = "reg_periodicity"');
        $this->connection->exec('UPDATE parameter SET name = "eval_site_teacher" WHERE name = "reg_teacher_access"');
        $this->connection->exec('UPDATE parameter SET name = "general_show" WHERE name = "header_show"');
        $this->connection->exec('UPDATE parameter SET name = "general_title" WHERE name = "title"');
        $this->connection->exec('UPDATE parameter SET name = "general_color" WHERE name = "header_color"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_print" WHERE name = "reg_print"');
        $this->connection->exec('UPDATE parameter SET name = "reg_site_anticipated" WHERE name = "reg_anticipated"');
        $this->connection->exec("DELETE FROM parameter WHERE name = 'reg_name'");
        $this->connection->exec("DELETE FROM parameter WHERE name = 'reg_fullname'");
        $this->connection->exec("DELETE FROM parameter WHERE name = 'reg_logo'");
    }

    /**
     * @param Schema $schema
     */
    public function preDown(Schema $schema)
    {
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20160825214618"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20160826220514"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20160829180021"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20160929195514"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20160930102821"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20161113145951"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20161121125206"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20161125084335"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20161208070600"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170218062755"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170304161841"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170316093620"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170317071504"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170317113526"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170625091305"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170625094530"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170625104228"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170625110211"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170728171852"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20170914154343"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20180206163355"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20180207161819"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20181016161948"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20181028205116"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20181119220054"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20190517065735"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20190811173211"');
        $this->connection->exec('DELETE FROM migration_versions WHERE version="20190811173219"');
        $this->connection->exec('UPDATE membership SET amount = amount/100 WHERE 1');
        $this->connection->exec('UPDATE parameter SET name = "eval_active" WHERE name = "eval_site_active"');
        $this->connection->exec('UPDATE parameter SET name = "simul_active" WHERE name = "simul_site_active"');
        $this->connection->exec('UPDATE parameter SET name = "eval_limit" WHERE name = "eval_site_limit"');
        $this->connection->exec('UPDATE parameter SET name = "eval_moderate" WHERE name = "eval_site_moderate"');
        $this->connection->exec('UPDATE parameter SET name = "eval_block_unevaluated" WHERE name = "eval_site_unevaluated"');
        $this->connection->exec('UPDATE parameter SET name = "eval_block_nonmember" WHERE name = "eval_site_nonmember"');
        $this->connection->exec('UPDATE parameter SET name = "eval_block_min" WHERE name = "eval_site_min"');
        $this->connection->exec('UPDATE parameter SET name = "reg_active" WHERE name = "reg_site_active"');
        $this->connection->exec('UPDATE parameter SET name = "reg_auto" WHERE name = "reg_site_auto"');
        $this->connection->exec('UPDATE parameter SET name = "reg_payment" WHERE name = "reg_site_payment"');
        $this->connection->exec('UPDATE parameter SET name = "reg_date" WHERE name = "reg_site_date"');
        $this->connection->exec('UPDATE parameter SET name = "reg_periodicity" WHERE name = "reg_site_periodicity"');
        $this->connection->exec('UPDATE parameter SET name = "reg_teacher_access" WHERE name = "eval_site_teacher"');
        $this->connection->exec('UPDATE parameter SET name = "header_show" WHERE name = "general_show"');
        $this->connection->exec('UPDATE parameter SET name = "title" WHERE name = "general_title"');
        $this->connection->exec('UPDATE parameter SET name = "header_color" WHERE name = "general_color"');
        $this->connection->exec('UPDATE parameter SET name = "reg_print" WHERE name = "reg_site_print"');
        $this->connection->exec('UPDATE parameter SET name = "reg_anticipated" WHERE name = "reg_site_anticipated"');
        $this->connection->exec("DELETE FROM parameter WHERE name = 'placement_site_active'");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE parameter ADD activates_at DATETIME DEFAULT NULL, ADD expires_at DATETIME DEFAULT NULL');
    }
}
