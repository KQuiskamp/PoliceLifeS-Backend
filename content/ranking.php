<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

echo '
<p class="msg-info">All stats will be reset, after the Alpha and the balancing!</p>

<ul>
	<li>Level 1 - Level 10: Recruit</li>
  	<li>Level 11 - Level 15: Officer</li>
  	<li>Level 16 - Level 20: Officer II</li>
  	<li>Level 21 - Level 30: Officer III</li>  
 	<li>Level 31 - Level 40: Detective</li>
 	<li>Level 41 - Level 50: Detective II</li>
  	<li>Level 51 - Level 60: Sergeant</li>
  	<li>Level 61 - Level 70: Sergeant II</li>
  	<li>Level 71 - Level 80: Lieutenant</li>
  	<li>Level 81 - Level 100: Captain</li>
  	<li>Level 101 - Level 120: Major</li>
  	<li>Level 121 - Level 140: Colonel</li>
  	<li>Level 141 - Level 160: Commander</li>
  	<li>Level 161 - Level 180: Deputy Chief of Police</li>
  	<li>Level 181 - Level 199: Assistant Chief</li>
  	<li>Level >= 200: Chief of Police</li>
</ul>

<table style="text-align: left; width: 100%; text-align: center;" border="0" cellpadding="2" cellspacing="2">
	<tbody>
    	<tr>
      		<td width="50%">
      			<h3>Most people arrested:</h3>
      			<div class="ranking_table_wrapper">
	    			<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
	    				<thead>
	    					<th width="15%">Order</th>
	    					<th>Name</th>
	    					<th width="5%">Arrested</th>
	    				</thead>
	  					<tbody>';
	  						$result = $mysql->query("SELECT `StaffRang`, `Username`, `ArrestedPeds` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' ORDER BY `ArrestedPeds` DESC LIMIT 100");
	  						if($result && $result->num_rows > 0) {
	  							$rang = 1;
	  							while($row = $result->fetch_assoc()) {
	  								echo '
	  								<tr>
	  									<td>'.$rang++.'</td>
	  									<td><a href="officer/'.urlencode($row["Username"]).'"><font color="'.$utility->getRangColor($row["StaffRang"]).'">'.$row["Username"].'</font></a></td>
	  									<td>'.$row["ArrestedPeds"].'</td>
	  								</tr>';
	  							}
	  						}  						
	  			echo '	</tbody>
	  				</table>
  				</div>
      		</td>
      		<td width="50%">
      			<h3>Highest Level:</h3>
      			<div class="ranking_table_wrapper">
	      			<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
	    				<thead>
	    					<th width="15%">Order</th>
	    					<th>Name</th>
	    					<th>Level</th>
	    				</thead>
	  					<tbody>';
	  						$result = $mysql->query("SELECT `StaffRang`, `Username`, `Exp` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' AND `Banned` = '0' ORDER BY `Exp` DESC LIMIT 100");
	  						if($result && $result->num_rows > 0) {
	  							$rang = 1;
	  							while($row = $result->fetch_assoc()) {
	  								echo '
	  								<tr>
	  									<td>'.$rang++.'</td>
	  									<td><a href="officer/'.urlencode($row["Username"]).'"><font color="'.$utility->getRangColor($row["StaffRang"]).'">'.$row["Username"].'</font></a></td>
	  									<td>'.$utility->getLevel($row["Exp"]).' ('.$utility->getRangTitle($row["StaffRang"], $utility->getLevel($row["Exp"])).')</td>
	  								</tr>';
	  							}
	  						}  						
	  			echo '	</tbody>
	  				</table>
	  			</div>
      		</td>
    	</tr>
    	<tr>
      		<td width="50%">
      			<h3>Most money collected:</h3>
      			<div class="ranking_table_wrapper">
	      			<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
	    				<thead>
	    					<th width="15%">Order</th>
	    					<th>Name</th>
	    					<th width="5%">Money</th>
	    				</thead>
	  					<tbody>';
	  						$result = $mysql->query("SELECT `StaffRang`, `Username`, `Money` FROM `".$mysql_database."`.`User` WHERE `Aktiv` = '1' AND `Banned` = '0' ORDER BY `Money` DESC LIMIT 100");
	  						if($result && $result->num_rows > 0) {
	  							$rang = 1;
	  							while($row = $result->fetch_assoc()) {
	  								echo '
	  								<tr>
	  									<td>'.$rang++.'</td>
	  									<td><a href="officer/'.urlencode($row["Username"]).'"><font color="'.$utility->getRangColor($row["StaffRang"]).'">'.$row["Username"].'</font></a></td>
	  									<td>'.$row["Money"].'</td>
	  								</tr>';
	  							}
	  						}  						
	  			echo '	</tbody>
	  				</table>
  				</div>
      		</td>    
      		<td width="50%">
      			<h3>Most Accepted Callouts:</h3>
      			<div class="ranking_table_wrapper">
	      			<table style="text-align: left; width: 100%;" border="0" cellpadding="2" cellspacing="2">
	    				<thead>
	    					<th width="10%">Order</th>
	    					<th>Name</th>
	    					<th width="5%">Count</th>
	    				</thead>
	  					<tbody>';
	  						$result = $mysql->query("SELECT COUNT(`ID`) AS `TotalAnzahl`, `Name` FROM `".$mysql_database."`.`CalloutLog` GROUP BY `Name` ORDER BY `TotalAnzahl` DESC");
	  						if($result && $result->num_rows > 0) {
	  							$rang = 1;
	  							while($row = $result->fetch_assoc()) {
	  								echo '
	  								<tr>
	  									<td>'.$rang++.'</td>
	  									<td>'.$row["Name"].'</td>
	  									<td>'.$row["TotalAnzahl"].'</td>
	  								</tr>';
	  							}
	  						}  						
	  			echo '	</tbody>
	  				</table>
	  			</div>
      		</td>   		
    	</tr>      		     
  	</tbody>
</table>';

?>