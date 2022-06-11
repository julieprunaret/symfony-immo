<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220611120029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens ADD status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE biens ADD CONSTRAINT FK_1F9004DD6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_1F9004DD6BF700BD ON biens (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE biens DROP FOREIGN KEY FK_1F9004DD6BF700BD');
        $this->addSql('DROP INDEX IDX_1F9004DD6BF700BD ON biens');
        $this->addSql('ALTER TABLE biens DROP status_id');
    }
}
