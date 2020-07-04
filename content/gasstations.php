<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");
echo '
<div class="gasstationContent">';
	$result = $mysql->query("SELECT * FROM `".$mysql_database."`.`Tankstellen`");
	if($result && $result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo '
			<div class="gasstationBox">
				<img src="images/gasstations/gasstation_'.($row["ID"] < 10 ? "0".$row["ID"] : $row["ID"]).'.png" alt="'.$row["Username"].'" />
				<span>
					Owner: <font color="red">'.($row["userID"] != 0 ? "" : "Country San Andreas").'</font><br /><br />
					Purchase Price: <font color="white">'.money_format('%(#10n', $row["Preis"]).'</font><br />					
					Price per liter: <font color="lightgreen">'.money_format('%(#10n', $row["LiterPreis"]).'</font><br />
				</span>
			</div>';
		}
	}
echo '</div>';

?>