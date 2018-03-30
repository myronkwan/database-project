<?php
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$rjid=$_POST['rjid2'];
	$status=$_POST['status'];
	
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$query=oci_parse($conn,"update repairjob set status=:status where repairjobid=:rjid");
	oci_bind_by_name($query,':rjid', $rjid);
	oci_bind_by_name($query,':status', $status);
	

	$res = oci_execute($query);
	if ($res)
		echo '<br>Data successfully inserted</br>';
	else{
		$e = oci_error($query); 
        	echo $e['message']; 
	}
	OCILogoff($conn);
}


?>

