<?php 
//session_start();
header("Content-type: text/html; charset=utf-8");
require_once "../conn/connect.php";
$verify = stripslashes(trim($_GET['verify'])); 

$verify = base64_decode($verify);
//找到studentid相同的行
$verify = strtoupper($verify);
$sql = "SELECT * FROM students WHERE studentid=:ver"; 
$st = $con->prepare($sql);
$st->bindParam(':ver', $verify);
$st->execute();
//$re = $st->fetch(PDO::FETCH_BOTH);

if ($st) {
	if ( $re['registed'] == 0 ) {
		//update设置registed为1
		$sql_update = "UPDATE students SET registed = 1 WHERE studentid = :ver";
		$stm = $con->prepare($sql_update);
		$stm->bindParam(':ver', $verify);
		$stm->execute();
		echo "<script>alert('您已经注册成功，马上去登陆吧~'); window.location= '../index.php';</script>";
	}else{
		echo "<script>alert('您已经注册，请马上去登录吧~!'); window.location= '../index.php';</script>";
	}
}else{
	echo "您的链接有问题了，请再试试吧！";
}

?>