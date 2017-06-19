<?php
	$id=1;
	if(!empty($_POST)){
		$id=urlencode($_POST['id']);	
		if($id!=intval($id)){ die('bad parameter');}		
	}
?>
<html>
<head>
	
</head>
<body style="background-color:black;color:white;">
	<form method='POST' id='form'>
		Id systeme: <input type='text' name='id' value='<?php echo $id;?>' size="5"/>
		<input type='submit' name='ok' value='Go'>
	</form>
	<img id="screen" src='./viewer_system_work.php?id=<?php echo $id; ?>' width="1200px" height="1200px"/>

</body>
</html>