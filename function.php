<?php 
// include'user.config.php';
// session_start();
function is_post(){
	return (isset($_SERVER['REQUEST_METHOD']))&&($_SERVER['REQUEST_METHOD']=='POST');
}
/**
 * [user_link 连接数据库]
 * @param  [string] $ip     [数据库host]
 * @param  [string] $u      [数据库用户名]
 * @param  [string] $p      [数据库密码]
 * @param  [string] $DBname [数据库名]
 * @return [type]         [description]
 */
function user_link($ip,$u,$p,$DBname){
	$user_link=mysqli_connect($ip,$u,$p,$DBname);
	mysqli_set_charset($user_link,'utf8');	
	return $user_link;
}
 


function sql($field,$from,$where){
	$sql="SELECT $field FROM $from WHERE $where";
	return $sql;
}





function login_sql($user_link,$field,$from,$where){
	$user_sql="SELECT $field FROM $from WHERE $where";
	// echo($user_sql);
	$user_id_link=mysqli_query($user_link,$user_sql);
	$user_list=[];
	while ($user_id=mysqli_fetch_assoc($user_id_link)) {
		$user_list[]=$user_id;
		// var_dump($user_id);
	}
	// var_dump($user_list);
	if (count($user_list)==1&&$user_list!=null) {
		return $user_list;
	}
}

function login_all_sql($user_link,$field,$from,$where){
	$user_sql="SELECT $field FROM `$from` WHERE $where";
	// echo($user_sql);
	$user_id_link=mysqli_query($user_link,$user_sql);
	$user_list=[];
	 // var_dump(mysqli_fetch_all($user_id_link));
	// var_dump(mysqli_fetch_fields($user_id_link));
	
	while ($user_id=mysqli_fetch_assoc($user_id_link)) {
		$user_list[]=$user_id;
		// var_dump($user_id);
	}

	// var_dump($user_list);
	if ($user_list!=null) {
		return $user_list;
	}
}


function delete_info($user_link,$table,$where=0){
	$user_sql="DELETE FROM $table WHERE $where";
	// echo($user_sql);
	$user_id_link=mysqli_query($user_link,$user_sql);
	if ($user_id_link) {
		return $user_id_link;
	}
	return false;
}


function update_info($user_link,$table,$Modify,$where=0){
	$user_sql="UPDATE $table SET $Modify WHERE $where";
	// echo($user_sql);
	$user_id_link=mysqli_query($user_link,$user_sql);
	if ($user_id_link) {
		return $user_id_link;
	}
	return $user_id_link;
}





function login_re_sql($user_link,$field,$from,$where){
	$user_sql="SELECT $field FROM $from WHERE $where";
	// echo($user_sql);
	$user_id_link=mysqli_query($user_link,$user_sql);
	$user_id=mysqli_fetch_assoc($user_id_link);
	return $user_id;
}

function trim_re($str){
	$str=trim($str);
	$str=addslashes($str);
	if (empty($str)) {
		return false;
		exit;
	}return $str;
}


function str_len($str,$min,$max){
	$len=mb_strlen($str,'utf8');
	// echo($len);
	if ($len>=$min&&$len<=$max) {
		// echo($str);
		return $str;
	}return false;

}

function insertinto($user_link,$from,$field,$where){
	$re_sql="INSERT INTO `$from` ($field) VALUES ($where)";
	echo($re_sql);
	$re_link=mysqli_query($user_link,$re_sql);
	return $re_link;
}


function show_tb($user_link){
	$re_sql="SHOW TABLES";
	$re_link=mysqli_query($user_link,$re_sql);
	$user_list=[];
	while ($user_id=mysqli_fetch_assoc($re_link)) {
		$user_list[]=$user_id;
		// var_dump($user_id);
	}
	return $user_list;
}


function create_fried_tb($user_link,$tbname){
	$add="CREATE TABLE `$tbname` (`friend_id` smallint (5) unsigned zerofill,`friend_name` varchar (8), PRIMARY KEY (`friend_id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 ";
	 // echo($add);
	$re_link=mysqli_query($user_link,$add);
	if ($re_link) {
		return true;
	}return false;
}



function create_message_tb($user_link,$tbname){
	// echo 2134567890;
	$add="CREATE TABLE `$tbname` (
		`friend_id` smallint (5) unsigned zerofill,
		`friend_name` varchar (8) , 
		`friend_message` varchar (255), 
		`message_time` int,
		PRIMARY KEY (`friend_id`)
		)ENGINE=MyISAM DEFAULT CHARSET=utf8 ";
	// echo($add);
	$re_link=mysqli_query($user_link,$add);
	if ($re_link) {
		return true;
	}return false;
}




function portrait_upload($size,$pic){
	$a=date('Y/m/d');
	if (!is_dir($a)) {
		mkdir($a,0777,true);
	}
	echo(1);
// var_dump($_FILES['portrait']);
	if (isset($_FILES[$pic])) {
		$file=$_FILES[$pic];
		// var_dump($file);
		$lujing=$file['tmp_name'];
		$type=['image/png','image/jpg','image/jpeg','image/gif'];
		if (in_array($file['type'], $type)) {
			if ($file['size']<=$size) {
				if ($file['error']==0) {
					$type_ex=explode('/', $file['type']);
					$file_type=array_pop($type_ex);
					move_uploaded_file($lujing, $a.'/'.time().'.'."$file_type");
					// $Z=$a.'/'.time().'.'."$file_type";
					return $a.'/'.time().'.'."$file_type";
					// echo($Z);
				}else{
					return('文件不符合要求');
				}				
			}else{
				return('文件过大');
			}
		}

	}

}


function thumbnail($path,$width,$height){

	$img_info=getimagesize($path);
	$img_mime=explode('/', $img_info['mime']);
	$img_type=array_pop($img_mime);
	$from_type="imagecreatefrom{$img_type}";
	$img_list=$from_type($path);
	// $img_list=imagecreatefromjpeg($img_info);
	$bg=imagecreatetruecolor($width, $height);
	imagecopyresampled($bg, $img_list, 0, 0, 0, 0, $width,$height, $img_info['0'], $img_info['1']);
	$out_img="image{$img_type}";
	$a='thumbnail'.'/'.date('Y/m/d');
	if (!is_dir($a)) {
	mkdir($a,0777,true);
	}
	$thumbnail_path=$a.'/'.time().'.'."$img_type";
	$out_img($bg,$thumbnail_path);
	imagedestroy($bg);
	return $thumbnail_path;
}



 ?>

