<?php if(!isset($_GET['txt'])) { ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="mp4.ico" />
	<link href="https://fonts.googleapis.com/css?family=Sigmar+One" rel="stylesheet">
	<?php 
		$directory_t = $_SERVER['DOCUMENT_ROOT'].'/mp4/share/';
		$filecount_title = 0;
		$files_t = glob($directory_t . "*");
		if ($files_t){
			$filecount_title = count($files_t);
		}
	?>
	<title><?php echo $filecount_title.' videos'; ?></title>
</head>
<?php if(!isset($_GET['list'])) { ?>
<style>
	body {
		background-color: #000;
	}
	#playlist {
		display: none;
	}
	
	<?php if(!isset($_GET['cover'])) { ?>
	
		video{
			max-width: 100%;
			height: auto;
			background-color: #000;

			position:absolute;
			left:0; right:0;
			top:0; bottom:0;
			margin:auto;

			max-width:100%;
			max-height:100%;
			overflow:auto;
		}
	
	<?php } else { ?>
	
		video{
			position: fixed;
			top: 50%;
			left: 50%;
			min-width: 100%;
			min-height: 100%;
			width: 100%;;
			height: 100%;;
			z-index: -100;
			transform: translateX(-50%) translateY(-50%);
			/*background: url('background.jpg') no-repeat;*/
			background-color: #000;
			background-size: cover;
			transition: 1s opacity;
		}
	
	<?php } ?>
	
	#share, a {
		position: fixed;
		top: 0px;
		left: 0px;
		padding-left: 10px;
		width:
		text-decoration: none;
		font-family: 'Sigmar One', cursive;
		font-size: 20px;
		z-index:300000;
		color: #fff;
		-webkit-text-fill-color: #fff;
		-webkit-text-stroke-width: 1px;
		-webkit-text-stroke-color: #000;
	}
</style>
<?php } ?>
<body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"> </script>

<?php
if(isset($_GET['list'])) {
	// directory we want to scan
	$dircontents = scandir('share');
	
	// list the contents
	echo '<ol>';
	foreach ($dircontents as $file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension == 'mp4') {
			echo '<li><a href=https://naatti.win/mp4/share/'.str_replace(" ", "%20", $file).' target="_blank">'.str_replace(" ", "%20", $file).'</a></li>';
		}
	}
	echo '</ol>';
	
} else {
	if(isset($_GET['play']) || isset($_GET['playlist'])) {
		
		$counter = 0;
		$files = scandir($_SERVER['DOCUMENT_ROOT'] . '/mp4/share');
		$files = array_diff($files, array('.', '..'));
		shuffle($files);
		foreach($files as $file){
			if($counter == 0) {
				echo '<div id="share"><a href="https://naatti.win/mp4/share/'.$file.'">'.$file.'</a></div><video id="video" onClick="playPause();" ><source id="source" src="https://naatti.win/mp4/share/'.$file.'"></video><ul id="playlist"><li class="active"><a href="https://naatti.win/mp4/share/'.$file.'"></a></li>';
			} else {
				echo'<li><a href="https://naatti.win/mp4/share/'.$file.'">'.$file.'</a></li>';
			}
			$counter++;
		}
		echo'</ul>';
	
	} else {
		echo '<script>window.location.replace("https://naatti.win/mp4/?playlist");</script>';
	}

?>
	<script>
			
		var myVideo=document.getElementById('video');
				
		function playPause()
		{ 
			if (myVideo.paused)
				myVideo.play(); 
			else 
				myVideo.pause(); 
		}
		
<?php if(isset($_GET['playlist'])) { ?>

				function previous() {
					var link = playlist.find('a')[current--];
					var player = $('#video');
					run(link, player);
				}
				
				function next() {
					var link = playlist.find('a')[current++];
					var player = $('#video');
					run(link, player);
				}

		$(document).ready(function() {
				init();

				function init() {
					
					var current = 0;
					var video = $('#video');
					var playlist = $('#playlist');
					var tracks = playlist.find('li a');
					var len = tracks.length - 1;
					var filecounter = "<?php echo $filecount_title.' videos'; ?>";
					video[0].volume = 1.00;
					video[0].play();
					playlist.on('click', 'a', function(e) {
						e.preventDefault();
						link = $(this);
						current = link.parent().index();
						run(link, video[0]);
					});
					video[0].addEventListener('ended', function(e) {
						current++;
						if (current == len) {
							current = 0;
							link = playlist.find('a')[0];
						} else {
							link = playlist.find('a')[current];
						}
						run($(link), video[0]);
					});
					document.onkeydown = function(e) {
						switch (e.keyCode) {
							case 37:
								// left
								if(current > 0){
									current--;
								}
								if (current == len) {
									current = 0;
									link = playlist.find('a')[0];
								} else {
									link = playlist.find('a')[current];
								}
								run($(link), video[0]);
								break;
							case 39:
								// right
								current++;
								if (current == len) {
									current = 0;
									link = playlist.find('a')[0];
								} else {
									link = playlist.find('a')[current];
								}
								run($(link), video[0]);
								break;
						}
					};
				}
				
				function run(link, player){
					document.getElementById('share').innerHTML = '<a href="' + link.attr('href') + '"><b>' + link.attr('href').replace(/^.*[\\\/]/, '') + '</b></a>';
					player.src = link.attr('href');
					par = link.parent();
					par.addClass('active').siblings().removeClass('active');
					player.load();
					player.play();
				}
			});
<?php } ?>

<?php if(isset($_GET['play'])) { ?> // if playing one video, then loop it

	var myVideo = document.getElementById('video');
	if (typeof myVideo.loop == 'boolean') { // loop supported
	  myVideo.loop = true;
	} else { // loop property not supported
	  myVideo.addEventListener('ended', function () {
		this.currentTime = 0;
		this.play();
	  }, false);
	}
	//...
	myVideo.play();
	
<?php } ?>
	</script>
<?php } ?>
</body>
</html>
<?php } else {
	header('Content-disposition: attachment; filename=list.txt');
	header('Content-type: text/plain');

	// directory we want to scan
	$dircontents = scandir('share');
	
	// list the contents
	foreach ($dircontents as $file) {
		$extension = pathinfo($file, PATHINFO_EXTENSION);
		if ($extension == 'mp4') {
			echo 'https://naatti.win/mp4/share/'.str_replace(" ", "%20", $file)."\n";
		}
	}
}?>
	