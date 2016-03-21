<?php
require_once('./includes/db.php');
require_once('./includes/fns.php');

$item_id = $_GET['id'];
$news = getSingleNews($item_id);
$a = strtotime($news[date]);
$data = date("Y M d", $a);

$cat_id = getCategoryID($item_id);
$cat_name = getCategoryName($cat_id[0]);
?>
<!DOCTYPE html>
<html lang="en">
	<head>		
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="description" content="News listing view">
		<meta name="keywords" content="HTML,CSS,jQuery,PHP, Bootstrap">
		<meta name="author" content="Dawid Moroch">

		<title>CleverCherry - David Moroch</title>

		<!-- bootstrap css -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

		<style>		
			span {
				margin-right: 5px;
			}
			p {
				padding: 15px;
			}
		</style>

	</head>

	<body>

	    <div class="container">

	    	<div class="header">
       			<h3 class="text-muted"><a href="index.php" >David Moroch</a></h3>
       			<hr>
      		</div>

		    <div class="headline">
		    	<h1><?php echo $news[title] ?></h1>
	    	</div>

	    	<div class="content">
	    	<h5><?php echo $data?></h5>
	    		<?php 
	    			foreach ($cat_id as $value) {
				    	echo "<span class=\"label label-default\">".getCategoryName($value)."</span>";
				    }
				?>
	    	<br>
	    	<h3><p><?php echo $news[content] ?></p></h3>
	    	<br>
	    	<a href="index.php"><button type="button" class="btn reset">Go back to homepage</button></a>
				
		    </div>	
	    </div>

		<!-- jQuery -->	    
    	<script src="js/jquery-1.12.1.min.js"></script>

		<!-- bootstrap js -->
		<script src="js/bootstrap.min.js"></script>
		
	</body>

</html>