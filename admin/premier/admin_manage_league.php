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
        $adn="DELETE FROM  competition WHERE id = ?";
        $stmt= $mysql->prepare($adn);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $stmt->close();  
  
          if($stmt)
          {
            $success = "League Deleted";
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
                <h2 class="card-header">Manage Competition</h2>
                <div class="card-body">
                    <div class="table-responsive">
                    <!-- refereee table -->
                        <table class="table align-items-center table-flush">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">League Name</th>
                                <th scope="col">Start Date </th>
                                <th scope="col">End Date </th>
                                <th scope="col">Season</th>
                                <th scope="col">League Type</th>
                                <th scope="col">Action<th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                                
                                    $ret="SELECT * FROM competition WHERE league_type='Premier' "; 
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
                                    <?php echo $row->name;?>
                                </td>
                                                        
                                <td>
                                    <?php echo $row->start_date;?>
                                </td>
                                <td>
                                    <?php echo $row->end_date;?>
                                </td>
                                
                                <td>
                                    <?php echo $row->season;?>
                                </td>

                                <td>
                                    <?php echo $row->league_type;?>
                                </td>
                                <td>
                                    
                                        <a href  ="admin_update_league.php?id=<?php echo $row->id;?>& name=<?php echo $row->name;?>" class="badge badge-primary">
                                            <i class="fa fa-edit"></i> <i class="fa fa-user"></i> 
                                                Update
                                        </a>
                                        <a href  ="admin_manage_league.php?delete_id=<?php echo $row->id;?>" class="badge badge-danger">
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