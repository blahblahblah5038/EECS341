<?php
///////////////////////////////////////////////////////////////////////////////
////
//// This page shows a more detailed version of the member information.  That 
//// is to say that the main contacts page may not show all of the fields, and
//// that this one is the most detailed view possible.
////
//// Furthermore, we will be offering edit and deletion features for officers
////
///////////////////////////////////////////////////////////////////////////////

         include("../phpincludes/header.php");
         include("../phpincludes/login.php");
         include("../phpincludes/db_access.php");
         include("../phpincludes/db_members.php");

    $pid = db_access::getPidFromCaseId(phpCAS::getUser());    
    if(!(db_access::isAdmin($pid)))
    {
         echo "Error, you are not a club officer.  Go away.";
    }
    else
    {
         if(isset($_POST['edituser']))
         {
             editMember($_POST['mid'], $_POST['netid'],$_POST['studid'],$_POST['bowpref'],$_POST['hand'],$_POST['clubexpir'],$_POST['memid'],$_POST['uscaexpir'],$_POST['emername'],$_POST['emerphone'],$_POST['insurer'], $_POST['policy']); 
          echo '<script language="javascript"><!--
              window.location.href = "index.php"
              //--></script>';
       
         }
         else if( isset($_POST['adduser']))
         {
             echo $_POST['pid'];
             if(isContact($_POST['pid']))
             {
                 addMember($_POST['pid'], $_POST['netid'],$_POST['studid'],$_POST['bowpref'],$_POST['hand'],$_POST['clubexpir'],$_POST['memid'],$_POST['uscaexpir'],$_POST['emername'],$_POST['emerphone'],$_POST['insurer'], $_POST['policy']);
              echo '<script language="javascript"><!--
              window.location.href = "index.php"
              //--></script>';
             }
             else echo "ERROR: Make sure that person is a contact first!";
         }
         else
         {
             echo "ERROR: invalid state!\n";
         }
    }  
  
?>
