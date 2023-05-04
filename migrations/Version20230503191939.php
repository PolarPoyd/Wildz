<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503191939 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_cats (user_id INT NOT NULL, cats_id INT NOT NULL, INDEX IDX_B91CF65BA76ED395 (user_id), INDEX IDX_B91CF65B84200A6 (cats_id), PRIMARY KEY(user_id, cats_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_cats ADD CONSTRAINT FK_B91CF65BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cats ADD CONSTRAINT FK_B91CF65B84200A6 FOREIGN KEY (cats_id) REFERENCES cats (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_cats DROP FOREIGN KEY FK_B91CF65BA76ED395');
        $this->addSql('ALTER TABLE user_cats DROP FOREIGN KEY FK_B91CF65B84200A6');
        $this->addSql('DROP TABLE user_cats');
    }
}
