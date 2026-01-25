<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Ajout du token Stun avec duree d'immobilisation
 */
final class Version20260125100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add stun token fields to game_settings';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_settings ADD stun_token_probability DOUBLE PRECISION DEFAULT 5.0 NOT NULL');
        $this->addSql('ALTER TABLE game_settings ADD min_stun_tokens INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE game_settings ADD max_stun_tokens INT DEFAULT 2 NOT NULL');
        $this->addSql('ALTER TABLE game_settings ADD stun_duration DOUBLE PRECISION DEFAULT 2.0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_settings DROP stun_token_probability');
        $this->addSql('ALTER TABLE game_settings DROP min_stun_tokens');
        $this->addSql('ALTER TABLE game_settings DROP max_stun_tokens');
        $this->addSql('ALTER TABLE game_settings DROP stun_duration');
    }
}
