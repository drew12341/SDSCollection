<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="row">
    <div class="col-lg-12">
        <h1>Add New SDS</h1>
        <?php if (isset($_SESSION['aa_message'])) : ?>
            <div class="alert alert-success"><?=$_SESSION['aa_message'];?>
            </div>
        <?php endif; ?>

        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <?= $this->form_builder->open_form(array('action' => ''));
        echo $this->form_builder->build_form_horizontal(
            array(

                array(
                    'id' => 'substance_name',
                ),
                array(
                    'id' => 'userfile',
                    'type'=>'file'
                ),



                array(
                    'id' => 'cas',
                ),
                array(
                    'id' => 'vendor',
                ),
                array(
                    'id' => 'expiry',
                    'type'=>'date'
                ),


                array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'label'=>'Save'

                )
            )
        );
        echo $this->form_builder->close_form();
        ?>
    </div>
</div>
