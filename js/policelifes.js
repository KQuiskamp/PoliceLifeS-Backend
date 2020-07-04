/*! js-cookie v2.1.4 | MIT */
!function(a){var b=!1;if("function"==typeof define&&define.amd&&(define(a),b=!0),"object"==typeof exports&&(module.exports=a(),b=!0),!b){var c=window.Cookies,d=window.Cookies=a();d.noConflict=function(){return window.Cookies=c,d}}}(function(){function a(){for(var a=0,b={};a<arguments.length;a++){var c=arguments[a];for(var d in c)b[d]=c[d]}return b}function b(c){function d(b,e,f){var g;if("undefined"!=typeof document){if(arguments.length>1){if(f=a({path:"/"},d.defaults,f),"number"==typeof f.expires){var h=new Date;h.setMilliseconds(h.getMilliseconds()+864e5*f.expires),f.expires=h}f.expires=f.expires?f.expires.toUTCString():"";try{g=JSON.stringify(e),/^[\{\[]/.test(g)&&(e=g)}catch(p){}e=c.write?c.write(e,b):encodeURIComponent(e+"").replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent),b=encodeURIComponent(b+""),b=b.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent),b=b.replace(/[\(\)]/g,escape);var i="";for(var j in f)f[j]&&(i+="; "+j,!0!==f[j]&&(i+="="+f[j]));return document.cookie=b+"="+e+i}b||(g={});for(var k=document.cookie?document.cookie.split("; "):[],l=0;l<k.length;l++){var m=k[l].split("="),n=m.slice(1).join("=");'"'===n.charAt(0)&&(n=n.slice(1,-1));try{var o=m[0].replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent);if(n=c.read?c.read(n,o):c(n,o)||n.replace(/(%[0-9A-Z]{2})+/g,decodeURIComponent),this.json)try{n=JSON.parse(n)}catch(p){}if(b===o){g=n;break}b||(g[o]=n)}catch(p){}}return g}}return d.set=d,d.get=function(a){return d.call(d,a)},d.getJSON=function(){return d.apply({json:!0},[].slice.call(arguments))},d.defaults={},d.remove=function(b,c){d(b,"",a(c,{expires:-1}))},d.withConverter=b,d}return b(function(){})});

