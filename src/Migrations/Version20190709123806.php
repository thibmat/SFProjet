<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190709123806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF3466812469DE2');
        $this->addSql('DROP INDEX IDX_3AF3466812469DE2 ON categories');
        $this->addSql('ALTER TABLE categories CHANGE category_id categorie_mere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668665D6AAC FOREIGN KEY (categorie_mere_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_3AF34668665D6AAC ON categories (categorie_mere_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668665D6AAC');
        $this->addSql('DROP INDEX IDX_3AF34668665D6AAC ON categories');
        $this->addSql('ALTER TABLE categories CHANGE categorie_mere_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF3466812469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('CREATE INDEX IDX_3AF3466812469DE2 ON categories (category_id)');
    }
}
