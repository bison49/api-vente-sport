<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230301183948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, buyer_id INT DEFAULT NULL, seller_id INT NOT NULL, name VARCHAR(150) NOT NULL, description LONGTEXT NOT NULL, price INT NOT NULL, is_sold TINYINT(1) NOT NULL, date_for_sale DATE NOT NULL, date_sold DATE DEFAULT NULL, INDEX IDX_23A0E66BCF5E72D (categorie_id), INDEX IDX_23A0E666C755722 (buyer_id), INDEX IDX_23A0E668DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E666C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E668DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image_article ADD article_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image_article ADD CONSTRAINT FK_972A59BA7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('CREATE INDEX IDX_972A59BA7294869C ON image_article (article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_article DROP FOREIGN KEY FK_972A59BA7294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E666C755722');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E668DE820D9');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP INDEX IDX_972A59BA7294869C ON image_article');
        $this->addSql('ALTER TABLE image_article DROP article_id');
    }
}
