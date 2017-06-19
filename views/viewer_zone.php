<?php
	$src="./viewer_zone_work.php";
	$distance=20000;
	$angle=61;
	if(!empty($_POST)){
		$distance=urlencode($_POST['distance']);
		$angle=urlencode($_POST['angle']);
		if($distance!=intval($distance)){ die('bad parameter');}
		if($angle!=intval($angle)){ die('bad parameter');}
		$src.="?distance=".$distance."&angle=".$angle;
	}
?><html><head></head>
<body style=''>
	<div style=''>
				<form method='POST' id='form'>
				Angle: <input type='text' name='angle' value='<?php echo  $angle;?>' size="5"/>
				Distance:  <input type='text' name='distance' value='<?php echo $distance;?>'  size="8" />
				Altitude:  <input type='text' name='altitude' value='0'  size="5"/>
				<input type='submit' name='ok' value='Go'>
				</form>
			</div>
			<img src='<?php echo $src;?>'/>

	</body></html>
