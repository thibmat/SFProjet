<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190709130820 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6F3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_CB988C6F3DA5256D ON annonces (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6F3DA5256D');
        $this->addSql('DROP INDEX IDX_CB988C6F3DA5256D ON annonces');
        $this->addSql('ALTER TABLE annonces DROP image_id');
    }
}
