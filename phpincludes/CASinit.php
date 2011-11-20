<?php
//this file should be included once if you want to make sure that cas is init'd
phpCAS::client(CAS_VERSION_2_0, 'login.case.edu', 443, '/cas');
?>
