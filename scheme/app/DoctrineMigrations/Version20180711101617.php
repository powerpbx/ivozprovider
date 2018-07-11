<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180711101617 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
        'CREATE TABLE CallCsvSchedulers (
              id INT UNSIGNED AUTO_INCREMENT NOT NULL,
              iden VARCHAR(40) NOT NULL,
              unit VARCHAR(30) DEFAULT \'month\' NOT NULL COMMENT \'[enum:week|month|year]\',
              frequency SMALLINT UNSIGNED NOT NULL,
              email VARCHAR(140) NOT NULL,
              lastExecution DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime)\',
              nextExecution DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime)\',
              companyId INT UNSIGNED NOT NULL,
              INDEX IDX_100E171E2480E723 (companyId),
              UNIQUE INDEX invoiceScheduler_iden_company (iden, companyId),
              PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB
              '
        );
        $this->addSql('ALTER TABLE CallCsvSchedulers ADD CONSTRAINT FK_100E171E2480E723 FOREIGN KEY (companyId) REFERENCES Companies (id) ON DELETE CASCADE');

        $this->addSql('ALTER TABLE Companies ADD callRegistryNotificationTemplateId INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE Companies ADD CONSTRAINT FK_B52899E557E2A3 FOREIGN KEY (callRegistryNotificationTemplateId) REFERENCES NotificationTemplates (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_B52899E557E2A3 ON Companies (callRegistryNotificationTemplateId)');

        $this->addSql('ALTER TABLE NotificationTemplates CHANGE type type VARCHAR(25) NOT NULL COMMENT \'[enum:voicemail|fax|limit|lowbalance|invoice|callRegistry]\'');

        $this->addSql(
            'INSERT INTO NotificationTemplates (name, type) 
            VALUES ("Generic Call Registry Notification Template", "callRegistry")'
        );

        $this->addSql(
            'INSERT INTO NotificationTemplatesContents (
                  fromName,
                  fromAddress,
                  subject,
                  body,
                  notificationTemplateId,
                  languageId
              ) VALUES (
                "IvozProvider Notifications",
                "no-reply@ivozprovider.com",
                "Call registry available",
                CONCAT_WS(CHAR(10 using utf8),
                  "Greetings ${INVOICE_COMPANY}!",
                  "",
                  "Here you have your call history ",
                  "for the period ${INVOICE_DATE_IN} - ${INVOICE_DATE_OUT}.",
                  "",
                  "Check out attached file for further details.",
                  "",
                  "Best Regards,",
                  "IvozProvider"
                ),
                (SELECT id FROM NotificationTemplates WHERE brandId IS NULL and type = "callRegistry"),
                (SELECT id FROM Languages WHERE iden = "en")
             )'
        );

        $this->addSql(
            'INSERT INTO NotificationTemplatesContents (
                  fromName,
                  fromAddress,
                  subject,
                  body,
                  notificationTemplateId,
                  languageId
              ) VALUES (
                "Notificaciones IvozProvider",
                "no-reply@ivozprovider.com",
                "Factura disponible",
                CONCAT_WS(CHAR(10 using utf8),
                  "Hola ${INVOICE_COMPANY}!",
                  "",
                  "Aquí tienes tú registro de llamadas ",
                  "para el período ${INVOICE_DATE_IN} - ${INVOICE_DATE_OUT}.",
                  "",
                  "Consulte el fichero adjunto para más detalles. ",
                  "",
                  "Atentamente,",
                  "IvozProvider"
                ),
                (SELECT id FROM NotificationTemplates WHERE brandId IS NULL and type = "callRegistry"),
                (SELECT id FROM Languages WHERE iden = "es")
             )'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE CallCsvSchedulers');
        $this->addSql('ALTER TABLE Companies DROP FOREIGN KEY FK_B52899E557E2A3');
        $this->addSql('DROP INDEX IDX_B52899E557E2A3 ON Companies');
        $this->addSql('ALTER TABLE Companies DROP callRegistryNotificationTemplateId');
        $this->addSql('ALTER TABLE NotificationTemplates CHANGE type type VARCHAR(25) NOT NULL COLLATE utf8_unicode_ci COMMENT \'[enum:voicemail|fax|limit|lowbalance|invoice]\'');
    }
}
