<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<a href="<?php echo site_url('Sds').'/newSds/'?>" class="btn btn-primary" style="float:left">Upload a SDS</a>
<h4>UTS Safety Data Sheet (SDS) Library</h4>
<table class="dashboard table table-striped outstanding table-bordered table-hover" style="border: 2px solid #ddd" cellspacing="0" width="100%">
    <thead>

    <tr>
        <th>&nbsp;</th>
        <th>Substance Name</th>
        <th>Link</th>
        <th>Uploader</th>
        <th>CAS</th>
        <th>Vendor</th>
        <th>Expiry</th>
    </tr>
    </thead>
    <tbody>

    <?php foreach($sds as $i):?>
        <tr>
            <td>
                <a class="btn btn-primary" href="<?php echo site_url('Sds').'/edit/'.$i['id'];?>">Open</a>
            </td>
            <td><?=$i['substance_name']?></td>
            <td><a href="<?=$i['link']?>" class="btn btn-primary">Download</a></td>
            <td><?=$i['uploader']?></td>
            <td><?=$i['cas']?></td>


            <td><?=$i['vendor']?></td>
            <td ><?=date("Y-m-d", strtotime($i['expiry']))?></td>


          </tr>

    <?php endforeach; ?>
    </tbody>
    <tfoot>
    <tr>
        <th>&nbsp;</th>
        <th>Substance Name</th>
        <th>Link</th>
        <th>Uploader</th>
        <th>CAS</th>
        <th>Vendor</th>
        <th>Expiry</th>
    </tr>
    </tfoot>
</table>
<script type="text/javascript">
$(document).ready(function() {

$('.table').DataTable({
"order": [[0, "desc"]],
    columnDefs: [{
        targets: [6],
        render: $.fn.dataTable.render.moment('YYYY-MM-DD', 'DD/MM/YYYY'),
    }]

});

});
</script>