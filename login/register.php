<?php
@session_start();

require_once ('../conn/connect.php');
@$studentid = $_POST['studentid'];
@$passwd    = $_POST['password'];
@$submit    = $_POST['signup'];

@$image = strtoupper($_POST['image']);//取得用户输入的图片验证码并转换为大写
$image2 = $_SESSION['pic'];//取得图片验证码中的四个随机数

if (isset($submit)) {
	if (strlen( $_POST["password"] ) >= 6) {
		if ($_POST['studentid'] != "" || $_POST['password'] != "") {
			if ($_POST['password'] == $_POST['passwordrepeat']) {
				if ($image == $image2) {// 验证码正确

					//对密码进行加密
					$pwd_md5      = md5($passwd);
					$md5_sha      = hash('sha256', $pwd_md5);
					$sha_pwd_hash = password_hash($md5_sha, PASSWORD_DEFAULT);

					$insert_in = "INSERT INTO EIC (studentid, password, grade)
							  VALUES ( '$studentid', '$passwd', 90 )";
					if ($con->query($insert_in)) {//执行sql语句
						echo "<script>alert('注册成功');window.location= 'index.php';</script>";
					} else {
						echo "insert error".$con->error;
					}
				} else {
					echo "<script>alert('验证码错误！');history.go(-1)</script>";
				}
			} else {
				echo "<script>alert('两次密码输入不一致！');history.go(-1)</script>";
			}
		} else {
			echo "<script>alert('用户名密码不能为空!');history.go(-1)</script>";
		}
	} else {
		echo "<script>alert('密码不能少于6位!');history.go(-1)</script>";
	}
}
?>