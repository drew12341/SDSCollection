<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="row">
    <div class="col-lg-12">
        <h1>Add New SDS</h1>
        <?php if (isset($_SESSION['aa_message'])) : ?>
            <div class="alert alert-success"><?= $_SESSION['aa_message']; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($record)): ?>
            <div>

                <table style="margin: 0 auto; width:80%" class=MsoTableGrid border=1 cellspacing=0 cellpadding=2 style='border-collapse:collapse;border:none'>
                    <tr><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Substance: </td>   <td class="text-left">&nbsp<mark><a href="<?=base_url('Sds/editSDS/').$record['id']?>"><?=$record['substance_name']?></a></mark>&nbsp</td></tr>
                    <tr><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>CAS No.:</td>       <td class="text-left">&nbsp<mark><?=$record['cas']?></mark>&nbsp</td></tr>
                    <tr><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Vendor:</td>    <td class="text-left">&nbsp<mark><?=$record['vendor']?></mark>&nbsp</td></tr>
                    <tr><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Expiry:</td>    <td class="text-left">&nbsp<mark><?=date("d/m/Y", strtotime($record['expiry']))?></mark>&nbsp</td></tr>
                </table>


            <br/>
            <a href="<?php echo site_url('Sds').'/newSds/1'?>" class="btn btn-primary" style="float:left">Upload another SDS</a>
            </div>
        <?php endif; ?>

        <div <?php if(isset($record)): ?> style="display:none"<?php endif;?> >
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

            <?= $this->form_builder->open_form(array('action' => '', 'id'=>"myDropzoneForm"));
            echo $this->form_builder->build_form_horizontal(
                array(
                    array(
                        'id' => 'substance_name',
                    ),

                    array(
                        'id' => 'cas',
                        'label'=>'CAS No.',
                        'placeholder'=>'1234567-12-1'
                    ),
                    array(
                        'id' => 'vendor',
                    ),
                    array(
                        'id' => 'published',
						'value'=>date("d/m/Y ", time()),
                        'data-provide'=>'datepicker',
                        'data-date-format'=>"dd/mm/yyyy",
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

					<div align = "left">
					<strong>NOTE:</strong></BR>
					- Only use SDS from the vendor/supplier, not a generic SDS.</BR>
					- <i>Substance Name</i> is the main product identifier on the SDS.</BR>
					- <i>CAS-No.</i> is found in section 3 of the SDS. &nbsp;If a <i>CAS-No.</i> does not exist, just enter "000-00-0". </BR>
					- <i>Published</i> is Date Last Updated (aka Creation Date or Revision Date) usually found in header or footer of SDS.
					</div>

        </div>
    </div>
</div>



<!--<script type="text/javascript" src="--><?php //echo base_url(); ?><!--assets/themes/default/js/dropzone.js"></script>-->
<!---->
<!--<script type="text/javascript">-->
<!--    Dropzone.options.myDropzoneForm = { // The camelized version of the ID of the form element-->
<!---->
<!--        // The configuration we've talked about above-->
<!--        autoProcessQueue: false,-->
<!--        uploadMultiple: true,-->
<!--        parallelUploads: 100,-->
<!--        maxFiles: 100,-->
<!---->
<!--        // The setting up of the dropzone-->
<!--        init: function() {-->
<!--            var myDropzone = this;-->
<!---->
<!--            // First change the button to actually tell Dropzone to process the queue.-->
<!--            this.element.querySelector("button[type=submit]").addEventListener("click", function(e) {-->
<!--                // Make sure that the form isn't actually being sent.-->
<!--                e.preventDefault();-->
<!--                e.stopPropagation();-->
<!--                myDropzone.processQueue();-->
<!--            });-->
<!---->
<!--            // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead-->
<!--            // of the sending event because uploadMultiple is set to true.-->
<!--            this.on("sendingmultiple", function() {-->
<!--                // Gets triggered when the form is actually being sent.-->
<!--                // Hide the success button or the complete form.-->
<!--            });-->
<!--            this.on("successmultiple", function(files, response) {-->
<!--                // Gets triggered when the files have successfully been sent.-->
<!--                // Redirect user or notify of success.-->
<!--            });-->
<!--            this.on("errormultiple", function(files, response) {-->
<!--                // Gets triggered when there was an error sending the files.-->
<!--                // Maybe show form again, and notify user of error-->
<!--            });-->
<!--        }-->
<!---->
<!--    }-->
<!---->
<!---->
<!--</script>-->