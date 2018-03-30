<?php
if ($_SERVER["REQUEST_METHOD"]=="POST"){
	$conn=oci_connect('mkwan','pass', '//dbserver.engr.scu.edu/db11g');
	if(!$conn) {
	     print "<br> connection failed:";       
        exit;
	}
	$query=oci_parse($conn,"Select * from repairlog");
	oci_execute($query);
	oci_fetch_all($query,$result,null,null,OCI_FETCHSTATEMENT_BY_ROW);
	echo "<table style='width:30%'>";
	echo "<caption>REPAIR LOG:</caption>";
	echo "<tr><th>REPAIRJOBID: </th><th>MACHINE ID</th><th>STATUS</th><th>UPDATED</th></tr>";
	foreach($result as $row){
		echo"<tr><th>".$row['REPAIRJOBID']."</th><th>".$row['MACHINEID']."</th><th>".$row['STATUS']."</th><th>".$row['UPDATED']."</th></tr>";
	}
	echo "</table>";
	

	oci_free_statement($query);
	OCILogoff($conn);
}

?>
