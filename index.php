<?php
	define("main", true);
	header('Content-Type: text/html; charset=UTF8');	
	require_once "include/head.inc.php";
?>

<!DOCTYPE html> 
<html>
	<base href="<?php echo "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] ?>">
	<head>	
		<title>PoliceLifeS - Control Panel</title>

		<!-- Meta -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Quiskamp">

		<!-- JS Scripts -->
		<script type="text/javascript" src="js/jquery-2.2.4.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/query-string.js"></script>
		<script type="text/javascript" src="js/moment.js"></script>
		<script type="text/javascript" src="js/moment-timezone.js"></script>
		<script type="text/javascript" src="js/jstz.min.js"></script>
		<script type="text/javascript" src="js/detect_timezone.js"></script>
		<script type="text/javascript" src="js/jquery.circliful.min.js"></script>
		<script type="text/javascript" src="js/lightgallery/js/lightgallery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script src="js/lightgallery/js/lg-thumbnail.min.js"></script>
    	<script src="js/lightgallery/js/lg-fullscreen.min.js"></script>
		<script type="text/javascript" src="js/policelifes.js"></script>
		<script type="text/javascript" src="js/tipped.js"></script>		
		<script type="text/javascript" src="js/ads.js"></script>

		<?php 
			if(!isset($_GET["AdBlock"])) {
				echo '
				<script type="text/javascript">
					$(function () {
						if($("#CDIQcrPilfgB").length <= 0){
						  	window.location.replace("https://policelifes.de/index.php?AdBlock");
						}
					});			
				</script>
				';
			}
		?>

		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script>
		  (adsbygoogle = window.adsbygoogle || []).push({
		    google_ad_client: "ca-pub-9659371395431356",
		    enable_page_level_ads: true
		  });
		</script>
		<script async custom-element="amp-auto-ads"
		        src="https://cdn.ampproject.org/v0/amp-auto-ads-0.1.js">
		</script>

		<!-- Styles -->
		<link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="js/tipped.css" />
		<link rel="stylesheet" type="text/css" href="js/jquery.circliful.css" />
		<link rel="stylesheet" type="text/css" href="js/font-awesome.min.css" />
		<link type="text/css" rel="stylesheet" href="js/lightgallery/css/lightgallery.css" />
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>		
		<?php if(!isset($_GET["noJava"])) { echo '<noscript><meta http-equiv="refresh" content="0;url=index.php?noJava"></noscript>'; } ?>
		<?php if(isset($_GET["noJava"])) { ?>
			<p class="msg-info error-box">This Page need Javascript! Please activate Javascript! <a href="/">Back to Dashboard</a></p>
		<?php } else if(isset($_GET["AdBlock"])) { ?>
			<p class="msg-info error-box">Please disable Adblock, PoliceLifeS is financed by advertising! <a href="/">Back to Dashboard</a></p>
		<?php } else { ?>
		<div class="wrapper">	
			<amp-auto-ads type="adsense" data-ad-client="ca-pub-9659371395431356"></amp-auto-ads>	
			<div class="header">			
				<?php include_once "include/navi.inc.php" ?>				
			</div>
			<div class="content">
				<?php include_once "include/content.inc.php" ?>
			</div>
			<div class="footer">
				<div class="footer-item"><a href="contact">PoliceLifeS (Contact)</a><br />Visitors: <b><?php echo $totalVisitor; ?></b></a><br /></div>
				<div class="footer-item">The CMS is a self-written copyrighted script<br /><font color="orange">&copy; PoliceLifeS 2015. All Rights reserved.</font></div>
				<div class="footer-item">					
				</div>
			</div>			
		</div>
		<?php } ?>
	</body>
</html>
