<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<div class="row">
    <div class="col-lg-12">
        <h1>Edit SDS</h1>
        <?php if (isset($_SESSION['edit_message'])) : ?>
            <div class="alert alert-success"><?=$_SESSION['edit_message'];?>
            </div>
        <?php endif; ?>

        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <?= $this->form_builder->open_form(array('action' => ''));
        echo $this->form_builder->build_form_horizontal(
            array(
                array(
                    'id' => 'id',
                    'value'=>$dataSet['id'],
                    'type'=>'hidden'
                ),

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
                    'id' => 'link',
                    'label'=>'Link to SDS',
                    'value'=>$dataSet['link'],
                    'readonly'=>'true',
//                    'input_addons' => array(
//                        'post' => '<input name="userfile" id="userfile" label="Attach SDS (PDF)" class="form-control" value="" type="file">'
//                    ),
                ),

                array(
                    'id' => 'userfile',
                    'type' => 'file',
                    'label' => 'Replace SDS (PDF)',

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


