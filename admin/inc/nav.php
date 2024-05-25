<?php
    $admim_id = $_SESSION['admin_id'];
    $ret="SELECT  * FROM  fixture_admin  WHERE admin_id=?";
    $stmt= $mysql->prepare($ret) ;
    $stmt->bind_param('i',$admin_id);
    $stmt->execute() ;
    $res=$stmt->get_result();
    
    while($row=$res->fetch_object())
    {
?>
  <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
        <div class="container-fluid">
          <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="admin_dashboard.php"><?php echo $row->admin_name;?> Dashboard</a>
    </div>
  </nav>
<?php }?>