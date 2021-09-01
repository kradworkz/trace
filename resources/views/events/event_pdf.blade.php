<!DOCTYPE html>
<html><head>
		<meta charset="utf-8">
		<title>Schedule of Activities</title>
		<link rel="stylesheet" href="{{ asset('css/event_schedule.css') }}" type="text/css">
	</head><body>
		<table class="uppertable" width="100%">
			<thead>
				<tr>
					<th width="40%" style="text-align: right;"><img src="{{ asset('images/logos/dost_logo.png') }}" style="display:inline;" width="150" height="150"></th>
					<th style="text-align: left;">					
						<label class="calabarzon">DOST CALABARZON</label><br>
						<label class="dt">Activities for {{ date('F Y', strtotime($date)) }}<label><br>
					</th>
				</tr>
			</thead>
		</table>

		<br>
		<table class="maintable" width="100%">
			<thead>
				<tr>
					<th style="border: 1px solid #0b0291;">ACTIVITY</th>
			    	<th style="border: 1px solid #0b0291;">DATE</th>
			    	<th style="border: 1px solid #0b0291;">VENUE</th>
			    	<th style="border: 1px solid #0b0291;">STAFF INVOLVED</th>
				</tr>
			</thead>
		</table>
		<table class="maintable" width="100%">
			<tbody>
				@foreach($events as $event)
				<tr>
					<td>{{ $event->e_name }}</td>
					<td>
						<?php
							$start 	= App\Models\Event::where('e_id', $event->e_id)->value('e_start_date'); 
							$end 	= App\Models\Event::where('e_id', $event->e_id)->value('e_end_date');
							if ( $start == $end) {
								$date 	= strtotime($start);
								$day 	= date('d', $date);
								echo $day;
							} else {
								$s 		= strtotime($start);
								$sdate 	= date('d', $s);

								$e 		= strtotime($end);
								$edate 	= date('d', $e);
								echo $sdate.'-'.$edate;
							}
						?>
					</td>
					<td>{{ $event->e_venue }}</td>
					<td>
					<?php
						$output =str_replace(',', '<br />', $event->e_staff);
						echo $output;
					?>					
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</body></html>