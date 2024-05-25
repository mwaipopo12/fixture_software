<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
?>
<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php");?>

<body class="">
 <!--Sidebar-->
 <?php include("inc/sidebar.php");?>
  
  <div class="main-content">
    <!-- Navbar -->
   <?php include("inc/nav.php");?>
    <!-- End Navbar -->
    <!-- Header -->
    <div class="header  pb-8 pt-5 pt-md-8" style="min-height: 470px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
    <span class="mask bg-gradient-default opacity-5"></span>
      <div class="container-fluid">
        <div class="header-body">
          <!-- Card stats -->
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      
                      <p><b>Create Premier League Fixture for New Season</b></p>
                      
                    </div>
                    <div class="col-auto">
                      
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"></span>
                    <span class="text-nowrap"></span>
                  </p>

                  <div class="text-center mt-3">
                <a href="../premier/admin_add_league.php" class="btn btn-primary">Start</a>
               </div>

                </div>
              </div>
            </div>

            
            
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                    <p><b>Create Second League fixture for New Season</b> </p>
                    </div>
                    <div class="col-auto">
                    
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"></span>
                    <span class="text-nowrap"></span>
                  </p>

                  <div class="text-center mt-3">
                <a href="../second/admin_add_league.php" class="btn btn-primary">Start</a>
               </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                    <p><b>Create First League Fixture for New Season</b></p>
                    </div>
                    <div class="col-auto">
                    
                  </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-warning mr-2"></span>
                    <span class="text-nowrap"></span>
                  </p>
                  <div class="text-center mt-3">
                <a href="../first/admin_add_league.php" class="btn btn-primary">Start</a>
             </div>
                </div>
              </div>
            </div>

            

            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                    <p><b>Create Single Leg Fixture for New Season</b></p>
                    </div>
                    <div class="col-auto">
                      
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-warning mr-2"></span>
                    <span class="text-nowrap"></span>
                  </p>

                  <div class="text-center mt-3">
                <a href="" class="btn btn-primary">Start</a>
            </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid mt--7">
      <div class="row">
        <div class="col-xl-12">
          <div class="card shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <html>
                  <head>
                    <style>
                      .column{
                        width: 19%;
                        float: left;
                        padding: 10px;
                        text-align: center;
                      }

                      .column img{
                        width: 100%;
                        height: auto;
                      }

                      .logo-name{
                        margin-top: 10px;
                      }
                      </style>
                  </head>
                  <body>
                    <?php
                      $logos = array(
                        array(
                            'url' => 'assets/logo/simba.png',
                            'name' => 'Simba sport club'

                        ),
                        array(
                          'url' => 'assets/logo/yanga.jpeg',
                          'name' => 'Da Young Africa'

                      ),
                      array(
                        'url' => 'assets/logo/azam.png',
                        'name' => 'Azam fc'

                    ),
                    array(
                      'url' => 'assets/logo/singida.jpeg',
                      'name' => 'Singida star'

                  ),
                  array(
                    'url' => 'assets/logo/ihefu.jpeg',
                    'name' => 'Ihefu fc'
  
                ),
                  array(
                    'url' => 'assets/logo/prison.png',
                    'name' => 'Prison fc'

                ),
                
              array(
                'url' => 'assets/logo/kmc.jpeg',
                'name' => 'Kmc fc'

            ),
            array(
              'url' => 'assets/logo/mtibwa.png',
              'name' => 'Mtibwa fc'

          ),
          array(
            'url' => 'assets/logo/polisi.png',
            'name' => 'Polisi fc'

        ),
        array(
          'url' => 'assets/logo/namungo.jpeg',
          'name' => 'Namungo fc'

      ),
      array(
        'url' => 'assets/logo/kagera.jpeg',
        'name' => 'Kagera fc'

    ),
    array(
      'url' => 'assets/logo/mbeya.png',
      'name' => 'Mbeya fc'

  ),
  array(
    'url' => 'assets/logo/coast.png',
    'name' => 'Coast Union'

),
array(
  'url' => 'assets/logo/geita.jpeg',
  'name' => 'Geita Gold'

),
array(
  'url' => 'assets/logo/dodoma.jpeg',
  'name' => 'Dodoma Jiji'

),
);
 foreach ($logos as $logo){
  ?>
 
  <div class="column">
    <img src="<?php echo $logo['url']; ?>" alt="<?php echo $logo['name']; ?>">
    <p class ="logo-name"><?php echo $logo['name']; ?></p>
 </div>
 <?php
 }
 ?>
 <div style="clear:both;"></div>
</body>
</html>
</div>
</div>
</div>
</div>
</div> 
</div>
</div>
</html>
