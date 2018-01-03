<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'type' => $this->integer()->notNull(),
            'logo' => $this->string(255),
            'caption' => $this->string(),
        ], $tableOptions);

        $this->createTable('{{%evaluation}}', [
            'id' => $this->primaryKey(),
            'project_id' => $this->integer()->notNull(),
            'image' => $this->string(255),
            'score' => $this->integer()->notNull()->defaultValue(0),
            'brief' => $this->string(100)->notNull(),
            'desc' => $this->string(1000)->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_evaluation_project', 'evaluation', 'project_id', 'project', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32),
            'type' => $this->integer()->notNull()->defaultValue(1),
            'project_id' => $this->integer()->notNull(),
            'index' => $this->integer()->notNull(),
            'topic' => $this->string(),
        ], $tableOptions);
        $this->addForeignKey('fk_question_project', 'question', 'project_id', 'project', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%question_option}}', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer()->notNull(),
            'index' => $this->smallInteger()->notNull(),
            'content' => $this->string(500),
            'score' => $this->integer()->defaultValue(0)->notNull()
        ], $tableOptions);
        $this->addForeignKey('fk_option_question', 'question_option', 'question_id', 'question', 'id', 'CASCADE', 'CASCADE');

        $this->createTable('{{%player}}', [
            'id' => $this->primaryKey(),
            'nickname' => $this->string(32)->notNull(),
            'head_image' => $this->integer(),
            'last_login_at' => $this->integer(),
            'register_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%player_answer}}', [
            'id' => $this->primaryKey(),
            'player_id' => $this->integer()->notNull(),
            'question_id' => $this->integer(),
            'option_index' => $this->integer()->notNull(),
            'time' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('fk_player_answer_player', 'player_answer', 'player_id', 'player', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_player_answer_question', 'player_answer', 'question_id', 'question', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_player_answer_question', 'player_answer');
        $this->dropForeignKey('fk_player_answer_player', 'player_answer');
        $this->dropForeignKey('fk_option_question', 'question_option');
        $this->dropForeignKey('fk_question_project', 'question');
        $this->dropForeignKey('fk_evaluation_project', 'evaluation');

        $this->dropTable('question_option');
        $this->dropTable('question');
        $this->dropTable('project');
        $this->dropTable('player');
        $this->dropTable('{{%user}}');
    }
}
