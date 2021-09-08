<?php

use yii\db\Migration;

/**
 * Class m210907_175239_add_link_table
 */
class m210907_175239_add_link_table extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS `link` (
              `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              `url` varchar(2048) NOT NULL COMMENT 'Url',
              `hash` char(8) NOT NULL COMMENT 'Hash',
              `follows_cnt` int UNSIGNED DEFAULT 0 COMMENT 'Follows actions count',
              `follows_limit` int UNSIGNED DEFAULT 0 COMMENT 'Follows limit',
              `expired_at` timestamp NULL DEFAULT NULL,
              `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `updated_at` timestamp NULL DEFAULT NULL,
              PRIMARY KEY (id),
              UNIQUE INDEX (`hash`)
            )
            ENGINE = INNODB
            CHARACTER SET utf8
            COLLATE utf8_unicode_ci;
        ");

        echo "m210907_175239_add_link_table has migrated up successfully.\n";
        return true;
    }

    public function down()
    {
        $this->execute("
            DROP TABLE IF EXISTS `link`;
        ");

        echo "m210907_175239_add_link_table has migrated down successfully.\n";
        return true;
    }
}
