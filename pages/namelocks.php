<?php
if(!defined('INITIALIZED'))
	exit;

$playerNamelocks = new DatabaseList('PlayerNamelocks');
$playerNamelocks->addOrder(new SQL_Order(new SQL_Field('date'), SQL_Order::DESC));

$main_content .= '<div class="title">Namelocks list</div>';
$main_content .= '<table class="table table-bordered">
	<tr>
	<th>Current Name</th>
	<th>Old Names</th>';
if(count($playerNamelocks) > 0)
{
	$playersNamelocksInfo = array();
	foreach($playerNamelocks as $namelock)
	{
		if(!isset($playersNamelocksInfo[$namelock->getID()]))
		{
			$playersNamelocksInfo[$namelock->getID()] = array();
			$playersNamelocksInfo[$namelock->getID()]['name'] = $namelock->getNewName();
			$playersNamelocksInfo[$namelock->getID()]['oldNames'] = array();
			$playersNamelocksInfo[$namelock->getID()]['oldNames'][] = $namelock;
		}
		else
			$playersNamelocksInfo[$namelock->getID()]['oldNames'][] = $namelock;
	}
	$old_names_text = array();
	foreach($playersNamelocksInfo as $playerInfo)
	{
		$old_names_text = array();
		foreach($playerInfo['oldNames'] as $oldName)
		{
			$old_names_text[] = 'until ' . date("j F Y, g:i a", $oldName->getDate()) . ' known as <b>' . htmlspecialchars($oldName->getName()) . '</b>';
		}
		$main_content .= '<tr><td style="vertical-align:top"><a href="?subtopic=characters&name=' . urlencode($playerInfo['name']) . '">' . htmlspecialchars($playerInfo['name']) . '</a></td><td>' . implode('<br />', $old_names_text) . '</td></tr>';
	}
}
else
{
	$main_content .= '<tr><td colspan="2">No one changed name on ' . $config['server']['serverName'] . '.</td></tr>';
}

$main_content .= "</table>";