<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261028180229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Catch-up migration: orders, coupons, contact, featured products, favorites, product references, avatars, extra user fields';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE additional_product_image (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, product_variation_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A7111DED4584665A (product_id), INDEX IDX_A7111DEDDC269DB3 (product_variation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, full_name VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(10) NOT NULL, company VARCHAR(180) NOT NULL, subject VARCHAR(100) DEFAULT NULL, message LONGTEXT NOT NULL, is_client TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4C62E63819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupons (id INT AUTO_INCREMENT NOT NULL, on_product_id INT DEFAULT NULL, on_category_id INT DEFAULT NULL, coupons_types_id INT NOT NULL, code VARCHAR(10) NOT NULL, description LONGTEXT NOT NULL, discount INT NOT NULL, currency_type VARCHAR(10) NOT NULL, max_usage INT NOT NULL, usage_count INT NOT NULL, validity DATETIME NOT NULL, is_valid TINYINT(1) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F564111877153098 (code), INDEX IDX_F5641118AADCD131 (on_product_id), INDEX IDX_F5641118C8C97D85 (on_category_id), INDEX IDX_F56411183DDD47B7 (coupons_types_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coupons_types (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorites (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, user_id INT NOT NULL, still_liked TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_E46960F54584665A (product_id), INDEX IDX_E46960F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE featured_product (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, display_order INT NOT NULL, INDEX IDX_728B14E04584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, coupons_id INT DEFAULT NULL, user_id INT NOT NULL, reference VARCHAR(20) NOT NULL, transporter_name VARCHAR(20) NOT NULL, transporter_price INT NOT NULL, is_paid TINYINT(1) NOT NULL, method VARCHAR(20) NOT NULL, stripe_session_id VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_E52FFDEEAEA34913 (reference), INDEX IDX_E52FFDEE6D72B15C (coupons_id), INDEX IDX_E52FFDEEA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders_details (id INT AUTO_INCREMENT NOT NULL, orders_id INT NOT NULL, product_id INT NOT NULL, size INT NOT NULL, quantity INT NOT NULL, price NUMERIC(10, 3) NOT NULL, INDEX IDX_835379F1CFFE9AD6 (orders_id), INDEX IDX_835379F14584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_reference (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, product_variation_id INT DEFAULT NULL, reference_code VARCHAR(20) NOT NULL, is_variant TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C003FF9ED6C838 (reference_code), UNIQUE INDEX UNIQ_C003FF9E4584665A (product_id), UNIQUE INDEX UNIQ_C003FF9EDC269DB3 (product_variation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_avatar (id INT AUTO_INCREMENT NOT NULL, avatar_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE additional_product_image ADD CONSTRAINT FK_A7111DED4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE additional_product_image ADD CONSTRAINT FK_A7111DEDDC269DB3 FOREIGN KEY (product_variation_id) REFERENCES product_variation (id)');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E63819EB6921 FOREIGN KEY (client_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coupons ADD CONSTRAINT FK_F5641118AADCD131 FOREIGN KEY (on_product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE coupons ADD CONSTRAINT FK_F5641118C8C97D85 FOREIGN KEY (on_category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE coupons ADD CONSTRAINT FK_F56411183DDD47B7 FOREIGN KEY (coupons_types_id) REFERENCES coupons_types (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F54584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE featured_product ADD CONSTRAINT FK_728B14E04584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6D72B15C FOREIGN KEY (coupons_id) REFERENCES coupons (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F1CFFE9AD6 FOREIGN KEY (orders_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders_details ADD CONSTRAINT FK_835379F14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_reference ADD CONSTRAINT FK_C003FF9E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_reference ADD CONSTRAINT FK_C003FF9EDC269DB3 FOREIGN KEY (product_variation_id) REFERENCES product_variation (id)');
        $this->addSql('ALTER TABLE category ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE product ADD color VARCHAR(255) NOT NULL, ADD color_code VARCHAR(255) NOT NULL, ADD weight NUMERIC(10, 0) NOT NULL, ADD country_of_origin VARCHAR(255) NOT NULL, ADD date_of_manufacture DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE price price NUMERIC(10, 2) NOT NULL');
        $this->addSql('ALTER TABLE product_size DROP FOREIGN KEY FK_7A2806CB498DA827');
        $this->addSql('DROP INDEX IDX_7A2806CB498DA827 ON product_size');
        $this->addSql('ALTER TABLE product_size ADD available_size_id INT DEFAULT NULL, CHANGE size_id product_variation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CBDC269DB3 FOREIGN KEY (product_variation_id) REFERENCES product_variation (id)');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CBC2DB8372 FOREIGN KEY (available_size_id) REFERENCES available_size (id)');
        $this->addSql('CREATE INDEX IDX_7A2806CBDC269DB3 ON product_size (product_variation_id)');
        $this->addSql('CREATE INDEX IDX_7A2806CBC2DB8372 ON product_size (available_size_id)');
        $this->addSql('ALTER TABLE product_variation ADD color_code VARCHAR(255) NOT NULL, ADD image_name VARCHAR(255) DEFAULT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP size, CHANGE stock_quantity available_stock INT NOT NULL, CHANGE additional_price price NUMERIC(10, 0) NOT NULL');
        $this->addSql('ALTER TABLE user ADD avatar_id INT DEFAULT NULL, ADD first_name VARCHAR(255) DEFAULT NULL, ADD last_name VARCHAR(255) DEFAULT NULL, ADD date_of_birth DATE DEFAULT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD gender VARCHAR(20) DEFAULT NULL, ADD hero_nickname VARCHAR(255) DEFAULT NULL, ADD street_address VARCHAR(255) DEFAULT NULL, ADD address_complement VARCHAR(255) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD department VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(100) DEFAULT \'France\' NOT NULL, ADD newsletter TINYINT(1) NOT NULL, ADD created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES user_avatar (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64986383B10 ON user (avatar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE additional_product_image DROP FOREIGN KEY FK_A7111DED4584665A');
        $this->addSql('ALTER TABLE additional_product_image DROP FOREIGN KEY FK_A7111DEDDC269DB3');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E63819EB6921');
        $this->addSql('ALTER TABLE coupons DROP FOREIGN KEY FK_F5641118AADCD131');
        $this->addSql('ALTER TABLE coupons DROP FOREIGN KEY FK_F5641118C8C97D85');
        $this->addSql('ALTER TABLE coupons DROP FOREIGN KEY FK_F56411183DDD47B7');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F54584665A');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE featured_product DROP FOREIGN KEY FK_728B14E04584665A');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE6D72B15C');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEA76ED395');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F1CFFE9AD6');
        $this->addSql('ALTER TABLE orders_details DROP FOREIGN KEY FK_835379F14584665A');
        $this->addSql('ALTER TABLE product_reference DROP FOREIGN KEY FK_C003FF9E4584665A');
        $this->addSql('ALTER TABLE product_reference DROP FOREIGN KEY FK_C003FF9EDC269DB3');
        $this->addSql('DROP TABLE additional_product_image');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE coupons');
        $this->addSql('DROP TABLE coupons_types');
        $this->addSql('DROP TABLE favorites');
        $this->addSql('DROP TABLE featured_product');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_details');
        $this->addSql('DROP TABLE product_reference');
        $this->addSql('DROP TABLE user_avatar');
        $this->addSql('ALTER TABLE category DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE product_size DROP FOREIGN KEY FK_7A2806CBDC269DB3');
        $this->addSql('ALTER TABLE product_size DROP FOREIGN KEY FK_7A2806CBC2DB8372');
        $this->addSql('DROP INDEX IDX_7A2806CBDC269DB3 ON product_size');
        $this->addSql('DROP INDEX IDX_7A2806CBC2DB8372 ON product_size');
        $this->addSql('ALTER TABLE product_size ADD size_id INT DEFAULT NULL, DROP product_variation_id, DROP available_size_id');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT FK_7A2806CB498DA827 FOREIGN KEY (size_id) REFERENCES available_size (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7A2806CB498DA827 ON product_size (size_id)');
        $this->addSql('ALTER TABLE product_variation ADD size DOUBLE PRECISION NOT NULL, DROP color_code, DROP image_name, DROP created_at, DROP updated_at, CHANGE available_stock stock_quantity INT NOT NULL, CHANGE price additional_price NUMERIC(10, 0) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_8D93D64986383B10 ON user');
        $this->addSql('ALTER TABLE user DROP avatar_id, DROP first_name, DROP last_name, DROP date_of_birth, DROP phone, DROP gender, DROP hero_nickname, DROP street_address, DROP address_complement, DROP postal_code, DROP city, DROP department, DROP country, DROP newsletter, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE product DROP color, DROP color_code, DROP weight, DROP country_of_origin, DROP date_of_manufacture, CHANGE price price NUMERIC(10, 3) NOT NULL');
    }
}
