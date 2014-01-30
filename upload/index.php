<?php
/*
	This file is part of FoGall by Daniel Skowroński, 2014.

    FoGall is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    FoGall is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	require_once("../config.php");
?>

<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<title><?php echo($sitename); ?> &bull; <?php echo($title); ?></title>
		<link rel="shortcut icon" href="<?php echo $site_url; ?>/bin/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/bin/style.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/bin/font/SegoeUI.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $site_url; ?>/bin/lightbox/lightbox.css" />
		<script type="text/javascript" src="js/plupload.full.min.js"></script>
		<script src="<?php echo $site_url; ?>/bin/lightbox/jquery-1.7.2.min.js"></script>
		<script src="<?php echo $site_url; ?>/bin/lightbox/lightbox.js"></script>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<img height="79" width="125" src="<?php echo $site_url; ?>/bin/upload.png" style="float:left; margin-right: 5px;" />
				<h2><?php echo($sitename); ?></h2>
				<h1>UPLOADER <?php echo($title); ?></h1>
			</div>
			<div id="content">
		<div id="filelist"></div>
		<div id="container2" style="font-weight: 600;">
    		<a id="pickfiles" href="javascript:;">[Select files]</a> 
    		<a id="uploadfiles" href="javascript:;">[Upload]</a>
		</div>

		<br />
		<div id="targetlink"></div>
		<pre id="console"></pre>

		<script type="text/javascript">
		var TARGET = prompt("Enter target dir name, or files will be stored in YYYY-MM-DD/","");
		if (TARGET == null) TARGET="";

		var uploader = new plupload.Uploader({
			runtimes : 'html5,flash,silverlight,html4',
			browse_button : 'pickfiles', // you can pass in id...
			container: document.getElementById('container'), // ... or DOM Element itself
			url : 'upload.php?target='+TARGET,
			flash_swf_url : 'js/Moxie.swf',
			silverlight_xap_url : 'js/Moxie.xap',
			
			filters : {
				max_file_size : '10mb',
				mime_types: [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Zip files", extensions : "zip"}
				]
			},
		
			init: {
				PostInit: function() {
					var today = new Date(); var dd = today.getDate(); var mm = today.getMonth()+1; var yyyy = today.getFullYear(); if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} 
					
					var TRGT = ""; 
					if (TARGET=="") TRGT=yyyy+"-"+mm+"-"+dd; 
					else TRGT=TARGET;
		
					document.getElementById('targetlink').innerHTML = 'Target link: <a href="<?php echo $site_url; ?>/'+TRGT+'"><?php echo $site_url; ?>/'+TRGT+'</a>';
					document.getElementById('uploadfiles').onclick = function() {
						uploader.start();
						return false;
					};
				},
		
				FilesAdded: function(up, files) {
					plupload.each(files, function(file) {
						document.getElementById('filelist').innerHTML += '<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
					});
				},
		
				UploadProgress: function(up, file) {
					document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
				},
		
				Error: function(up, err) {
					document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
				}
			}
		});
		
		uploader.init();
		
		</script>
	</div>
</body>
</html>
