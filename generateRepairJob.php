<?php
//generates a new repairjob, contractid is not necessary



if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$name=$_POST['name'];
	if(!empty($name)){$name=prepareInput($name);}
	$phone= $_POST['phone'];
		
	$itemid=$_POST['did'];
	if(!empty($itemid)){$itemid=prepareInput($itemid);}
	$model=$_POST['model'];
	if(!empty($model)){$model=prepareInput($model);}
	$price=$_POST['price'];
	$year=$_POST['year'];
	if($_POST['dtype']==1){
		$computer=1;
		$printer=0;	
	}
	else{
		$computer=0;
		$printer=1;	
	}
	if(empty($itemid)){
	insertCustomerInfo($name,$phone);
	$itemid=insertDevice($model,$price,$year,$computer,$printer,NULL,$name,$phone);	
	}
	insertRepairJob($itemid,NULL,$model,$name,$phone);
}
function prepareInput($input){
	$input=trim($input);
	$input=htmlspecialchars($input);
	return $input;
}
function generateRandomString($length) {
    return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
}
function insertCustomerInfo($name,$phone){
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$query=oci_parse($conn,"Insert into customers(name,phone) values (:name,:phone)");
	oci_bind_by_name($query,':name', $name);
	oci_bind_by_name($query,':phone', $phone);

	$res = oci_execute($query);
	if ($res)
		echo '<br>Data successfully inserted</br>';
	else{
		$e = oci_error($query); 
        	echo $e['message']; 
	}
	OCILogoff($conn);
}

function insertDevice($model,$price,$year,$computer,$printer,$contractid,$name,$phone){
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	
	$itemid=generateRandomString(9);
	
	$query=oci_parse($conn,"Insert into RepairItems(itemid,model,price,year,computer,printer,serviceContract) values (:itemid,:model,:price,:year,:computer,:printer,:contract)");
	oci_bind_by_name($query,':model', $model);
	oci_bind_by_name($query,':contract', $contractid);
	oci_bind_by_name($query,':price', $price);
	oci_bind_by_name($query,':year', $year);
	oci_bind_by_name($query,':computer', $computer);
	oci_bind_by_name($query,':printer', $printer);
	oci_bind_by_name($query,':itemid', $itemid);
	
	$res = oci_execute($query);
	if ($res)
		echo '<br><br> <p style="color:green;font-size:20px">Data successfully inserted</p>';
	else{
		$e = oci_error($query); 
        	echo $e['message']; 
	}
	OCILogoff($conn);
	return $itemid;
}
function insertRepairJob($itemid,$contractid,$model,$name,$phone){
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$repairid=generateRandomString(9);
	$query=oci_parse($conn,"Insert into repairjob(repairjobid,machineid,contractid,status,timeofarrival,model,customername,phone,timeout) values (:repairid,:itemid,:contractid,'UNDER_REPAIR',SYSDATE,:model,:name,:phone,NULL)");
	oci_bind_by_name($query,':repairid', $repairid);
	oci_bind_by_name($query,':model', $model);
	oci_bind_by_name($query,':contractid', $contractid);
	oci_bind_by_name($query,':itemid', $itemid);
	oci_bind_by_name($query,':phone',$phone);
	oci_bind_by_name($query,':name',$name);
	$res = oci_execute($query);




	if ($res)
		echo '<br><br> <p style="color:green;font-size:20px">Data successfully inserted</p>';
	else{
		$e = oci_error($query); 
        	echo $e['message']; 
	}
	OCILogoff($conn);
}

?>
