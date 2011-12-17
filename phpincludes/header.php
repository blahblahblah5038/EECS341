<?
include_once('CAS/CAS.php');
phpCAS::client(CAS_VERSION_2_0, 'login.case.edu', 443, '/cas');
session_start();
?>

<html>
<head>
	<title>Archery Club Website / EECS 341 Project</title>
	<link rel="stylesheet" type="text/css" href="/styles.css" />
</head>
<body>
<div id="header">
	CWRU Archery Club
</div>
<div id="navbar">
	<h2>Links</h2>
	<ul>
		<li><a href="/index.php">Home</a></li>
		<li><a href="/calendar/">Calendar</a></li>
		<li><a href="/contacts/">Contacts</a></li>
		<li><a href="/membership/">Members</a></li>
		<li><a href="/fileuploads/">File Uploads</a></li>
		<li><a href="/inventory/">Inventory</a></li>
		<li><a href="/finance/">Finance</a></li>
		<li><a href="/officer_positions/">Officer Positions</a></li>
		<li><a href="/officers/">Officers</a></li>
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
