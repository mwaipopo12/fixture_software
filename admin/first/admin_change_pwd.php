<?php
  session_start();
  include('inc/config.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
  if(isset($_POST['change_pwd']))
  {
      $admin_id = $_SESSION['admin_id'];
      $admin_pwd = $_POST['admin_pwd'];
     
      //sql to insert captured values
      $query="UPDATE fixture_admin SET admin_pwd=? WHERE admin_id =?";
      $stmt = $mysqli->prepare($query);
      $rc=$stmt->bind_param('si', $admin_pwd, $admin_id);
      $stmt->execute();

      if($stmt)
      {
                $success = "Password Updated";
                
      }
      else {
        $err = "Please Try Again Or Try Later";
      }
      
      
  }
?>

<!DOCTYPE html>
<html>

<?php include("inc/head.php");?>

<body>
  <!-- Sidenav -->
  <?php include("inc/sidebar.php");?>
  <!-- Main content -->
  
        <div class="main-content">
            <!-- Top navbar -->
            <?php include("inc/nav.php");?>
            <!-- Header -->
        <?php
        //Get single details of logged in user
            $admin_id = $_SESSION['admin_id'];
            $ret="SELECT  * FROM  fixture_admin  WHERE admin_id=?";
            $stmt= $mysqli->prepare($ret) ;
            $stmt->bind_param('i',$admin_id);
            $stmt->execute() ;//ok
            $res=$stmt->get_result();
            
            while($row=$res->fetch_object())
            {
        ?>
            <div class="header pb-8 pt-5 pt-lg-8 d-flex align-items-center" style="min-height: 600px; background-image: url(assets/img/theme/<?php echo $row->a_dpic;?>); background-size: cover; background-position: center top;">
            <!-- Mask -->
            <span class="mask bg-gradient-default opacity-8"></span>
            <!-- Header container -->
            <div class="container-fluid d-flex align-items-center">
                <div class="row">
                <div class="col-lg-12 col-md-10">
                    <h3 class="display-2 text-white">Hello <?php echo $row->admin_name;?></h3>
                </div>
                </div>
            </div>
            </div>
            <!-- Page content -->
            <div class="container-fluid mt--7">
            <div class="row">
                
                <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-8">
                        <h3 class="mb-0">Update My Password</h3>
                        </div>
                        <div class="col-4 text-right">
                        </div>
                    </div>
                    </div>
                    <div class="card-body">

                    <form method ="POST" enctype="multipart/form-data">
                        <h6 class="heading-small text-muted mb-4">Change Password</h6>
                        <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-username">Old Password</label>
                                    <input type="password" id="input-username" name="" class="form-control form-control-alternative" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">New Password</label>
                                    <input type="password"  name="password"  class="form-control form-control-alternative" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email"> Confirm New Password</label>
                                    <input type="password"  name=""  class="form-control form-control-alternative" >
                                </div>
                            </div>
                        </div>
                     
                        </div>
                        <div class="col-lg-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-outline-success" name="change_password" value="Change Password">
                                </div>
                        </div>

                    </form>
                    </div>
                </div>
                </div>
            </div>
            <?php }?>
        </div>
    </div>
</body>
</html>