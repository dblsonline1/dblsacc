<?php
if(!defined('INITIALIZED'))
	exit;

		$tablica = array (
		'Killer'=>
			 array('Obrazek'=>'500frags','Opis'=>'Get 500 frags!','global_storage'=>46185),
		'Bug Finder'=>
			 array('Obrazek'=>'report1','Opis'=>'Find and report at least 1 bug!','global_storage'=>46186),
		'Transformer'=>
			 array('Obrazek'=>'hyper_transform','Opis'=>'Get a hyper transformation!','global_storage'=>46187),
		'Beginner'=>
			 array('Obrazek'=>'firstlogin','Opis'=>'Login on server for the 1st time!','global_storage'=>46188),
		'Demon Hunter'=>
			 array('Obrazek'=>'666demons','Opis'=>'Kill 6.666 Red / Blue Demons!','global_storage'=>46189),
		'Event Member'=>
			 array('Obrazek'=>'event','Opis'=>'Take a part in one of events!','global_storage'=>46190),
		'Powergamer'=>
			 array('Obrazek'=>'staminadoll','Opis'=>'Use stamina doll when 0h stamina!','global_storage'=>46191),
		'Easy Arena'=>
			 array('Obrazek'=>'arena_easy','Opis'=>'Complete arena on easy difficulty!','global_storage'=>46192),
		'Medium Arena'=>
			 array('Obrazek'=>'arena_medium','Opis'=>'Complete arena on medium difficulty!','global_storage'=>46193),
		'Hard Arena'=>
			 array('Obrazek'=>'arena_hard','Opis'=>'Complete arena on hard difficulty!','global_storage'=>46194),
		'Rich boy'=>
			 array('Obrazek'=>'222points','Opis'=>'Spend 222 premium points in Item Shop.','global_storage'=>46195),
		'Donator'=>
			 array('Obrazek'=>'sms','Opis'=>'Send at least 1 sms.','global_storage'=>46196),
		'Blessinger'=>
			 array('Obrazek'=>'bless','Opis'=>'Buy blesses at least 30 times.','global_storage'=>46197),

		);
$rozszerzenie_obrazka = ".png";
$main_content .= '
<div class="title"><center>Achievements</center></div></br>
	<table class="table table-bordered">';
	foreach ( $tablica as $nazwa => $zmienna)
	{		
		$zdobyto_ilosc =  $SQL->query('SELECT COUNT(*) FROM `player_storage` WHERE `key`='.$zmienna['global_storage'].' AND `value`=1;')->fetch();
		$main_content .= '
		<tr>
		<td rowspan="2"><center><img src="./images/achivements/'.$zmienna['Obrazek'].$rozszerzenie_obrazka.'"></center></td>
		<td><center>'.htmlspecialchars($nazwa).' (zdobyto '.htmlspecialchars($zdobyto_ilosc[0]).')</center></td>
		</tr>
		
		<tr>
		<td height="80">'.htmlspecialchars($zmienna['Opis']).'</td>
		</tr>';
	}
$main_content .= '</table>';
