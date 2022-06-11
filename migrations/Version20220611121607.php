<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220611121607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status ADD colors_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C5C002039 FOREIGN KEY (colors_id) REFERENCES colors (id)');
        $this->addSql('CREATE INDEX IDX_7B00651C5C002039 ON status (colors_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status DROP FOREIGN KEY FK_7B00651C5C002039');
        $this->addSql('DROP INDEX IDX_7B00651C5C002039 ON status');
        $this->addSql('ALTER TABLE status DROP colors_id');
    }
}
