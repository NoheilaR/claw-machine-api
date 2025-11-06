<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251105203712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_settings (id INT AUTO_INCREMENT NOT NULL, claw_speed DOUBLE PRECISION NOT NULL, time_limit INT NOT NULL, difficulty VARCHAR(50) NOT NULL, item_spawn_rate DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_stats (id INT AUTO_INCREMENT NOT NULL, total_games INT NOT NULL, average_duration DOUBLE PRECISION NOT NULL, highest_score INT NOT NULL, last_updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, player_name VARCHAR(50) NOT NULL, score INT NOT NULL, duration DOUBLE PRECISION NOT NULL, played_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE game_settings');
        $this->addSql('DROP TABLE game_stats');
        $this->addSql('DROP TABLE score');
    }
}
