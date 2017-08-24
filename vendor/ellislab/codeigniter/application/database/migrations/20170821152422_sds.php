<?php

class Migration_sds extends CI_Migration {

    public function up() {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'substance_name'=>array(
                'type'=> 'varchar',
                'constraint'=>250,
                'null'=>true
            ),
            'link'=>array(
                'type'=> 'varchar',
                'constraint'=>250,
                'null'=>true
            ),
            'uploader'=>array(
                'type'=> 'int',
            ),
            'cas'=>array(
                'type'=> 'varchar',
                'constraint'=>50,
                'null'=>true
            ),
            'vendor'=>array(
                'type'=> 'varchar',
                'constraint'=>100,
                'null'=>true
            ),
            'expiry'=>array(
                'type'=>'datetime',
                'null'=>true
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('sds');
    }

    public function down() {
        $this->dbforge->drop_table('sds');
    }

}