<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if($this->ion_auth->logged_in() && ( $this->ion_auth->is_admin()||$this->ion_auth->in_group("edit",$this->ion_auth->user()->row()->id))): ?>
<a href="<?php echo site_url('Sds').'/newSds/'?>" class="btn btn-primary hidemobile" style="float:left">Upload a SDS</a>
<?php endif; ?>

<table class="dashboard table table-striped outstanding table-bordered table-hover wrap" style="border: 2px solid #ddd" cellspacing="0" width="100%">
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
                <input type="hidden" class="data" id="data<?=$i['sds_id']?>" value='<?=json_encode($i,JSON_HEX_APOS | JSON_PRETTY_PRINT);?>'/>
                <a id="more_<?=$i['sds_id']?>" class="btn btn-primary moretoggle tablebutton">More</a>
            </td>
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
        },
        {
            targets:[5,7],
            visible:false
        }
    ],
    "createdRow": function( row, data, dataIndex ) {
        if ( data[8] == 1)  {
            $(row).addClass( 'expired' );
        }

    },
    "lengthMenu": [50, 100, 150, 200],
    dom: 'Bfrtlip'
});

});

$('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    // other options
});
// Add event listener for opening and closing details
$('tbody').on('click', 'td a.moretoggle', function () {
    var tr = $(this).closest('tr');
    var table = $(this).closest('.table').DataTable();
    var row = table.row( tr );

    var d1 = jQuery.parseJSON($(this).closest('tr').find('.data').val());

    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
        $(this).text("More");
    }
    else {
        // Open this row
        row.child( format(d1) ).show("slow");
        tr.addClass('shown');
        $(this).text("Hide");
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]',
            // other options
        });
    }
} );

function format ( d ) {
    let logged_in = <?=$this->ion_auth->logged_in();?>;
    console.log(logged_in);
    let edit_sds_url = '<?= site_url('Sds');?>'+'/editSDS/'+d.sds_id;
    let delete_sds_url = '<?= site_url('Sds');?>'+'/delete_sds/'+d.sds_id;
    var logged_in_user = 0;
    var is_admin = false;
    var is_edit = false;
    if(logged_in){
        logged_in_user = <?=$this->ion_auth->user()->row()->id;?>;
        is_admin = '<?=$this->ion_auth->is_admin();?>';
        is_edit = '<?=$this->ion_auth->in_group("edit",$this->ion_auth->user()->row()->id);?>';
    }
    let display_buttons = (logged_in && logged_in_user == d.uploader || is_admin || is_edit);

    // `d` is the original data object for the row
    var returnstr =  '<table class="table table-bordered table-detail" style="padding-left:50px;white-space: pre-line;">'+
        '<tr>'+
        '<td class="text-right strong" style="width:50%">SDS Number:</td>'+
        '<td class="text-left">'+d.sds_id+'</td>'+
        '</tr>'+
        '<tr>'+
        '<td class="text-right strong">SDS Publish Date:</td>'+
        '<td class="text-left">'+(d.published != null ? moment( d.published).format('DD/MM/YYYY') : 'Not Known')+'</td>'+
        '</tr>'+
        '<tr>'+
        '<td class="text-right strong">Uploader:</td>'+
        '<td class="text-left">'+ d.first_name+' '+d.last_name+'</td>'+
        '</tr>';

       if (display_buttons){
           returnstr = returnstr+
            '<tr>' +
            '<td class="text-right strong">' +
            '<a class="btn btn-primary" href="' + edit_sds_url + '">Edit</a>' +
            '</td>' +
            '<td class="text-left">' +
            '<a class="btn btn-primary" data-toggle="confirmation"' +
            'data-btn-ok-label="Delete" data-btn-ok-icon="glyphicon glyphicon-share-alt"' +
            'data-btn-ok-class="btn-primary" data-btn-cancel-label="Cancel" ' +
            'data-btn-cancel-icon="glyphicon glyphicon-ban-circle" data-btn-cancel-class="btn-danger"' +
            'data-title="Warning" data-content="This will delete this SDS"' +
            'href="' + delete_sds_url + '">Delete</a>' +

            '</td>' +
            '</tr>';
        }
        returnstr = returnstr+'</table>';
       return returnstr;

}

</script>