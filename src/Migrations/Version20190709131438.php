<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190709131438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE annonces ADD CONSTRAINT FK_CB988C6FF675F31B FOREIGN KEY (author_id) REFERENCES app_user (id)');
        $this->addSql('CREATE INDEX IDX_CB988C6FF675F31B ON annonces (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annonces DROP FOREIGN KEY FK_CB988C6FF675F31B');
        $this->addSql('DROP INDEX IDX_CB988C6FF675F31B ON annonces');
        $this->addSql('ALTER TABLE annonces DROP author_id');
    }
}
