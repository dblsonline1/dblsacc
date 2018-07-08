<?php
if(!defined('INITIALIZED'))
	exit;

$main_content .= '<article><div class="heading"><center>Server information</center></div></article>';
$main_content .= '<table class="table tableSpacing">';
$bgcolor = "white";
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;width:150px">PVP protection</td><td>to ' . $config['server']['protectionLevel'] . ' level</td></tr>';
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Exp rate</td><td>';
if($config['server']['experienceStages'])
{
	$stages = new DOMDocument();
	if($stages->load($config['site']['serverPath'] . 'data/XML/stages.xml'))
	{
		$stagesOfFirstWorldInStages = $stages->getElementsByTagName('world')->item(0);
		$worldMultiplier = $stagesOfFirstWorldInStages->getAttribute('multiplier');
		foreach($stagesOfFirstWorldInStages->getElementsByTagName('stage') as $stage)
		{
			$main_content .= $stage->getAttribute('minlevel');
			if($stage->hasAttribute('maxlevel'))
			{
				$main_content .= ' - ' . $stage->getAttribute('maxlevel') . ' level';
			}
			else
			{
				$main_content .= '+ level';
			}
			$main_content .= ', ' . ($stage->getAttribute('multiplier') * $worldMultiplier) . 'x<br />';
		}
		$main_content .= '';
	}
	else
	{
		$main_content .= 'Cannot load experience stages.';
	}
}
else
{
	$main_content .= $config['server']['rateExperience'] . 'x';
}
$main_content .= '</td></tr>';
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Exp from players</td><td>' . $config['server']['rateExperienceFromPlayers'] . 'x</td></tr>';
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Skill rate</td><td>' . $config['server']['rateSkill'] . 'x</td></tr>';
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Magic rate</td><td>' . $config['server']['rateMagic'] . 'x</td></tr>';
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Loot rate</td><td>' . $config['server']['rateLoot'] . 'x</td></tr></table>

';

$main_content .= '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';




