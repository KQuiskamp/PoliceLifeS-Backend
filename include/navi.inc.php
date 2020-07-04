<?php
defined("main") or die ("<html><head><title>ACCESS DENIED</title></head><body><h1>ACCESS DENIED</h1></body></html>");


$xml = @simplexml_load_file('/var/customers/webs/PoliceLifeS/version.xml');

echo '
<div class="header_inner">
	<a href="//policelifes.de/dashboard"><div class="logo"></div></a>
	<div class="version_box">
		<div class="version_box_inner">
			<img id="secureSSL" src="//policelifes.de/images/secure.png" width="35" style="display: inline-block; vertical-align:middle;"/>
			<h2>Release Alpha '.$xml->version[0].'</h2>&nbsp;<span class="ui-spacer ui-spacer-green">Stable Version</span>&nbsp;<a href="//policelifes.de/PoliceLifeS_'.$xml->version[0].'.zip" download><span class="ui-spacer ui-spacer-blue">Download here</span></a>&nbsp;<a href="//policelifes.de/guide/PoliceLifeS_GUIDE.pdf" target="_blank"><span class="ui-spacer ui-spacer-orange">User-Guide</span></a><br /><br />
			<a href="https://discord.gg/djgn6PG" title="Join on Discord!" target="_blank">				
				<img src="//policelifes.de/images/discord.png" width="35" style="display: inline-block; vertical-align:middle;"/>
				We have a Discord-Server, Join now!
			</a><br>
			<a href="https://fivem.net/" title="Join FiveM" target="_blank">
			<img src="https://pbs.twimg.com/profile_images/847824193899167744/J1Teh4Di_400x400.jpg" width="35" style="display: inline-block; vertical-align:middle;"/> We have a FiveM Server!<br>
				Address: PoliceLifeS.de:30120<br>
			</a>			
			<p style="color: red;">For our Forum, you need an separate account</p>
			<a href="mailto:mail@PoliceLifeS.de">Any bugs or crashes? Contact me</a>
			<p>Our newest and first Version, for more Information <a href="//policelifes.de/features">click here</a></p>
			<a style="color: red;" href="https://bondora.com/ref/BO1A4K9A7" target="_blank">Investing at Bondora.com | 6%-12% per year (Affiliate Link)</a>
		</div>
	</div>
</div>
<div class="naviBar">	
	<ul>
		<li class="ui-active"><a href="//policelifes.de/dashboard">PoliceLifeS</a></li>
		<li><a href="//policelifes.de/features">Features</a></li>
		<li><a href="//policelifes.de/ranking">Ranking</a></li>
		<li><a href="//policelifes.de/forum">Forum</a></li>
		<li><a href="https://www.youtube.com/channel/UCvHYkFUxmbnI2-9-ka3z4gw" target="_blank">YouTube</a></li>
		'.(isset($_SESSION["angemeldet"]) ? "<li><a href=\"//policelifes.de/officer/".urlencode($_SESSION["userData"]["Username"])."\">".$_SESSION["userData"]["Username"]."</a></li>" : "").'
	</ul>
</div>';

/*PoliceLifeS costs money, if you want to support us to keep the project alive. All of the money is used for <a href="//policelifes.de/donators">PoliceLifeS</a>:<br /><br />		
			<a href="https://www.paypal.com/myaccount/transfer/send" target="_blank">
				<img alt="" style="display:block; margin: auto; "border="0" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donate_SM.gif" width="74" height="21">
			</a>
			<p style="color: orange;">The PayPal Mail-Address: mail@PoliceLifeS.de</p>	*/
