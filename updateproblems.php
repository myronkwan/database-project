<?php
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$problemcodes="";
	$codes=$_POST['problemcodes'];
	for($i=0;$i<7;$i++){
		$problemcodes.=$codes[$i];
	}
	$rjid=$_POST['rjid3'];
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$query=oci_parse($conn,"Update problemreport set problemcodes=:codes where repairjobid=:rjid");
	oci_bind_by_name($query,':codes',$problemcodes);
	oci_bind_by_name($query,':rjid',$rjid);
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
