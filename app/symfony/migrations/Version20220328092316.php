<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220328092316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, numero VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, active INT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, option_choice_id INT DEFAULT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), INDEX IDX_DADD4A25481D7651 (option_choice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, option_choice_id INT DEFAULT NULL, question_id INT DEFAULT NULL, INDEX IDX_D3B83589A76ED395 (user_id), INDEX IDX_D3B83589481D7651 (option_choice_id), INDEX IDX_D3B835891E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mark (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, quiz_id INT DEFAULT NULL, point DOUBLE PRECISION DEFAULT NULL, mark DOUBLE PRECISION DEFAULT NULL, INDEX IDX_6674F271A76ED395 (user_id), INDEX IDX_6674F271853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_choice (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_82FB58451E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, theme_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, INDEX IDX_A412FA92A76ED395 (user_id), INDEX IDX_A412FA9259027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, passwordReset VARCHAR(255) DEFAULT NULL, passwordDispatch VARCHAR(255) DEFAULT NULL, passwordResetDate DATETIME DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, role VARCHAR(255) NOT NULL, has_validated_cgu TINYINT(1) NOT NULL, reference VARCHAR(255) DEFAULT NULL, ip VARCHAR(255) DEFAULT NULL, validated INT DEFAULT NULL, active INT DEFAULT NULL, state INT DEFAULT NULL, gender VARCHAR(10) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25481D7651 FOREIGN KEY (option_choice_id) REFERENCES option_choice (id)');
        $this->addSql('ALTER TABLE answer_user ADD CONSTRAINT FK_D3B83589A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE answer_user ADD CONSTRAINT FK_D3B83589481D7651 FOREIGN KEY (option_choice_id) REFERENCES option_choice (id)');
        $this->addSql('ALTER TABLE answer_user ADD CONSTRAINT FK_D3B835891E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mark ADD CONSTRAINT FK_6674F271853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE option_choice ADD CONSTRAINT FK_82FB58451E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA92A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA9259027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25481D7651');
        $this->addSql('ALTER TABLE answer_user DROP FOREIGN KEY FK_D3B83589481D7651');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE answer_user DROP FOREIGN KEY FK_D3B835891E27F6BF');
        $this->addSql('ALTER TABLE option_choice DROP FOREIGN KEY FK_82FB58451E27F6BF');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271853CD175');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA9259027487');
        $this->addSql('ALTER TABLE answer_user DROP FOREIGN KEY FK_D3B83589A76ED395');
        $this->addSql('ALTER TABLE mark DROP FOREIGN KEY FK_6674F271A76ED395');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA92A76ED395');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE answer_user');
        $this->addSql('DROP TABLE mark');
        $this->addSql('DROP TABLE option_choice');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE user');
    }
}
