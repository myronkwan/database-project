<?php 
//generate customer bill given repairjob id
if ($_SERVER["REQUEST_METHOD"]=="POST"){ 
	$rjid=$_POST['rjid']; 
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g'); 
	if(!$conn) { 
	print "<br> connection failed:"; exit; } 
	$query=oci_parse($conn,"Select * from customerbill where repairjobid=:machineid"); 
	oci_bind_by_name($query,':machineid',$rjid); 
	oci_execute($query); 
	oci_fetch_all($query,$result,null,null,OCI_FETCHSTATEMENT_BY_ROW);
	echo "<br>CUSTOMER BILL for: ".$result[0]['MACHINEID']."</br>"; 
	echo "<br>contractid: ".$result[0]['CONTRACTID']."</br>";
	echo "<br> model: ".$result[0]['MODEL']."</br>";
	echo"<br>customer name: ". $result[0]['CUSTOMERNAME']."</br>"; 
	echo"<br>phone: ".$result[0]['PHONE']."</br>"; 
	echo"<br>time in: ".$result[0]['TIMEIN']."</br>"; 
	echo"<br>time out: ".$result[0]['TIMEOUT']."</br>"; 
	echo"<br>problem codes: ".$result[0]['PROBLEMCODES']."</br>"; 
	echo"<br>repairer: ".$result[0]['REPAIRPERSON']."</br>"; 
	echo"<br>labor hours: ".$result[0]['LABORHOURS']."</br>"; 
	echo"<br>costs: $".$result[0]['COSTS']."</br>"; 
	echo"<br>total: $".$result[0]['TOTAL']."</br>"; 
	oci_free_statement($query); 
	OCILogoff($conn); 
}
	
 ?>