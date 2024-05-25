<?php
    session_start();
    include('inc/dbconn.php');
    if(isset($_POST['login']))
    {
        $email=$_POST['email'];
        $password=sha1(md5($_POST['password']));//double encrypt to increase security
        $stmt=$mysql->prepare("SELECT email , password , id FROM admin WHERE email=? AND password=? ");//sql to log in user
        $stmt->bind_param('ss', $email,$password);
        $stmt->execute();
        $stmt -> bind_result($email, $password, $admin_id);
        $rs=$stmt->fetch();
        $_SESSION['admin_id']= $admin_id; 
        
        if($rs)
            {
                header("location:premier/admin_dashboard.php");
               
            }
           
        else
            {
            
                $err = "Access Denied Please Check Your Credentials";
            }

    }
      
?>

<!DOCTYPE html>
<html lang="en">

<?php include("inc/head.php");?>

<body class="bg-default">
  <div class="main-content">
  
    <!-- Header -->
    <div class="header  py-7 py-lg-8 " style="min-height: 500px; background-image: url(../../img/bg.jpeg); background-size: cover; background-position: center top;">
    <span class="mask bg-gradient-default opacity-5"></span>
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-white">Fixture Generator Login</h1>
              
            </div>
          </div>
        </div>
      </div>
      <br><br>

      
    
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-5">
                <!--Login Form-->
              <form method = "post" role="form">
                <div class="form-group mb-3">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                    </div>
                    <input class="form-control" required name="email" placeholder="Email" type="email">
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group input-group-alternative">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                    </div>
                    <input class="form-control" required name="password" placeholder="Password" type="password">
                  </div>
                </div>
                <div class="custom-control custom-control-alternative custom-checkbox">
                  <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                  <label class="custom-control-label" for=" customCheckLogin">
                    <span class="text-muted">Remember me</span>
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" name="login" class="btn btn-primary my-4">Sign in</button>
                  <a href="admin_pwd_reset.php" class="btn btn-danger"><small>Forgot password?</small></a>
                </div>
              </form>

              <!-- ./ Login Form-->

            </div>
          </div>
    
        </div>
      </div>
    </div>
   

   
  </div>
  </div> 
 
</body>

</html>