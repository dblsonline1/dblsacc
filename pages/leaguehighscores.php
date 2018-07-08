<?php
if(!defined('INITIALIZED'))
	exit;


$main_content .= '
<div class="title"><center>#1 League </center></div>
<script language="JavaScript">
TargetDate = "7/31/2015 11:59 PM";
CountActive = true;
CountStepper = -1;
LeadingZero = false;
DisplayFormat = "<font size=\'2\' color=\'grey\'><h1><center><SORRY, LAST TIME> DBNS #1 League starts in: <br/>%%D%% Days, %%H%% Hours, %%M%% minutes and %%S%% seconds!<br/></center></h1></font>";
FinishMessage = "";
</script>
<script language="JavaScript" src="http://scripts.hashemian.com/js/countdown.js"></script>

	<table class="table table-bordered">
	
		<tr>
		<td colspan="2"><center><b>Rewards</b></center></td>
		</tr>
		
		<tr>
		<td style="width:50%"><center>Place</center></td>
		<td style="width:50%"><center>Reward</center></td>
		</tr>
		
		<tr>
		<td height="60" style="width:50%"><center>#1</center></td>
		<td height="60" style="width:50%"><center>Tablet Lenovo TAB S8</center></td>
		</tr>
		
		<tr>
		<td height="60" style="width:50%"><center>#2</center></td>
		<td height="60" style="width:50%"><center>Paysafecard  500 PLN</center></td>
		</tr>
		
		<tr>
		<td height="60" style="width:50%"><center>#3</center></td>
		<td height="60" style="width:50%"><center>Paysafecard 250 PLN</center></td>
		</tr>
		
		</table>
		
';

	$update_interval = 3600;
			$tmp_file_name = 'cache/leaguehighscores.tmp';
			if(file_exists($tmp_file_name) && (filemtime($tmp_file_name) > (time() - $update_interval)))
			{
				$tmp_file_content = file_get_contents($tmp_file_name);
				$echo_scores = $tmp_file_content;
			}
			else
			{
			$order = 1;
			$leaguepoints = $SQL->query('SELECT name,league_points FROM players WHERE players.deleted = 0 AND players.group_id NOT IN ('.implode(',', $config['site']['groups_hidden']).')  AND name != "Account Manager" AND name NOT LIKE "%sample" ORDER BY league_points DESC LIMIT 10;');
				foreach($leaguepoints as $lp) {
					$echo_scores .= '<tr>
						<td><center>'. $order++ . '.</center></td>
						<td><center><a href="?subtopic=characters&name=' . urlencode($lp['name']) . '">'.$lp['name'].'</a></center></td>
						<td><center>' . $lp['league_points'] . '</center></td>
					</tr>';
				}	
			 #echo "$players_skill";
			 file_put_contents($tmp_file_name, $echo_scores);
			}
			$timeleft = (($update_interval-(time()-filemtime($tmp_file_name)))/60);						
$main_content .= '		
		<h2 class="text-center">Ranking for #1 LEAGUE on DBNS.EU <br> League END<br>1. Scooby Akka bann<br>2. True Magic Shady<br> 3. Sun Best trol</h2>
		<table class="table table-bordered">
		<tr>
			<th style="width: 18px">#</th>
			<th style="width: 65%">Name</th>
			<th>Points</th>
		</tr>
		

		'.$echo_scores.'
		';
			 
$main_content .= '</table>';
$main_content .= '
				<textarea style="resize:none" rows="19" disabled>
Zdobywanie punktów. 
Punktowane będą następujące rzeczy:
Fragi- każdego dnia za zabicie 1 gracza będzie otrzymywało się 1 punkt, maksymalnie 10 na dzień.
Turnieje pvp- turnieje będą odbywać się dwa razy w tygodniu, wziąć w nich udział będą mogli wszyscy gracze (dokładny opis działania turniejów pojawi się w osobnym temacie), punkty będą rozdzielane następująco: 1. miejsce = 10 punktów, 2. miejsce = 7 punktów, 3. miejsce = 5 punktów, oraz po 2 punkty dla każdego gracza, który wziął w turnieju udział, lecz nie zajął miejsca na podium.
Daily taski- 2 punkty dziennie za wykonanie daily taska.
Brotherhood quest- 1 punkt za każdy wykonany kontrakt, maksymalnie 5 dziennie (opis brotherhood questa będzie można znaleźć w osobnym temacie)
Top ranking (dotyczy tylko i wyłącznie skilli Strenght, Sword, Ki Blasting, Defense)- gracze, którzy utrzymają się w najlepszej dziesiątce w/w skilla otrzymają pod koniec ligi pewną sumę punktów (do ustalenia pod koniec ligi).
		
		
How to get points?
You can get points by doing following actions:
Frags- everyday for each frag you will get 1 point, max 10 per day.
PVP tournaments- tournaments will be organised 2 times in the week, everyone can take part in them (specific despriction of how tournaments work will be posted in another thread), you can get: 10 points for 1st place, 7 for 2nd place and 5 for 3rd place, also all the players that took part in tournaments and didn\'t manage to be top 3 will receive 2 points.
Daily tasks- 2 points for each task done.
Brotherhood quest- 1 point for each contract done, max 5 per day (descritpion of brotherhood quest will also be posted in another thread).
Top ranking (only Strenght, Sword, Ki Blasting and Defense)- players who will manage to be in the top 10 at the end of the league will get some points.	
				</textarea>
';

