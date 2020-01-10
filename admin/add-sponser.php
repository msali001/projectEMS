<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['adminsession'])==0)
{   
header('location:logout.php');
}
else{ 
if(isset($_POST['add']))
{
// Posted Values
$sponser=$_POST['sponser'];
$slogo=$_FILES["sponserlogo"]["name"];
// get the image extension
$extension = substr($slogo,strlen($slogo)-4,strlen($slogo));
// allowed extensions
$allowed_extensions = array(".jpg","jpeg",".png",".gif");
// Validation for allowed extensions .in_array() function searches an array for a specific value.
if(!in_array($extension,$allowed_extensions))
{
echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
}
else
{
//rename the image file
$imgnewfile=md5($slogo).$extension;
// Code for move image into directory
move_uploaded_file($_FILES["sponserlogo"]["tmp_name"],"sponsers/".$imgnewfile);
// Query for insertion data into database
$sql="INSERT INTO  tblsponsers(sponserName,sponserLogo) VALUES(:sponser,:slogo)";
$query = $dbh->prepare($sql);
$query->bindParam(':sponser',$sponser,PDO::PARAM_STR);
$query->bindParam(':slogo',$imgnewfile,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId)
{
$msg="Sponser created successfully.";
}
else 
{
$error="Something went wrong. Please try again";  
}

}
}    
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>EMS | Add Sponer</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
 <style>
    .errorWrap {
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #dd3d36;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
.succWrap{
    padding: 10px;
    margin: 0 0 20px 0;
    background: #fff;
    border-left: 4px solid #5cb85c;
    -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
    box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
}
</style>
</head>
<body>
<div id="wrapper">
<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
<!-- / Header -->
<?php include_once('includes/header.php');?>
<!-- / Leftbar -->
<?php include_once('includes/leftbar.php');?>
</nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header"> Add Sponser</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                           Add Sponser
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">

<!-- Success / Error Message -->
 <?php if($error){?><div class="errorWrap"><strong>ERROR</strong> : <?php echo htmlentities($error); ?> </div><?php } 
else if($msg){?><div class="succWrap"><strong>SUCCESS</strong> : <?php echo htmlentities($msg); ?> </div><?php }?> 

<form role="form" method="post" enctype="multipart/form-data">
<!--sponser Name -->
<div class="form-group">
<label>Sponser</label>
<input class="form-control" type="text" name="sponser" autocomplete="off" required autofocus>
</div>
<!--Sponser logo -->
<div class="form-group">
<label>Sponser Logo</label>
<input type="file" name="sponserlogo"  required autofocus /></td>
</div>


<!--Button -->   
<div class="form-group" align="center">                    
<button type="submit" class="btn btn-primary" name="add">Add</button>
</div>
                                    </form>
                                </div>

                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>
<?php } ?>
