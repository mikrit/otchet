<?php defined('EXT') OR die('No direct script access.');?>

<table id="table_t">

	<tr id="head_t">
		<td colspan="2">
			&nbsp;
		</td>
		<?foreach($data as $key => $val){?>
			<td colspan="2">
				<?=$key?>
			</td>
		<?}?>
	</tr>
	<tr>
		<td colspan="2">

		</td>
		<?foreach($data as $key => $val){?>
			<td>
				Команда
			</td>
			<td>
				Мои
			</td>
		<?}?>
	</tr>
	<tr>
		<td colspan="2">
			Всего
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['all']?>
			</td>
			<td>
				&nbsp;
			</td>
		<?}?>
	</tr>
	<tr>
		<td colspan="2">
			Всего в работе
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['all_in_work']?>
			</td>
			<td>
				&nbsp;
			</td>
		<?}?>
	</tr>
</table>