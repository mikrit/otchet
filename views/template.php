<?php defined('EXT') OR die('No direct script access.');?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

	<link rel="stylesheet" href="media/css/style.css" type="text/css">

	<title>Отчёт</title>
</head>

<body>
<table id="t_page">
	<tr id="h">
		<td>
			<div id="header">
				Отчёт по клиенту: <?=$client?>
			</div>
		</td>
	</tr>
	<tr id="menu">
		<td>
			<?=$menu?>
		</td>
	</tr>
	<tr>
		<td id="p">
			<div id="page">
				<?=$content?>
			</div>
		</td>
	</tr>
	<tr>
		<td id="f">
			<div id="footer">
				<p>Egor Isaev &copy;<?=date("Y") == '2015' ? '2015' : '2015-'.date("Y")?> All Rights Reserved.</p>
			</div>
		</td>
	</tr>
</table>
</body>
</html>