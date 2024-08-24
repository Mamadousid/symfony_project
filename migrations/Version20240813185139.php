<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240813185139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, time_meal_id INT NOT NULL, minutes_id INT NOT NULL, typetable_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_booking DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', guest INT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E00CEDDE3711C7B0 (time_meal_id), INDEX IDX_E00CEDDE6D8939F6 (minutes_id), INDEX IDX_E00CEDDEB096A6D4 (typetable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE3711C7B0 FOREIGN KEY (time_meal_id) REFERENCES time_meal (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE6D8939F6 FOREIGN KEY (minutes_id) REFERENCES minutes_repas (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEB096A6D4 FOREIGN KEY (typetable_id) REFERENCES `table` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE3711C7B0');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE6D8939F6');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEB096A6D4');
        $this->addSql('DROP TABLE booking');
    }
}
