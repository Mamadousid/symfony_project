<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823115612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Check if the column already exists
        if (!$schema->getTable('booking')->hasColumn('phone')) {
            $this->addSql('ALTER TABLE booking ADD phone VARCHAR(255) DEFAULT NULL');
        }

        // Check if the foreign key and index already exist
        $table = $schema->getTable('booking');
        if (!$table->hasForeignKey('FK_E00CEDDEA76ED395')) {
            $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        }
        if (!$table->hasIndex('IDX_E00CEDDEA76ED395')) {
            $this->addSql('CREATE INDEX IDX_E00CEDDEA76ED395 ON booking (user_id)');
        }
    }

    public function down(Schema $schema): void
    {
        // Remove the foreign key constraint and index if they exist
        $table = $schema->getTable('booking');
        if ($table->hasForeignKey('FK_E00CEDDEA76ED395')) {
            $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA76ED395');
        }
        if ($table->hasIndex('IDX_E00CEDDEA76ED395')) {
            $this->addSql('DROP INDEX IDX_E00CEDDEA76ED395 ON booking');
        }

        // Drop the phone column
        if ($table->hasColumn('phone')) {
            $this->addSql('ALTER TABLE booking DROP phone');
        }
    }
}
