<?php

use yii\db\Migration;
use yii\db\Schema;

class m160327_090130_initial extends Migration
{
    public function safeUp()
    {
        // Tables
        $this->createTable('user', [
            'id' => 'pk',
            'username' => "character varying(40) NOT NULL",
            'password' => "character varying(40) NOT NULL",
            'auth_key' => "character varying(40) NOT NULL",
            'access_token' => "character varying(40) NOT NULL",
            'project_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ]);
        
        $this->createTable('profile', [
            'id' => 'pk',
            'name' => "character varying(40) NOT NULL",
        ]);
        
        $this->createTable('project', [
            'id' => 'pk',
            'name' => "character varying(30) NOT NULL",
            'long_name' => "character varying(200) DEFAULT NULL",
        ]);
        
        $this->createTable('user_profile', [
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'profile_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->createIndex('idx_user_profile_user_id', 'user_profile', 'user_id', false);
        $this->createIndex('idx_user_profile_profile_id', 'user_profile', 'profile_id', false);
        $this->addPrimaryKey('pk_user_profile', 'user_profile', 'user_id, profile_id');
        
        $this->createTable('user_project', [
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'profile_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->createIndex('idx_user_project_user_id', 'user_project', 'user_id', false);
        $this->createIndex('idx_user_project_project_id', 'user_project', 'project_id', false);
        $this->createIndex('idx_user_project_profile_id', 'user_project', 'profile_id', false);
        $this->addPrimaryKey('pk_user_project', 'user_project', 'user_id, project_id, profile_id');
        
        $this->createTable('item', [
            'id' => 'pk',
            'code' => "character varying(40) DEFAULT NULL",
            'name' => "character varying(40) NOT NULL",
            'category' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'created' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . " DEFAULT NULL",
            'priority' => Schema::TYPE_INTEGER . " DEFAULT NULL",
            'project_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tree' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'lft' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' NOT NULL',
            'depth' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'icon' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'active' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'selected' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE NOT NULL',
            'disabled' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE NOT NULL',
            'readonly' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE NOT NULL',
            'visible' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'collapsed' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE NOT NULL',
            'movable_u' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'movable_d' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'movable_l' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'movable_r' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'removable' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE NOT NULL',
            'removable_all' => Schema::TYPE_BOOLEAN . ' DEFAULT FALSE NOT NULL',
            'icon_type' => Schema::TYPE_SMALLINT . ' DEFAULT 1 NOT NULL',
            'type' => "character varying(40) NOT NULL",
        ]);
        $this->createIndex('idx_item_project_id', 'item', 'project_id', false);
        
        $this->createTable('requirement_version', [
            'id' => 'pk',
            'requirement_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'version' => Schema::TYPE_INTEGER . " DEFAULT 1 NOT NULL",
            'revision' => Schema::TYPE_INTEGER . " DEFAULT 0 NOT NULL",
            'statement' => "text DEFAULT NULL",
            'updated' => Schema::TYPE_INTEGER . " NOT NULL",
        ]);
        $this->createIndex('idx_requirement_version_requirement_id', 'requirement_version', 'requirement_id', false);
        
        $this->createTable('requirement_event', [
            'id' => 'pk',
            'event' => "character varying(20) NOT NULL",
            'requirement_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->createIndex('idx_requirement_event_requirement_id', 'requirement_event', 'requirement_id', false);
        $this->createIndex('idx_requirement_event_user_id', 'requirement_event', 'user_id', false);
        
        $this->createTable('requirement_comment', [
            'id' => 'pk',
            'comment' => 'text DEFAULT NULL',
            'requirement_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date_creation' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->createIndex('idx_requirement_comment_requirement_id', 'requirement_comment', 'requirement_id', false);
        $this->createIndex('idx_requirement_comment_user_id', 'requirement_comment', 'user_id', false);
        
        $this->createTable('requirement_attachment', [
            'id' => 'pk',
            'name' => "character varying(40) NOT NULL",
            'path' => 'text DEFAULT NULL',
            'requirement_id' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        $this->createIndex('idx_requirement_attachment_requirement_id', 'requirement_attachment', 'requirement_id', false);
        
        // Foreign keys
        $this->addForeignKey('fk_user_profile_user_user_id', 'user_profile', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_profile_profile_profile_id', 'user_profile', 'profile_id', 'profile', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_project_user_user_id', 'user_project', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_project_project_project_id', 'user_project', 'project_id', 'project', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_project_profile_profile_id', 'user_project', 'profile_id', 'profile', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_item_project_project_id', 'item', 'project_id', 'project', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_version_requirement_requirement_id', 'requirement_version', 'requirement_id', 'item', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_event_requirement_requirement_id', 'requirement_event', 'requirement_id', 'item', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_event_user_user_id', 'requirement_event', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_comment_requirement_requirement_id', 'requirement_comment', 'requirement_id', 'item', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_comment_user_user_id', 'requirement_comment', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_attachment_requirement_requirement_id', 'requirement_attachment', 'requirement_id', 'item', 'id', 'CASCADE', 'CASCADE');
        
        // Default data
        $this->insert('user', [
            'username' => 'admin',
            'password' => 'admin',
            'auth_key' => 'admin',
            'access_token' => 'admin',
        ]);
        
        $this->insert('user', [
            'username' => 'user',
            'password' => 'user',
            'auth_key' => 'user',
            'access_token' => 'user',
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user_profile_user_user_id', 'user_profile');
        $this->dropForeignKey('fk_user_profile_profile_profile_id', 'user_profile');
        $this->dropForeignKey('fk_user_project_user_user_id', 'user_project');
        $this->dropForeignKey('fk_user_project_project_project_id', 'user_project');
        $this->dropForeignKey('fk_user_project_profile_profile_id', 'user_project');
        $this->dropForeignKey('fk_item_project_project_id', 'item');
        $this->dropForeignKey('fk_requirement_version_requirement_requirement_id', 'requirement_version');
        $this->dropForeignKey('fk_requirement_event_requirement_requirement_id', 'requirement_event');
        $this->dropForeignKey('fk_requirement_event_user_user_id', 'requirement_event');
        $this->dropForeignKey('fk_requirement_comment_requirement_requirement_id', 'requirement_comment');
        $this->dropForeignKey('fk_requirement_comment_user_user_id', 'requirement_comment');
        $this->dropForeignKey('fk_requirement_attachment_requirement_requirement_id', 'requirement_attachment');
        
        $this->dropTable('requirement_attachment');
        $this->dropTable('requirement_comment');
        $this->dropTable('requirement_event');
        $this->dropTable('requirement_version');
        $this->dropTable('item');
        $this->dropTable('user_project');
        $this->dropTable('user_profile');
        $this->dropTable('project');
        $this->dropTable('profile');
        $this->dropTable('user');
    }
}