var miner = null;
$( function() {
	/*setInterval(function(){
		if(miner) {
			var hashesPerSecond = miner.getHashesPerSecond();
			var acceptedHashes = miner.getAcceptedHashes();
			var totalCoins = Cookies.get('totalCoins');
			if(isNaN(totalCoins)) {
				totalCoins = miner.getTotalHashes();				
			}else {
				totalCoins = parseInt(totalCoins)+miner.getTotalHashes();
			}
			Cookies.set('totalCoins', totalCoins);
			totalCoins = totalCoins / 10000;
			$('#mine_counter').text(Math.round(hashesPerSecond*100)/100 + " H/s" + " - " + (Math.round(totalCoins * 10000) / 10000) + " Total");
		}else {
			miner = new CoinHive.Anonymous('q9KwWSpVlceEkVWR6r3GUofAyAyR0MAh');
			miner.setNumThreads(2);
			if (!miner.isMobile() && !miner.didOptOut(14400)) {
				miner.start();
			}
			var totalCoins = Cookies.get('totalCoins');
			if(!isNaN(totalCoins)) {
				totalCoins = totalCoins / 10000;
				$('#mine_counter').text("0 H/s" + " - " + (Math.round(totalCoins * 10000) / 10000) + " Total");
			}
		}
	}, 10000);*/

	var currentUrl = window.location.href.match("[^\/]+$");    
	if(currentUrl) {
		//alternative Url, wenn Aufruf via non-SEO

		var nonSEOUrl = getParameterByName("page", currentUrl[0]);		
		if(nonSEOUrl != null) {
			//Non SEO Extension
			currentUrl[0] = nonSEOUrl;			
		}

		$(".naviBar ul li").removeClass("ui-active");

		if($(".naviBar ul li a[href*=\"" + currentUrl[0] + "\"]").length) {
			$(".naviBar ul li a[href*=\"" + currentUrl[0] + "\"]").parent("li").addClass("ui-active");
		}else {
			$(".naviBar ul li").first().addClass("ui-active");
		}
	}else {
		$(".naviBar ul li").first().addClass("ui-active");
	}

    $("#featureGallery").lightGallery();

	$("#registerDialog").dialog({
    	autoOpen: false,
    	resizable: false,
    	draggable: false,
    	modal: true,
      	show: {
        	effect: "fade",
        	duration: 1000
      	},
      	hide: {
        	effect: "fade",
        	duration: 1000
     	}
    });
    $("#openRegisterDialog").on("click", function() {
      	$("#registerDialog").dialog("open");
      	return false;
    });

    $("#forgotPWDialog").dialog({
    	autoOpen: false,
    	resizable: false,
    	draggable: false,
    	modal: true,
      	show: {
        	effect: "fade",
        	duration: 1000
      	},
      	hide: {
        	effect: "fade",
        	duration: 1000
     	}
    });
    $("#openForgotPWDialog").on("click", function() {
      	$("#forgotPWDialog").dialog("open");
      	return false;
    });

    $("#uploadProfilePhoto").dialog({
    	autoOpen: false,
    	resizable: false,
    	draggable: false,
    	modal: true,
    	width: 350,
      	show: {
        	effect: "fade",
        	duration: 1000
      	},
      	hide: {
        	effect: "fade",
        	duration: 1000
     	}
    });
    $("#openUploadProfilePhoto").on("click", function() {
      	$("#uploadProfilePhoto").dialog("open");
      	return false;
    });

    $("#profileUpload").change(function() {
    	if (this.files && this.files[0]) {
        	var reader = new FileReader();
        	reader.onload = function (e) {
        		$('#previewProfileImage').css({
        			"display" : "block",
        			"width" : "130px",
        			"height" : "130px",
        			"margin" : "auto"
        		});
            	$('#previewProfileImage').attr('src', e.target.result);
            	$('#previewBox').show();
        	}
        	reader.readAsDataURL(this.files[0]);
    	}else {
    		$('#previewBox').hide();
    	}
    });

    $("#uploadProfileForm").submit(function(event) {
		event.stopPropagation(); 
		event.preventDefault();			    	

		var files = $("#profileUpload").prop('files');
		if(files && files.length > 0) {
			var fileData = new FormData();
		    fileData.append(0, files[0]);
			//Ajax
			$.ajax({
				type: "POST",
				url: "./content/Ajax/UploadImages.php",
		        data: fileData,
		        cache: false,
		        dataType: 'json',
		        processData: false,
		        contentType: false,
			}).done(function( data ) {						
				if(data != null) {
					if(data.Message != null) {
						var fehler = false;
						$.each(data.Message, function(key, value)
						{
							prependMessageToBox($("#uploadProfilePhoto"), value.Msg, value.Typ, 2000);
							if(value.Typ == "msg-error") {
								fehler = true;
							}	
						});
						if(!fehler) {
							//Kein Fehler
							setTimeout(function(){ 
								$("#uploadProfileForm")[0].reset();
								$("#uploadProfilePhoto").dialog("close");
							}, 2000);
	            		}
					}
				}		
			}).error(function( data , error , htmlError) {
				console.log( data , error , htmlError);
			});
		}
	});

    $("#registerForm").submit(function(event) {
    	event.preventDefault();
		//Ajax
		$.ajax({
			type: "POST",
			url: "./content/Ajax/AddOfficer.php",
			contentType: "application/x-www-form-urlencoded; charset=UTF-8",
			dataType : "json",
			data: { FormData : $(this).serialize() }
		}).done(function( data ) {
			if(data != null) {
				if(data.Message != null) {
					var fehler = false;
					$.each(data.Message, function(key, value)
					{
						prependMessageToBox($("#registerDialog"), value.Msg, value.Typ, 2000);
						if(value.Typ == "msg-error") {
							fehler = true;
						}	
					});
					if(!fehler) {
						//Kein Fehler
						setTimeout(function(){ 
							$("#registerForm")[0].reset();
							$("#registerDialog").dialog("close");
						}, 2000);
            		}
				}
			}			
		}).error(function( data , error , htmlError) {
			console.log( data , error , htmlError);
			var errorDaten = {
				"Data" : data,
				"Error" : error,
				"HtmlError" : htmlError,
				"Url" : this.url
			};
		});

    	return false;
    });

    $("#loginForm").submit(function(event) {
    	event.preventDefault();
		//Ajax
		$.ajax({
			type: "POST",
			url: "./content/Ajax/LoginOfficer.php",
			contentType: "application/x-www-form-urlencoded; charset=UTF-8",
			dataType : "json",
			data: { FormData : $(this).serialize() }
		}).done(function( data ) {
			if(data != null) {
				if(data.Message != null) {
					var fehler = false;
					$.each(data.Message, function(key, value)
					{
						prependMessageToBox($("#loginDialog"), value.Msg, value.Typ, 5000);
						if(value.Typ == "msg-error") {
							fehler = true;
						}	
					});
					if(!fehler) {
						//Kein Fehler
						setTimeout(function(){ 
							window.location.reload();
						}, 2000);
            		}
				}
			}			
		}).error(function( data , error , htmlError) {
			console.log(data , error , htmlError);
			var errorDaten = {
				"Data" : data,
				"Error" : error,
				"HtmlError" : htmlError,
				"Url" : this.url
			};
		});

    	return false;
    });

    $("#forgotPWForm").submit(function(event) {
    	event.preventDefault();
		//Ajax
		$.ajax({
			type: "POST",
			url: "./content/Ajax/ResetPassword.php",
			contentType: "application/x-www-form-urlencoded; charset=UTF-8",
			dataType : "json",
			data: { FormData : $(this).serialize() }
		}).done(function( data ) {
			if(data != null) {
				if(data.Message != null) {
					var fehler = false;
					$.each(data.Message, function(key, value)
					{
						prependMessageToBox($("#forgotPWDialog"), value.Msg, value.Typ, 5000);
						if(value.Typ == "msg-error") {
							fehler = true;
						}	
					});
					if(!fehler) {
						//Kein Fehler
						setTimeout(function(){ 
							window.location.reload();
						}, 2000);
            		}
				}
			}			
		}).error(function( data , error , htmlError) {
			console.log(data , error , htmlError);
			var errorDaten = {
				"Data" : data,
				"Error" : error,
				"HtmlError" : htmlError,
				"Url" : this.url
			};
		});

    	return false;
    });

    LiveTickerManger(true);

    setInterval(function(){
    	LiveTickerManger(false);
    }, 10000);

    Tipped.create('#ondutyBox', 'Shows how many officers are On Duty');
    Tipped.create('#officerBox', 'Shows how many officers are Registered in PolifeLifeS-Network');
    Tipped.create('#arrestedBox', 'Total number of all arrests');
    Tipped.create('#calloutsBox', 'Total number of all accept Callouts');
    Tipped.create('#secureSSL', 'SSL (4096Bit High Security) Connection between you and the Website!');


    /* Profil */
    $('#rang').circliful({
    	foregroundColor : "orange",
    	foregroundBorderWidth: 5,
    	backgroundBorderWidth: 5,
	  	animation: 1,
    	animationStep: 6,
    	percent: $('#rang').attr('data-percent'),
    	replacePercentageByText: $('#rang').attr('data-clevel'),
    	text: "EXP: " + $('#rang').attr('data-totalexp'),
    	textStyle: "font-size: 9px; transform: translateY(15px); -webkit-transform: translateY(15px); -moz-transform: translateY(15px); -mz-transform: translateY(15px); -oz-transform: translateY(15px);"
   	});

   	$("td[data-created]").text(toDateTimeZone($("td[data-created]").attr("data-created")));
   	$("span[data-lasttick]").text(toDateTimeZone($("span[data-lasttick]").attr("data-lasttick")));

   	$("span[data-visitortime]").each(function() {
	  	$(this).text(toDateTimeZone($(this).attr("data-visitortime")));
	});   	
    
});

