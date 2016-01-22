<?php
@session_start();

$user = "admin";
$password = "pass";

if (( isset( $_SESSION['pass'] ) && $_SESSION['pass'] == $password ) || ( isset( $_POST['p'] ) && isset( $_POST['u'] ) && $_POST['p'] == $password && $_POST['u'] == $user ) )
{
	if ( !isset($_SESSION['pass']) ){
		$_SESSION['pass'] = $password;
	}
}
else
{
	die("<center><h1>Admin Login Cp<h1>
	<form Method=POST>
	User: <input name=u type=text /><br>
	Pass: <input name=p type=password /><br>
	<input type=submit value=Login />
	</form>");
}


if ( is_file("setting.ini") ){
	$g = explode( "\n", str_replace( "\r", "", file_get_contents("setting.ini")  ) );
	$s = array();
	foreach($g as $m){
		if ( trim($m) != "")
		{
			$s[] = explode("=", $m);
		}
	}
	$setting = array();
	for ($i=0;$i<count($s);$i++){
		$setting[$s[$i][0]] = $s[$i][1];
	}
}else{
	die("setting.ini file dose not exists");
}
echo "<center><h1>Change Information</h1>
<form Method=POST>
App ID: <input name=appid type=text value=".$setting['appid']." /><br>
<input type=submit name=change value=Change />
</form>";

echo "<form Method=POST>
Share URL: <input name=url type=text value=".$setting['url']." /><br>
<input type=submit name=change value=Change />
</form>";
if ( isset( $_POST['change'] ) )
{
	$cleanIndex = trim(file_get_contents('.clean'));
	if ( isset( $_POST['appid'] ) && $_POST['appid'] != "" )
	{
		echo "APPID Channged";
		$setting['appid'] = trim($_POST['appid']);
		$index = str_replace('AAAAA', $setting['appid'], $cleanIndex );
	}
	else
	{
		$index = str_replace('AAAAA', $setting['appid'], $cleanIndex );
	}
	
	if( isset( $_POST['url'] ) && $_POST['url'] != "" )
	{
		echo "URL Channged";
		$setting['url'] = $_POST['url'];
		$index = str_replace("uuuu", $setting['url'], $index );
	}
	else
	{
		$index = str_replace("uuuu", $setting['url'], $index );
	}
	
	file_put_contents('index.html', $index);
	file_put_contents('setting.ini', '');
	foreach( $setting as $k=>$v )
	{
		file_put_contents('setting.ini', $k."=".$v."\r\n", FILE_APPEND);
	}
}

?>