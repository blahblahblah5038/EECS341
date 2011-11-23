<?
include_once('CAS/CAS.php');
phpCAS::client(CAS_VERSION_2_0, 'login.case.edu', 443, '/cas');
?>

<html>
<head>
	<title>Archery Club Website / EECS 341 Project</title>
	<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body>
<div id="header">
	Header Text Goes Here
</div>
<div id="navbar">
	<h2>Links</h2>
	<ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="#">Somewhere</a></li>
		<li><a href="#">Elsewhere</a></li>
	</ul>
</div>
<div id="sidebar">
	<h2>Sidebar</h2>
	<ul>
		<li><a href="#">Another</a></li>
		<li><a href="#">More</a></li>
		<li><a href="#">Stuff</a></li>
	</ul>
</div>
<div id="frame">
<!-- End header -->
