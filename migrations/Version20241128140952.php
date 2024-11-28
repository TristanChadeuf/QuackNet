<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128140952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ducks (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(180) NOT NULL, firstname VARCHAR(180) NOT NULL, lastname VARCHAR(180) NOT NULL, duckname VARCHAR(180) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F3E591AD90361416 ON ducks (duckname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON ducks (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__quack AS SELECT id, content, created_at FROM quack');
        $this->addSql('DROP TABLE quack');
        $this->addSql('CREATE TABLE quack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO quack (id, content, created_at) SELECT id, content, created_at FROM __temp__quack');
        $this->addSql('DROP TABLE __temp__quack');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ducks');
        $this->addSql('CREATE TEMPORARY TABLE __temp__quack AS SELECT id, content, created_at FROM quack');
        $this->addSql('DROP TABLE quack');
        $this->addSql('CREATE TABLE quack (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, content VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO quack (id, content, created_at) SELECT id, content, created_at FROM __temp__quack');
        $this->addSql('DROP TABLE __temp__quack');
    }
}
