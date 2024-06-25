<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240625081506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEE3184009');
        $this->addSql('DROP INDEX IDX_E00CEDDEE3184009 ON booking');
        $this->addSql('ALTER TABLE booking DROP court_id, CHANGE start_date start_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD court_id INT NOT NULL, CHANGE start_date start_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEE3184009 FOREIGN KEY (court_id) REFERENCES court (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEE3184009 ON booking (court_id)');
    }
}
