<?php
  session_start();
  include('inc/dbconn.php');
  include('inc/checklogin.php');
  check_login();
  //hold logged in user session.
  $admin_id = $_SESSION['admin_id'];
  
  
  if(isset($_GET['delete_id']))
  {
        $id=intval($_GET['delete_id']);
        $adn="DELETE FROM competition WHERE id = ?";
        $stmt= $mysql->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();  
  
          if($stmt)
          {
            $success = "Match Details Deleted";
          }
            else
            {
                $err = "Try Again Later";
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
        
        <div class="row">
            <div class="card col-md-12">

            <?php
                        // database connection 
                        include('inc/dbconn.php');
                        // Specify the table name where the league name is stored
                        $tableName = "competition";

                        // SQL query to fetch the league name from the specified table
                        $query = "SELECT name,season FROM $tableName"; // Add your WHERE conditions if needed
                        $result = $mysql->query($query);

                        if ($result && $result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $leagueName = $row['name'];
                            $seasonName= $row['season'];
                        } else {
                            $leagueName = "Unknown League"; // Provide a default name if no data is found
                            $seasonName = "Unkown Season";
                        }

                        // Close the result set
                        
                        ?>

                        <!-- Display the league name in your HTML -->
                        <h2 class="card-header">
                          Generated Fixture for <?php echo $leagueName; ?> <?php echo $seasonName;?> 
                        </h2>
                <div class="card-body">
                    <div class="table-responsive">
                    
                        <table class="table align-items-center table-flush">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">DATE</th>
                                <th scope="col">HOME TEAM</th>
                                <th scope="col">AWAY TEAM</th>
                                <th scope="col">TIME</th>
                                <th scope="col">VENUE </th>
                                <th scope="col">ROUND</th>
                                <th scope="col">LEAGUE TYPE</th>
                                <th scope="col">STATUS</th>
                                <th scope="col">Action<th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                
                                    $ret="SELECT * FROM fixture "; 
                                    $stmt= $mysql->prepare($ret) ;
                                    $stmt->execute() ;
                                    $res=$stmt->get_result();
                                    $cnt=1;
                                    while($row=$res->fetch_object())
                                    {
                            ?>
                                <tr>
                                <th scope="row">
                                    <?php echo $cnt;?>
                                </th>
                                <td>
                                    <?php echo $row->date;?>
                                </td>
                                                        
                                <td>
                                    <?php echo $row->home_team_name;?>
                                </td>
                                
                                <td>
                                    <?php echo $row->away_team_name;?>
                                </td>
                                <td>
                                    <?php echo $row->time;?>
                                </td>
                                <td>
                                    <?php echo $row->venue_name;?>
                                </td>
                               
                                <td>
                                    
                                        <a href  ="admin_update_team.php?id=<?php echo $row->id;?>&team_nnumber=<?php echo $row->region;?>" class="badge badge-primary">
                                            <i class="fa fa-edit"></i> <i class="fa fa-user"></i> 
                                                Update
                                        </a>
                                        <a href  ="admin_manage_team.php?delete_id=<?php echo $row->id;?>" class="badge badge-danger">
                                            <i class="fa fa-trash"></i> <i class="fa fa-user"></i>
                                                Delete
                                        </a>        
                                </td>
                                </tr>
                            <?php $cnt = 1+$cnt; }?>
                            </tbody>
                        </table>
                    </div>
                </div>    
            </div>
        </div>
          
    </div>
  </div>
 
  
</body>

</html>