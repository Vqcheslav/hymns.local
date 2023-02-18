<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230218143658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE couplet (id INT AUTO_INCREMENT NOT NULL, hymn_id SMALLINT NOT NULL, couplet VARCHAR(2000) NOT NULL, position SMALLINT NOT NULL, is_chorus TINYINT(1) NOT NULL, INDEX IDX_8AB2E3292A43AE1B (hymn_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(email)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE couplet ADD CONSTRAINT FK_8AB2E3292A43AE1B FOREIGN KEY (hymn_id) REFERENCES hymn (hymn_id)');
        $this->addSql('ALTER TABLE hymn DROP chorus, DROP couplets');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE couplet DROP FOREIGN KEY FK_8AB2E3292A43AE1B');
        $this->addSql('DROP TABLE couplet');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE hymn ADD chorus LONGTEXT NOT NULL, ADD couplets LONGTEXT NOT NULL');
    }
}
