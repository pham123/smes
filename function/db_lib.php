<?php


class db {
	public $dbh; // Create a database connection for use by all functions in this class
	public $lang="Vi";

	function __construct() {
		if($this->dbh = mysqli_connect(_DB_HOST_, _DB_USER_, _DB_PASS_, _DB_name_)) {

		} else {
			exit('Unable to connect to DB');
		}

		// Set every possible option to utf-8
		mysqli_query($this->dbh, 'SET NAMES utf8');
		mysqli_query($this->dbh, 'SET CHARACTER SET utf8');
		mysqli_query($this->dbh, 'SET character_set_results = utf8,' . 'character_set_client = utf8, character_set_connection = utf8,' . 'character_set_database = utf8, character_set_server = utf8'); 
	}

		// Create a standard data format for insertion of PHP dates into MySQL public
		function date($php_date) { return date('Y-m-d H:i:s', strtotime($php_date));	 }

		// All text added to the DB should be cleaned with mysqli_real_escape_string
		// to block attempted SQL insertion exploits
		public function escape($str) { 
			//return mysqli_real_escape_string($this->dbh,$str); 
			return $str;
		}

		// Test to see if a specific field value is already in the DB
		// Return false if no, true if yes
		public function in_table($table,$where) {
			$query = 'SELECT * FROM ' . _DB_PREFIX_ . $table . ' WHERE ' . $where;
			
			if ($result = mysqli_query($this->dbh,$query)) {
				return mysqli_num_rows($result) > 0;
			}
			
		}
		public function sl_one($table,$where) {
			if(!isset($_SESSION[_site_]['userid'])){
				header('Location: ../login.php');
			}
			$query = 'SELECT * FROM ' . _DB_PREFIX_ . $table . ' WHERE ' . $where;
			$rs = mysqli_query($this->dbh,$query);
			$result = $rs->fetch_array();
			return $result;
		}
		// Perform a generic select and return a pointer to the result
		public function select($query) {
			$result = mysqli_query( $this->dbh, $query );
			return $result;
		}
		//Thực thi 1 query đến DB
		public function query($query) {
			mysqli_query($this->dbh,$query);
		}
		//Đếm
		public function countdb($query) {
			$result = mysqli_query( $this->dbh, $query );
			$count = $result-> num_rows;
			return $count;
		}
		// Add a row to any table
		public function insert($table,$field_values) {
			$query = 'INSERT INTO ' . _DB_PREFIX_ . $table . ' SET ' . $field_values;
			mysqli_query($this->dbh,$query);
		}

		// Update any row that matches a WHERE clause
		public function update($table,$field_values,$where) {
			$query = 'UPDATE ' . _DB_PREFIX_ . $table . ' SET ' . $field_values . ' WHERE ' . $where;
			mysqli_query($this->dbh,$query);
		}

		public function delete($table,$where) {
			$query = 'DELETE FROM ' . _DB_PREFIX_ . $table . ' WHERE ' . $where;
			mysqli_query($this->dbh,$query);
		}
		public function fetchOne($query){
			$rs = mysqli_query($this->dbh,$query);
			return mysqli_fetch_assoc($rs);
		}

		public function fetchAll($query){
			$rows = array();
			$rs = mysqli_query($this->dbh,$query);
			while ($row = mysqli_fetch_assoc($rs)){
				$rows[] = $row;
			}
			return $rows;
		}

		public function sl_all($table,$where) {
			$query = 'SELECT * FROM ' . _DB_PREFIX_ . $table . ' WHERE ' . $where;
			$rs = mysqli_query($this->dbh,$query);
			$rows = [];
			while ($row = mysqli_fetch_assoc($rs)){
				$rows[] = $row;
			}
			return $rows;
		}

		public function sl_user_module($module) {
			$query = "select Users.* from Users
			inner join Access on Access.UsersId = Users.UsersId
			inner join Modules on Access.ModulesId = Modules.ModulesId AND Modules.ModulesName ='".$module."'";
			$rs = mysqli_query($this->dbh,$query);
			$rows = [];
			while ($row = mysqli_fetch_assoc($rs)){
				$rows[] = $row;
			}
			return $rows;
		}

		public function sl_col_all($colar,$table,$where) {
			$query = 'SELECT '.$table.'Id as id,' .$colar .' FROM ' . _DB_PREFIX_ . $table . ' WHERE ' . $where;
			$rs = mysqli_query($this->dbh,$query);
			$rows=[];
			while ($row = mysqli_fetch_assoc($rs)){
				$rows[] = $row;
			}
			return $rows;
		}

		//Kiểm tra nhanh thông tin
		public function getcol($table) {
			$query = "SHOW COLUMNS FROM ".$table."";
			$rs = mysqli_query($this->dbh,$query);
			// $text = '';
			$return = array();
			while ($row = mysqli_fetch_assoc($rs)){
				// $text = $text.". ".$row['Field'];
				$return[] = $row['Field'];
			}
			//$arr = mysqli_fetch_assoc($rs);
			return $return;
			// return $rows;
		}

