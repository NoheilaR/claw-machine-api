<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260121203155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_settings ADD glitch_probability DOUBLE PRECISION NOT NULL, ADD glitch_energy_threshold DOUBLE PRECISION NOT NULL, ADD glitch_change_interval DOUBLE PRECISION NOT NULL, CHANGE energy_token_probability energy_token_probability DOUBLE PRECISION NOT NULL, CHANGE min_energy_tokens min_energy_tokens INT NOT NULL, CHANGE max_energy_tokens max_energy_tokens INT NOT NULL, CHANGE bomb_token_probability bomb_token_probability DOUBLE PRECISION NOT NULL, CHANGE min_bomb_tokens min_bomb_tokens INT NOT NULL, CHANGE max_bomb_tokens max_bomb_tokens INT NOT NULL, CHANGE blackout_token_probability blackout_token_probability DOUBLE PRECISION NOT NULL, CHANGE min_blackout_tokens min_blackout_tokens INT NOT NULL, CHANGE max_blackout_tokens max_blackout_tokens INT NOT NULL, CHANGE initial_plushie_count initial_plushie_count INT NOT NULL, CHANGE min_plushies_before_respawn min_plushies_before_respawn INT NOT NULL, CHANGE max_plushies_in_bin max_plushies_in_bin INT NOT NULL, CHANGE plushies_per_spawn plushies_per_spawn INT NOT NULL, CHANGE common_probability common_probability DOUBLE PRECISION NOT NULL, CHANGE rare_probability rare_probability DOUBLE PRECISION NOT NULL, CHANGE legendary_probability legendary_probability DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_settings DROP glitch_probability, DROP glitch_energy_threshold, DROP glitch_change_interval, CHANGE energy_token_probability energy_token_probability DOUBLE PRECISION DEFAULT \'10\' NOT NULL, CHANGE min_energy_tokens min_energy_tokens INT DEFAULT 1 NOT NULL, CHANGE max_energy_tokens max_energy_tokens INT DEFAULT 3 NOT NULL, CHANGE bomb_token_probability bomb_token_probability DOUBLE PRECISION DEFAULT \'5\' NOT NULL, CHANGE min_bomb_tokens min_bomb_tokens INT DEFAULT 0 NOT NULL, CHANGE max_bomb_tokens max_bomb_tokens INT DEFAULT 2 NOT NULL, CHANGE blackout_token_probability blackout_token_probability DOUBLE PRECISION DEFAULT \'3\' NOT NULL, CHANGE min_blackout_tokens min_blackout_tokens INT DEFAULT 0 NOT NULL, CHANGE max_blackout_tokens max_blackout_tokens INT DEFAULT 1 NOT NULL, CHANGE initial_plushie_count initial_plushie_count INT DEFAULT 15 NOT NULL, CHANGE min_plushies_before_respawn min_plushies_before_respawn INT DEFAULT 3 NOT NULL, CHANGE max_plushies_in_bin max_plushies_in_bin INT DEFAULT 20 NOT NULL, CHANGE plushies_per_spawn plushies_per_spawn INT DEFAULT 8 NOT NULL, CHANGE common_probability common_probability DOUBLE PRECISION DEFAULT \'70\' NOT NULL, CHANGE rare_probability rare_probability DOUBLE PRECISION DEFAULT \'25\' NOT NULL, CHANGE legendary_probability legendary_probability DOUBLE PRECISION DEFAULT \'5\' NOT NULL');
    }
}
