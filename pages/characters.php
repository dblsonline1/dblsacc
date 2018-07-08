
<?php
if(!defined('INITIALIZED'))
	exit;

$name = '';
if(isset($_REQUEST['name']))
	$name = (string) $_REQUEST['name'];

$main_content .= '		
	<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Players Info</p>
				</div>
			</div><br>';





if(!empty($name))
{
	$player = new Player();
	$player->find($name);
	if($player->isLoaded())
	{
		$number_of_rows = 0;
		$account = $player->getAccount();
		$main_content .= '
			<div class="col-md-11 your_characters">

				<div class="row">
					<table class="table table-striped">

							<tr>
								<td>Name</td>
								<td style="font-weight:bold;color:' . (($player->isOnline()) ? 'green' : 'red') . '">' . htmlspecialchars($player->getName()) . ' ' . $skull . ' <img src="' . $config['site']['flag_images_url'] . $account->getFlag() . $config['site']['flag_images_extension'] . '" title="Country: ' . $account->getFlag() . '" alt="' . $account->getFlag() . '" />';
								if($player->isBanned() || $account->isBanned())
									$main_content .= '<span style="color:red"> [BANNED]</span>';
								if($player->isNamelocked())
									$main_content .= '<span style="color:red">[NAMELOCKED]</span>';
								$main_content .= '<br /></td>
							</tr>
							<tr>
								<td>Sex</td>
								<td>'. htmlspecialchars((($player->getSex() == 0) ? 'female' : 'male')) . '</td>
							</tr>
							<tr>
								<td>Vocation</td>
								<td>' . htmlspecialchars(Website::getVocationName($player->getVocation(), $player->getPromotion())) . '</td>
							</tr>
							<tr>
								<td>Level</td>
								<td>' . htmlspecialchars($player->getLevel()) . '</td>
							</tr>
							<tr>
								<td>World</td>
								<td>'. htmlspecialchars($config['site']['worlds'][$player->getWorldID()]) .'</td>
							</tr>
							<tr>
							<td>Guild</td>';
							$rank_of_player = $player->getrank();
							if(!empty($rank_of_player))
							{
								$main_content .= '<td>'. htmlspecialchars($rank_of_player->getName()) . ' of the <a href="?subtopic=guilds&action=show&guild='. $rank_of_player->getGuild()->getID() .'">' . htmlspecialchars($rank_of_player->getGuild()->getName()) . '</a></td>';
							}
							else
							{
								$main_content .= '<td>No guild</td>';
							}

							$main_content .= '
							</tr>
							<tr>
								<td>Last Login</td>
								<td>'. (($player->getLastLogin() > 0) ? date("j F Y, g:i a", $player->getLastLogin()) : 'Never logged in.') . '</td>
							</tr>
							<tr>
								<td>Created</td>
								<td>'. date("j F Y, g:i a", $player->getCreateDate()) . '</td>
							</tr>
					</table>
				</div>
			</div><br>			
		';


		$main_content .= '
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


		<script>
		$(function(){
			$("#aczivmenty").hide();
		});
		

		</script>

		  <script type="text/javascript">
		  var hajd = true;
                function achievements_operator() {
				
					if(hajd)
					{
						$("#aczivmenty").show("slow");
						hajd = false;
					}
					else
					{

						$("#aczivmenty").hide("slow");
						hajd = true;
					}
                }
            </script>
		<div class="col-md-11 buttons">
				<input type="submit" class ="btn register-account" id="btn_achievements" value="Achievements" onclick="achievements_operator()"/> 
			</div>

		 
		 
		';

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
		$AllAchievmentsStorage = 45555;
		$rozszerzenie_obrazka = ".png";
		$AllAchievmentsReal = 0;
		
		if($player->getStorage($AllAchievmentsStorage) > 0)
		{
			$AllAchievmentsReal = $player->getStorage($AllAchievmentsStorage);
		}
		
		
		$main_content .= '
		<div class="col-md-11 your_characters">
				<div class="row">
					<table class="table table-striped" id="aczivmenty">
						<tr>
							<td colspan="4" style="text-align: center;"><strong>Achievements</strong>
							<br>
							'.$AllAchievmentsReal.'/'.count($tablica).'
							<br>
							<progress max="100" value="8"></progress><br><br>
							</td>
						</tr>
						<tr>

';

		
	$odliczanie_ach = 3;
	foreach ( $tablica as $nazwa => $zmienna)
	{								
		if($odliczanie_ach <= 0)
		{
			$odliczanie_ach = 3;
			$main_content .= '</tr><tr>';
			
		}
		
		$printTextResoult = "";
		if($player->getStorage($zmienna['global_storage']) == 1)
		{
			$printTextResoult = '<td><center><img class="hastip" title="'.$zmienna['Opis'].'" src="./images/achivements/'.$zmienna['Obrazek'].$rozszerzenie_obrazka.'"</center>';
		}
		else
		{
			$printTextResoult = '<td><center><img class="hastip" title="'.$zmienna['Opis'].'" style="opacity:0.1" src="./images/achivements/'.$zmienna['Obrazek'].$rozszerzenie_obrazka.'"</center>';
		}
		
		$main_content .= '						
			'.$printTextResoult.'
			<center>'.htmlspecialchars($nazwa).'</center>
			</td>
		
		';
		$odliczanie_ach--;
	}

		$main_content .= '	
		<script type="text/javascript">
		$(".hastip").tooltipsy();
		</script>
		</tr><br>
										</table>
				</div><br>
		</div>

			';
		
		if($config['site']['show_skills_info'])
		{
			$main_content .= '<div class="col-md-11 your_characters">
				<div class="row">
					<table class="table table-striped">
					<tr>
						<td>Level</td>
						<td>KiLevel</td>
						<td>AttackSpeed</td>
						<td>Strenght</td>
						<td>Sword</td>
						<td>Blasting</td>
						<td>Defense</td>
					</tr>
					<tr>
						<td>' . $player->getLevel() . '</td>
						<td>' . $player->getMagLevel().'</td>
						<td>' . $player->getSkill(0) . '</td>
						<td>' . $player->getSkill(1) . '</td>
						<td>' . $player->getSkill(2) . '</td>
						<td>' . $player->getSkill(4) . '</td>
						<td>' . $player->getSkill(5) . '</td>
					</tr>
			</table>
			</div>
			</div>
			';
		}

		if(isset($config['site']['quests']) && is_array($config['site']['quests']) && count($config['site']['quests']) > 0)
		{
			$main_content .= '<div class="col-md-11 your_characters">
				<div class="row">
					<table class="table table-striped">
					<td><B>Quests</B></td><td><b>Status</b></td>';		
			$number_of_quests = 0;
			foreach($config['site']['quests'] as $questName => $storageID)
			{
				$number_of_quests++;
				$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=95%>' . $questName . '</td>';
				if($player->getStorage($storageID) === null)
				{
					$main_content .= '<td><img src="images/false.png"/></td></tr>';
				}
				else
				{
					$main_content .= '<td><img src="images/true.png"/></td></tr>';
				}
			}
			$main_content .= '</table>
			</div>
			</div></br>';
		}

		$deads = 0;

		//deaths list
		$player_deaths = $SQL->query('SELECT ' . $SQL->fieldName('id') . ', ' . $SQL->fieldName('date') . ', ' . $SQL->fieldName('level') . ' FROM ' . $SQL->tableName('player_deaths') . ' WHERE ' . $SQL->fieldName('player_id') . ' = '.$player->getId().' ORDER BY ' . $SQL->fieldName('date') . ' DESC LIMIT 10');
		foreach($player_deaths as $death)
		{
			$deads++;
			$dead_add_content .= "<tr bgcolor=\"".$bgcolor."\"><td width=\"20%\" align=\"center\">".date("j M Y, H:i", $death['date'])."</td><td>";
			$killers = $SQL->query('SELECT ' . $SQL->tableName('environment_killers') . '.' . $SQL->fieldName('name') . ' AS monster_name, ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ' AS player_name, ' . $SQL->tableName('players') . '.' . $SQL->fieldName('deleted') . ' AS player_exists FROM ' . $SQL->tableName('killers') . ' LEFT JOIN ' . $SQL->tableName('environment_killers') . ' ON ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('environment_killers') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('player_killers') . ' ON ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('player_killers') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('players') . ' ON ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('player_killers') . '.' . $SQL->fieldName('player_id') . '  WHERE ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('death_id') . ' = ' . $SQL->quote($death['id']) . ' ORDER BY ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('final_hit') . ' DESC, ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('id') . ' ASC')->fetchAll();

			$i = 0;
			$count = count($killers);
			foreach($killers as $killer)
			{
				$i++;
				if($i == 1)
				{
					if($count <= 4)
						$dead_add_content .= "Killed at level <b>".$death['level']."</b> by ";
					elseif($count > 4 and $count < 10)
						$dead_add_content .= "Slain at level <b>".$death['level']."</b> by ";
					elseif($count > 9 and $count < 15)
						$dead_add_content .= "Crushed at level <b>".$death['level']."</b> by ";
					elseif($count > 14 and $count < 20)
						$dead_add_content .= "Eliminated at level <b>".$death['level']."</b> by ";
					elseif($count > 19)
						$dead_add_content .= "Annihilated at level <b>".$death['level']."</b> by ";
				}
				elseif($i == $count)
					$dead_add_content .= " and ";
				else
					$dead_add_content .= ", ";

				if($killer['player_name'] != "")
				{
					if($killer['monster_name'] != "")
						$dead_add_content .= htmlspecialchars($killer['monster_name'])." summoned by ";

					if($killer['player_exists'] == 0)
					$dead_add_content .= "<a href=\"index.php?subtopic=characters&name=".urlencode($killer['player_name'])."\"><font color='orange'>";

					$dead_add_content .= htmlspecialchars($killer['player_name']);
					if($killer['player_exists'] == 0)
						$dead_add_content .= "</font></a>";
				}
				else
					$dead_add_content .= htmlspecialchars($killer['monster_name']);
			}

			$dead_add_content .= "</td></tr>";
		}

		if($deads > 0)
			$main_content .= '<div class="col-md-11 your_characters">
						<div class="info_header">
					<p>Deaths</p>
				</div>
				<div class="row">
					<table class="table table-striped">
					' . $dead_add_content . '</table></div></div><br />';
		
		//frags list by Xampy 
             
            $frags_limit = 8; // frags limit to show? // default: 8 
            $player_frags = $SQL->query('SELECT `player_deaths`.*, `players`.`name`, `killers`.`unjustified` FROM `player_deaths` LEFT JOIN `killers` ON `killers`.`death_id` = `player_deaths`.`id` LEFT JOIN `player_killers` ON `player_killers`.`kill_id` = `killers`.`id` LEFT JOIN `players` ON `players`.`id` = `player_deaths`.`player_id` WHERE `player_killers`.`player_id` = '.$player->getId().' ORDER BY `date` DESC LIMIT 0,'.$frags_limit.';'); 
            if(count($player_frags)) 
            { 
                $frags = 0; 
                $frag_add_content .= '<div class="col-md-11 your_characters">
						<div class="info_header">
					<p>Victims</p>
				</div>
				<div class="row">
					<table class="table table-striped">';

                foreach($player_frags as $frag) 
                { 
                $frags++; 
                    $number_of_rows++; 
                    $frag_add_content .= "<tr bgcolor=\"".$bgcolor."\"> 
                    <td width=\"20%\" align=\"center\">".date("j M Y, H:i", $frag['date'])."</td> 
                    <td>".(($player->getSex() == 0) ? 'She' : 'He')." fragged <a href=\"index.php?subtopic=characters&name=".$frag[name]."\"><font color='red'>".$frag[name]."</font></a> at level ".$frag[level].""; 
 
                    $frag_add_content .= ". (".(($frag[unjustified] == 0) ? "<font size=\"1\" color=\"green\">Justified</font>" : "<font size=\"1\" color=\"red\">Unjustified</font>").")</td></tr>"; 
                } 
            if($frags >= 1) 
                $main_content .= $frag_add_content . '</table></div></div>'; 
            } 
            // end of frags list by Xampy 
		
		if(!$player->getHideChar())
		{
			$main_content .= '<div class="col-md-11 your_characters">
						<div class="info_header">
					<p>Account Information</p>
				</div>
				<div class="row">
					<table class="table table-striped">';
			if($account->getrLName())
			{
				$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=20%>Real name:</td><td>' . $account->getrLName() . '</td></tr>';
			}
			if($account->getLocation())
			{
				$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=20%>Location:</td><td>' . $account->getLocation() . '</td></tr>';
			}
			if($account->getLastLogin())
				$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=20%>Last login:</td><td>' . date("j F Y, g:i a", $account->getLastLogin()) . '</td></tr>';
			else
				$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=20%>Last login:</td><td>Never logged in.</td></tr>';
			if($account->getCreateDate())
			{
				$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=20%>Created:</td><td>' . date("j F Y, g:i a", $account->getCreateDate()) . '</td></tr>';
			}
			$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td>Account&#160;Status:</td><td>';
			$main_content .= ($account->isPremium() > 0) ? '<b><font color="gold">Golden Account</font></b>' : '<b><font color="red">Free Account</font></b>';
			if($account->isBanned())
			{
				if($account->getBanTime() > 0)
					$main_content .= '<font color="red"> [Banished]</font>';
			}
			$main_content .= '</td></tr></table></div></div>';
			$main_content .= '
			<div class="col-md-11 your_characters">
						<div class="info_header">
					<p>Characters</p>
				</div>
				<div class="row">
					<table class="table table-striped">

			</tr>
			<tr BGCOLOR="' . $bgcolor . '"><td><B>Name</B></td><td><B>World</B></td><td><B>Level</B></td><td><b>Status</b></td><td><B>&#160;</B></td></tr>';
			$account_players = $account->getPlayersList();
			$player_number = 0;
			foreach($account_players as $player_list)
			{
				if(!$player_list->getHideChar())
				{
					$player_number++;
					if(!$player_list->isOnline())
						$player_list_status = '<font color="red">Offline</font>';
					else
						$player_list_status = '<font color="green">Online</font>';
					$main_content .= '<tr BGCOLOR="' . $bgcolor . '"><td WIDTH=52%><NObr>'.$player_number.'.&#160;'.htmlspecialchars($player_list->getName());
					$main_content .= ($player_list->isDeleted()) ? '<font color="red"> [DELETED]</font>' : '';
					$main_content .= '</NObr></td><td WIDTH=15%>'.$config['site']['worlds'][$player_list->getWorld()].'</td><td WIDTH=25%>'.$player_list->getLevel().' '.htmlspecialchars($vocation_name[$player_list->getPromotion()][$player_list->getVocation()]).'</td><td WIDTH="8%"><b>'.$player_list_status.'</b></td>
						<td><a href="?subtopic=characters&name='.htmlspecialchars($player_list->getName()).'" class="btn edit-character">Check Player</a></td></tr>';
				}
			}
			$main_content .= '</table></td><td><img SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></td></tr></table></div></div>';
		}
	}
	else
		$search_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> does not exist.';	
}
if(!empty($search_errors))
{
	$main_content .= '<div class="col-md-11 your_characters">
						<div class="info_header">
					<p>Errors</p>
				</div>
				<br><br><font color="white">
				<ul>';
	foreach($search_errors as $search_error)
		$main_content .= '<li>'.$search_error.'</li>';
	$main_content .= '</ul></font></div><br><br>';
}
$main_content .= '<br>
<div class="col-md-11 your_characters">
	<div class="info_header">
		<p>Search Charater</p>
	</div>
	<div class="row">
		<br>
		<form action="?subtopic=characters" method="post">
	<b>	<font color="gold"><b>Name: </b><INPUT NAME="name" VALUE=""SIZE=29 MAXLENGTH=29></font>
		<font color="black"><input type="submit" value="Submit" class="btn edit-character"></font>
</b>
		</form>
		<br>
	</div>
</div>';