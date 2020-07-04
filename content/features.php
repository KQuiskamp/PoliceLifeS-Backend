<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");

echo '
<div class="officerFeatures">
	<div class="officerFeatures_inner">
		<h3>What is stored/transmitted?:</h3>
		<p>We want to make everything transparent, what information are received.</p>
		<h4>Website:</h4>
		<ul>
		  <li>PoliceLifeS <b style="color: green">doesn\'t</b> save the Location, IP address or other personal data.</li>
		  <li>PoliceLifeS save all Passwords with 256Bit AES Encryption! (Only you know the correct Password).</li>
		</ul>
		<h4>In-Game:</h5>
		<ul>
		  <li>We transmitted is Player arrested someone.</li>
		  <li>We transmitted the current Duty State, Player is On-Duty/Off-Duty.</li>
		  <li>We transmitted the current running Callout.</li>
		</ul>
		<hr />
		<h3>Features:</h3>
		<ul class="features">
		  <li><h4>Control Panel</h4>
		    <span style="text-decoration: underline;">We have our
		own
		online Control Panel</span>, <span style="font-weight: bold;">each
		player can create his own profile</span><br>
		The Control Panel has currently not many functions but I
		have many
		functions in Planning<br>
		e.g. a house system, Car System , statistic how many people
		were
		arrested and more.&nbsp;<br>
		  </li>
		</ul>
		<ul class="features">
		  <li><h4>Hunger System</h4>
		To stay alive you need to eat, we have created
		on the map eat stations
		where you can fill your life.&nbsp;<br>
		  </li>
		</ul>
		<ul class="features">
		  <li><h4>Vehicle gas tank system</h4>
		Each vehicle has its own fuel tank, the tank is stored in a
		local
		database after restarting GTA it is therefore still present<br>
		There are petrol stations on the map where you can fill up
		your vehicle
		tank<br>
		The faster you drive the more fuel you consume&nbsp;<br>
		You have now the choice what you want for a Texture and the
		Texture -
		position, read the GTAV/Plugins/LSPDFR/PoliceLifeS/PoliceLifeS.ini file
		for more information!&nbsp;<br>
		  </li>
		</ul>
		<ul class="features">
		  <li><h4>Rank System</h4>
		If you successfully complete tasks then you get experience
		points, your
		rank you can view on our website<br>
		so when you arrest a person you get Experience Points<br>
		Later features are available only with a certain
		rank&nbsp;<br>
		  </li>
		</ul>
		<ul class="features">
		  <li><h4>Shift Work System</h4>
		You can now start your shift work, the shift is 8 hours (30
		Min.
		reallife time) if you die, your shift is canceled<br>
		If you survive the time then you get Exp. If you arrested
		more
		criminals then you get more Exp.<br>
		To get Exp you must be logged, read the PoliceLifeS.ini
		file
		(GTAV/Plugins/LSPDFR/PoliceLifeS/PoliceLifeS.ini)<br>
		The starting point is marked on the map were you can going
		Onduty<br>
		  </li>
		</ul>
		<ul class="features">
		  <li><h4>Money System</h4>
		You have now your own Money System, when you press Capslock
		you can see
		your money.<br>
		After a shift work you get 75-800$.<br>
		You can buy something soon with your money, this is in
		development
		  </li>
		</ul>
		<ul class="features">
		  <li><h4>Speedometer + Gauge Fuel</h4>
		  	The speedometer is in KM/H, 1 KM/H ~ 0,62 MPH
			<br /><br />
			<div id="featureGallery">
				<a href="images/presentation/speedogaugeoff.png" data-download-url="false">
      				<img src="images/presentation/speedogaugeoff.png" width="150"/>
  				</a>
  				<a href="images/presentation/speedogaugeon.png" data-download-url="false">
      				<img src="images/presentation/speedogaugeon.png" width="150"/>
  				</a>
  			</div>
		  </li>
		</ul>
	</div>
</div>';
