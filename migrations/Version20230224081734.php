<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224081734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info DROP is_active');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1087D9EF85E0677 ON user_info (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_B1087D9EF85E0677 ON user_info');
        $this->addSql('ALTER TABLE user_info ADD is_active TINYINT(1) NOT NULL');
    }
}
