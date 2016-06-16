<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160613173212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX strProductCode ON tblProductData');
        $this->addSql('ALTER TABLE tblProductData ADD strProductStock INT NOT NULL, ADD strProductCost DOUBLE PRECISION NOT NULL, DROP stmTimestamp, CHANGE intProductDataId intProductDataId INT AUTO_INCREMENT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData ADD stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, DROP strProductStock, DROP strProductCost, CHANGE intProductDataId intProductDataId INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX strProductCode ON tblProductData (strProductCode)');
    }
}
