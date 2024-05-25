<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
  
  
		if(isset($_POST['update_league_premier']))
		{
            $id = $_GET['id'];
            $name = $_POST['name'];
            $start_date = $_POST['start_date'];
            $end_date =$_POST['end_date'];
            $season =$_POST['season'];
         
            
            
            //sql to insert captured values
            $query="UPDATE  league_premier SET name=?, start_date=?, end_date=?, season=? WHERE id =?";
            $stmt = $mysql->prepare($query);
            $rc=$stmt->bind_param('ssssi',$name,$start_date, $end_date, $season,$id);
            $stmt->execute();

            if($stmt)
            {
                      $success = "League Updated";
                      
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
                $ret="SELECT  * FROM  league_premier WHERE id=?";
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
                                <label for="name">League Name</label>
                                <input type="text" value ="<?php echo $row->name;?>" required name="name" class="form-control" id="name" aria-describedby="emailHelp">
                            </div> 
                            
                            <div class="form-group col-md-6">
                                <label for="start_date">Start Date</label>
                                <input type="date"value=" <?php echo $row->start_date;?>" required name="start_date" class="form-control" id="start_date" aria-describedby="emailHelp">
                            </div> 
                    </div>

                            <div class="row">
                            <div class="form-group col-md-6">
                                <label for="end_date">End Date</label>
                                <input type="date" value="<?php echo $row->end_date;?>"required name="end_date" class="form-control" id="end_date" aria-describedby="emailHelp">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="season">Season</label>
                                <input type="text"value="<?php echo $row->season;?>" required name="season" class="form-control" id="season" aria-describedby="emailHelp">
                            </div>
                 </div>  

                            
              </div> 
                            
                            <button type="submit" name="update_league_premier" class="btn btn-primary">Add League</button>
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