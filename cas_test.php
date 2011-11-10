<?php
 
//initialize the CAS library
require_once('CAS/CAS.php');
phpCAS::client(CAS_VERSION_2_0, 'login.case.edu', 443, '/cas');
 
//if the user is requesting to be logged in
if (isset($_REQUEST['login'])) {
   phpCAS::forceAuthentication();
   //the user is known to be logged in to CAS at this point
   $_SESSION['loggedInLocally'] = true;  //set a local variable telling the program we are logged in
   $_SESSION['username'] = phpCAS::getUser();  //this stores their network user id
}
 
//if we want to log out of the program
if (isset($_REQUEST['logout'])) {
   $_SESSION['loggedInLocally'] = false;
   unset($_SESSION['username']);
}
 
if (isset($_SESSION['loggedinLocally']) && $_SESSION['loggedInLocally']===true) {
   echo "You are logged in to the application ".phpCAS::getUser();
} else {
   echo "You are not logged in to the application.  Log in by specifying the 'login' log parameter to this script.";
}
 
?>
