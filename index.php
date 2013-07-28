<?php 
session_start();
include('includes/metaFunctions.php');
include('includes/rank.php.inc');
//

if(isset($_POST['urlSubmit']))
{
	
	$user_url = clean_input($_POST['url']);
	$user_url = rtrim($user_url,"/");
	header("location: result.php?url=$user_url");
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Meta Checker</title>
<!--Meta tags go here -->

<style>
.inputbox {
	font-family: Verdana, Geneva, sans-serif;
	font-size: 18px;
	padding: 6px;
}
</style>
<link href="css/style1.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="container3">
  <h1 align="center">Meta Tag Finder</h1>
  <div align="center">
    <form action="index.php" method="post" name="form1" id="form1">
     
      <input name="url" type="text" class="inputbox" id="url" size="42" placeholder="Enter URL to check" required="required" autofocus="autofocus" />
      <input type="submit" name="urlSubmit" id="urlSubmit" class="inputbox" value="Submit" />
    </form>
   
  </div>

</div>
<div id="footer">Copyright <a href="http://www.barrysmith.org" title="My Homepage" target="_new">Barry Smith</a> <?php echo date('Y'); ?></div>
</body>
</html>