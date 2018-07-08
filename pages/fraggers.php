<?php
		$update_interval = 12000;
		$tmp_file_name = 'cache/topfraggers.tmp';



$main_content .= '
		<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>DBNS - TOP 30 FRAGGERS</p>
					
				</div>
			</div>';


$main_content .= '
			<div class="col-md-11 your_characters">
				<div class="row">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Frags</th>
							</tr>
						</thead>
						<tbody>
						';

	if(file_exists($tmp_file_name) && (filemtime($tmp_file_name) > (time() - $update_interval)))
		{
			$tmp_file_content = file_get_contents($tmp_file_name);
			$main_content = $tmp_file_content;
		}
		else {
$i = 0;
foreach($SQL->query('SELECT `p`.`name` AS `name`, COUNT(`p`.`name`) as `frags`
	FROM `killers` k
	LEFT JOIN `player_killers` pk ON `k`.`id` = `pk`.`kill_id`
	LEFT JOIN `players` p ON `pk`.`player_id` = `p`.`id`
WHERE `k`.`unjustified` = 1 AND `k`.`final_hit` = 1
	GROUP BY `name`
	ORDER BY `frags` DESC, `name` ASC
	LIMIT 0,30;') as $player)
{
	$i++;
	$main_content .= '<tr>
		<td><a href="?subtopic=characters&name=' . urlencode($player['name']) . '"><b>' . $player['name'] . '</b></a></td>
		<td style="text-align: center;">' . $player['frags'] . '</td>
	</tr>';
	file_put_contents($tmp_file_name, $main_content);
	
}

							$main_content .= '	
						</tbody>
					</table>
				</div>
			</div>';

}
		$timeleft = (($update_interval-(time()-filemtime($tmp_file_name)))/60);
		//if (($timeleft) > 0) { $main_content .= '<center>Ranking will be refreshed in: '.round($timeleft).' minutes.</center>'; }
		$main_content .= '</table>';
?>