		//Lấy thông tin
		public function lang($key,$value=Null) {
			$ngongu = $this->lang;
			if (isset($_SESSION[_site_]['userlang'])) {
				$ngongu = ucfirst($_SESSION[_site_]['userlang']);
			}else{
				$ngongu = 'En';
			}
			if ($value==Null) {
				$value = implode(" ",preg_split('/(?=[A-Z])/', $key, -1, PREG_SPLIT_NO_EMPTY));
			}
			$return = $value;
			$query = "SELECT Lang".$ngongu." FROM " . _DB_PREFIX_ . "Lang WHERE LangName='".$key."'" ;
			$rs = mysqli_query($this->dbh,$query);
			$result = $rs->fetch_array();
			if (isset($result['Lang'.$ngongu])) {
				$return =  $result['Lang'.$ngongu];
			}else{
				$sql = "INSERT INTO Lang(`LangName`,`LangVi`,`LangEn`,`LangCn`,`LangKr`,`LangOption`)
				VALUES('".$key."','".$value."','".$value."','".$value."','".$value."',1)";
				$result = mysqli_query( $this->dbh, $sql );
			}
			return $return;
		}

		// public function test_test($a){
		// 	return $_SESSION[_site_]['userlang'];
		// }
		//
		public function sl_id($table) {
			$query = 'SELECT MAX('.$table.'Id) As '.$table.'Id FROM ' . _DB_PREFIX_ . $table . ' WHERE 1';
			$rs = mysqli_query($this->dbh,$query);
			$result = $rs->fetch_array();
			return $result[$table.'Id'];
		}

		public function get_one($column,$table,$id) {
			$query = 'SELECT '.$column.' FROM ' . _DB_PREFIX_ . $table . ' WHERE '.$table.'Id=' . $id;
			$rs = mysqli_query($this->dbh,$query);
			$result = $rs->fetch_array();
			return $result[$column];
		}
}


class products extends db{
	public $id;
	public $name;
	public $number;

	public function get($id) {
		$result = $this->sl_one('Products','ProductsId='.$id);
		$this->name = $result['ProductsName'];
		$this->number = $result['ProductsNumber'];
	}

	public function getnum($number) {
		$result = $this->sl_one('Products',"ProductsNumber='".$number."'");
		$this->name = $result['ProductsName'];
		$this->number = $result['ProductsNumber'];
		$this->id = $result['ProductsId'];
	}

}


class Users extends db{
	public $id;
	// public $access;
	public $module;
	public $name;
	public $lang;
	public $ecode;
	public $section;

	public function set($id) {
		$this->id = $id;

		$query = "
		SELECT Users.*, em.EmployeesCode,st.SectionId FROM Users
		INNER JOIN Employees em ON em.EmployeesId = Users.EmployeesId
		INNER JOIN Section st ON st.SectionId = em.SectionId
		WHERE UsersId='".$id."'" ;
		$rs = mysqli_query($this->dbh,$query);
		$user = $rs->fetch_array();
		$this->name = $user['UsersName'];
		$this->lang= $user['UsersLang'];
		$this->ecode= $user['EmployeesCode'];
		$this->section= $user['SectionId'];
		return $this;
	}
	public function acess() {
		$result = $this->sl_one('Modules','ModulesName="'.$this->module.'"');
		if(!$result){
			header('Location: ../403.php');
		}
		$result2 = $this->sl_one('Access','ModulesId='.$result['ModulesId'].' AND UsersId='.$this->id);
		return $result2['AccessOption'];
	}

}



function safe($x){
$rv= addslashes($x);
$rv = strip_tags($rv);
     return $rv;
}

function getStartAndEndDate($week, $year)
{
	$week = $week-1;
    $time = strtotime("1 January $year", time());
    $day = date('w', $time);
    $time += ((7*$week)+1-$day)*24*3600;
    $return[0] = date('Y-m-d', $time);
    $time += 6*24*3600;
    $return[1] = date('Y-m-d', $time);
    return $return;
}

function viewprice($price){
	$number = $price;
	 $arrfloat = explode('.',$number);
	 $a = $arrfloat[0];
	 $arr = str_split($a);
	 $c=array_reverse($arr);
	 $k=1;
	 $text = '';
	 for ($i=0; $i < strlen($a); $i++) { 
		 if ($k<3) {
			  $text = $text.$c[$i];
		 }elseif($k=3){
			 $text = $text.$c[$i].',';
			 $k=0;
		 }
		$k++;
	 }
	$dr = str_split($text);
	if($dr[(count($dr)-1)]==","){
	  unset($dr[(count($dr)-1)]);
	}
	$d = array_reverse($dr);
	$text2 = '';
	foreach ($d as $key => $value) {
	   $text2=$text2.$value;
	}
	$retVal = (isset($arrfloat[1])) ? $text2.'.'.$arrfloat[1] : $text2 ;
	return $retVal;
	}
?>