<?php
class DbFileUploads
{
   //store a file on the server using the provided filname and description
   //note that the filename and description are the ones we will use in the
   //database, not anything relating to the user's machine.  $description 
   //may be null
   public function addFile($filename, $description)
   {
       
   }

   //Returns all the files matching filename
   public function listFiles($filename)
   {
       $querystr = "SELECT * FROM file_uploads WHERE filename = ".$filename;
       return db_connect::run_query($querystr);
   }   

   //retrieve a file given the fid of the file.   Downloads to a user's
   //machine
   public function retrieveFile($fid)
   {
       $querystr = "SELECT * FROM file_uploads WHERE fid = ".$fid;
       return db_connect::run_query($query_str);
   }

   //delete a file given the fid
   public function deleteFile($fid)
   {
       $querystr = "DELETE FROM file_uploads WHERE fid = ".$fid;
       db_connect::run_query($querystr);
   }

   public function hasPermissions($fid, $pid)
   {
       $querystr = "SELECT * FROM file_permissions WHERE fid = ".$fid." AND pid = ".$pid;
       if(is_null(db_connect::run_query($querystr))) return true;
       else return false;
   }

   // returns the fid, pid combinations that match the fid and pid provided
   // if fid or pid is null, then they are ignored
   // for example listPermissions(null, null) should dump the whole table
   public function listPermissions($fid, $pid)
   {
       $querystr = "SELECT * FROM file_permissions ";
       if(!is_null($fid)||!is_null($pid))
       {
           $querystr = $querystr."WHERE "
           if(!is_null($fid)) $querystr = $querystr."fid=".$fid." ";
           if(!is_null($pid)) $querystr = $querystr."pid=".$pid;
       }
       return db_connect::run_query($querystr);
   }

   //adds an entry to the permissions table if it does not exist
   public function addPermissions($fid, $pid)
   {
       if(!hasPermissions($fid, $pid))
       {
           $querystr = "INSERT ".$fid.",".$pid." INTO file_permissions";
           db_connect::run_query($querystr);
       }
   }
   
   //removes an entry from the permissions table
   public function deletePermissions($fid, $pid)
   {
       $querystr = "INSERT ".$fid.",".$pid." INTO file_permissions";
BORED BORED BORED WILL WORK ON THIS LATER
   }
}
?>
