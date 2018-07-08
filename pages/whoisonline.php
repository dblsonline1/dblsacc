<?php
if(!defined('INITIALIZED'))
	exit;

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

$main_content .= '<div class="title">Who is online</div>';

if(count($config['site']['worlds']) > 1)
{
	$main_content .= '<table BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><tr><td></td><td>
	<form ACTION="" METHOD=get><INPUT TYPE="hidden" NAME="subtopic" VALUE="whoisonline"><table WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><tr><td><B>World Selection</B></td></tr><tr><td>
	<table BORDER=0 CELLPADDING=1><tr><td>Players online on world:</td><td><SELECT SIZE="1" NAME="world">';
	foreach($config['site']['worlds'] as $id => $world_n)
	{
		if($id == $world_id)
			$main_content .= '<OPTION VALUE="'.htmlspecialchars($id).'" selected="selected">'.htmlspecialchars($world_n).'</OPTION>';
		else
			$main_content .= '<OPTION VALUE="'.htmlspecialchars($id).'">'.htmlspecialchars($world_n).'</OPTION>';
	}
	$main_content .= '</SELECT> </td><td><input type="submit" value="Submit" class="btn">
		</td></tr></table></table></form></table>';
}
$orderby = 'name';
if(isset($_REQUEST['order']))
{
	if($_REQUEST['order']== 'level')
		$orderby = 'level';
	elseif($_REQUEST['order'] == 'vocation')
		$orderby = 'vocation';
}
$players_online_data = $SQL->query('SELECT ' . $SQL->tableName('accounts') . '.' . $SQL->fieldName('flag') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('vocation') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('promotion') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('level') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('skull') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('looktype') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookaddons') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookhead') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookbody') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('looklegs') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('lookfeet') . ' FROM ' . $SQL->tableName('accounts') . ', ' . $SQL->tableName('players') . ' WHERE ' . $SQL->tableName('players') . '.' . $SQL->fieldName('world_id') . ' = ' . $SQL->quote($world_id) . ' AND ' . $SQL->tableName('players') . '.' . $SQL->fieldName('online') . ' = ' . $SQL->quote(1) . ' AND ' . $SQL->tableName('accounts') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('players') . '.' . $SQL->fieldName('account_id') . ' ORDER BY ' . $SQL->fieldName($orderby))->fetchAll();
$number_of_players_online = 0;
$vocations_online_count = array(0,0,0,0,0); // change it if you got more then 5 vocations
$players_rows = '';
foreach($players_online_data as $player)
{
	$vocations_online_count[$player['vocation']] += 1;
	$players_rows .= '<tr><td WIDTH=65%><A HREF="?subtopic=characters&name='.urlencode($player['name']).'">'.htmlspecialchars($player['name']).$skull.' <img src="' . $config['site']['flag_images_url'] . $player['flag'] . $config['site']['flag_images_extension'] . '" title="Country: ' . $player['flag'] . '" alt="' . $player['flag'] . '" /></A></td><td WIDTH=10%>'.$player['level'].'</td><td WIDTH=20%>'.htmlspecialchars($vocation_name[$player['promotion']][$player['vocation']]).'</td></tr>';
}		
//search bar
	$main_content .= '<BR><form ACTION="?subtopic=characters" METHOD=post>  <table WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><tr><td><B>Search Character</B></td></tr><tr><td><table BORDER=0 CELLPADDING=1><tr><td>Name:</td><td><INPUT NAME="name" VALUE="" SIZE="29" MAXLENGTH="29"></td><td><input type="submit" value="Submit" class="btn"></td></tr></table></td></tr></table></form>';

{

	$main_content .= '
	<div style="text-align: center;">&nbsp;</div>';

	//list of players
	$main_content .= '<table class="table table-bordered"><tr><td><A HREF="?subtopic=whoisonline&order=name&world='.$world_id.'">Name</a></td><td><a href="?subtopic=whoisonline&order=level&world='.urlencode($world_id).'">Level</a></td><td><A HREF="?subtopic=whoisonline&order=vocation&world='.urlencode($world_id).'">Vocation</td></tr>'.$players_rows.'</table>';
}