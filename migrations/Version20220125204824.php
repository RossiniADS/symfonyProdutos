<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220125204824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produto CHANGE categoria_id categoria_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE produto ADD CONSTRAINT FK_5CAC49D77E735794 FOREIGN KEY (categoria_id_id) REFERENCES categoria (id)');
        $this->addSql('CREATE INDEX IDX_5CAC49D77E735794 ON produto (categoria_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produto DROP FOREIGN KEY FK_5CAC49D77E735794');
        $this->addSql('DROP INDEX IDX_5CAC49D77E735794 ON produto');
        $this->addSql('ALTER TABLE produto CHANGE categoria_id_id categoria_id INT NOT NULL');
    }
}
