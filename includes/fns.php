<?php

function getNews($filter) {
	try {
		GLOBAL $DB_PDO;

		if ( $filter['select_count'] == 0 ) {
			$limit = 5;
		} else {
			$limit = $filter['select_count'];	
		}
		//var_dump($filter);
		if ( isset($_GET["page"]) ) { 
			$page = $_GET["page"]; 
		} else { 
			$page = 1; 
		}
		$offset = ( $page - 1 ) * $limit;

		if ( $filter['select_category']!=0 || $filter['select_date']!=0 ) {

			if ( $filter['select_category'] != 0) {				
				$where_cat = 'category_news_item.category_id=:sel_cat';
			} else {
				$where_cat = '';
			}

			if ( $filter['select_date'] != 0) {				
				$where_date = 'news_item.date LIKE :sel_date';
				$d = $filter['select_date'];
				$d .= '%';
			} else {
				$where_date = '';
			}					
			
			$where_string = array();
			array_push($where_string, $where_cat, $where_date);
			$where_string = implode(' AND ', array_filter($where_string));

			if ($where_string == null) {
				$where_tag = '';
			} else {
				$where_tag = 'WHERE';
			}

			$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item INNER JOIN category_news_item ON news_item.id=category_news_item.news_item_id '.$where_tag.' '.$where_string.' GROUP BY id ORDER BY news_item.date ASC LIMIT '.$offset.','.$limit.' ');
			if ( $where_cat != null ) {
				$stmt->bindValue(':sel_cat',$filter['select_category'],PDO::PARAM_INT);
			}
			if ( $where_date != null ) {
				$stmt->bindValue(':sel_date',$d,PDO::PARAM_STR);
			}

		} else {
			$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item ORDER BY date ASC LIMIT '.$offset.', '.$limit.'');
		}

		$stmt -> execute();
		
		if ($result = $stmt -> fetchAll(PDO::FETCH_ASSOC)) {
			$stmt -> closeCursor();
			return $result;		
		} else {
			$stmt -> closeCursor();
			throw new Exception ('I could not find any news :( ');
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}

function getTotalNews($filter) {
	try {
		GLOBAL $DB_PDO;

		if ( $filter['select_category']!=0 || $filter['select_date']!=0 ) {

			if ( $filter['select_category'] != 0) {				
				$where_cat = 'category_news_item.category_id=:sel_cat';
			} else {
				$where_cat = '';
			}

			if ( $filter['select_date'] != 0) {				
				$where_date = 'news_item.date LIKE :sel_date';
				$d = $filter['select_date'];
				$d .= '%';
			} else {
				$where_date = '';
			}					
			
			$where_string = array();
			array_push($where_string, $where_cat, $where_date);
			$where_string = implode(' AND ', array_filter($where_string));

			if ($where_string == null) {
				$where_tag = '';
			} else {
				$where_tag = 'WHERE';
			}

			$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item INNER JOIN category_news_item ON news_item.id=category_news_item.news_item_id '.$where_tag.' '.$where_string.' GROUP BY id ORDER BY news_item.date ASC ');
			if ( $where_cat != null ) {
				$stmt->bindValue(':sel_cat',$filter['select_category'],PDO::PARAM_INT);
			}
			if ( $where_date != null ) {
				$stmt->bindValue(':sel_date',$d,PDO::PARAM_STR);
			}

		} else {
			$stmt = $DB_PDO -> prepare(' SELECT * FROM news_item ORDER BY date ASC ');
		}

		$stmt -> execute();
		
		if ($result = $stmt -> rowCount()) {
			$stmt -> closeCursor();
			return $result;		
		} else {
			$stmt -> closeCursor();
			throw new Exception ('getTotalNews error');
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
			throw new Exception("I could not find any categories :( ");
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

function getSingleNews($news_item_id) {
	try {
		GLOBAL $DB_PDO;

		$stmt = $DB_PDO -> prepare('SELECT * FROM news_item WHERE id=:news_item_id');
		$stmt->bindValue(':news_item_id', $news_item_id, PDO::PARAM_INT);
		$stmt->execute();

		if ($result = $stmt -> fetchAll(PDO::FETCH_ASSOC)) {
			$stmt->closeCursor();
			$result = $result[0];
			return $result;
		} else {
			$stmt->closeCursor();
			throw new Exception("Error Processing Request getSingleNews()");		
		}

	} catch (Exception $error) {
		echo $error->getMessage();
	}
}
?>