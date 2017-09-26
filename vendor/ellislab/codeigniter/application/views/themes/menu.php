<!-- Navigation -->
  <nav class="navbar navbar-default navbar-fixed-top" role="navigation" >
      <a class="navbar-brand page-scroll" style="padding:0 10px 0 0 !important" href="<?php echo site_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/Black-UTS-logo.png" height="47px"></a>

      <div class="container">
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse navbar-ex1-collapse">
              <ul class="nav navbar-nav">


                      <li>
                          <a id="defaulttab" class="page-scroll" href="<?php echo site_url('Sds'); ?>">SDS Collection</a>
                      </li>


                  <?php if($this->ion_auth->logged_in()): ?>



                          <?php if($this->ion_auth->is_admin()): ?>

                          <li class="dropdown">
                              <a class="dropdown-toggle" id="admintab" data-toggle="dropdown" href="#">Admin Functions<span class="caret"></span></a>
                              <ul class="dropdown-menu" id="admin">
                                  <li>
                                      <a class="page-scroll" href="<?php echo site_url('User'); ?>">Users</a>
                                  </li>
                                  <li class="divider"></li>


                              </ul><!-- end of dropdown menu -->
                          </li>
<!--

                              -->
                          <?php endif; ?>
                  <?php endif; ?>
                  </ul>

                    <ul class="nav navbar-nav" style="float:right">
                        <?php  if($this->ion_auth->logged_in()): ?>
                      <li><a class="page-scroll">Welcome, <?= $this->ion_auth->user()->row()->first_name;?>
                              <?=$this->ion_auth->user()->row()->last_name;?></a>
                      </li>
                            <li ><a class="page-scroll" href="<?php echo site_url('User/edit/').$this->ion_auth->user()->row()->id; ?>"><span class="glyphicon glyphicon-user"/></a>
                            </li>
                      <li >
                          <a class="page-scroll" href="<?php echo site_url('User/logout'); ?>"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>
                      </li>

                    <?php else: ?>
                      <li>
                          <a class="page-scroll" href="<?php echo site_url('User/login'); ?>"><span class="glyphicon glyphicon-log-in" aria-hidden="true"></span></a>
                      </li>

                        <?php endif; ?>
              </ul>

          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container -->
  </nav>
<script type="text/javascript">
    $(document).ready(function () {
        var active_link = $('.navbar li a[href~="<?=base_url($this->uri->segment(1))?>"]');
        //console.log(active_link.html());
        //console.log("<?=base_url($this->uri->segment(1));?>");
        if(active_link.size() > 0){
            active_link.addClass('active');
            if(active_link.parent().parent().prop("id") == 'admin') {
                //active_link.parent().parent().parent().addClass('active');
                $("#admintab").addClass('active');
            }
        }
        else{
            $("#defaulttab").addClass('active');
        }
    });

    //can check correct-loading of bootstrap with this
    //console.log((typeof $().emulateTransitionEnd == 'function'));

    //$('.dropdown-toggle').dropdown()
</script>