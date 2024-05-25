<?php
	session_start();
	include('inc/config.php');
		if(isset($_POST['reset_pwd']))
		{
      $email=$_POST['email'];
      $token=$_POST['token'];
      
      //sql to insert captured values
      $query="INSERT INTO pwd_resets (email, token) values(?,?)";
      $stmt = $mysqli->prepare($query);
      $rc=$stmt->bind_param('ss', $email, $token);
      $stmt->execute();

            if($stmt)
            {
                      $success = "Check Your Mail For Password Reset Instructions.";
                      
            }
            else {
              $err = "Please Try Again Or Try Later";
            }
			
			
		}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php");?>

<body class="bg-default">
  <div class="main-content">
    
  
    <!-- Header -->
    <div class="header  py-7 py-lg-8" style="min-height: 500px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
    <span class="mask bg-gradient-default opacity-5"></span>
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
                <h1 class="text-white">Password Reset</h1>
            </div>
          </div>
        </div>
      </div>
      
    <br><br><br><br>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <!-- Table -->
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="card bg-secondary shadow border-0">
          
            <div class="card-body px-lg-5 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Provide Your Email Inorder To Reset Password</small>
              </div>
              <form method = "post" role="form">
                
                <div class="form-group">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" name="email" placeholder="Email" type="email">
                  </div>
                </div>

                <div class="form-group" style="display:none">
                  <div class="input-group input-group-alternative mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                        <?php 
                          $length = 20;    
                          $number =  substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),1,$length);
                        ?>
                    <input class="form-control" name="token" value="<?php echo $number;?>" placeholder="Number" type="text">
                  </div>
                </div>

                <div class="text-center">
                  <button type="submit" name="reset_pwd" class="btn btn-primary mt-4">Reset Password</button>
                </div>
              </form>
            </div>
          </div>
         
          <div class="row mt-3">

            
          </div>
        </div>
      </div>
    </div>
   
  </div>  
  </div>

</body>

</html>