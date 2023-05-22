<?php
    $api_url = 'http://www.sitetest.local/illustrator2_project/back/index.php/symbols/';
    $json_data = file_get_contents($api_url);
	// echo $json_data;
    $response_data = json_decode($json_data, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<? foreach ($response_data as $symbol){ ?>
		<div>
			<?= $symbol['name_file'] ?>
		</div>
	<? } ?>






</body>
</html>