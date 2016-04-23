<?php

use yii\db\Migration;

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
        
        $this->createTable('document', [
            'id' => 'pk',
            'name' => "character varying(40) NOT NULL",
            'project_id' => 'integer NOT NULL',
            'position' => 'integer DEFAULT 0 NOT NULL',
        ]);        
        $this->createIndex('idx_document_project_id', 'document', 'project_id', false);
        
        $this->createTable('section', [
            'id' => 'pk',
            'name' => "character varying(40) NOT NULL",
            'document_id' => 'integer NOT NULL',
            'position' => 'integer DEFAULT 0 NOT NULL',
        ]);
        $this->createIndex('idx_section_document_id', 'section', 'document_id', false);
        
        $this->createTable('user_profile', [
            'user_id' => 'integer NOT NULL',
            'profile_id' => 'integer NOT NULL',
        ]);
        $this->createIndex('idx_user_profile_user_id', 'user_profile', 'user_id', false);
        $this->createIndex('idx_user_profile_profile_id', 'user_profile', 'profile_id', false);
        $this->addPrimaryKey('pk_user_profile', 'user_profile', 'user_id, profile_id');
        
        $this->createTable('user_project', [
            'user_id' => 'integer NOT NULL',
            'project_id' => 'integer NOT NULL',
            'profile_id' => 'integer NOT NULL',
        ]);
        $this->createIndex('idx_user_project_user_id', 'user_project', 'user_id', false);
        $this->createIndex('idx_user_project_project_id', 'user_project', 'project_id', false);
        $this->createIndex('idx_user_project_profile_id', 'user_project', 'profile_id', false);
        $this->addPrimaryKey('pk_user_project', 'user_project', 'user_id, project_id, profile_id');
        
        $this->createTable('requirement', [
            'id' => 'pk',
            'code' => "character varying(10) NOT NULL",
            'type' => 'integer NOT NULL',
            'created' => 'integer NOT NULL',
            'section_id' => 'integer NOT NULL',
            'status' => "integer DEFAULT 1 NOT NULL",
            'priority' => "integer DEFAULT 1 NOT NULL",
        ]);
        $this->createIndex('idx_requirement_section_id', 'requirement', 'section_id', false);
        
        $this->createTable('requirement_version', [
            'id' => 'pk',
            'requirement_id' => 'integer NOT NULL',
            'version' => "integer DEFAULT 1 NOT NULL",
            'revision' => "integer DEFAULT 0 NOT NULL",
            'title' => "text NOT NULL",
            'description' => "text DEFAULT NULL",
            'updated' => "integer NOT NULL",
        ]);
        $this->createIndex('idx_requirement_version_requirement_id', 'requirement_version', 'requirement_id', false);
        
        $this->createTable('requirement_event', [
            'id' => 'pk',
            'event' => "character varying(20) NOT NULL",
            'requirement_id' => 'integer NOT NULL',
            'user_id' => 'integer NOT NULL',
            'date' => 'integer NOT NULL',
        ]);
        $this->createIndex('idx_requirement_event_requirement_id', 'requirement_event', 'requirement_id', false);
        $this->createIndex('idx_requirement_event_user_id', 'requirement_event', 'user_id', false);
        
        $this->createTable('requirement_comment', [
            'id' => 'pk',
            'comment' => 'text DEFAULT NULL',
            'requirement_id' => 'integer NOT NULL',
            'user_id' => 'integer NOT NULL',
            'date_creation' => 'integer NOT NULL',
        ]);
        $this->createIndex('idx_requirement_comment_requirement_id', 'requirement_comment', 'requirement_id', false);
        $this->createIndex('idx_requirement_comment_user_id', 'requirement_comment', 'user_id', false);
        
        $this->createTable('requirement_attachment', [
            'id' => 'pk',
            'name' => "character varying(40) NOT NULL",
            'path' => 'text DEFAULT NULL',
            'requirement_id' => 'integer NOT NULL',
        ]);
        $this->createIndex('idx_requirement_attachment_requirement_id', 'requirement_attachment', 'requirement_id', false);
        
        // Foreign keys
        $this->addForeignKey('fk_user_profile_user_user_id', 'user_profile', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_profile_profile_profile_id', 'user_profile', 'profile_id', 'profile', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_project_user_user_id', 'user_project', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_project_project_project_id', 'user_project', 'project_id', 'project', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_user_project_profile_profile_id', 'user_project', 'profile_id', 'profile', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_document_project_project_id', 'document', 'project_id', 'project', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_section_document_document_id', 'section', 'document_id', 'document', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_section_section_id', 'requirement', 'section_id', 'section', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_version_requirement_requirement_id', 'requirement_version', 'requirement_id', 'requirement', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_event_requirement_requirement_id', 'requirement_event', 'requirement_id', 'requirement', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_event_user_user_id', 'requirement_event', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_comment_requirement_requirement_id', 'requirement_comment', 'requirement_id', 'requirement', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_comment_user_user_id', 'requirement_comment', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_requirement_attachment_requirement_requirement_id', 'requirement_attachment', 'requirement_id', 'requirement', 'id', 'CASCADE', 'CASCADE');
        
        // Default data
        $this->insert('user', [
            'username' => 'admin',
            'password' => 'admin',
            'auth_key' => 'admin',
            'access_token' => 'admin',
        ]);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_user_profile_user_user_id', 'user_profile');
        $this->dropForeignKey('fk_user_profile_profile_profile_id', 'user_profile');
        $this->dropForeignKey('fk_user_project_user_user_id', 'user_project');
        $this->dropForeignKey('fk_user_project_project_project_id', 'user_project');
        $this->dropForeignKey('fk_user_project_profile_profile_id', 'user_project');
        $this->dropForeignKey('fk_document_project_project_id', 'document');
        $this->dropForeignKey('fk_section_document_document_id', 'section');
        $this->dropForeignKey('fk_requirement_section_section_id', 'requirement');
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
        $this->dropTable('requirement');
        $this->dropTable('section');
        $this->dropTable('document');
        $this->dropTable('user_project');
        $this->dropTable('user_profile');
        $this->dropTable('project');
        $this->dropTable('profile');
        $this->dropTable('user');
    }
}
