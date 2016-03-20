<?php
require_once('./includes/db.php');
require_once('./includes/fns.php');
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
			body {
			  	padding-top: 20px;
			}
			.header {
				padding-bottom: 20px;
			}			
			.news_data {
				padding-bottom: 30px;
			}
			.news_data span {
				margin-right: 5px;
			}
			.n_item {
				padding-bottom: 30px;	
			}
			.n_item p {
				margin: 0px;
			}
			.btn:focus,.btn:active:focus {
			    outline: none;
			}
			.select-bar {
				padding-bottom: 30px;
			}
			.read_more {
				margin-top: 10px;
			}
			.reset {
				color: #333;
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
		    	<h1>News</h1>
	    	</div>

	    	<div class="content">  

		    	<div class="select-bar">

		    		<form class="form-inline" role="form" method="post" action="<?php $_SERVER['PHP_SELF']; ?>" >

    					<div class="form-group">
      						<span>Category: </span>
				    		<select class="form-control" name="select_category" id="select_category">
				    			<option value="">Please select</option>
				    			<?php
				    				$cat_count = count(getCategories());
				    				$categories = getCategories();

				    				try {
					    				if ( $categories > 0 ) {
						    				for ( $i=0; $i<$cat_count; $i++ ) {
						    					echo "<option value=".$categories[$i][id].">".$categories[$i][title]."</option>";
						    				}
					    				} else {
					    					throw new Exception ('There is no categories to show.');
					    				}
					    			} catch (Exception $error) {
					    				echo $error->getMessage();
					    			}
				    			?>
				    		</select>
    					</div>

						<div class="form-group">
						    <span> Month: </span>
			    			<select class="form-control" name="select_date" id="select_date">
			    				<option value="">Please select</option>			    					
			    				<?php
			    					$years_group = getYearsArray();
			    					$count = count($years_group);

			    					try {			    					
					    				for ($a=0; $a<$count; $a++){

					    					$b = getMonthsfromYear($years_group[$a]);

					    					if ( $b > 0 ) {
						    					echo "<optgroup label='".$years_group[$a]."'>";	
						    						foreach ($b as $key=>$value) {
						    							echo "<option value='".$years_group[$a]."-".$value."'>".$key."</option>";
						    						}
						    					echo "</optgroup>";
						    				} else {
					    						throw new Exception("Error Processing getMonthsfromYear()");
					    					}	
					    				}					    			
				    				} catch (Exception $error) {
				    					echo $error->getMessage();
				    				}
			    				?>
			    			</select>
    					</div>

    					<div class="form-group">
      						<span> Show: </span>		    		
				    		<select class="form-control" name="select_count" id="select_count">
				    			<option value="">Please select</option>
				    			<option value="5">5</option>
				    			<option value="10">10</option>
				    			<option value="25">25</option>
				    		</select>		    	
				    		<span> items per page.</span>
    					</div>

    				<button type="submit" class="btn">Filter</button> <a href="index.php"><button type="button" class="btn reset">Reset</button></a>
 	 				</form>	    		
 	 			</div>


		    	<div id="news_items">
		    		<div class="row">
		    			<?php

		    				if ( ($_SERVER['REQUEST_METHOD'] == 'POST') && ( ($_POST['select_count'] != 0) || ($_POST['select_date'] != 0) || ($_POST['select_category'] !=0)) ) {
		    					$filter = $_POST;
		    				} else {
		    					$filter = null;
		    				}

		    				if ($_POST['select_count'] == 0) {
								$limit=5;
							} else {
								$limit = $_POST['select_count'];	
							}
		    				$news = getNews($filter);
		    				$n_count = count($news);
		    				$n_pages = ceil($n_count / $limit); 

		    				for ($d=0; $d<$n_count; $d++) {

		    					$title = $news[$d][title];
		    					$date_time = strtotime($news[$d][date]);
		    					$excerpt = substr($news[$d][content],0,300);
		    					$date = date("jS F Y",$date_time);
	    						$news_item_id = $news[$d][id];
	    						$cat_id = getCategoryID($news_item_id);

		    					echo "<div class=\"item\">";
				    			echo "<div class=\"col-md-4 col-sm-6\">";
				    				echo "<div class=\"n_item\">";
				    					echo "<h1>".$title."</h1>";
				    					echo "<div class=\"news_data text-muted\">".$date."<br>";
				    						foreach ($cat_id as $value) {
				    							echo "<span class=\"label label-default\">".getCategoryName($value)."</span>";
				    						}				    					
				    					echo "</div><p>".$excerpt."...</p>";
				    					echo "<button type=\"button\" class=\"btn read_more\">Read more</button>";
				    				echo "</div>";
				    			echo "</div>";
				    			echo "</div>";
			    			}

			    		?>
		    		</div>
		    	</div>

 				
		    	<ul class="pagination">
		    		<li><a href='index.php?page=1'><span aria-hidden="true">&laquo;</span></a></li>
				  	<li class="active"><a href="index.php?page=1">1</a></li>
				  		<?php 
				  			for ($i=2; $i<$n_pages; $i++) { 
            					echo "<li><a href='index.php?page=".$i."'>".$i."</a></li>"; 
							}
						?>
					<li><a href='index.php?page=<?php echo $n_pages ?>'><span aria-hidden="true">&raquo;</span></a></li>
				</ul>	
				
		    </div>	
	    </div>

		<!-- jQuery -->	    
    	<script src="js/jquery-1.12.1.min.js"></script>

		<!-- bootstrap js -->
		<script src="js/bootstrap.min.js"></script>
		
	</body>

</html>