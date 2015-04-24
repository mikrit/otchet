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
	<tr class="task_1">
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
	<tr class="task_2">
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
	<tr class="task_1">
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
	<tr class="task_2">
		<td colspan="2">
			Доработок fix
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['dorobotok_fix']?>
			</td>
			<td>
				&nbsp;
			</td>
		<?}?>
	</tr>
	<tr class="task_1">
		<td colspan="2">
			Доработок not fix
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['dorobotok_not_fix']?>
			</td>
			<td>
				&nbsp;
			</td>
		<?}?>
	</tr>
	<tr class="task_2">
		<td colspan="2">
			Доработок без оценки
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['dorobotok_bez_ocenki']?>
			</td>
			<td>
				&nbsp;
			</td>
		<?}?>
	</tr>
	<tr class="task_1">
		<td colspan="2">
			Ошибок
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['errors']?>
			</td>
			<td>
				&nbsp;
			</td>
		<?}?>
	</tr>
	<tr class="task_2">
		<td colspan="2">
			Я-аналитик
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				&nbsp;
			</td>
			<td>
				<?=$val['my']?>
			</td>
		<?}?>
	</tr>
	<tr class="task_1">
		<td width="130px">
			Отладка релиза (Осталось из всего)
		</td>
		<td width="150px">
			В статусе, отличном от решена и закрыта/Всего в релизе
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				<?=$val['all_bez_rechena_y_zakrita']?>/<?=$val['all_in_reliz']?>
			</td>
			<td>
				<?=$val['all_bez_rechena_y_zakrita_my']?>/<?=$val['all_in_reliz_my']?>
			</td>
		<?}?>
	</tr>
	<tr class="task_2">
		<td>
			Просрочено без оценки(Из всего not fix в Анализе)
		</td>
		<td>
			not fix в статусе Анализ без оценки/Всего в анализе более 1 дня(Наивысший), более 2 дней(остальные)
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				&nbsp;
			</td>
			<td>
				<?=$val['not_fix_analiz_bez_ocenki_my']?>/<?=$val['all_in_analiz_more_day_naivishiy_my']?>
			</td>
		<?}?>
	</tr>
	<tr class="task_1">
		<td>
			Просрочено в Анализе notfix доработок(Из всего not fix в Анализе not fix)
		</td>
		<td>
			В статусе Анализ/Всего в анализе более 1 дня(Наивысший), более 2 дней(остальные)
		</td>
		<?foreach($data as $key => $val){?>
			<td>
				&nbsp;
			</td>
			<td>
				<?=$val['not_fix_analiz_bez_ocenki_my']?>/<?=$val['all_in_analiz_more_day_naivishiy_my']?>
			</td>
		<?}?>
	</tr>
</table>