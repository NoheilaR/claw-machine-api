<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration: Ajout des paramètres de spawn configurables à distance
 * - Supprime: claw_speed, difficulty, item_spawn_rate
 * - Ajoute: paramètres tokens (energy, bomb, blackout) et peluches (quantités, raretés)
 */
final class Version20260120150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute les paramètres de spawn configurables pour Unity (tokens et peluches)';
    }

    public function up(Schema $schema): void
    {
        // Supprimer les anciennes colonnes
        $this->addSql('ALTER TABLE game_settings DROP COLUMN claw_speed');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN difficulty');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN item_spawn_rate');

        // Ajouter les colonnes pour Energy Tokens
        $this->addSql('ALTER TABLE game_settings ADD energy_token_probability DOUBLE PRECISION NOT NULL DEFAULT 10.0');
        $this->addSql('ALTER TABLE game_settings ADD min_energy_tokens INT NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE game_settings ADD max_energy_tokens INT NOT NULL DEFAULT 3');

        // Ajouter les colonnes pour Bomb Tokens
        $this->addSql('ALTER TABLE game_settings ADD bomb_token_probability DOUBLE PRECISION NOT NULL DEFAULT 5.0');
        $this->addSql('ALTER TABLE game_settings ADD min_bomb_tokens INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE game_settings ADD max_bomb_tokens INT NOT NULL DEFAULT 2');

        // Ajouter les colonnes pour Blackout Tokens
        $this->addSql('ALTER TABLE game_settings ADD blackout_token_probability DOUBLE PRECISION NOT NULL DEFAULT 3.0');
        $this->addSql('ALTER TABLE game_settings ADD min_blackout_tokens INT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE game_settings ADD max_blackout_tokens INT NOT NULL DEFAULT 1');

        // Ajouter les colonnes pour Peluches - Quantités
        $this->addSql('ALTER TABLE game_settings ADD initial_plushie_count INT NOT NULL DEFAULT 15');
        $this->addSql('ALTER TABLE game_settings ADD min_plushies_before_respawn INT NOT NULL DEFAULT 3');
        $this->addSql('ALTER TABLE game_settings ADD max_plushies_in_bin INT NOT NULL DEFAULT 20');
        $this->addSql('ALTER TABLE game_settings ADD plushies_per_spawn INT NOT NULL DEFAULT 8');

        // Ajouter les colonnes pour Peluches - Raretés
        $this->addSql('ALTER TABLE game_settings ADD common_probability DOUBLE PRECISION NOT NULL DEFAULT 70.0');
        $this->addSql('ALTER TABLE game_settings ADD rare_probability DOUBLE PRECISION NOT NULL DEFAULT 25.0');
        $this->addSql('ALTER TABLE game_settings ADD legendary_probability DOUBLE PRECISION NOT NULL DEFAULT 5.0');
    }

    public function down(Schema $schema): void
    {
        // Restaurer les anciennes colonnes
        $this->addSql('ALTER TABLE game_settings ADD claw_speed DOUBLE PRECISION NOT NULL DEFAULT 5.0');
        $this->addSql('ALTER TABLE game_settings ADD difficulty VARCHAR(50) NOT NULL DEFAULT \'Medium\'');
        $this->addSql('ALTER TABLE game_settings ADD item_spawn_rate DOUBLE PRECISION NOT NULL DEFAULT 2.5');

        // Supprimer les nouvelles colonnes - Energy Tokens
        $this->addSql('ALTER TABLE game_settings DROP COLUMN energy_token_probability');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN min_energy_tokens');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN max_energy_tokens');

        // Supprimer les nouvelles colonnes - Bomb Tokens
        $this->addSql('ALTER TABLE game_settings DROP COLUMN bomb_token_probability');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN min_bomb_tokens');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN max_bomb_tokens');

        // Supprimer les nouvelles colonnes - Blackout Tokens
        $this->addSql('ALTER TABLE game_settings DROP COLUMN blackout_token_probability');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN min_blackout_tokens');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN max_blackout_tokens');

        // Supprimer les nouvelles colonnes - Peluches Quantités
        $this->addSql('ALTER TABLE game_settings DROP COLUMN initial_plushie_count');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN min_plushies_before_respawn');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN max_plushies_in_bin');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN plushies_per_spawn');

        // Supprimer les nouvelles colonnes - Peluches Raretés
        $this->addSql('ALTER TABLE game_settings DROP COLUMN common_probability');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN rare_probability');
        $this->addSql('ALTER TABLE game_settings DROP COLUMN legendary_probability');
    }
}
