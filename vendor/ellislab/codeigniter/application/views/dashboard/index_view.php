<?php defined('BASEPATH') OR exit('No direct script access allowed');?>


<h4 >SDS</h4>
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
                <a class="btn btn-primary" href="ActionRegister/request/<?=$i['item_id'].$i['audit']?>">Open</a>
            </td>
            <td><?=$i['substance_name']?></td>
            <td><?=$i['link']?></td>
            <td><?=$i['uploader']?></td>
            <td><?=$i['cas']?><b> - No</b></td>


            <td><?=$i['vendor']?></td>
            <td ><?=$i['expiry']?></td>

            </td>

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
"order": [[1, "desc"]],


});

});
</script>