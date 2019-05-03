<?php

class Migration_remove_cloudstor extends CI_Migration {

    public function up() {

        $fields = array(
            'filename'=>array(
                'type'=>'varchar',
                'constraint'=>255,

            )
        );

        $this->dbforge->add_column('sds', $fields);
        $this->db->query("update sds set filename = substr(link, instr(link,'files=')+6)");
        $this->db->query("update sds set filename = substr(filename, instr(filename,'_')+1)");
    }

    public function down() {

    }

}