<?php

use yii\db\Migration;

/**
 * Class m181108_103937_nfy_set_unique_categories
 */
class m181108_103937_nfy_set_unique_categories extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE {{nfy_subscription_categories}} ADD CONSTRAINT {{nfy_subscription_categories}}_unique_categories UNIQUE (subscription_id, category, is_exception);');
    }

    public function safeDown()
    {
        $this->execute('ALTER TABLE {{nfy_subscription_categories}} DROP CONSTRAINT {{nfy_subscription_categories}}_unique_categories;');
    }


    // Use up()/down() to run migration code without a transaction.
    // public function up()
    // {
    //     $this->execute('ALTER TABLE {{nfy_subscription_categories}} ADD CONSTRAINT {{nfy_subscription_categories}}_unique_categories UNIQUE (subscription_id, category, is_exception);');
    // }

    // public function down()
    // {
    //     $this->execute('ALTER TABLE {{nfy_subscription_categories}} DROP CONSTRAINT {{nfy_subscription_categories}}_unique_categories;');
    // }

}
