<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if($this->ion_auth->logged_in() && ( $this->ion_auth->is_admin()||$this->ion_auth->in_group("edit",$this->ion_auth->user()->row()->id))): ?>
    <a href="<?php echo site_url('Sds').'/newSds/'?>" class="btn btn-primary hidemobile" style="float:left">Upload a SDS</a>
<?php endif; ?>

<table class="dashboard table table-striped outstanding table-bordered table-hover wrap" style="border: 2px solid #ddd" cellspacing="0" width="100%">
    <thead>
    <tr>

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

            <td><?= $i['sds_id'] ?></td>
            <td>
                <?php if($i['filename'] == '' || !file_exists(APPPATH."../files/".$i['filename'])) :?>
                    <?=$i['substance_name']?>
                <?php else : ?>
                    <a href="<?php echo base_url(); ?>Sds/get_file/<?=$i['sds_id']?>" ><?=$i['substance_name']?></a>
                <?php endif; ?>
            </td>
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
            "order": [[0, "desc"]],
            columnDefs: [{
                targets: [4,5  ],
                render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'),
            },

                {
                    targets:[4,6],
                    visible:false
                }
            ],
            "createdRow": function( row, data, dataIndex ) {
                if ( data[7] == 1)  {
                    $(row).addClass( 'expired' );
                }

            },
            "lengthMenu": [50, 100, 150, 200],
            dom: 'Bfrtlip'
        });

    });




</script>