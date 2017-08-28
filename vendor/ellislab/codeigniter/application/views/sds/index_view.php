<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<a href="<?php echo site_url('Sds').'/newSds/'?>" class="btn btn-primary" style="float:left">Upload a SDS</a>
<h4>UTS Safety Data Sheet (SDS) Library</h4>
<table class="dashboard table table-striped outstanding table-bordered table-hover" style="border: 2px solid #ddd" cellspacing="0" width="100%">
    <thead>

    <tr>
        <th style="display:none">&nbsp;</th>
        <th>Substance Name</th>


        <th>CAS</th>
        <th>Vendor</th>
        <th>Published</th>
        <th>Expiry</th>
        <th>Uploader</th>
        <th style="display:none"></th>
    </tr>
    </thead>
    <tbody>

    <?php foreach($sds as $i):?>
        <tr>
            <td style="display:none">
                <a class="btn btn-primary" href="<?php echo site_url('Sds').'/edit/'.$i['id'];?>">Open</a>
            </td>
            <td><a href="<?=$i['link']?>" ><?=$i['substance_name']?></a></td>


            <td><?=$i['cas']?></td>


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
        <th style="display:none">&nbsp;</th>
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
        targets: [4, 5  ],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'),
    }],
    "createdRow": function( row, data, dataIndex ) {
        if ( data[7] == 1)  {
            $(row).addClass( 'expired' );
        }

    }

});

});
</script>