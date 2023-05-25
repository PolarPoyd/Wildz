<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230504174203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_dogs (user_id INT NOT NULL, dogs_id INT NOT NULL, INDEX IDX_4FBAA27AA76ED395 (user_id), INDEX IDX_4FBAA27AD0AFB20A (dogs_id), PRIMARY KEY(user_id, dogs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_dogs ADD CONSTRAINT FK_4FBAA27AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_dogs ADD CONSTRAINT FK_4FBAA27AD0AFB20A FOREIGN KEY (dogs_id) REFERENCES dogs (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_dogs DROP FOREIGN KEY FK_4FBAA27AA76ED395');
        $this->addSql('ALTER TABLE user_dogs DROP FOREIGN KEY FK_4FBAA27AD0AFB20A');
        $this->addSql('DROP TABLE user_dogs');
    }
}
