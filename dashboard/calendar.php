
<!-- Kalenteri -->
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">
			<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Kalenteri
		</h3></div>
	<div class="panel-body">
		<?php 
		$calendarType = isset($calendarType) ? $calendarType : "";

		if ($calendarType === "agenda")
		{
			echo '<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;mode=AGENDA&amp;height=450&amp;wkst=1&amp;bgcolor=%23333333&amp;src=henri.rantanen92%40gmail.com&amp;color=%232F6309&amp;src=%23contacts%40group.v.calendar.google.com&amp;color=%2342104A&amp;src=en.finnish%23holiday%40group.v.calendar.google.com&amp;color=%238D6F47&amp;src=ah31vg7fjs0ruq81te17lpkc68%40group.calendar.google.com&amp;color=%23125A12&amp;src=8mf2f3useeo1fogesttr5amnt8%40group.calendar.google.com&amp;color=%232952A3&amp;src=hg76dtpp5jsk0kh7hmcdhls9sk%40group.calendar.google.com&amp;color=%23182C57&amp;src=fi.finnish%23holiday%40group.v.calendar.google.com&amp;color=%23875509&amp;ctz=Europe%2FHelsinki" style="border-width:0" width="100%" height="450" frameborder="0" scrolling="no"></iframe>';
		}
		else{
			echo '<iframe src="https://calendar.google.com/calendar/embed?showTitle=0&amp;showNav=0&amp;showDate=0&amp;showPrint=0&amp;showTabs=0&amp;showCalendars=0&amp;showTz=0&amp;height=450&amp;wkst=1&amp;bgcolor=%23333333&amp;src=henri.rantanen92%40gmail.com&amp;color=%232F6309&amp;src=%23contacts%40group.v.calendar.google.com&amp;color=%2342104A&amp;src=en.finnish%23holiday%40group.v.calendar.google.com&amp;color=%238D6F47&amp;src=ah31vg7fjs0ruq81te17lpkc68%40group.calendar.google.com&amp;color=%23125A12&amp;src=8mf2f3useeo1fogesttr5amnt8%40group.calendar.google.com&amp;color=%232952A3&amp;src=hg76dtpp5jsk0kh7hmcdhls9sk%40group.calendar.google.com&amp;color=%23182C57&amp;src=fi.finnish%23holiday%40group.v.calendar.google.com&amp;color=%23875509&amp;ctz=Europe%2FHelsinki" style="border-width:0" width="100%" height="450" frameborder="0" scrolling="no"></iframe>';
		}
	?>
	</div>
</div>