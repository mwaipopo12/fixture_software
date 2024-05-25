<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
  
  
		if(isset($_POST['update_team']))
		{
            $id = $_GET['id'];
            $name = $_POST['name'];
            $region = $_POST['region'];
            $big_team =$_POST['big_team'];
            $caf_qualifier = $_POST['caf_qualifier'];
            $venue_id = $_POST['venue_id'];
            
            
            //sql to insert captured values
            $query="UPDATE  team SET   name=?,  region=?, big_team=?, caf_qualifier=?, venue_id=? WHERE id =?";
            $stmt = $mysql->prepare($query);
            $rc=$stmt->bind_param('sssssi', $name,   $region, $big_team, $caf_qualifier, $venue_id,$id);
            $stmt->execute();

            if($stmt)
            {
                      $success = "Team  Updated";
                      
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
                $ret="SELECT  * FROM  team WHERE id=?";
                $stmt= $mysql->prepare($ret) ;
                $stmt->bind_param('i',$id);
                $stmt->execute() ;
                $res=$stmt->get_result();
                while($row=$res->fetch_object())
            {
            ?>
            <div class="row">
                <div class="card col-md-12">
                    <h2 class="card-header">Update <?php echo $row->name;?>  Details</h2>
                    <div class="card-body">
                        <!--Form-->
                        <form method="post" enctype="multipart/form-data" >
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Team Name</label>
                                    <input type="text" value="<?php echo $row->name;?>" required name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="exampleInputEmail1">Team Region</label>
                                    <input type="text" value="<?php echo $row->region;?>" required name="region" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                              
                            </div>

                            <div class="row">
                            <div class="form-group col-md-6">
                             <label for="big_team">Big Team</label>
                             <select value ="<?php echo $row->bigteam;?>" required name="big_team" class="form-control" id="big_team">
                              <option value="yes">Yes</option>
                              <option value="no">No</option>
                            </select>
                            </div>

                            <div class="form-group col-md-6">
                            <label for="caf_qualifier">CAF Qualifier</label>
                            <select value ="<?php echo $row->caf_qualifier;?>" required name="caf_qualifier" class="form-control" id="caf_qualifier">
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                         </div>

                        </div>  

                            <div class="row">
                            <div class="form-group col-md-6">
                            <label for="venue_id">Team Venue</label>
                            <select value="<?php echo $row->venue_id;?>" class="form-control" required name="venue_id">
                             <option value="">select venue</option> <!-- Default empty option -->
                            <?php
                            //  database connection
                            include('inc/dbconn.php');
                            $query = "SELECT id, name FROM venue WHERE league_type='Premier'  ";
                            $result = $mysql->query($query);

                            if ($result) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                              $result->free(); // Free the result set
                          }
                          ?>
                      </select>
                  </div>
              </div> 
                            
                            <button type="submit" name="update_team" class="btn btn-primary">Update Team</button>
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