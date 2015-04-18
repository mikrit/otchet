<?php defined('EXT') OR die('No direct script access.');?>

<div id="menu_d">
	<div id="t-center_m">
		<ul>
			<?foreach($clients as $client => $client_txt){?>
				<li <?if($client_current == $client_txt){echo 'id="current"';}?>>
					<a href="/?client=<?=$client?>"><?=$client_txt?></a>
				</li>
			<?}?>
		</ul>
	</div>
</div>