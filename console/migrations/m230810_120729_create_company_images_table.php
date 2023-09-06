<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%company_images}}`.
 */
class m230810_120729_create_company_images_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%company_images}}', [
            'id' => $this->primaryKey(),
            'company_id' => $this->integer()->notNull(),
            'image_name' => $this->string()
        ]);

        $this->addForeignKey("FK_company_company_id", "company_images", "company_id", "company", "id", "NO ACTION", "NO ACTION");
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey('FK_company_company_id', 'company_images');
        $this->dropTable('{{%company_images}}');
    }
}
