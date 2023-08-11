<?php

use yii\db\Migration;

/**
 * Class m230809_104100_creat_company_table
 */
class m230809_104100_create_company_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('{{%company}}', [
            'id' => $this->primaryKey(),
            'comapny_name' => $this->string()->notNull(),
            'address' => $this->text(),
            'company_email' => $this->string()->notNull()->unique(),
            'contact_number' => $this->string()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(2),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%company}}');
    }
}
