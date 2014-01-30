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

	require_once("config.php");
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
		<script src="<?php echo $site_url; ?>/bin/lightbox/jquery-1.7.2.min.js"></script>
		<script src="<?php echo $site_url; ?>/bin/lightbox/lightbox.js"></script>
	</head>
	<body>
		<div id="container">
			<div id="header">
				<img width="105" height="79" src="<?php echo $site_url; ?>/bin/camera.png" style="float:left; margin-right: 5px;" />
				<h2><?php echo($sitename); ?></h2>
				<h1><?php echo($title); ?></h1>
			</div>
			<?php
				$filetypes  = array(".png", ".PNG", ".jpg", ".JPG", ".jpeg", ".JPEG", ".gif", ".GIF");
				$basedir    = './galleries';
				$currentdir = '';
				if(isset($_GET['f']) ? $_GET['f'] : '')
					{
					$currentdir = '/'.$_GET['f'].'/';
					}

				function scandirSorted($path)
					{
					$sortedData  = array();
					$data1       = array();
					$data2       = array();
					foreach(scandir($path) as $file)
						{
						if(!strstr($path, '..'))
							{
							if(is_file($path.$file))
								{
								array_push($data2, $file);
								}
							else
								{
								array_push($data1, $file);
								}
							}
						}
					$sortedData = array_merge($data1, $data2);
					return $sortedData;
					}

				function strpos_arr($haystack, $needle)
					{
					if(!is_array($needle))
						{
						$needle = array($needle);
						}
					foreach($needle as $what)
						{
						if(($pos = strpos($haystack, $what)) !== false)
							{
							return $pos;
							}
						}
					return false;
					}

				function addThumb($filename)
					{
					$filename    = array_reverse(explode('.', $filename));
					$filename[0] = 'smpgthumb.'.$filename[0];
					$filename    = implode('.', array_reverse($filename));
					return $filename;
					}

				if(is_dir($basedir.$currentdir))
					{
					$folder = array_diff(scandirSorted($basedir.$currentdir), array('..', '.', 'Thumbs.db', 'thumbs.db', '.DS_Store'));
					}

				$navigation = explode('/', $currentdir);
				$navigation_elements = count($navigation);
				if(isset($_GET['f']))
					{
					echo('<div id="navigation"><a href="'.$site_url.'">Home</a>');
					}
				foreach($navigation as $element)
					{
					if($element)
						{
						echo(' / <a href="'.$site_url.str_replace('//', '/', str_replace(' ', '%20', substr($currentdir, 0, strpos($currentdir, $element)+strlen($element)))).'">'.$element.'</a>');
						}
					}
				if(isset($_GET['f']))
					{
					echo('</div>');
					}
				function sub_in_array($needle, $haystack){
					foreach($haystack as $grain){
						if ( strpos($needle, "/".$grain."//") !== false )
							return true;
					}
					return false;
				}
				echo('<div id="content">');
				//echo "<h1>".$currentdir."</h1>";
				if (sub_in_array($currentdir, $zakazane) && ($currentdir != "")){
					echo "<a href='#' style='color: red;5'>Ten katalog jest zabezpieczony przed listowaniem</a>";
				}
				else
					foreach($folder as $item)
					{
						if ( in_array($item, $zakazane) )
							continue;
						if(!strstr(isset($_GET['f']), '..'))
						{
							if(!strstr($item, 'smpgthumb'))
							{
								if(strpos_arr($item, $filetypes))
								{
									if(file_exists($basedir.$currentdir.'/'.addThumb($item)))
									{
										echo('<a href="'.$site_url.str_replace('//', '/', str_replace(' ', '%20', substr($basedir,1).$currentdir.'/'.$item)).'" rel="friend"><img src="'.str_replace('//', '/', str_replace(' ', '%20', $basedir.$currentdir.'/'.addThumb($item))).'" class="img" alt="" /></a> ');
									}
									else
									{
										echo('<a href="'.$site_url.str_replace('//', '/', str_replace(' ', '%20', substr($basedir,1).$currentdir.'/'.$item)).'" rel="friend"><img src="bin/thumb.php?file='.str_replace('//', '/', str_replace(' ', '%20', $basedir.$currentdir.'/'.$item)).'" class="img" alt="" /></a> ');
										}
									}
								else
								{
								echo('<a href="'.$site_url.''.str_replace('//', '/', str_replace(' ', '%20', $currentdir.'/'.$item)).'">'.$item.'</a><br>');
									}
							}
						}
					}
				echo('</div>');
			?>
		</div>
		<!--<span id="copy" title="Copyright &copy; 2012 WindowsWiki &bull; <?php echo($version); ?>">&copy;</span>-->
	</body>
</html>