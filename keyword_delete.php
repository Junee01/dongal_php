
<?php 
	
	include_once('common.php');
	conn();
	
	if($_SERVER['REQUEST_METHOD'] == "GET"){	
		
	} else if($_SERVER['REQUEST_METHOD'] == "POST"){
		$uuid = $_POST[uuid];	
		$keyword_no = $_POST[keyword_no];	


		$result = sql_query("delete from dongal_keyword where keyword_no='$keyword_no'");
		$results = array("results"=>"success");
		
	} else{
		$results = array("results","error");
	}
		
		
	
	

	$results = json_encode($results); 	
	echo $results;
	
?>
