<?php

use yii\db\Migration;
use yii\db\Schema;

class m160327_090130_initial extends Migration
{
    public function safeUp()
    {
        // Tables
        $this->createTable('category', [
            'id' => 'pk',
            'name' => "character varying(20) NOT NULL",
            'order' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        
        $this->createTable('priority', [
            'id' => 'pk',
            'name' => "character varying(20) NOT NULL",
            'color' => "character varying(6) NOT NULL",
            'order' => Schema::TYPE_INTEGER . ' NOT NULL',
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
        
        $this->createTable('status', [
            'id' => 'pk',
            'code' => "character varying(3) NOT NULL",
            'name' => "character varying(30) NOT NULL",
            'color' => "character varying(6) NOT NULL",
            'order' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
        
        $this->createTable('user', [
            'id' => 'pk',
            'username' => "character varying(40) NOT NULL",
            'password' => "character varying(64) NOT NULL",
            'auth_key' => "character varying(40) NOT NULL",
            'access_token' => "character varying(40) NOT NULL",
            'project_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ]);
        $this->createIndex('idx_user_project_id', 'user', 'project_id', false);
        
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
            'reference' => "character varying(40) DEFAULT NULL",
            'name' => "character varying(40) NOT NULL",
            'category_id' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
            'created' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status_id' => Schema::TYPE_INTEGER . " DEFAULT NULL",
            'priority_id' => Schema::TYPE_INTEGER . " DEFAULT 3 NOT NULL",
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
            'title' => "character varying(255) DEFAULT NULL",
            'version' => Schema::TYPE_INTEGER . " DEFAULT 1 NOT NULL",
            'revision' => Schema::TYPE_INTEGER . " DEFAULT 0 NOT NULL",
            'wording' => "text NOT NULL",
            'justification' => "text DEFAULT NULL",
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
        $this->insert('category', [
            'name' => 'Functional',
            'order' => 1,
        ]);
        
        $this->insert('category', [
            'name' => 'Security',
            'order' => 2,
        ]);
        
        $this->insert('category', [
            'name' => 'Availability',
            'order' => 3,
        ]);
        
        $this->insert('category', [
            'name' => 'Performance',
            'order' => 4,
        ]);
        
        $this->insert('category', [
            'name' => 'Ergonomics',
            'order' => 5,
        ]);
        
        $this->insert('category', [
            'name' => 'Testability',
            'order' => 6,
        ]);
        
        $this->insert('category', [
            'name' => 'Constraint',
            'order' => 7,
        ]);
        
        $this->insert('category', [
            'name' => 'Other',
            'order' => 99,
        ]);
        
        $this->insert('user', [
            'username' => 'admin',
            'password' => '$2y$13$wx2PUMw9Rx5XprLE.uJ5ye0svd.znTvfGUc40zy0bMexDivWka5F6',
            'auth_key' => 'admin',
            'access_token' => 'admin',
        ]);
        
        $this->insert('user', [
            'username' => 'user',
            'password' => '$2y$13$OIpWTY95kcnt03vXnozzCuNeFvL6J2vPe78wby4578CStVX8Gdt/S',
            'auth_key' => 'user',
            'access_token' => 'user',
        ]);
        
        $this->insert('priority', [
            'name' => 'Low',
            'color' => 'cccccc',
            'order' => 4,
        ]);
        
        $this->insert('priority', [
            'name' => 'Medium',
            'color' => 'cccccc',
            'order' => 3,
        ]);
        
        $this->insert('priority', [
            'name' => 'High',
            'color' => 'cccccc',
            'order' => 2,
        ]);
        
        $this->insert('priority', [
            'name' => 'Critical',
            'color' => 'cccccc',
            'order' => 1,
        ]);
        
        $this->insert('profile', [
            'name' => 'Administrator',
        ]);
        
        $this->insert('profile', [
            'name' => 'Customer',
        ]);
        
        $this->insert('profile', [
            'name' => 'Developer',
        ]);
        
        $this->insert('status', [
            'code' => 'N',
            'name' => 'New',
            'color' => 'ffcced',
            'order' => 1,
        ]);
        
        $this->insert('status', [
            'code' => 'A',
            'name' => 'Accepted',
            'color' => 'ffcced',
            'order' => 2,
        ]);
        
        $this->insert('status', [
            'code' => 'V',
            'name' => 'Validated',
            'color' => 'ffcced',
            'order' => 3,
        ]);
        
        $this->insert('status', [
            'code' => 'R',
            'name' => 'Refused',
            'color' => 'ffcced',
            'order' => 4,
        ]);
        
        $this->insert('status', [
            'code' => 'I',
            'name' => 'Implemented',
            'color' => 'ffcced',
            'order' => 5,
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
        $this->dropTable('status');
        $this->dropTable('project');
        $this->dropTable('profile');
        $this->dropTable('priority');
        $this->dropTable('user');
        $this->dropTable('category');
    }
}
