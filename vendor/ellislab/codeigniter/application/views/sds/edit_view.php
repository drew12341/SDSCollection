<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="row">
    <div class="col-lg-12">
        <h1>Edit User</h1>
        <?php if (isset($_SESSION['edit_message'])) : ?>
            <div class="alert alert-success"><?=$_SESSION['edit_message'];?>
            </div>
        <?php endif; ?>

        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <?= $this->form_builder->open_form(array('action' => ''));
        echo $this->form_builder->build_form_horizontal(
            array(

                array(
                    'id' => 'substance_name',
                    'value'=>$dataSet['substance_name']
                ),

                array(
                    'id' => 'cas',
                    'label'=>'CAS',
                    'placeholder'=>'1234567-12-1',
                    'value'=>$dataSet['cas']
                ),
                array(
                    'id' => 'vendor',
                    'value'=>$dataSet['vendor']
                ),
                array(
                    'id' => 'published',
                    'value'=>date("d/m/Y ", time()),
                    'data-provide'=>'datepicker',
                    'data-date-format'=>"dd/mm/yyyy",
                    'value'=>date("d/m/Y", strtotime($dataSet['published']))
                ),

                array(
                    'id' => 'userfile',
                    'type' => 'file',
                    'label' => 'Attach SDS (PDF)',

                ),
                array(
                    'id' => 'submit',
                    'type' => 'submit',
                    'label' => 'Save'
                )
            )
        );
        echo $this->form_builder->close_form();
        ?>
    </div>
</div>

?>
</div>
</div>

