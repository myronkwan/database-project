<?php
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$query=oci_parse($conn,"Select * from customerbill");
	oci_execute($query);
	oci_fetch_all($query,$result,null,null,OCI_FETCHSTATEMENT_BY_ROW);
	$revenue=0;
	echo "<table style='width:30%'>";
	echo "<caption>REPAIR LOG:</caption>";
	echo "<tr><th>MID</th><th>CID</th><th>MODEL</th><th>NAME</th><th>PHONE</th><th>IN</th><th>OUT</th><th>LABOR HOURS</th><th>COSTS</th><th>TOTAL</th></tr>";
	foreach($result as $row){
		echo "<tr>";
		echo "<th>".$row['MACHINEID']."</th>";
		echo "<th>".$row['CONTRACTID']."</th>";
		echo "<th>".$row['MODEL']."</th>";
		echo"<th>". $row['CUSTOMERNAME']."</th>";
		echo"<th>".$row['PHONE']."</th>";
		echo"<th>".$row['TIMEIN']."</th>";
		echo"<th>".$row['TIMEOUT']."</th>";
		echo"<th>".$row['LABORHOURS']."</th>";
		echo"<th>".$row['COSTS']."</th>";
		echo"<th>".$row['TOTAL']."</th>";
		$revenue+=$row['TOTAL'];
	}
	echo "<tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th>revenue: $".number_format($revenue,2)."</tr>";
	echo "</tr>";
	OCILogoff($conn);
}

?>
