<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

echo '
<div class="officerStatistic">
	<div class="officerStatisticBox">
		<span id="ondutyBox">ONDUTY</span>
		<div id="onDutyCount">0</div>
	</div>
	<div class="officerStatisticBox">
		<span id="officerBox">OFFICERS</span>
		<div id="officerCount">0</div>
	</div>
	<div class="officerStatisticBox">
		<span id="arrestedBox">ARRESTED</span>
		<div id="arrestedCount">0</div>
	</div>
	<div class="officerStatisticBox">
		<span id="calloutsBox">CALLOUTS</span>
		<div id="calloutCount">0</div>
	</div>
</div>';

if(file_exists("content/" . $page . ".php")){
	//PHP Datein includen mit erster Priorität
	include "content/" . $page . ".php";
} else if (file_exists("content/" . $page . ".html")) {
	//HTML Datein includen mit zweiter Priorität
	include "content/" . $page . ".html";
} else include "content/nopage.php";	

