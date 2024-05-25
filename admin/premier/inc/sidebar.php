 <?php
    $admin_id = $_SESSION['admin_id'];
    $ret="SELECT  * FROM  admin  WHERE id=?";
    $stmt= $mysql->prepare($ret) ;
    $stmt->bind_param('i',$admin_id);
    $stmt->execute() ;//ok
    $res=$stmt->get_result();
    //$cnt=1;
    while($row=$res->fetch_object())
    {
?> 
 <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">

    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="admin_dashboard.php">
        <img src="assets/logo/tff.png" class="navbar-brand-img" alt="..." height="300px" widith="400px">
      </a> 

      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="admin_dashboard.php">
                <img src="assets/logo/tff.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item   ">
          <strong><a class="btn btn-primary" href="admin_dashboard.php">
              <i class="ni ni-tv-2 text-primary"></i>Admin Panel
            </a></strong>
          </li>

          <li class="nav-item">
          <b><a class="nav-link " href="admin_add_league.php">
             Add Competition
            </a></b>
          </li>

          <li class="nav-item">
          <b><a class="nav-link " href="admin_add_dayMatch.php">
             Add Day 
            </a></b>
          </li>
          <li class="nav-item">
          <b><a class="nav-link " href="admin_add_venue.php">
            Add Venue
            </a></b>
          </li>
          <li class="nav-item">
          <b><a class="nav-link " href="admin_add_team.php">
            Add Team
            </a></b>
          </li>
          <li class="nav-item">
          <b><a class="nav-link " href="admin_add_event.php">
            Add Exceptional
            </a></b>
          </li>
      
          <li class="nav-item">
          <b><a class="nav-link " href="admin_manage_league.php">
             Manage Competition
            </a></b>
         </li>
         <li class="nav-item">
         <b><a class="nav-link " href="admin_manage_dayMatch.php">
            Manage Day 
            </a></b>
          </li>
          
          <li class="nav-item">
          <b><a class="nav-link " href="admin_manage_venue.php">
            Manage Venue
            </a></b>
           </li>
           <li class="nav-item">
           <b><a class="nav-link " href="admin_manage_team.php">
            Manage Team
            </a></b>
          </li>

          <li class="nav-item">
          <b><a class="nav-link " href="admin_manage_event.php">
            Manage Exceptional
            </a></b>
          </li>
          <li class="nav-item">
          <b><a class="nav-link " href="admin_generate_fixture.php">
            Generate Fixture
            </a></b>
          </li>
          <li class="nav-item">
          <b><a class="nav-link " href="admin_view_fixture.php">
            View Fixture
            </a></b>
          </li>
          <li class="nav-item">
            <b><a class="nav-link " href="admin_logout.php">
             Log Out
            </a></b>
          </li>

          
        </ul>
        
      </div>
    </div>
  </nav>

<?php }?>