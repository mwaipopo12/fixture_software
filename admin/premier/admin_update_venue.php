<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
  
  
		if(isset($_POST['update_venue']))
		{
            $id = $_GET['id'];
            $name = $_POST['name'];
            $region = $_POST['region'];
            $quality =$_POST['quality'];
            $league_type=$_POST['league_type'];
         
            
            
            //sql to insert captured values
            $query="UPDATE  venue SET   name=?,  region=?, quality=? league_type=? WHERE id =?";
            $stmt = $mysql->prepare($query);
            $rc=$stmt->bind_param('ssssi', $name,   $region, $quality,$league_type,$id);
            $stmt->execute();

            if($stmt)
            {
                      $success = "venue  Updated";
                      
            }
            else {
              $err = "Please Try Again Or Try Later";
            }
			
			
		}
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
    <div class="header  pb-8 pt-5 pt-md-8" style="min-height: 300px; background-image: url(../../img/header-bg.jpg); background-size: cover; background-position: center top;">
        <span class="mask bg-gradient-default opacity-5"></span>
    </div>

    
        <div class="container-fluid mt--7">
            <?php
               
                $id = $_GET['id'];
                $ret="SELECT  * FROM venue WHERE id=?";
                $stmt= $mysql->prepare($ret) ;
                $stmt->bind_param('i',$id);
                $stmt->execute() ;
                $res=$stmt->get_result();
                while($row=$res->fetch_object())
            {
            ?>
            <div class="row">
                <div class="card col-md-12">
                    <h2 class="card-header">Update <?php echo $row->name;?> Details</h2>
                    <div class="card-body">
                        <!--Form-->
                        <form method="post" enctype="multipart/form-data" >
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Venue Name</label>
                                    <input type="text" value="<?php echo $row->name;?>" required name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Venue Region</label>
                                    <input type="text" value="<?php echo $row->region;?>" required name="region" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                              
                            </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                             <label for="bigteam">Venue Quality</label>
                             <select value ="<?php echo $row->quality;?>" required name="quality" class="form-control" id="bigteam">
                              <option value="light">light</option>
                              <option value="no light">No light</option>
                            </select>
                          </div>
                          
                        </div>  

                            
              </div> 
                            
                            <button type="submit" name="update_venue" class="btn btn-primary">Update Venue</button>
                        </form>
                        <!-- ./ Form -->
                    </div>    
                </div>
            </div>

            <?php }?>  
        </div>
  </div>
</body>

</html>