
<!-- <div class="row"> -->
    
    <!--
    <div class ="row">
        <div class="col-sm-2">

        </div>

        <div class="col-sm-10">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Menu 1</a></li>
                <li><a href="#">Menu 2</a></li>
                <li><a href="#">Menu 3</a></li>
            </ul>
        </div>
    </div>
    -->

    <div class="row">
        <div class="col-md-2">
         <div class="panel panel-success">
             <div class="panel-heading">Control Panel</div>
             <div class="panel-body">
          <ul class="nav nav-pills nav-stacked">
            <!-- <li class="active"><a href="#">Overview</a></li> -->
            <li <?php if ($currentpage === 'usermgt'){ echo 'class="active"'; } ?>>
                <a href="<?php echo site_url("administrator/usermgt") ?>">User Management</a>
            </li>
            <li <?php if ($currentpage === 'crimemap'){ echo 'class="active"'; } ?>>
                <a href="<?php echo site_url("administrator/crime_map") ?>">Crime Map</a>
            </li>
            <li <?php if ($currentpage === 'user_reports'){ echo 'class="active"'; } ?>>
                <a href="<?php echo site_url("administrator/user_reports") ?>">User Reports</a>
            </li>
            <li <?php if ($currentpage === 'crime_pedia'){ echo 'class="active"'; } ?>>
                <a href="<?php echo site_url("administrator/crime_pedia") ?>">Crime Pedia</a>
            </li>
          </ul>
         </div> 
        </div>
            
     </div>
        <div class="col-md-10">
            <?php echo $inline_content ?>
        </div>
    </div>


<!-- </div> -->
