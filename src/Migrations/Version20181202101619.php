<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181202101619 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE action CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE school_class CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE year_start year_start INT NOT NULL, CHANGE year_end year_end INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6495126AC48 ON user (mail)');
        $this->addSql('ALTER TABLE children CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE school_class_id school_class_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE children_action CHANGE children_id children_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE action_id action_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE action CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE children CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE school_class_id school_class_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE children_action CHANGE children_id children_id INT NOT NULL, CHANGE action_id action_id INT NOT NULL');
        $this->addSql('ALTER TABLE school_class CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE year_start year_start VARCHAR(4) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE year_end year_end VARCHAR(4) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_8D93D6495126AC48 ON user');
    }
}
