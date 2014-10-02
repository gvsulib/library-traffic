<?php include 'includes.php'; ?>
<!DOCTYPE html>
<html>
<head>
	<title>GVSU MIP Library Traffic</title>
	<!-- <link rel="stylesheet" type="text/css" href="http://gvsu.edu/cms3/assets/741ECAAE-BD54-A816-71DAF591D1D7955C/libui.css" /> -->
	<link rel="stylesheet" type="text/css" href="css/styles.css" />
</head>
<body>
<h1>GVSU MIP Library Traffic <small><a href="index.php">Space Use Form</a></small></h1>
<h2><?php checkIP();?></h2>
	<?php
	displayForm("traffic");
	?>
	<script src="//code.jquery.com/jquery.js"></script>
	<script src="js/jquery.validate.js"></script>
    <script src="js/jquery.swap.js"></script>
	<script src="js/scripts.js"></script>
</body>
</html>