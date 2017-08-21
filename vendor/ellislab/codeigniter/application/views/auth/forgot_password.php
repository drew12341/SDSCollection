<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-lg-12">

        <h1><?php echo lang('forgot_password_heading');?></h1>
        <p><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>

        <div id="infoMessage"><?php echo $message;?></div>

        <?php echo form_open("auth/forgot_password", "class='form-horizontal col-sm-12'"); ?>

        <div class="form-group">
            <div class="col-sm-3 control-label">
                <label for="identity"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label> <br />
            </div>
            <div class="col-sm-8">
                <?php echo form_input($identity, '', array('class'=>'form-control'));?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 control-label">
                &nbsp;
            </div>
            <div class="col-sm-8">
                <?php echo form_submit('submit', lang('forgot_password_submit_btn'), "class='btn btn-primary'"); ?>
            </div>
        </div>

        <?php echo form_close();?>
    </div>
</div>
