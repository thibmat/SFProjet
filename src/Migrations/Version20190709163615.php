<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190709163615 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, role_libelle VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(180) NOT NULL, user_mail VARCHAR(160) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_adress VARCHAR(255) DEFAULT NULL, user_code_postal INT DEFAULT NULL, user_ville VARCHAR(128) DEFAULT NULL, user_tel VARCHAR(20) DEFAULT NULL, is_valid TINYINT(1) DEFAULT NULL, token VARCHAR(32) NOT NULL, UNIQUE INDEX UNIQ_88BDF3E924A232CF (user_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, image_id INT DEFAULT NULL, author_id INT NOT NULL, annonce_titre VARCHAR(255) NOT NULL, annonce_texte LONGTEXT NOT NULL, is_published TINYINT(1) DEFAULT NULL, annonce_prix NUMERIC(9, 2) NOT NULL, INDEX IDX_CB988C6F12469DE2 (category_id), INDEX IDX_CB988C6F3DA5256D (image_id), INDEX IDX_CB988C6FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonces_user (annonces_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B755A8804C2885D7 (annonces_id), INDEX IDX_B755A880A76ED395 (user_id), PRIMARY KEY(annonces_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, categorie_mere_id INT DEFAULT NULL, category_libelle VARCHAR(128) NOT NULL, INDEX IDX_3AF34668665D6AAC (categorie_mere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, image_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FF675F31B FOREIGN KEY (author_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE annonces_user ADD CONSTRAINT FK_B755A8804C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces_user ADD CONSTRAINT FK_B755A880A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668665D6AAC FOREIGN KEY (categorie_mere_id) REFERENCES categories (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FF675F31B');
        $this->addSql('ALTER TABLE annonces_user DROP FOREIGN KEY FK_B755A880A76ED395');
        $this->addSql('ALTER TABLE annonces_user DROP FOREIGN KEY FK_B755A8804C2885D7');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F12469DE2');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668665D6AAC');
        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F3DA5256D');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE annonces');
        $this->addSql('DROP TABLE annonces_user');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE image');
    }
}
