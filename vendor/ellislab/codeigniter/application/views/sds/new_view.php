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
                    <tr></td><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Substance: </td>   <td class="text-left">&nbsp<mark><?=$record['substance_name']?></mark>&nbsp</td></tr>
                    <tr></td><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Link:</td>      <td class="text-left">&nbsp<mark><a href="<?=$record['link']?>">Link to File</a></mark>&nbsp</td></tr>
                    <tr></td><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>CAS:</td>       <td class="text-left">&nbsp<mark><?=$record['cas']?></mark>&nbsp</td></tr>
                    <tr></td><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Vendor:</td>    <td class="text-left">&nbsp<mark><?=$record['vendor']?></mark>&nbsp</td></tr>
                    <tr></td><td width=160 valign=top style='border:solid windowtext 1.0pt;padding:2px'>Expiry:</td>    <td class="text-left">&nbsp<mark><?=date("d/m/Y", strtotime($record['expiry']))?></mark>&nbsp</td></tr>
                </table>


            <br/>
            <a href="<?php echo site_url('Sds').'/newSds/'?>" class="btn btn-primary" style="float:left">Upload another SDS</a>
            </div>
        <?php endif; ?>

        <div <?php if(isset($record)): ?> style="display:none"<?php endif;?> >
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>

            <?= $this->form_builder->open_form(array('action' => ''));
            echo $this->form_builder->build_form_horizontal(
                array(
                    array(
                        'id' => 'substance_name',
                    ),
                    array(
                        'id' => 'userfile',
                        'type' => 'file',
                        'label' => 'File'
                    ),
                    array(
                        'id' => 'cas',
                    ),
                    array(
                        'id' => 'vendor',
                    ),
                    array(
                        'id' => 'expiry',
                        'value'=>date("d/m/Y ", time()),
                        'data-provide'=>'datepicker',
                        'data-date-format'=>"dd/mm/yyyy",
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
</div>
