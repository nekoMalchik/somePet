<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260609120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add user.balance column (custom MoneyType)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD balance VARCHAR(32) NOT NULL DEFAULT \'0 EUR\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP balance');
    }
}
