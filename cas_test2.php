<?php
// Example for a simple client

// Load the settings from the central config file
//include_once('config.php');
// Load the CAS lib
include_once('phpincludes/CAS/CAS.php');
include_once('phpincludes/header.php');
include_once('phpincludes/login.php');
// for this test, simply print that the authentication was successfull
?>
<html>
  <head>
    <title>phpCAS simple client</title>
  </head>
  <body>
    <h1>Successfull Authentication!</h1>
    <?php include 'script_info.php' ?>
    <p>the user's login is <b><?php echo phpCAS::getUser(); ?></b>.</p>
    <p>phpCAS version is <b><?php echo phpCAS::getVersion(); ?></b>.</p>
    <p><a href="?logout=">Logout</a></p>
  </body>
</html>
<?php include_once('phpincludes/footer.php');?>
