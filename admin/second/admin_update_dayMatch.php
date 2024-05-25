<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
  
  
		if(isset($_POST['update_day_match']))
		{
            $id = $_GET['id'];
            $name = $_POST['name'];
            $number_of_matches= $_POST['number_of_matches'];
           
         
            
            
            //sql to insert captured values
            $query="UPDATE  venue SET   name=?,  number_of_matches=?, WHERE id =?";
            $stmt = $mysql->prepare($query);
            $rc=$stmt->bind_param('sssi', $name,   $number_of_matches, $id);
            $stmt->execute();

            if($stmt)
            {
                      $success = "day  Updated";
                      
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
                $ret="SELECT  * FROM  day_match WHERE id=?";
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
                    <label for="name">Select a Day</label>
                    <select value ="<?php echo $row->name;?>"required name="name" class="form-control" id="name">
                    
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
              

              <div class="form-group col-md-6">
                  <label for="number_match">Number of Matches</label>
                  <input type="number" value ="<?php echo $row->number_match;?>" required name="number_match" class="form-control" id="number_match" aria-describedby="emailHelp">
              </div> 
          </div>

                         

                            
              </div> 
                            
                            <button type="submit" name="update_day_match" class="btn btn-primary">Update Day</button>
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