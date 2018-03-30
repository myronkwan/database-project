<?php
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$query=oci_parse($conn,"Select * from repairitems");
	oci_execute($query);
	oci_fetch_all($query,$result,null,null,OCI_FETCHSTATEMENT_BY_ROW);
	echo "<table style='width:30%'>";
	echo "<caption>DEVICES</caption>";
	echo "<tr><th>MID</th><th>MODEL</th><th>PRICE</th><th>YEAR</th><th>COMPUTER</th><th>PRINTER</th><th>CONTRACT</th></tr>";
	foreach($result as $row){
		echo "<tr>";
		echo "<th>".$row['ITEMID']."</th>";
		echo "<th>".$row['MODEL']."</th>";
		echo "<th>".$row['PRICE']."</th>";
		echo"<th>". $row['YEAR']."</th>";
		echo"<th>".$row['COMPUTER']."</th>";
		echo"<th>".$row['PRINTER']."</th>";
		echo"<th>".$row['SERVICECONTRACT']."</th>";
		echo "</tr>";
	}
	echo "</table>";
	oci_free_statement($query);
	OCILogoff($conn);
}

?>
