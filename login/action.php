<?php
header("Content-type: text/html; charset=utf-8");

session_start();

require_once ('../conn/connect.php');
require_once ('../mysql/build.php');

@$studentid = $_POST['studentid'];
@$passwd    = $_POST['password'];

//匹配密码用户名
$stmt = $con->prepare("SELECT * FROM students WHERE studentid = :studentid");
$stmt->bindParam(':studentid', $studentid);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_BOTH);

$stu_id = $row[1];
$pass   = $row[2];
$registed = $row['registed'];

//页面密码加密
$pwd_md5_ = md5($passwd);
$md5_sha_ = hash('sha256', $pwd_md5_);

$image  = strtoupper($_POST['image']);//取得用户输入的图片验证码并转换为大写
$image2 = $_SESSION['pic'];//取得图片验证码中的四个随机数
if ($registed == 1) {
	if ($stu_id == $studentid && password_verify($md5_sha_, $pass) && $image == $image2)//验证用户名和密码是否一致
	{
		//打印成绩单
		$stmt = $con->prepare("SELECT * FROM grades WHERE studentid = :studentid");
		$stmt->bindParam(':studentid', $studentid);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_BOTH);

		$student_id = $row['studentid'];
		$grade      = $row['grade'];
		$name		= $row['name'];
		$rank       = $row['rank'];

		echo <<<HTML
		<!doctype html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="../css/my.css">
			<title>成绩页面</title>
		</head>

		<body>
			<div class="container" >
			<img src="../img/logo.jpg" class="img" >
			<h3>Sign in to EIC,HUST</h3>

				<div class="col-sm-4" style="margin: 0 auto;">
					<table class="table table-bordered table-hover">
						<caption></caption>

						<thead>
						<tr>
							<th>姓名</th>
							<th>学号</th>
							<th>成绩</th>
							<th>排名</th>
						</tr>
						</thead>

						<tbody>
						<tr>
							<td>$name</td>
							<td>$studentid</td>
							<td>$grade</td>
							<td>$rank</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>

			<p class="footer2">2017 © Powered by 科协技术部  All rights reserved.</p>

		</body>


HTML;

		//echo "欢迎你".$row['studentid']."你的成绩".$row['grade']."排名".$row['rank'];
		//其余参照14 15 行
	} else {
		echo "<script>alert('帐户名或密码错误！');history.go(-1)</script>";
		//用户名和密码不一致，跳转到当前页面重新输入
	}
}else{
	echo "<script>alert('您还未激活链接！');history.go(-1)</script>";
}

?>



