<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= isset($title) ? $title : 'UTS SDS Collection'; ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <?php
    /** -- Copy from here -- */
    if(!empty($meta))
        foreach($meta as $name=>$content){
            echo "\n\t\t";
            ?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
        }
    echo "\n";

    if(!empty($canonical))
    {
        echo "\n\t\t";
        ?><link rel="canonical" href="<?php echo $canonical?>" /><?php

    }
    echo "\n\t";

    foreach($css as $file){
        echo "\n\t\t";
        ?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
    } echo "\n\t";

    foreach($js as $file){
        echo "\n\t\t";
        ?><script src="<?php echo $file; ?>"></script><?php
    } echo "\n\t";

    /** -- to here -- */
    ?>

    <!--- Loaded manually -->
    <link rel="icon"  type="image/x-icon"  href="<?php echo base_url(); ?>assets/images/favicon.ico" />
    <link rel="apple-touch-icon"           href="<?php echo base_url(); ?>assets/images/apple-touch-icon.png">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/themes/default/css/dataTables.bootstrap.css"/>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/tether.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/jquery-2.2.3.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/datatables.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/dataTables.foundation.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/jquery.dataTables.yadcf.js"></script>
     <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/moment.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/themes/default/js/dataTable.moment.js"></script>


    <link href="<?php echo base_url(); ?>assets/themes/default/css/scrolling-nav.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/additions.css" rel="stylesheet">
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

<nav class="navbar navbar-default navbar-fixed-top" role="navigation" >
    <a class="navbar-brand page-scroll" style="padding:0 3px 0 0 !important" href="<?php echo site_url(); ?>">

        <div style="float:left; padding-right:15px">
            <img src="<?php echo base_url(); ?>assets/images/uts-logo-University-of-Technology-Sydney.png" height="47px">
        </div>

        <div style="height:47px;color:white;width:321px;position:relative">
            <span style="bottom:0;right:0;padding-bottom:10px;padding-right:5px">UTS Safety Data Sheet (SDS) Collection</span>
        </div>

    </a>
</nav>
<section id="intro" class="intro-section">

    <div class="container">
        <?php if($this->load->get_section('text_header') != '') { ?>
            <h1><?php echo $this->load->get_section('text_header');?></h1>
        <?php }?>

        <!--<div class="row">-->
        <?php echo $output;?>
        <?php echo $this->load->get_section('sidebar'); ?>
        <!--</div>-->
        <hr/>

        <footer>
            <div class="row">

                <!--div class="span6 b10"-->
                <p style="text-align:center">
                    Copyright &copy; 2017 <a target="_blank" href="http://www.uts.edu.au">uts.edu.au</a> &nbsp; | &nbsp;
                    email <a href = "mailto:safetyandwellbeing@uts.edu.au">safetyandwellbeing@uts.edu.au</a> for support
                </p>
                <!--/div-->

            </div>
            <script type="text/javascript">
                //Auto scroll to any error messages
                $(document).ready(function (){
                    if ($(".alert").length) {
                        $('html, body').animate({
                            scrollTop: ($(".alert").first().offset().top)-61
                        },500);
                    }

                });
            </script>
        </footer>

    </div> <!-- /container -->
</section>
</body></html>
