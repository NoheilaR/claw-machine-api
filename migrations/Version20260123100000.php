<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260123100000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove legendary_probability column from game_settings';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_settings DROP legendary_probability');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE game_settings ADD legendary_probability DOUBLE PRECISION DEFAULT \'5\' NOT NULL');
    }
}
