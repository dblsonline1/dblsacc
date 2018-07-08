<?php
if(!defined('INITIALIZED'))
	exit;

$list = 'experience';
if(isset($_REQUEST['list']))
	$list = $_REQUEST['list'];

$page = 0;
if(isset($_REQUEST['page']))
	$page = min(50, $_REQUEST['page']);

$vocation = '';
if(isset($_REQUEST['vocation']))
	$vocation = $_REQUEST['vocation'];

switch($list)
{
	case "aspeed":
		$id=Highscores::SKILL_FIST;
		$list_name='Attack Speed';
		break;
	case "strenght":
		$id=Highscores::SKILL_CLUB;
		$list_name='Strenght';
		break;
	case "sword":
		$id=Highscores::SKILL_SWORD;
		$list_name='Sword';
		break;
	case "axe":
		$id=Highscores::SKILL_AXE;
		$list_name='Axe Fighting';
		break;
	case "blasting":
		$id=Highscores::SKILL_DISTANCE;
		$list_name='Ki Blasting';
		break;
	case "defense":
		$id=Highscores::SKILL_SHIELD;
		$list_name='Defense';
		break;
	case "fishing":
		$id=Highscores::SKILL_FISHING;
		$list_name='Fishing';
		break;
	case "kilvl":
		$id=Highscores::SKILL__MAGLEVEL;
		$list_name='Ki Level';
		break;
	default:
		$id=Highscores::SKILL__LEVEL;
		$list_name='Experience';
		break;
}
if(count($config['site']['worlds']) > 1)
{
	foreach($config['site']['worlds'] as $idd => $world_n)
	{
		if($idd == (int) $_REQUEST['world'])
		{
			$world_id = $idd;
			$world_name = $world_n;
		}
	}
}
if(!isset($world_id))
{
	$world_id = 0;
	$world_name = $config['server']['serverName'];
}

$main_content .= '
		<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Highscores - Ranking for '.htmlspecialchars($list_name).' on '.htmlspecialchars($world_name).'</p>
					
				</div>
			</div>';




$offset = $page * 100;
$skills = new Highscores($id, 100, $page, $world_id, $vocation);

$main_content .= '
	<div class="col-md-3 buttons">
		<a href="?subtopic=highscores&list=experience&world='.$world_id.'" class="btn change-password">Experience</a>
		<a href="?subtopic=highscores&list=kilvl&world='.$world_id.'" class="btn change-password">Ki level</a>
		<a href="?subtopic=highscores&list=aspeed&world='.$world_id.'" class="btn change-password">Attack Speed</a>
		<a href="?subtopic=highscores&list=strenght&world='.$world_id.'" class="btn change-password">Strenght</a>
		<a href="?subtopic=highscores&list=sword&world='.$world_id.'" class="btn change-password">Sword</a>
		<a href="?subtopic=highscores&list=blasting&world='.$world_id.'" class="btn change-password">Ki Blasting</a>
		<a href="?subtopic=highscores&list=defense&world='.$world_id.'" class="btn change-password">Defense</a>
	</div><br>
';

$main_content .= '

<div class="col-md-11 your_characters">
<div class="info_header">
					<p>Legends</p>
				</div>
				<div class="row">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Badge</th>
								<th>Info</th>

							</tr>
						</thead>
						<tbody>
							<td><img src="http://media.dbns.eu/crown.png" heght="84" width="84"><br>  </td>	
							<td><br><b><font color="gold">One of the top 5 players in this category.</font></b></td>	
						</tbody>
					</table>
				</div>				
</div>
';


$main_content .= '

		

			<div class="col-md-11 your_characters">
				<div class="info_header">
					<p>'.htmlspecialchars($list_name).'</p>
				</div>
				<div class="row">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Flag</th>
								<th>Name</th>
								<th>Status</th>
								<th>Skill Level</th>
								<th>Vocation</th>
								<th>Character Level</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
						';
								$crownCounter = 0;
								foreach($skills as $skill)
								{
									if($list == "kilvl")
										$value = $skill->getMagLevel();
									elseif($list == "experience")
										$value = $skill->getLevel();
									else
										$value = $skill->getScore();

									$crownCounter = $crownCounter + 1;
									if($crownCounter <= 5)
									{
										$crown = '<img src="http://media.dbns.eu/crown.png" height="42" width="42">';
									}
									else
									{
										$crown = '';
									}
									
									$main_content .='
								<tr>
								<td>'.($offset + ++$number_of_rows).'</td>
								<td><img src="' . $config['site']['flag_images_url'] . $skill->getFlag() . $config['site']['flag_images_extension'] . '" title="Country: ' . $skill->getFlag() . '" alt="' . $skill->getFlag() . '"></td>
								<td><a href="?subtopic=characters&name='.urlencode($skill->getName()).'"><b>'.htmlspecialchars($skill->getName()).'</b></a></td>
								<td>
								'.($skill->getOnline()>0 ? "<img src='http://media.dbns.eu/on.png' height='32' width='32'>" : "<img src='http://media.dbns.eu/off.png' height='32' width='32'>").'
								</td>
								<td>'.$value.'</td>
								<td>'.htmlspecialchars(Website::getVocationName($skill->getVocation(), $skill->getPromotion())).'</td>
								<td>'.$skill->getLevel().'</td>
								<td>'.$crown.'<a href="?subtopic=characters&name='.urlencode($skill->getName()).'" class="btn edit-character">Check Player</a></td></tr>
								';
								}
								$crownCounter = 0;
						$main_content .= '	
						</tbody>
					</table>
				</div>
			</div>		
';
$main_content .= '<div class="col-md-3 buttons">';

if($page > 0)
	$main_content .= '
		<a href="?subtopic=highscores&list=experience&world='.$world_id.'" class="btn change-password">Prev Page</a>';


if($page < 50)
	$main_content .= '
		<a href="?subtopic=highscores&list='.urlencode($list).'&page='.($page + 1).'&vocation=' . urlencode($vocation) . '&world=' . urlencode($world_id) . '" class="btn change-password">Next Page</a>'
;

$main_content .='</div>';