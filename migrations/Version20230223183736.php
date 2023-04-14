<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230223183736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adress (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(150) NOT NULL, city VARCHAR(50) NOT NULL, post_code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_info (id INT AUTO_INCREMENT NOT NULL, adress_id INT DEFAULT NULL, fistname VARCHAR(100) NOT NULL, lastname VARCHAR(100) NOT NULL, phone VARCHAR(20) DEFAULT NULL, username VARCHAR(50) NOT NULL, creation_date DATE NOT NULL, last_connection_date DATE DEFAULT NULL, last_update_date DATE DEFAULT NULL, birthdate DATE NOT NULL, UNIQUE INDEX UNIQ_B1087D9E8486F9AC (adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_info ADD CONSTRAINT FK_B1087D9E8486F9AC FOREIGN KEY (adress_id) REFERENCES adress (id)');
        $this->addSql('ALTER TABLE user ADD user_info_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649586DFF2 FOREIGN KEY (user_info_id) REFERENCES user_info (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649586DFF2 ON user (user_info_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649586DFF2');
        $this->addSql('ALTER TABLE user_info DROP FOREIGN KEY FK_B1087D9E8486F9AC');
        $this->addSql('DROP TABLE adress');
        $this->addSql('DROP TABLE user_info');
        $this->addSql('DROP INDEX UNIQ_8D93D649586DFF2 ON user');
        $this->addSql('ALTER TABLE user DROP user_info_id');
    }
}
