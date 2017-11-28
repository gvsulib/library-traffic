<?

if(!isset($_COOKIE['loggedIn'])) {
    setcookie('loggedIn', 'false', time() + (86400 * 30), "/");
    $_COOKIE['loggedIn'] = 'false';
}

include 'password.php';

//echo $_COOKIE['loggedIn'];

//echo var_dump($_POST);

if ($_COOKIE['loggedIn'] == 'true'){
	
	header('Location: index.php');
}
if (isset($_POST['password'])) {
	if (sha1($_POST['password']) == $pass){
		setcookie('loggedIn', 'true', time() + (86400 * 30), "/");
		header('Location: index.php');
	} else {
		$error = "Invalid password.";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>GVSU MIP Library Traffic</title>
	<style type="text/css">
	html,body{
		font-family: Helvetica;
	}
	</style>
</head>
<body>
<div style="margin: 0 auto;text-align: center;width:500px">
	<form action='' method="POST">
			<label for"password">Enter password:</label>
			<input name="password" type="password" required>
		<input type="submit" value="Submit">		
	</form>
<div>
<span style="color:red;"><?php echo $error;?></span>

</body>
</html>	
