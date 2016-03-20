<?php

function getNews($filter) {
	try {
		GLOBAL $DB_PDO;

		if ($_POST['select_count']!=0) {
			if (isset($_COOKIE['news_amount'])){
				setcookie("news_amount", "", time()-3600);
			}	
			setcookie("news_amount", $_POST['select_count'], time()+3600);
		}

		if ( $_POST['select_category']!=0 || $_POST['select_date']!=0 ) {

			if ( $_POST['select_category'] != 0) {				
				$where_cat = 'category_news_item.category_id='.$_POST['select_category'].'';
			} else {
				$where_cat = '';
			}

			if ( $_POST['select_date'] != 0) {				
				$where_date = 'news_item.date LIKE "'.$_POST['select_date'].'%"';
			} else {
				$where_date = '';
			}

			$where_string = array();
			array_push($where_string, $where_cat, $where_date);
			$where_string = array_filter($where_string);

			$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item INNER JOIN category_news_item ON news_item.id=category_news_item.news_item_id WHERE '.implode(' AND ', $where_string).' GROUP BY id ORDER BY news_item.date ASC');

		} else {
			$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item ORDER BY date ASC');
		}

		$stmt -> execute();
		
		if ($result = $stmt -> fetchAll(PDO::FETCH_ASSOC)) {
			$stmt -> closeCursor();
			return $result;		
		} else {
			$stmt -> closeCursor();
			throw new Exception ('I could not get any news :( ');
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}

function getMonthsfromYear($y) {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare('SELECT date FROM news_item');
		$stmt->execute();

		if ($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$stms->closeCursor;

			$months_count = count($result);
			$all_months = array();
			    							
			for ( $d=0; $d<$months_count; $d++) {
		    	$date_time=strtotime($result[$d][date]);
		    	$month = date("F", $date_time);
		    	$year = date("Y", $date_time);
		    	$month_number = date("m", $date_time);

		    	if (!in_array($month,$all_months) && $year == $y) {
		    		$all_months[$month] = $month_number;
		    	}
			}

			return $all_months;

		} else {
			$stmt -> closeCursor();
			throw new Exception("There is no months to show");
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}

function getYearsArray() {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare('SELECT date FROM news_item');
		$stmt->execute();

		if ($result = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
			$stms->closeCursor;

			$years_count = count($result);
			$all_years = array();
			    							
			for ( $d=0; $d<$years_count; $d++) {
		    	$date_time=strtotime($result[$d][date]);
		    	$year = date("Y", $date_time);

		    	if (!in_array($year,$all_years)) {
		    		array_push($all_years, $year);
		    	}
			}
			return $all_years;

		} else {
			$stmt -> closeCursor();
			throw new Exception("There is no years to show");
		}		
	} catch (Exception $error) {
		echo $error->getMessage();
	}
}

function getCategories() {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare('SELECT * FROM category');
		$stmt->execute();

		if ($result = $stmt -> fetchALL(PDO::FETCH_ASSOC)) {
			$stmt -> closeCursor();
			return $result;
		} else {
			$stmt -> closeCursor();
			throw new Exception("I could not get any categories :( ");
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}

function getCategoryID($news_item_id) {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare('SELECT category_id FROM category_news_item WHERE news_item_id=:news_item_id');
		$stmt->bindValue(':news_item_id', $news_item_id, PDO::PARAM_INT);
		$stmt->execute();

		if ($result = $stmt->fetchAll(PDO::FETCH_COLUMN)) {
			$stmt->closeCursor();
			return $result;
		} else {
			$stmt -> closeCursor();
			throw new Exception ("This news has no category :( ");
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}

function getCategoryName($category_id) {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare('SELECT title FROM category WHERE id=:category_id');
		$stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
		$stmt->execute();

		if ($result = $stmt->fetchColumn(0)) {
			$stmt->closeCursor();
			return $result;
		} else {
			$stmt->closeCursor();
			throw new Exception("Error Processing Request getCategoryName()");		
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}
?>