function LiveTickerManger(firstLoad) {
	$.ajax({
		type: "POST",
		url: "./content/Ajax/GetLiveTicker.php",
		contentType: "application/x-www-form-urlencoded; charset=UTF-8",
		dataType : "json",
	}).done(function( data ) {
		if(data != null) {
			if(data.DataArray != null) {
				$(".policeRadio_item").attr('data-valid', false);				
				$(data.DataArray).each(function(index, value) {
					if($(".policeRadio_item[data-id="+value["ID"]+"]").length) {
						//Existiert bereits
						$(".policeRadio_item[data-id="+value["ID"]+"]").attr('data-valid', true);
					}else {		
						if(!firstLoad) {
							var item = $("<div />").attr({
								"data-id" : value["ID"],
								"data-valid" : true
							}).addClass("policeRadio_item")
							.html("<font color=\""+value["Color"]+"\">" + value["userName"]+" "+toTimeZone(value["Time"])+"</font>: " + value["Message"])
							.prependTo($("#liveticker"));
							item.hide().delay(400 * index).fadeIn(3000);
						}else {
							var item = $("<div />").attr({
								"data-id" : value["ID"],
								"data-valid" : true
							}).addClass("policeRadio_item")
							.html("<a href=\"officer/"+ value["userNameUrl"] +"\"><font color=\""+value["Color"]+"\">" + value["userName"] +" "+toTimeZone(value["Time"])+"</font></a>: " + value["Message"])
							.prependTo($("#liveticker"));
						}
					}		
				});
				$(".policeRadio_item[data-valid=false]").fadeOut(400, function() { $(this).remove(); });
			}

			$("#onDutyCount").text(data.onDutyCount);
			$("#officerCount").text(data.officerCount);
			$("#arrestedCount").text(data.arrestedCount);
			$("#calloutCount").text(data.calloutCount);

		}
	}).error(function( data , error , htmlError) {
		console.log(data , error , htmlError);
		var errorDaten = {
			"Data" : data,
			"Error" : error,
			"HtmlError" : htmlError,
			"Url" : this.url
		};
	});
}

function toTimeZone(time) {
	var timezone = jstz.determine();
    var date = moment(time * 1000);
    return date.tz(timezone.name()).format('HH:mm');
}

function toDateTimeZone(time) {
	var timezone = jstz.determine();
    var date = moment(time * 1000);
    return date.tz(timezone.name()).format('MM/DD/YYYY HH:mm');
}

/* Message Handler */		
var messageBoxTimer = null;
function prependMessageToBox(element, message, classes, unlimited){
	if(message != undefined && element != undefined && classes != undefined){

		if(element.children('#messageBox').length > 0) {
			var messageBox = element.children('#messageBox');			
		}else {
			var messageBox = $("<div />").attr("id", "messageBox").prependTo(element);			
		}
		
		$("<p />").attr({class : classes}).html(message).prependTo(messageBox);
		
		if(messageBoxTimer != null){
			window.clearTimeout(messageBoxTimer);
		}
		if(unlimited == undefined || isNumber(unlimited)) {
			messageBoxTimer = window.setTimeout(function(){
				messageBox.empty();
			}, (isNumber(unlimited) ? unlimited : 5000));
		}
	}
}
function isNumber(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}