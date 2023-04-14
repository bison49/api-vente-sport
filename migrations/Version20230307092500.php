<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230307092500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE date_sold date_sold DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user_info CHANGE last_connection_date last_connection_date DATETIME DEFAULT NULL, CHANGE last_update_date last_update_date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_info CHANGE last_connection_date last_connection_date DATE DEFAULT NULL, CHANGE last_update_date last_update_date DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE article CHANGE date_sold date_sold DATE DEFAULT NULL');
    }
}
