<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if($this->ion_auth->logged_in()): ?>
<a href="<?php echo site_url('Sds').'/newSds/'?>" class="btn btn-primary" style="float:left">Upload a SDS</a>
<?php endif; ?>

<table class="dashboard table table-striped outstanding table-bordered table-hover" style="border: 2px solid #ddd" cellspacing="0" width="100%">
    <thead>

    <tr>
        <th >&nbsp;</th>
        <th>ID</th>
        <th>Substance Name</th>


        <th>CAS</th>
        <th>Vendor</th>
        <th>SDS Publish Date</th>
        <th>Expiry</th>
        <th>Uploader</th>
        <th style="display:none"></th>
    </tr>
    </thead>
    <tbody>


    <?php foreach($sds as $i):

        $link = base_url().'files/';
        ?>
        <tr>
            <td >
                <?php if($this->ion_auth->logged_in() && ($this->ion_auth->user()->row()->id == $i['uploader'] || $this->ion_auth->is_admin())): ?>
                    <a class="btn btn-primary" href="<?php echo site_url('Sds').'/editSDS/'.$i['sds_id'];?>">Edit</a>
                    <a
                        class="btn btn-primary"
                        data-toggle="confirmation"
                        data-btn-ok-label="Delete" data-btn-ok-icon="glyphicon glyphicon-share-alt"
                        data-btn-ok-class="btn-primary"
                        data-btn-cancel-label="Cancel" data-btn-cancel-icon="glyphicon glyphicon-ban-circle"
                        data-btn-cancel-class="btn-danger"
                        data-title="Warning" data-content="This will delete this SDS"
                        href="<?php echo site_url('Sds').'/delete_sds/'.$i['sds_id']?>">Delete</a>
                <?php endif; ?>
            </td>
            <td><?= $i['sds_id'] ?></td>
            <td>
                <?php if($i['filename'] == '' || !file_exists(APPPATH."../files/".$i['filename'])) :?>
                    <?=$i['substance_name']?>
               <?php else : ?>
                    <a href="<?php echo base_url(); ?>Sds/get_file/<?=$i['sds_id']?>" ><?=$i['substance_name']?></a>

                <?php endif; ?>
                </td>


<!--
            <td><?=$i['cas']?></td>
-->

		<?php if ($i['cas'] == "000-00-0" or $i['cas'] == "00-00-0") : ?>
			<td>No CAS</td>
		<?php else : ?>
			<td><?=$i['cas']?></td>
		<?php endif; ?>



            <td><?=$i['vendor']?></td>
            <td ><?=date("Y-m-d", strtotime($i['published']))?></td>
            <td ><?=date("Y-m-d", strtotime($i['expiry']))?></td>

            <td><?=$i['first_name']?>&nbsp;<?=$i['last_name']?> </td>
            <td style="display:none"><?=$i['expired']?></td>
          </tr>

    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <th >&nbsp;</th>
        <th>ID</th>
        <th>Substance Name</th>
        <th>CAS</th>
        <th>Vendor</th>
        <th>Published</th>
        <th>Expiry</th>
        <th>Uploader</th>
        <th style="display:none"></th>
    </tr>
    </tfoot>
</table>
<script type="text/javascript">
$(document).ready(function() {

$('.table').DataTable({
"order": [[1, "desc"]],
    columnDefs: [{
        targets: [5,6  ],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'),
    },
        {
            targets: [0],
            bSortable: false
        }
    ],
    "createdRow": function( row, data, dataIndex ) {
        if ( data[8] == 1)  {
            $(row).addClass( 'expired' );
        }

    },
    "lengthMenu": [50, 100, 150, 200]

});

});

$('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    // other options
});
</script>