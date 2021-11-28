<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128184139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C17E3C61F9 FOREIGN KEY (owner_id) REFERENCES sshelter_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT fk_1505df84a76ed395');
        $this->addSql('DROP INDEX idx_1505df84a76ed395');
        $this->addSql('ALTER TABLE machine ADD category_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE machine ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE machine ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE machine RENAME COLUMN user_id TO owner_id');
        $this->addSql('COMMENT ON COLUMN machine.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN machine.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN machine.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF8412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT FK_1505DF847E3C61F9 FOREIGN KEY (owner_id) REFERENCES sshelter_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1505DF8412469DE2 ON machine (category_id)');
        $this->addSql('CREATE INDEX IDX_1505DF847E3C61F9 ON machine (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF8412469DE2');
        $this->addSql('ALTER TABLE machine DROP CONSTRAINT FK_1505DF847E3C61F9');
        $this->addSql('DROP INDEX IDX_1505DF8412469DE2');
        $this->addSql('DROP INDEX IDX_1505DF847E3C61F9');
        $this->addSql('ALTER TABLE machine ADD user_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE machine DROP category_id');
        $this->addSql('ALTER TABLE machine RENAME COLUMN owner_id TO user_id');
        $this->addSql('ALTER TABLE machine DROP created_at');
        $this->addSql('ALTER TABLE machine DROP updated_at');
        $this->addSql('COMMENT ON COLUMN machine.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE machine ADD CONSTRAINT fk_1505df84a76ed395 FOREIGN KEY (user_id) REFERENCES sshelter_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1505df84a76ed395 ON machine (user_id)');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C17E3C61F9');
    }
}
