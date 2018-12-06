<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181206001617 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE action (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', creator_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', label VARCHAR(255) NOT NULL, score SMALLINT NOT NULL, type ENUM(\'user\', \'admin\'), INDEX IDX_47CC8C9261220EA6 (creator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE children (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', parent_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', school_class_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, borned_at DATETIME NOT NULL, INDEX IDX_A197B1BA727ACA70 (parent_id), INDEX IDX_A197B1BA14463F54 (school_class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE children_action (children_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', action_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_7D9D3E4F3D3D2749 (children_id), INDEX IDX_7D9D3E4F9D32F035 (action_id), PRIMARY KEY(children_id, action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school_class (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', teacher_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', school_level_id VARCHAR(255) NOT NULL, year_start INT NOT NULL, year_end INT NOT NULL, INDEX IDX_33B1AF8541807E1D (teacher_id), INDEX IDX_33B1AF85A1F77FE3 (school_level_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school_level (label VARCHAR(255) NOT NULL, PRIMARY KEY(label)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', api_token VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json_array)\', borned_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), UNIQUE INDEX UNIQ_8D93D6495126AC48 (mail), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C9261220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE children ADD CONSTRAINT FK_A197B1BA727ACA70 FOREIGN KEY (parent_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE children ADD CONSTRAINT FK_A197B1BA14463F54 FOREIGN KEY (school_class_id) REFERENCES school_class (id)');
        $this->addSql('ALTER TABLE children_action ADD CONSTRAINT FK_7D9D3E4F3D3D2749 FOREIGN KEY (children_id) REFERENCES children (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE children_action ADD CONSTRAINT FK_7D9D3E4F9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE school_class ADD CONSTRAINT FK_33B1AF8541807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE school_class ADD CONSTRAINT FK_33B1AF85A1F77FE3 FOREIGN KEY (school_level_id) REFERENCES school_level (label)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE children_action DROP FOREIGN KEY FK_7D9D3E4F9D32F035');
        $this->addSql('ALTER TABLE children_action DROP FOREIGN KEY FK_7D9D3E4F3D3D2749');
        $this->addSql('ALTER TABLE children DROP FOREIGN KEY FK_A197B1BA14463F54');
        $this->addSql('ALTER TABLE school_class DROP FOREIGN KEY FK_33B1AF85A1F77FE3');
        $this->addSql('ALTER TABLE action DROP FOREIGN KEY FK_47CC8C9261220EA6');
        $this->addSql('ALTER TABLE children DROP FOREIGN KEY FK_A197B1BA727ACA70');
        $this->addSql('ALTER TABLE school_class DROP FOREIGN KEY FK_33B1AF8541807E1D');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE children');
        $this->addSql('DROP TABLE children_action');
        $this->addSql('DROP TABLE school_class');
        $this->addSql('DROP TABLE school_level');
        $this->addSql('DROP TABLE user');
    }
}
