<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503162504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, sizes LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchased_products (id INT AUTO_INCREMENT NOT NULL, sale_id INT DEFAULT NULL, product_id INT DEFAULT NULL, product_name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, size VARCHAR(255) NOT NULL, qty INT NOT NULL, INDEX IDX_8825943E4A7E4868 (sale_id), INDEX IDX_8825943E4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sales (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, total_amount DOUBLE PRECISION NOT NULL, INDEX IDX_6B817044A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, credits DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE purchased_products ADD CONSTRAINT FK_8825943E4A7E4868 FOREIGN KEY (sale_id) REFERENCES sales (id)');
        $this->addSql('ALTER TABLE purchased_products ADD CONSTRAINT FK_8825943E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sales ADD CONSTRAINT FK_6B817044A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE purchased_products DROP FOREIGN KEY FK_8825943E4A7E4868');
        $this->addSql('ALTER TABLE purchased_products DROP FOREIGN KEY FK_8825943E4584665A');
        $this->addSql('ALTER TABLE sales DROP FOREIGN KEY FK_6B817044A76ED395');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE purchased_products');
        $this->addSql('DROP TABLE sales');
        $this->addSql('DROP TABLE `user`');
    }
}
