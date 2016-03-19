<?php

function getNews() {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item ORDER BY date ASC');	
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

		    	if (!in_array($month,$all_months) && $year == $y) {
		    		array_push($all_months, $month);
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