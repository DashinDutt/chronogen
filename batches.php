<?php

/**
 * Back end routines to add/delete batches, invoked by manage.php
 */

require_once('functions.php');
require_once('connect_db.php');
if(!sessionCheck('logged_in'))
  postResponse("error","Your session has expired, please login again");
if(!sessionCheck('level','dean'))
    die('You are not authorized to perform this action');
if(valueCheck('action','add'))
{
    rangeCheck('batch_name',2,30);
  
    try{
        $query = $db->prepare('INSERT INTO batches(batch_name,batch_dept) values (?,?)');
        $query->execute([$_POST['batch_name'],$_POST['dept']]);
        postResponse("addOpt","Batch Added",[$_POST['batch_name'].' : '.$_POST['dept']]);    
    }
    catch(PDOException $e)
    {
        if($e->errorInfo[0]==23000)
            postResponse("error","Batch already exists");
        else
            postResponse("error",$e->errorInfo[2]);
    }
    
}
elseif(valueCheck('action','delete'))
{
    $query = $db->prepare('DELETE FROM batches where batch_name = ? AND batch_dept=?');
    $batch = explode(" : ",$_POST['batch']);
    $query->execute([$batch[0],$batch[1]]);
    postResponse("removeOpt","Faculty deleted");
}

?>