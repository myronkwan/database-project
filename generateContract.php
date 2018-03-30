<?php
//creates a new service contract

if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$machineid=$_POST['itemid'];
	if(!empty($machineid)){$machineid=prepareInput($machineid);}
	$name=$_POST['name2'];
	if(!empty($name)){$name=prepareInput($name);}
	$phone=$_POST['phone2'];
	
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$contractid=generateRandomString(9);
	
	$query=oci_parse($conn,"Insert into servicecontract(contractid,machineid,startdate,enddate,name,phone) values (:contractid,:machineid,SYSDATE,SYSDATE+7,:name,:phone)");
	oci_bind_by_name($query,':contractid',$contractid);
	oci_bind_by_name($query,':machineid',$machineid);
	oci_bind_by_name($query,':name',$name);
	oci_bind_by_name($query,':phone',$phone);
	
	$res = oci_execute($query);
	if ($res)
		echo '<br><br> <p style="color:green;font-size:20px">Data successfully inserted</p>';
	else{
		$e = oci_error($query); 
        	echo $e['message']; 
	}	
	
	OCILogoff($conn);
}
function generateRandomString($length) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
function prepareInput($input){
	$input=trim($input);
	$input=htmlspecialchars($input);
	return $input;
}
?>
