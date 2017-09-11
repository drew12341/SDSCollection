<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
    <div class="col-md-12">
        <h1>Log In</h1>

        <?php if (isset($_SESSION['auth_message'])) : ?>
            <div class="alert alert-warning"><?=$_SESSION['auth_message'];?>
            </div>
        <?php endif; ?>


        <div id="infoMessage"><?php if (isset($message)){ echo $message; }?></div>
        <?= $this->form_builder->open_form(array('action' => ''));
        echo $this->form_builder->build_form_horizontal(
            array(
                array(
                    'id' => 'email'
                ),
                array(
                    'id' => 'password',
                    'type' => 'password',
                ),
                array(
                    'id' => 'remember',
                    'type' => 'checkbox',
                    'options' => array(
                        array(

                            'label' => 'Remember Me',
                            'value'=>TRUE,

                        ),
                    ),
                ),
                array(
                    'id' => 'submit',
                    'label' => 'Log In',
                    'type' => 'submit'
                )

            ));
?>
        <div class="form-group">

            <div class="col-sm-12">
                <a style="text-decoration: underline !important" href="<?php echo site_url('auth/forgot_password'); ?>">Forgot your password?</a><br/>
                <a style="text-decoration: underline !important" href="<?php echo site_url('User/register'); ?>">Create an account</a>
            </div>
        </div>


</div>
    </div>

