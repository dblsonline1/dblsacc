<?php
if(!defined('INITIALIZED'))
	exit;

$main_content .= '<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Guilds - REVE.DBNS.EU</p>
				</div>
			</div>';


if($action == 'login')
{
	if(check_guild_name($_REQUEST['guild']))
		$guild = $_REQUEST['guild'];
	if($_REQUEST['redirect'] == 'guild' || $_REQUEST['redirect'] == 'guilds')
		$redirect = $_REQUEST['redirect'];
	if(!$logged)
	{
		$main_content .= '<form action="?subtopic=accountmanagement" method="post" class="loginform">
		<table class="tableSpacing">
			<tr>
				<td>
					<label>Account Name:</label>
				</td>
				<td>
					<input type="password" name="account_login" size="30" maxlength="10">
				</td>
			</tr>
			<tr>
				<td>
					<label>Password:</label>
				</td>
				<td>
					<input type="password" name="password_login" size="30" maxlength="29">
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="Submit" alt="Submit" value="Login" class="btn">
				</td>
				<td>
					<a href="?subtopic=lostaccount">Account lost?</a>
				</td>
			</tr>
		</table>
		</form>';
	}
	else
	{
		$main_content .= '<center><h3>Now you are logged. Redirecting...</h3></center>';
		if($redirect == 'guilds')
			header("Location: ?subtopic=guilds");
		elseif($redirect == 'guild')
			header("Location: ?subtopic=guilds&action=show&guild=".urlencode($guild));
		else
			$main_content .= 'Wrong address to redirect!';
	}
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show list of guilds
if($action == '')
{
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
	if(count($config['site']['worlds']) > 1)
	{
		$main_content .= '<table class="table table-striped"><tr><td></td><td>
		<form ACTION="" method="get">
			<input TYPE="hidden" NAME="subtopic" VALUE="guilds">
			<table class="table">
				<tr>
					<td><B>World Selection</B></td>
				</tr>
				<tr>
				<td>
					<table class="table table-striped"><tr><td>Guilds on world:</td><td><SELECT SIZE="1" NAME="world">';
		foreach($config['site']['worlds'] as $id => $world_n)
		{
			if($id == $world_id)
				$main_content .= '<OPTION VALUE="'.$id.'" selected="selected">'.htmlspecialchars($world_n).'</OPTION>';
			else
				$main_content .= '<OPTION VALUE="'.$id.'">'.htmlspecialchars($world_n).'</OPTION>';
		}
		$main_content .= '</SELECT> </td><td><input type="submit" value="Submit" class="btn">
			</td></tr></table></table></form></table>';
	}
	
	$guilds_list = new DatabaseList('Guild');
	$filterWorld = new SQL_Filter(new SQL_Field('world_id', 'guilds'), SQL_Filter::EQUAL, $world_id);
	$guilds_list->setFilter($filterWorld);
	$guilds_list->addOrder(new SQL_Order(new SQL_Field('name'), SQL_Order::ASC));

	$goblets = array('<img src="http://media.dbns.eu/goblet.png" height="22px" width="22px">', '<img src="http://media.dbns.eu/goblet2.png" height="22px" width="22px">', '<img src="http://media.dbns.eu/goblet3.png" height="22px" width="22px">', '');
	$nameColors = array('gold', 'silver', '#cd7f32', 'white');
// top kills - guilds
$update_interval = 30000;
$tmp_file_name = 'cache/mostpowerfullguilds.tmp';
	$main_content .= '
<div class="col-md-11 your_characters">
<div class="info_header">
					<p>Best Guilds</p>
				</div>
				<div class="row">
					<table class="table table-striped">';
if(file_exists($tmp_file_name) && (filemtime($tmp_file_name) > (time() - $update_interval)))
        {
            $tmp_file_content = file_get_contents($tmp_file_name);
            $main_content = $tmp_file_content;
        }
        else {
        foreach($SQL->query('SELECT ' . $SQL->tableName('g') . '.' . $SQL->fieldName('id') . ' AS ' . $SQL->fieldName('id') . ', ' . $SQL->tableName('g') . '.' . $SQL->fieldName('name') . ' AS ' . $SQL->fieldName('name') . ', COUNT(' . $SQL->tableName('g') . '.' . $SQL->fieldName('name') . ') AS ' . $SQL->fieldName('frags') . ' FROM ' . $SQL->tableName('killers') . ' k LEFT JOIN ' . $SQL->tableName('player_killers') . ' pk ON ' . $SQL->tableName('k') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('pk') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('players') . ' p ON ' . $SQL->tableName('pk') . '.' . $SQL->fieldName('player_id') . ' = ' . $SQL->tableName('p') . '.' . $SQL->fieldName('id') . ' LEFT JOIN ' . $SQL->tableName('guild_ranks') . ' gr ON ' . $SQL->tableName('p') . '.' . $SQL->fieldName('rank_id') . ' = ' . $SQL->tableName('gr') . '.' . $SQL->fieldName('id') . ' LEFT JOIN ' . $SQL->tableName('guilds') . ' g ON ' . $SQL->tableName('gr') . '.' . $SQL->fieldName('guild_id') . ' = ' . $SQL->tableName('g') . '.' . $SQL->fieldName('id') . ' WHERE ' . $SQL->tableName('g') . '.' . $SQL->fieldName('id') . ' > 0 AND ' . $SQL->tableName('k') . '.' . $SQL->fieldName('unjustified') . ' = 1 AND ' . $SQL->tableName('k') . '.' . $SQL->fieldName('final_hit') . ' = 1 GROUP BY ' . $SQL->fieldName('name') . ' ORDER BY ' . $SQL->fieldName('frags') . ' DESC, ' . $SQL->fieldName('name') . ' ASC LIMIT 4;') as $guild)
        {
        	$topGuildsArray[htmlspecialchars($guild['name'])] = array("frags" => $guild['frags'], "guildID" => $guild['id']);
        }

        $main_content .= '
        	<thead>
							<tr>';
							$iterator = 0;
							foreach($topGuildsArray as $nazwa => $tablica)
							{
								$tmpNumber = $iterator + 1;
								$main_content .='<th><a href="?subtopic=guilds&action=show&guild=' . $tablica['guildID'] . '">'.$goblets[$iterator].'<font color="'.$nameColors[$iterator].'">'.$nazwa.' #'.$tmpNumber.'</font></a></th>';
								$iterator = $iterator + 1;
							}

							$main_content .='
								<th></th>
							</tr>
						</thead>
						<tbody>

							<tr>';
								foreach($topGuildsArray as $nazwa => $tablica)
							{
								$main_content .='<td><img src="guild_image.php?id=' . $tablica['guildID'] . '" height="84" width="84"><br>
								<br></td>	';
							}

							$main_content .='<td><img src="https://cdn0.iconfinder.com/data/icons/awards-6/500/award_like-512.png" height="84" width="84"><br>

							<br> </td>
							</tr>

							<tr>';
							foreach($topGuildsArray as $nazwa => $tablica)
							{

								$main_content .='<th>Frags<br>'.$tablica['frags'].'
								<br>
								<font size="2px">(Won Leagues: <font color="red">0</font>)</font>
								</th>';
							}
								
							$main_content .='<th></th></tr>

						</tbody>

        ';
        file_put_contents($tmp_file_name, $main_content);
        }
        $timeleft = (($update_interval-(time()-filemtime($tmp_file_name)))/60);


						
$main_content .='						
					</table>
				</div>				
</div>





<div class="col-md-11 info">
				<div class="row">
	<table class="table table-striped">
	<tr><td COLSPAN=3><center><img src="http://media.dbns.eu/guilds.png" width="150px" height="150px" style="opacity:0.4;filter:allpha(opacity=40);"></center></B></td></tr>
	<tr><td WIDTH=64><B>Logo</B></td>
	<td WIDTH=100%><B>Description</B></td>
	<td WIDTH=56><B>&#160;</B></td></tr>';
	$showed_guilds = 1;
	if(count($guilds_list) > 0)
	{
		foreach($guilds_list as $guild)
		{
			$description = $guild->getdescription();
			$newlines   = array("\r\n", "\n", "\r");
			$description_with_lines = str_replace($newlines, '<br />', $description, $count);
			if($count < $config['site']['guild_description_lines_limit'])
				$description = $description_with_lines;
			$main_content .= '<tr><td><center><IMG SRC="'. $guild->getGuildLogoLink() .'" WIDTH=64 HEIGHT=64></center></td>
			<td valign="top"><B>'.htmlspecialchars($guild->getName()).'</B><BR/>'.$description.'';
			if($group_id_of_acc_logged >= $config['site']['access_admin_panel'])
				$main_content .= '<br />
			<div class="col-md-11 buttons">
				<a href="?subtopic=guilds&action=deletebyadmin&guild='.$guild->getId().'" class="btn change-password">Delete (ADMIN)</a>
			</div>';
			$main_content .= '</td><td><form ACTION="?subtopic=guilds&action=show&guild='.$guild->getId().'" method=post>
			<div class="col-md-11 buttons"><input type="submit" value="Submit" class="btn register-account"></div>
			</form>
			</td></tr>';
		}
	}
	else
		$main_content .= '<tr BGCOLOR='.$config['site']['lightborder'].'><td><IMG SRC="images/default_guild_logo.gif" WIDTH=64 HEIGHT=64></td>
		<td valign="top"><B>Create guild</B><BR/>Currently there is no guild on server. Create first! Press button "Create Guild".</td>
		<td><form ACTION="?subtopic=guilds&action=createguild" method=post>
		<input type="submit" value="Create" class="btn">
		</form></td></tr>';
	$main_content .= '</table><br><br>';
	if($logged)
		$main_content .= '
	<div class="col-md-11 your_characters">
		
				<div class="row">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Action</th>
								<th>Info</th>
							</tr>
						</thead>
						<tbody>
					

							<tr>
							<td>
							<form ACTION="?subtopic=guilds&action=createguild" method=post>
								<div class="col-md-11 buttons"><input type="submit" value="Create" class="btn register-account"></div>
							</form>
							<br><br>
							</td>
							<td><br><b>Create your own guild!</b></td>
							</tr>
							<tr>
								<td>
									<div class="col-md-11 buttons"><a href="?subtopic=guilds&action=cleanup_players" class="btn change-password">Cleanup Players</a></div><br>
								</td>
								<td><br>
								<b>If you have any problem with guilds try:
		Cleanup players - can\'t join guild/be invited? Can\'t create guild? try cleanup players.</b>
								</td>
							</tr>
					
						</tbody>
					</table>
				</div>
			</div><br><br>';
	else
		$main_content .= '<font color="white">Before you can create guild you must login.</font><br>
	<div class="col-md-11 buttons"><a href="?subtopic=accountmanagement" class="btn change-password">Login</a></div><br>';
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show guild page
if($action == 'show')
{
	$guild_id = (int) $_REQUEST['guild'];
	$guild = new Guild();
	$guild->load($guild_id);
	if(!$guild->isLoaded())
		$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	if(!empty($guild_errors))
	{
		//show errors
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error;
		//errors and back button
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
	{
		//check is it vice or/and leader account (leader has vice + leader rights)
		$guild_leader_char = $guild->getOwner();
		$rank_list = $guild->getGuildRanksList();
		$guild_leader = FALSE;
		$guild_vice = FALSE;
		if($logged)
		{
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
			{
				$players_from_account_ids[] = $player->getId();
				$player_rank = $player->getrank();
				if(!empty($player_rank))
					foreach($rank_list as $rank_in_guild)
						if($rank_in_guild->getId() == $player_rank->getId())
						{
							$players_from_account_in_guild[] = $player->getName();
							if($player_rank->getLevel() > 1)
							{
								$guild_vice = trUE;
								$level_in_guild = $player_rank->getLevel();
							}
							if($guild->getOwner()->getId() == $player->getId())
							{
								$guild_vice = trUE;
								$guild_leader = trUE;
							}
						}
			}
		}
		//show guild page
		$description = $guild->getdescription();
		$newlines   = array("\r\n", "\n", "\r");
		$description_with_lines = str_replace($newlines, '<br />', $description, $count);
		if($count < $config['site']['guild_description_lines_limit'])
			$description = $description_with_lines;
		$guild_owner = $guild->getOwner();
		if($guild_owner->isLoaded())
			$guild_owner = $guild_owner->getName();

		//TUTAJ WNETRZE GILDI, TJ PANEL VIEW DLA KAZDEGO
		$main_content .= '
		<table BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><tr>
		<td><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></td><td>
		<table BORDER=0 WIDTH=100%>
		<tr><td WIDTH=64><IMG SRC="' . $guild->getGuildLogoLink() . '" WIDTH=64 HEIGHT=64></td>
		<td ALIGN=left WIDTH=100%><H1>'.htmlspecialchars($guild->getName()).'</H1></td>
		</table><BR>'.$description.'<BR><BR><a href="?subtopic=characters&name='.urlencode($guild_owner).'"><b>'.htmlspecialchars($guild_owner).'</b></a> is guild leader of <b>'.htmlspecialchars($guild->getName()).'</b>.<BR>The guild was founded on '.htmlspecialchars($config['server']['serverName']).' on '.date("j F Y", $guild->getCreationData()).'.';
		if($guild_leader)
			$main_content .= '&nbsp;&nbsp;&nbsp;<a href="?subtopic=guilds&action=manager&guild='.$guild_id.'" class="btn">Manage</a>
<br>	<br>	

<b>Change GuildTAG</b>
';



		$main_content .= '<BR><BR>

					<div class="col-md-11 your_characters">
				<div class="row">
		<table class="table table-striped">

arararara

		<tr><td COLSPAN=3 CLASS=white><B>Guild Members</B></td></tr>
		<tr><td WIDTH=30%><B>Rank</B></td>
		<td WIDTH=70%><B>Name and Title</B></td></tr>';
		$showed_players = 1;
		foreach($rank_list as $rank)
		{
			$players_with_rank = $rank->getPlayersList();
			$players_with_rank_number = count($players_with_rank);
			if($players_with_rank_number > 0)
			{
				$main_content .= '<tr><td>'.htmlspecialchars($rank->getName()).'</td>
				<td><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%>';
				foreach($players_with_rank as $player)
				{
					$main_content .= '<tr style="border: 0"><td style="border: 0"><form ACTION="?subtopic=guilds&action=change_nick&name='.urlencode($player->getName()).'" method="post"><A HREF="?subtopic=characters&name='.urlencode($player->getName()).'">'.($player->isOnline() ? "<font color=\"green\">".htmlspecialchars($player->getName())."</font>" : "<font color=\"red\">".htmlspecialchars($player->getName())."</font>").'</A>';
					$guild_nick = $player->getGuildNick();
					if($logged)
						if(in_array($player->getId(), $players_from_account_ids))
							$main_content .= '(<input type="text" style="width: 30%" name="nick" value="'.htmlspecialchars($player->getGuildNick()).'"><input type="submit" class="btn" value="Change">)';
						else
						if(!empty($guild_nick))
							$main_content .= ' ('.htmlspecialchars($player->getGuildNick()).')';
					else
						if(!empty($guild_nick))
							$main_content .= ' ('.htmlspecialchars($player->getGuildNick()).')';
					if($level_in_guild > $rank->getLevel() || $guild_leader)
						if($guild_leader_char->getName() != $player->getName())
							$main_content .= '&nbsp;<font size=1>{<a href="?subtopic=guilds&action=kickplayer&guild='.$guild->getId().'&name='.urlencode($player->getName()).'">KICK</a>}</font>';
					$main_content .= '</form></td></tr>';
				}
				$main_content .= '</table></td></tr>';
			}
		}
		$main_content .= '</table>';
		$invited_list = $guild->listInvites();
		if(count($invited_list) == 0)
		{
			$main_content .= '<BR><table class="table table-striped"><tr><th>Invited Characters</th>';
		
			if($guild_vice) {
				$main_content .= '<th><a href="?subtopic=guilds&action=invite&guild='.$guild_id.'" class="btn">Invite player</a></th>';
			}
		
			$main_content .= '</tr><tr><td colspan="2">No invited characters found.</td></tr></table>';
		}
		else
		{
			$main_content .= '<BR><table class="table table-striped"><tr><th style="vertical-align: middle">Invited Characters</th>';
			
			if($guild_vice) {
				$main_content .= '<th><a href="?subtopic=guilds&action=invite&guild='.$guild_id.'" class="btn">Invite player</a></th>';
			}
			
			$main_content .= '</tr>';
			
			$show_accept_invite = 0;
			$showed_invited = 1;
			foreach($invited_list as $invited_player)
			{
				if(count($account_players) > 0)
					foreach($account_players as $player_from_acc)
						if($player_from_acc->getName() == $invited_player->getName())
							$show_accept_invite++;

				$main_content .= '<tr><td colspan="2"><a href="?subtopic=characters&name='.urlencode($invited_player->getName()).'">'.htmlspecialchars($invited_player->getName()).'</a>';
				if($guild_vice)
					$main_content .= '  (<a href="?subtopic=guilds&action=deleteinvite&guild='.$guild_id.'&name='.urlencode($invited_player->getName()).'" class="red">Delete</a>)';
				$main_content .= '</td></tr>'; 
			}
			$main_content .= '</table>';
		}
		$main_content .= '<br><table class="table-striped">';
		if(!$logged)
			$main_content .= '<tr><td><a href="?subtopic=guilds&action=login&guild='.$guild_id.'&redirect=guild" class="btn">Login</a></td></tr>';
		else
		{
			if($show_accept_invite > 0)
				$main_content .= '<tr><td><a href="?subtopic=guilds&action=acceptinvite&guild='.$guild_id.'" class="btn">Accept invite</td></tr>';
				
			if($guild_vice)
			{
				$main_content .= '<td><a href="?subtopic=guilds&action=changerank&guild='.$guild_id.'" class="btn">Change rank</a></td>';
			}
			
			if($players_from_account_in_guild > 0)
				$main_content .= '<tr><td><a href="?subtopic=guilds&action=leaveguild&guild='.$guild_id.'" class="btn">Leave guild</a></td></tr>';
		}
		$main_content .= '</tr></table></td></tr></table></table>
		<div class="text-center"><a href="?subtopic=guilds&world='.$guild->getWorld().'" class="btn">Back</a></div>';
	}
}

//--------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------
//change rank of player in guild
if($action == 'changerank')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t change rank.';
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(!empty($guild_errors))
	{
		//show errors
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error;
		//errors and back button
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
	{
	//check is it vice or/and leader account (leader has vice + leader rights)
	$rank_list = $guild->getGuildRanksList();
	$guild_leader = FALSE;
	$guild_vice = FALSE;
	$account_players = $account_logged->getPlayers();
	foreach($account_players as $player)
	{
		$player_rank = $player->getrank();
		if(!empty($player_rank))
			foreach($rank_list as $rank_in_guild)
				if($rank_in_guild->getId() == $player_rank->getId())
				{
					$players_from_account_in_guild[] = $player->getName();
					if($player_rank->getLevel() > 1) {
						$guild_vice = trUE;
						$level_in_guild = $player_rank->getLevel();
					}
					if($guild->getOwner()->getId() == $player->getId()) {
						$guild_vice = trUE;
						$guild_leader = trUE;
					}
				}
	}
	if($guild_vice)
	{
		foreach($rank_list as $rank)
		{
			if($guild_leader || $rank->getLevel() < $level_in_guild)
			{
				$ranks[$rid]['0'] = $rank->getId();
				$ranks[$rid]['1'] = $rank->getName();
				$rid++;
				$players_with_rank = $rank->getPlayersList();
				if(count($players_with_rank) > 0)
				{
					foreach($players_with_rank as $player)
					{
						if($guild->getOwner()->getId() != $player->getId() || $guild_leader)
						{
							$players_with_lower_rank[$sid]['0'] = htmlspecialchars($player->getName());
							$players_with_lower_rank[$sid]['1'] = htmlspecialchars($player->getName()).' ('.htmlspecialchars($rank->getName()).')';
							$sid++;
						}
					}
				}
			}
		}
		if($_REQUEST['todo'] == 'save')
		{
			$player_name = $_REQUEST['name'];
			$new_rank = (int) $_REQUEST['rankid'];
			if(!check_name($player_name))
				$change_errors[] = 'Invalid player name format.';
			$rank = new GuildRank();
			$rank->load($new_rank);
			if(!$rank->isLoaded())
				$change_errors[] = 'Rank with this ID doesn\'t exist.';
			if($level_in_guild <= $rank->getLevel() && !$guild_leader)
				$change_errors[] = 'You can\'t set ranks with equal or higher level than your.';
			if(empty($change_errors))
			{
				$player_to_change = new Player();
				$player_to_change->find($player_name);
				if(!$player_to_change->isLoaded())
					$change_errors[] = 'Player with name '.htmlspecialchars($player_name).'</b> doesn\'t exist.';
				else
				{
					$player_in_guild = FALSE;
					if($guild->getName() == $player_to_change->getrank()->getGuild()->getName() || $guild_leader)
					{
						$player_in_guild = trUE;
						$player_has_lower_rank = FALSE;
						if($player_to_change->getrank()->getLevel() < $level_in_guild || $guild_leader)
							$player_has_lower_rank = trUE;
					}
				}
				$rank_in_guild = FALSE;
				foreach($rank_list as $rank_from_guild)
					if($rank_from_guild->getId() == $rank->getId())
						$rank_in_guild = trUE;
				if(!$player_in_guild)
				$change_errors[] = 'This player isn\'t in your guild.';
				if(!$rank_in_guild)
					$change_errors[] = 'This rank isn\'t in your guild.';
				if(!$player_has_lower_rank)
					$change_errors[] = 'This player has higher rank in guild than you. You can\'t change his/her rank.';
			}
			if(empty($change_errors))
			{
				$player_to_change->setrank($rank);
				$player_to_change->save();
				$main_content .= 'Rank of player <b>'.htmlspecialchars($player_to_change->getName()).'</b> has been changed to <b>'.htmlspecialchars($rank->getName()).'<br><br>';
				unset($players_with_lower_rank);
				unset($ranks);
				$rid = 0;
				$sid= 0;
				foreach($rank_list as $rank)
				{
					if($guild_leader || $rank->getLevel() < $level_in_guild)
					{
						$ranks[$rid]['0'] = $rank->getId();
						$ranks[$rid]['1'] = $rank->getName();
						$rid++;
						$players_with_rank = $rank->getPlayersList();
						if(count($players_with_rank) > 0)
						{
							foreach($players_with_rank as $player)
							{
								if($guild->getOwner()->getId() != $player->getId() || $guild_leader)
								{
									$players_with_lower_rank[$sid]['0'] = htmlspecialchars($player->getName());
									$players_with_lower_rank[$sid]['1'] = htmlspecialchars($player->getName()).' ('.htmlspecialchars($rank->getName()).')';
									$sid++;
								}
							}
						}
					}
				}
			}
			else
			{
				$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
				foreach($change_errors as $change_error)
					$main_content .= '<li>'.$change_error;
				$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/>';
			}
		}
		$main_content .= '<form ACTION="?subtopic=guilds&action=changerank&guild='.$guild_id.'&todo=save" method=post>
		<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>
		<tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Change Rank</B></td></tr>
		<tr BGCOLOR='.$config['site']['darkborder'].'><td>Name: <SELECT NAME="name">';
		foreach($players_with_lower_rank as $player_to_list)
			$main_content .= '<OPTION value="'.$player_to_list['0'].'">'.$player_to_list['1'];
		$main_content .= '</SELECT>&nbsp;Rank:&nbsp;<SELECT NAME="rankid">';
		foreach($ranks as $rank)
			$main_content .= '<OPTION value="'.htmlspecialchars($rank['0']).'">'.htmlspecialchars($rank['1']);
		$main_content .= '</SELECT>&nbsp;&nbsp;&nbsp;<input TYPE="submit" value="Submit" class="btn"></td><tr>
		</table></form><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" ALT="Back" class="btn"></center></td></tr></form></table>';
	}
	else
		$main_content .= 'Error. You are not a leader or vice leader in guild '.htmlspecialchars($guild->getName()).'.<form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><input TYPE="submit" value="Back" class="btn"></form>';
	}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show guild page
if($action == 'deleteinvite')
{
	//set rights in guild
	$guild_id = (int) $_REQUEST['guild'];
	$name = $_REQUEST['name'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t delete invitations.';
	if(!check_name($name))
		$guild_errors[] = 'Invalid name format.';
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		$rank_list = $guild->getGuildRanksList();
		$guild_leader = FALSE;
		$guild_vice = FALSE;
		$account_players = $account_logged->getPlayers();
		foreach($account_players as $player)
		{
			$player_rank = $player->getrank();
			if(!empty($player_rank))
			{
				foreach($rank_list as $rank_in_guild)
				{
					if($rank_in_guild->getId() == $player_rank->getId())
					{
						$players_from_account_in_guild[] = $player->getName();
						if($player_rank->getLevel() > 1)
						{
							$guild_vice = trUE;
							$level_in_guild = $player_rank->getLevel();
						}
						if($guild->getOwner()->getId() == $player->getId())
						{
							$guild_vice = trUE;
							$guild_leader = trUE;
						}
					}
				}
			}
		}
	}
	if(empty($guild_errors))
	{
		$player = new Player();
		$player->find($name);
		if(!$player->isLoaded())
			$guild_errors[] = 'Player with name <b>'.htmlspecialchars($name).'</b> doesn\'t exist.';
	}
	if(!$guild_vice)
		$guild_errors[] = 'You are not a leader or vice leader of guild <b>'.htmlspecialchars($guild->getName()).'</b>.';
	if(empty($guild_errors))
	{
		$invited_list = $guild->listInvites();
		if(count($invited_list) > 0)
		{
			$is_invited = FALSE;
			foreach($invited_list as $invited)
				if($invited->getName() == $player->getName())
					$is_invited = trUE;
			if(!$is_invited)
				$guild_errors[] = '<b>'.htmlspecialchars($player->getName()).'</b> isn\'t invited to your guild.';
		}
		else
			$guild_errors[] = 'No one is invited to your guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error;
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
	{
		if($_REQUEST['todo'] == 'save')
		{
			$guild->deleteInvite($player);
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Delete player invitation</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Player with name <b>'.htmlspecialchars($player->getName()).'</b> has been deleted from "invites list".</td></tr></table><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
		}
		else
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Delete player invitation</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Are you sure you want to delete player with name <b>'.htmlspecialchars($player->getName()).'</b> from "invites list"?</td></tr></table><br/><center><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><tr><form ACTION="?subtopic=guilds&action=deleteinvite&guild='.$guild_id.'&name='.urlencode($player->getName()).'&todo=save" method=post><td align="right" width="50%"><input TYPE="submit" value="Submit" class="btn">&nbsp;&nbsp;</td></form><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><td>&nbsp;&nbsp;<input TYPE="submit" value="Back" class="btn"></td></tr></form></table></center>';
	}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show guild page
if($action == 'invite')
{
	//set rights in guild
	$guild_id = (int) $_REQUEST['guild'];
	$name = $_REQUEST['name'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t invite players.';
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		$rank_list = $guild->getGuildRanksList();
		$guild_leader = FALSE;
		$guild_vice = FALSE;
		$account_players = $account_logged->getPlayers();
		foreach($account_players as $player)
		{
			$player_rank = $player->getrank();
			if(!empty($player_rank))
				foreach($rank_list as $rank_in_guild)
					if($rank_in_guild->getId() == $player_rank->getId())
					{
						$players_from_account_in_guild[] = $player->getName();
						if($player_rank->getLevel() > 1)
						{
							$guild_vice = trUE;
							$level_in_guild = $player_rank->getLevel();
						}
						if($guild->getOwner()->getId() == $player->getId())
						{
							$guild_vice = trUE;
							$guild_leader = trUE;
						}
					}
		}
	}
	if(!$guild_vice)
		$guild_errors[] = 'You are not a leader or vice leader of guild ID <b>'.$guild_id.'</b>.';
	if($_REQUEST['todo'] == 'save')
	{
		if(!check_name($name))
			$guild_errors[] = 'Invalid name format.';
		if(empty($guild_errors))
		{
			$player = new Player();
			$player->find($name);
			if(!$player->isLoaded())
				$guild_errors[] = 'Player with name <b>'.htmlspecialchars($name).'</b> doesn\'t exist.';
			else
			{
				$rank_of_player = $player->getrank();
				if(!empty($rank_of_player))
					$guild_errors[] = 'Player with name <b>'.htmlspecialchars($name).'</b> is already in guild. He must leave guild before you can invite him.';
			}
		}
		if(empty($guild_errors) && $guild->getWorld() != $player->getWorld())
			$guild_errors[] = '<b>'.htmlspecialchars($player->getName()).'</b> is from other world then your guild.';
		if(empty($guild_errors))
		{
			$invited_list = $guild->listInvites();
			if(count($invited_list) > 0)
				foreach($invited_list as $invited)
					if($invited->getName() == $player->getName())
						$guild_errors[] = '<b>'.htmlspecialchars($player->getName()).'</b> is already invited to your guild.';
		}
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error;
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
		if($_REQUEST['todo'] == 'save')
		{
			$guild->invite($player);
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Invite player</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Player with name <b>'.htmlspecialchars($player->getName()).'</b> has been invited to your guild.</td></tr></table><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
		}
		else
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Invite player</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%><form ACTION="?subtopic=guilds&action=invite&guild='.$guild_id.'&todo=save" method=post>Invite player with name:<input TYPE="text" NAME="name">&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE="submit" class="btn" value="Invite"></form></td></td></tr></tr></table><br/><center><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><tr><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><td><input TYPE="submit" value="Back" class="btn"></td></tr></form></table></center>';
}


//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show guild page
if($action == 'acceptinvite')
{
	//set rights in guild
	$guild_id = (int) $_REQUEST['guild'];
	$name = $_REQUEST['name'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t accept invitations.';
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}

	if($_REQUEST['todo'] == 'save')
	{
		if(!check_name($name))
			$guild_errors[] = 'Invalid name format.';
		if(empty($guild_errors))
		{
			$player = new Player();
			$player->find($name);
			if(!$player->isLoaded())
			{
				$guild_errors[] = 'Player with name <b>'.htmlspecialchars($name).'</b> doesn\'t exist.';
			}
			else
			{
				$rank_of_player = $player->getrank();
				if(!empty($rank_of_player))
				{
					$guild_errors[] = 'Character with name <b>'.htmlspecialchars($name).'</b> is already in guild. You must leave guild before you join other guild.';
				}
			}
		}
	}
	if($_REQUEST['todo'] == 'save')
	{
		if(empty($guild_errors))
		{
			$is_invited = FALSE;
			$invited_list = $guild->listInvites();
			if(count($invited_list) > 0)
			{
				foreach($invited_list as $invited)
				{
					if($invited->getName() == $player->getName())
					{
						$is_invited = trUE;
					}
				}
			}
			if(!$is_invited)
			{
				$guild_errors[] = 'Character '.htmlspecialchars($player->getName()).' isn\'t invited to guild <b>'.htmlspecialchars($guild->getName()).'</b>.';
			}
		}
	}
	else
	{
		if(empty($guild_errors))
		{
			$acc_invited = FALSE;
			$account_players = $account_logged->getPlayers();
			$invited_list = $guild->listInvites();
			if(count($invited_list) > 0)
			{
				foreach($invited_list as $invited)
				{
					foreach($account_players as $player_from_acc)
					{
						if($invited->getName() == $player_from_acc->getName())
						{
							$acc_invited = trUE;
							$list_of_invited_players[] = $player_from_acc->getName();
						}
					}
				}
			}
		}
		if(!$acc_invited)
		{
			$guild_errors[] = 'Any character from your account isn\'t invited to <b>'.htmlspecialchars($guild->getName()).'</b>.';
		}
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
		{
			$main_content .= '<li>'.$guild_error;
		}
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
	{
		if($_REQUEST['todo'] == 'save')
		{
			$guild->acceptInvite($player);
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Accept invitation</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Player with name <b>'.htmlspecialchars($player->getName()).'</b> has been added to guild <b>'.htmlspecialchars($guild->getName()).'</b>.</td></tr></table><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
		}
		else
		{
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Accept invitation</B></td></tr>';
			$main_content .= '<tr BGCOLOR='.$config['site']['lightborder'].'><td WIDTH=100%>Select character to join guild:</td></tr>';
			$main_content .= '<tr BGCOLOR='.$config['site']['darkborder'].'><td>
			<form action="?subtopic=guilds&action=acceptinvite&guild='.$guild_id.'&todo=save" method="post">';
			sort($list_of_invited_players);
			foreach($list_of_invited_players as $invited_player_from_list)
			{
				$main_content .= '<input style="width: 15%" type="radio" name="name" value="'.htmlspecialchars($invited_player_from_list).'" />'.htmlspecialchars($invited_player_from_list).'<br>';
			}
			$main_content .= '<br><input TYPE="submit" value="Submit" class="btn"></form></td></tr></table><br/><center><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><tr><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><td><input TYPE="submit" value="Back" class="btn"></td></tr></form></table></center>';
		}
	}
}


//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show guild page
if($action == 'kickplayer')
{
	//set rights in guild
	$guild_id = (int) $_REQUEST['guild'];
	$name = $_REQUEST['name'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t kick characters.';
	if(!check_name($name))
		$guild_errors[] = 'Invalid name format.';
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		$rank_list = $guild->getGuildRanksList();
		$guild_leader = FALSE;
		$guild_vice = FALSE;
		$account_players = $account_logged->getPlayers();
		foreach($account_players as $player)
		{
			$player_rank = $player->getrank();
			if(!empty($player_rank))
			{
				foreach($rank_list as $rank_in_guild)
				{
					if($rank_in_guild->getId() == $player_rank->getId())
					{
						$players_from_account_in_guild[] = $player->getName();
						if($player_rank->getLevel() > 1)
						{
							$guild_vice = trUE;
							$level_in_guild = $player_rank->getLevel();
						}
						if($guild->getOwner()->getId() == $player->getId())
						{
							$guild_vice = trUE;
							$guild_leader = trUE;
						}
					}
				}
			}
		}
	}
	if(empty($guild_errors))
	{
		if(!$guild_leader && $level_in_guild < 3)
		{
			$guild_errors[] = 'You are not a leader of guild <b>'.htmlspecialchars($guild->getName()).'</b>. You can\'t kick players.';
		}
	}
	if(empty($guild_errors))
	{
		$player = new Player();
		$player->find($name);
		if(!$player->isLoaded())
		{
			$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> doesn\'t exist.';
		}
		else
		{
			if($player->getrank()->getGuild()->getName() != $guild->getName())
			{
				$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> isn\'t from your guild.';
			}
		}
	}
	if(empty($guild_errors))
	{
		if($player->getrank()->getLevel() >= $level_in_guild && !$guild_leader)
		{
			$guild_errors[] = 'You can\'t kick character <b>'.htmlspecialchars($name).'</b>. Too high access level.';
		}
	}
	if(empty($guild_errors))
	{
		if($guild->getOwner()->getName() == $player->getName())
		{
			$guild_errors[] = 'It\'s not possible to kick guild owner!';
		}
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
		{
			$main_content .= '<li>'.$guild_error;
		}
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
		if($_REQUEST['todo'] == 'save')
		{
			$player->setrank();
			$player->save();
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Kick player</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Player with name <b>'.htmlspecialchars($player->getName()).'</b> has been kicked from your guild.</td></tr></table><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
		}
		else
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Kick player</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Are you sure you want to kick player with name <b>'.htmlspecialchars($player->getName()).'</b> from your guild?</td></tr></table><br/><center><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><tr><form ACTION="?subtopic=guilds&action=kickplayer&guild='.$guild_id.'&name='.urlencode($player->getName()).'&todo=save" method=post><td align="right" width="50%"><input TYPE="submit" value="Submit" class="btn">&nbsp;&nbsp;</td></form><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><td>&nbsp;&nbsp;<input TYPE="submit" value="Back" class="btn"></td></tr></form></table></center>';
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//show guild page
if($action == 'leaveguild')
{
	//set rights in guild
	$guild_id = (int) $_REQUEST['guild'];
	$name = $_REQUEST['name'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t leave guild.';
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}

	if(empty($guild_errors))
	{
		$guild_owner_id = $guild->getOwner()->getId();
		if($_REQUEST['todo'] == 'save')
		{
			if(!check_name($name))
				$guild_errors[] = 'Invalid name format.';
			if(empty($guild_errors))
			{
				$player = new Player();
				$player->find($name);
				if(!$player->isLoaded())
					$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> doesn\'t exist.';
				else
					if($player->getAccount()->getId() != $account_logged->getId())
						$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> isn\'t from your account!';
			}
			if(empty($guild_errors))
			{
				$player_loaded_rank = $player->getrank();
				if(!empty($player_loaded_rank) && $player_loaded_rank->isLoaded())
				{
					if($player_loaded_rank->getGuild()->getId() != $guild->getId())
						$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> isn\'t from guild <b>'.htmlspecialchars($guild->getName()).'</b>.';
				}
				else
					$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> isn\'t in any guild.';
			}
			if(empty($guild_errors))
				if($guild_owner_id == $player->getId())
					$guild_errors[] = 'You can\'t leave guild. You are an owner of guild.';
		}
		else
		{
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player_fac)
			{
				$player_rank = $player_fac->getrank();
				if(!empty($player_rank))
					if($player_rank->getGuild()->getId() == $guild->getId())
						if($guild_owner_id != $player_fac->getId())
							$array_of_player_ig[] = $player_fac->getName();
			}
		}
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
	}
	else
	{
		if($_REQUEST['todo'] == 'save')
		{
			$player->setrank();
			$player->save();
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Leave guild</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%>Player with name <b>'.htmlspecialchars($player->getName()).'</b> leaved guild <b>'.htmlspecialchars($guild->getName()).'</b>.</td></tr></table><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><tr><td><center><input TYPE="submit" value="Back" class="btn"></center></td></tr></form></table>';
		}
		else
		{
			$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Leave guild</B></td></tr>';
			if(count($array_of_player_ig) > 0)
			{
				$main_content .= '<tr BGCOLOR='.$config['site']['lightborder'].'><td WIDTH=100%>Select character to leave guild:</td></tr>';
				$main_content .= '<tr BGCOLOR='.$config['site']['darkborder'].'><td>
				<form action="?subtopic=guilds&action=leaveguild&guild='.$guild_id.'&todo=save" method="post">';
				sort($array_of_player_ig);
				foreach($array_of_player_ig as $player_to_leave)
					$main_content .= '<input type="radio" name="name" value="'.htmlspecialchars($player_to_leave).'" />'.htmlspecialchars($player_to_leave).'<br>';
				$main_content .= '</td></tr><br></table>';
			}
			else
				$main_content .= '<tr BGCOLOR='.$config['site']['lightborder'].'><td WIDTH=100%>Any of your characters can\'t leave guild.</td></tr>';
			$main_content .= '<table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><tr>';
			if(count($array_of_player_ig) > 0)
				$main_content .= '<td width="130" valign="top"><input TYPE="submit" value="Submit" class="btn"></form></td>';
			$main_content .= '<td><form ACTION="?subtopic=guilds&action=show&guild='.$guild_id.'" method=post><input TYPE="submit" value="Back" class="btn"></form></td></tr></table>';
		}
	}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//create guild
if($action == 'createguild')
{
	$new_guild_name = trim($_REQUEST['guild']);
	$name = $_REQUEST['name'];
	$todo = $_REQUEST['todo'];
	if(!$logged)
		$guild_errors[] = 'You are not logged in. You can\'t create guild.';
	if(empty($guild_errors)) 
	{
		$account_players = $account_logged->getPlayers();
		foreach($account_players as $player)
		{
			$player_rank = $player->getrank();
			if(empty($player_rank))
				if($player->getLevel() >= $config['site']['guild_need_level'])
					if(!$config['site']['guild_need_pacc'] || $account_logged->isPremium())
						$array_of_player_nig[] = $player->getName();
		}
	}

	if(count($array_of_player_nig) == 0)
		$guild_errors[] = 'On your account all characters are in guilds or have too low level to create new guild.';
	if($todo == 'save')
	{
		if(!check_guild_name($new_guild_name))
		{
			$guild_errors[] = 'Invalid guild name format.';
		}
		if(!check_name($name))
		{
			$guild_errors[] = 'Invalid character name format.';
		}
		if(empty($guild_errors))
		{
			$player = new Player();
			$player->find($name);
			if(!$player->isLoaded())
				$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> doesn\'t exist.';
		}
		if(empty($guild_errors))
		{
			$guild = new Guild();
			$guild->find($new_guild_name);
			if($guild->isLoaded())
				$guild_errors[] = 'Guild <b>'.htmlspecialchars($new_guild_name).'</b> already exist. Select other name.';
		}
		if(empty($guild_errors))
		{
			$bad_char = trUE;
			foreach($array_of_player_nig as $nick_from_list)
				if($nick_from_list == $player->getName())
					$bad_char = FALSE;
			if($bad_char)
				$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> isn\'t on your account or is already in guild.';
		}
		if(empty($guild_errors))
		{
			if($player->getLevel() < $config['site']['guild_need_level'])
				$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> has too low level. To create guild you need character with level <b>'.$config['site']['guild_need_level'].'</b>.';
			if($config['site']['guild_need_pacc'] && !$account_logged->isPremium())
				$guild_errors[] = 'Character <b>'.htmlspecialchars($name).'</b> is on FREE account. To create guild you need PREMIUM account.';
		}
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
		unset($todo);
	}

	if($todo == 'save')
	{
		$new_guild = new Guild();
		$new_guild->setCreationData(time());
		$new_guild->setName($new_guild_name);
		$new_guild->setOwner($player);
		$new_guild->setdescription('New guild. Leader must edit this text :)');
		$new_guild->setWorldID($player->getWorld());
		$new_guild->setGuildLogo('image/gif', Website::getFileContents('./images/default_guild_logo.gif'));
		
		$new_guild->save();
		$ranks = $new_guild->getGuildRanksList(true);
		foreach($ranks as $rank)
			if($rank->getLevel() == 3)
			{
				$player->setrank($rank);
				$player->save();
			}
		$main_content .= '<table BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><tr BGCOLOR='.$config['site']['vdarkborder'].'><td CLASS=white><B>Create guild</B></td></tr><tr BGCOLOR='.$config['site']['darkborder'].'><td WIDTH=100%><b>Congratulations!</b><br/>You have created guild <b>'.htmlspecialchars($new_guild_name).'</b>. <b>'.htmlspecialchars($player->getName()).'</b> is leader of this guild. Now you can invite players, change picture, description and motd of guild. Press submit to open guild manager.</td></tr></table><br/><table BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=100%><form ACTION="?subtopic=guilds&action=show&guild='.$new_guild->getId().'" method=post><tr><td><center><input TYPE="submit" value="Visit" class="btn"></center></td></tr></form></table>';
	}
	else
	{
		$main_content .= 'To play on '.$config['server']['serverName'].' you need an account. 
		All you have to do to create your new account is to enter your email address, password to new account, verification code from picture and to agree to the terms presented below. 
		If you have done so, your account number, password and e-mail address will be shown on the following page and your account and password will be sent 
		to your email address along with further instructions.<BR><BR>
		<form ACTION="?subtopic=guilds&action=createguild&todo=save" method=post>
		<table WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
		<tr><td CLASS=white><B>Create an '.htmlspecialchars($config['server']['serverName']).' Guild</B></td></tr>
		<tr><td><table BORDER=0 CELLSPACING=8 CELLPADDING=0>
		  <tr><td>
		    <table BORDER=0 CELLSPACING=5 CELLPADDING=0>';
		$main_content .= '<tr><td width="150" valign="top"><B>Leader: </B></td><td><SELECT name="name">';
		if(count($array_of_player_nig) > 0)
		{
			sort($array_of_player_nig);
			foreach($array_of_player_nig as $nick)
				$main_content .= '<OPTION>'.htmlspecialchars($nick).'</OPTION>';
		}
		$main_content .= '</SELECT><BR><font size="1" face="verdana,arial,helvetica">(Name of leader of new guild.)</font></td></tr>
			<tr><td width="150" valign="top"><B>Guild name: </B></td><td><input NAME="guild" VALUE="" SIZE=30 MAXLENGTH=50><BR><font size="1" face="verdana,arial,helvetica">(Here write name of your new guild.)</font></td></tr>
			</table>
		  </td></tr>
		</table></td></tr>
		</table>
		<BR>
		<table BORDER=0 WIDTH=100%>
		  <tr><td ALIGN=center VALIGN=top>
		    <input type="submit" value="submit" class="btn">
		    </form>
		  </td><td ALIGN=center>
		    <form  ACTION="?subtopic=guilds" method=post>
		    <input type="submit" value="Cancel" class="btn">
		    </form>
		  </td></tr>
		</table>
		</td>
		<td><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></td>
		</tr>
		</table>';
	}
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'manager')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild_leader_char->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				$main_content .= '<center><h2>Welcome to guild manager!</h2></center>Here you can change names of ranks, delete and add ranks, pass leadership to other guild member and delete guild.';
				$main_content .= '<br/><br/><table style="clear:both" class="table tableSpacing">
				<tr><th width="170"><b>Option</b></th><th><b>Description</b></th></tr>
				<tr><td width="170"><b><a href="?subtopic=guilds&guild='.$guild_id.'&action=passleadership">Pass Leadership</a></b></td><td><b>Pass leadership of guild to other guild member.</b></td></tr>
				<tr><td width="170"><b><a href="?subtopic=guilds&guild='.$guild_id.'&action=deleteguild">Delete Guild</a></b></td><td><b>Delete guild, kick all members.</b></td></tr>
				<tr><td width="170"><b><a href="?subtopic=guilds&guild='.$guild_id.'&action=changedescription">Change Description</a></b></td><td><b>Change description of guild.</b></td></tr>
				<tr><td width="170"><b><a href="?subtopic=guilds&guild='.$guild_id.'&action=changemotd">Change Motd</a></b></td><td><b>Change Motd of guild.</b></td></tr>
				<tr><td width="170"><b><a href="?subtopic=guilds&guild='.$guild_id.'&action=changelogo">Change guild logo</a></b></td><td><b>Upload new guild logo.</b></td></tr>
				</table>';
				
				$main_content .= '<div class="loginForm">
				<h3>New rank name:</h3>
				<form action="?subtopic=guilds&guild='.$guild_id.'&action=addrank" method="post">
					<input type="text" name="rank_name" size="20" style="width: 75%">
					<input type="submit" value="Add" class="btn">
				</form></div>';
				
				
				$main_content .= '<h3>Change rank names and levels</h3>
				<form action="?subtopic=guilds&action=saveranks&guild='.$guild_id.'" method=POST>
				<table class="table" style="clear:both">
				<tr>
					<th rowspan="2" width="120">Delete Rank</th>
					<th rowspan="2" width="300">Name</th>
				</tr>
				<tr>
					<td align="center" bgcolor="red"><font color="white"><b>Leader (3)</b></font></td>
					<td align="center" bgcolor="yellow"><font color="black"><b>Vice (2)</b></font></td>
					<td align="center" bgcolor="green"><font color="white"><b>Member (1)</b></font></td>
				</tr>';
				
				foreach($rank_list as $rank)
				{
					$main_content .= '<tr><td align="center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=deleterank&rankid='.urlencode($rank->getId()).'" border="0">Delete</a></td><td><input type="text" name="'.htmlspecialchars($rank->getId()).'_name" value="'.htmlspecialchars($rank->getName()).'" size="35"></td><td align="center"><input type="radio" name="'.$rank->getId().'_level" value="3"';
					if($rank->getLevel() == 3)
						$main_content .= ' checked="checked"';
					$main_content .= ' /></td><td align="center"><input type="radio" name="'.$rank->getId().'_level" value="2"';
					if($rank->getLevel() == 2)
						$main_content .= ' checked="checked"';
					$main_content .= ' /></td><td align="center"><input type="radio" name="'.$rank->getId().'_level" value="1"';
					if($rank->getLevel() == 1)
						$main_content .= ' checked="checked"';
					$main_content .= ' /></td></tr>';
				}
				$main_content .= '<tr><td>&nbsp;</td><td>&nbsp;</td><td colspan="3" align="center"><input type="submit" value="Save All" class="btn"></td></tr></table></form>';
				$main_content .= '<h3>Ranks info:</h3><b>0. Owner of guild</b> - it\'s highest rank, only one player in guild may has this rank. Player with this rank can:
				<li>Invite/Cancel Invitation/Kick Player from guild
				<li>Change ranks of all players in guild
				<li>Delete guild or pass leadership to other guild member
				<li>Change names, levels(leader,vice,member), add and delete ranks
				<li>Change MOtd, logo and description of guild<hr>
				<b>3. Leader</b> - it\'s second rank in guild. Player with this rank can:
				<li>Invite/Cancel Invitation/Kick Player from guild (only with lower rank than his)
				<li>Change ranks of players with lower rank level ("vice leader", "member") in guild<hr>
				<b>2. Vice Leader</b> - it\'s third rank in guild. Player with this rank can:
				<li>Invite/Cancel Invitation
				<li>Change ranks of players with lower rank level ("member") in guild<hr>
				<b>1. Member</b> - it\'s lowest rank in guild. Player with this rank can:
				<li>Be a member of guild';
				
				$main_content .= '<br/><div class="text-center"><a href="?subtopic=guilds&action=show&guild='.$guild_id.'" class="btn">Back</a></div>';
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
	}
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'changelogo')
{
	$guild_id = (int) $_REQUEST['guild'];

	$guild = new Guild();
	$guild->load($guild_id);
	if(!$guild->isLoaded())
		$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild_leader_char->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				$max_image_size_b = $config['site']['guild_image_size_kb'] * 1024;
				if($_REQUEST['todo'] == 'save')
				{
					$file = $_FILES['newlogo'];
					switch($file['error'])
					{
						case UPLOAD_ERR_OK:
							break; // all ok
						case UPLOAD_ERR_INI_SIZE:
						case UPLOAD_ERR_form_SIZE:
							$upload_errors[] = 'Image is too large';
							break;
						case UPLOAD_ERR_PARTIAL:
							$upload_errors[] = 'Image was only partially uploaded';
							break;
						case UPLOAD_ERR_NO_FILE:
							$upload_errors[] = 'No image was uploaded';
							break;
						case UPLOAD_ERR_NO_TMP_DIR:
							$upload_errors[] = 'Upload folder not found';
							break;
						case UPLOAD_ERR_CANT_WRITE:
							$upload_errors[] = 'Unable to write uploaded file';
							break;
						case UPLOAD_ERR_EXTENSION:
							$upload_errors[] =  'Upload failed due to extension';
							break;
						default:
							$upload_errors[] =  'Unknown error';
					}
					if(is_uploaded_file($file['tmp_name']))
					{
						if($file['size'] > $max_image_size_b)
							$upload_errors[] = 'Uploaded image is too big. Size: <b>'.$file['size'].' bytes</b>, Max. size: <b>'.$max_image_size_b.' bytes</b>.';
						$info = getimagesize($file['tmp_name']);
						if(!$info)
							$upload_errors[] = 'Uploaded file is not an image!';
					}
					else
						$upload_errors[] = 'You didn\'t send file or file is too big. Limit: <b>'.$config['site']['guild_image_size_kb'].' KB</b>.';
					//show errors or save file
					if(!empty($upload_errors))
					{
						$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
						foreach($upload_errors as $guild_error)
							$main_content .= '<li>'.$guild_error;
						$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
					}
					else
					{
						$guild->setGuildLogo($info['mime'], file_get_contents($file['tmp_name']));
						$guild->save();
					}
				}
				$main_content .= '<center><h2>Change guild logo</h2></center>Here you can change logo of your guild.<BR>Current logo: <img src="' . $guild->getGuildLogoLink() . '" HEIGHT="64" WIDTH="64"><BR><BR>';
				$main_content .= '<form enctype="multipart/form-data" action="?subtopic=guilds&guild='.$guild_id.'&action=changelogo" method="POST">
				<input type="hidden" name="todo" value="save" />
				<input type="hidden" name="MAX_FILE_SIZE" value="'.$max_image_size_b.'" />
				    Select new logo: <input name="newlogo" type="file" />
				    <input type="submit" value="Send new logo" /></form>Only <b>jpg, gif, png, bmp</b> pictures. Max. size: <b>'.$config['site']['guild_image_size_kb'].' KB</b><br>';
				$main_content .= '<br><center><form action="?subtopic=guilds&guild='.$guild_id.'&action=manager" method=post>
				<input class="btn" type="submit" value="Back"></form></center>';

			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
		$main_content .= '<br/><center><form action="?subtopic=guilds" method=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
	}
}


//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'deleterank')
{
	$guild_id = (int) $_REQUEST['guild'];
	$rank_to_delete = (int) $_REQUEST['rankid'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild->getOwner()->getId() == $player->getId())
				{
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				$rank = new GuildRank();
				$rank->load($rank_to_delete);
				if(!$rank->isLoaded())
					$guild_errors2[] = 'Rank with ID '.$rank_to_delete.' doesn\'t exist.';
				else
				{
					if($rank->getGuild()->getId() != $guild->getId())
						$guild_errors2[] = 'Rank with ID '.$rank_to_delete.' isn\'t from your guild.';
					else
					{
						if(count($rank_list) < 2)
							$guild_errors2[] = 'You have only 1 rank in your guild. You can\'t delete this rank.';
						else
						{
							$players_with_rank = $rank->getPlayersList();
							$players_with_rank_number = count($players_with_rank);
							if($players_with_rank_number > 0)
							{
								foreach($rank_list as $checkrank)
									if($checkrank->getId() != $rank->getId())
										if($checkrank->getLevel() <= $rank->getLevel())
											$new_rank = $checkrank;
								if(empty($new_rank))
								{
									$new_rank = new GuildRank();
									$new_rank->setGuild($guild);
									$new_rank->setLevel($rank->getLevel());
									$new_rank->setName('New Rank level '.$rank->getLevel());
									$new_rank->save();
								}
								foreach($players_with_rank as $player_in_guild)
								{
									$player_in_guild->setrank($new_rank);
									$player_in_guild->save();
								}
							}
							$rank->delete();
							$saved = trUE;
						}
					}
				}
				if($saved)
					$main_content .= 'Rank has been deleted. Players with this rank has now other rank.<br>';
				else
				{
					$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
					foreach($guild_errors2 as $guild_error) 
						$main_content .= '<li>'.$guild_error.'</li>';
					$main_content .= '</ul>';
				}
				//back button
				$main_content .= '<br><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
		
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		
		$main_content .= '</ul>';
		$main_content .= '<br><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
	}
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'addrank')
{
	$guild_id = (int) $_REQUEST['guild'];
	$ranknew = $_REQUEST['rank_name'];
	if(empty($guild_errors))
	{
		if(!check_rank_name($ranknew))
			$guild_errors[] = 'Invalid rank name format.';
		if(!$logged)
			$guild_errors[] = 'You are not logged.';
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
		if(empty($guild_errors))
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild_leader_char->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				$new_rank = new GuildRank();
				$new_rank->setGuild($guild);
				$new_rank->setLevel(1);
				$new_rank->setName($ranknew);
				$new_rank->save();
				header("Location: ?subtopic=guilds&guild=".$guild_id."&action=manager");
				$main_content .= 'New rank added. Redirecting...';
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		if(!empty($guild_errors))
		{
			$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
			foreach($guild_errors as $guild_error)
				$main_content .= '<li>'.$guild_error.'</li>';
			$main_content .= '</ul>';
			
			$main_content .= '<br><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
		}
	}
	else
		if(!empty($guild_errors))
		{
			$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
			foreach($guild_errors as $guild_error)
				$main_content .= '<li>'.$guild_error.'</li>';
			$main_content .= '</ul>';
			
			$main_content .= '<br><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
		}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'changedescription')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild->getOwner()->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				if($_REQUEST['todo'] == 'save')
				{
					$description = htmlspecialchars(substr(trim($_REQUEST['description']),0,$config['site']['guild_description_chars_limit']));
					$guild->set('description', $description);
					$guild->save();
					$saved = trUE;
				}
				$main_content .= '<center><h2>Change guild description</h2></center>';
				if($saved)
					$main_content .= '<center><font color="red" size="3"><b>CHANGES HAS BEEN SAVED!</b></font></center><br>';
				$main_content .= 'Here you can change description of your guild.<BR>';
				$main_content .= '<form enctype="multipart/form-data" action="?subtopic=guilds&guild='.$guild_id.'&action=changedescription" method="POST">
				<input type="hidden" name="todo" value="save" />
				    <textarea name="description" cols="60" rows="'.($config['site']['guild_description_lines_limit'] - 1).'">'.$guild->getdescription().'</textarea><br>
				    (max. '.$config['site']['guild_description_lines_limit'].' lines, max. '.$config['site']['guild_description_chars_limit'].' chars) <input type="submit" value="Save" class="btn" /></form><br>';
				$main_content .= '<br><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
		$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</ul>';
			
		$main_content .= '<br><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
	}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'passleadership')
{
	$guild_id = (int) $_REQUEST['guild'];
	$pass_to = trim($_REQUEST['player']);
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($_POST['todo'] == 'save')
		{
			if(!check_name($pass_to))
				$guild_errors2[] = 'Invalid player name format.';
			if(empty($guild_errors2))
			{
				$to_player = new Player();
				$to_player->find($pass_to);
				if(!$to_player->isLoaded())
					$guild_errors2[] = 'Player with name <b>'.htmlspecialchars($pass_to).'</b> doesn\'t exist.';
				if(empty($guild_errors2))
				{
					$to_player_rank = $to_player->getrank();
					if(!empty($to_player_rank))
					{
						$to_player_guild = $to_player_rank->getGuild();
						if($to_player_guild->getId() != $guild->getId())
							$guild_errors2[] = 'Player with name <b>'.htmlspecialchars($to_player->getName()).'</b> isn\'t from your guild.';
					}
					else
						$guild_errors2[] = 'Player with name <b>'.htmlspecialchars($to_player->getName()).'</b> isn\'t from your guild.';
				}
			}
		}
	}
	if(empty($guild_errors) && empty($guild_errors2))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild_leader_char->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				if($_POST['todo'] == 'save')
				{
					$guild->setOwner($to_player);
					$guild->save();
					$saved = trUE;
					$main_content .= '<b>'.htmlspecialchars($to_player->getName()).'</b> is now a Leader of <b>'.htmlspecialchars($guild->getName()).'</b>.<br/><center><a href="?subtopic=guilds&guild='.$guild_id.'&action=show" class="btn">Back</a></center>';
				}
				else
					$main_content .= '<strong>Pass leadership to:</strong><br>
					<form action="?subtopic=guilds&guild='.$guild_id.'&action=passleadership" method=post>
						<input type="hidden" name="todo" value="save">
						<input type="text" name="player" style="width: 75%">
						<input type="submit" value="Save" class="btn">
					</form>
					<br>
					<div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(empty($guild_errors) && !empty($guild_errors2))
	{
		$main_content .= '<b>The Following Errors Have Occurred:</b><ul>';
		foreach($guild_errors2 as $guild_error2)
			$main_content .= '<li>'.$guild_error2.'</li>';
		$main_content .= '</ul><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<b>The Following Errors Have Occurred:</b><ul>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		if(!empty($guild_errors2))
			foreach($guild_errors2 as $guild_error2)
				$main_content .= '<li>'.$guild_error2.'</li>';
				
		$main_content .= '</ul><div class="text-center"><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></div>';
	}
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'deleteguild')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild->getOwner()->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				if($_POST['todo'] == 'save')
				{
					$guild->delete();
					$saved = trUE;
				}
				if($saved)
				{
					$main_content .= '<strong>Guild with ID <b>'.$guild_id.'</b> has been deleted.</strong>';
					$main_content .= '<br><center><a href="?subtopic=guilds" class="btn">Back</a></center>';
				}
				else
				{
					$main_content .= 'Are you sure you want delete guild with ID <b>'.$guild_id.'</b>?<br>
					<form action="?subtopic=guilds&guild='.$guild_id.'&action=deleteguild" method=post>
						<input type="hidden" name="todo" value="save">
						<input type="submit" value="Yes, delete" class="btn">
					</form>';
					
					$main_content .= '<br><center><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></center>';
				}
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<b>The Following Errors Have Occurred:</b><ul>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
			
		$main_content .= '</ul><br><center><a href="?subtopic=guilds&guild='.$guild_id.'&action=manager" class="btn">Back</a></center>';
	}
}


//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'deletebyadmin')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			if($group_id_of_acc_logged >= $config['site']['access_admin_panel'])
			{
				if($_POST['todo'] == 'save')
				{
					$guild->delete();
					$main_content .= '<div class="tableContainer" >  <table class="table1" cellpadding="0" cellspacing="0" >    <div class="CaptionContainer" >      <div class="CaptionInnerContainer" >        <span class="CaptionEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span>        <span class="CaptionEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span>        <span class="CaptionBorderTop" style="background-image:url('.$layout_name.'/images/content/table-headline-border.gif);" ></span>        <span class="CaptionVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></span>        <div class="Text" >Guild Deleted</div>        <span class="CaptionVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></span>        <span class="CaptionBorderBottom" style="background-image:url('.$layout_name.'/images/content/table-headline-border.gif);" ></span>        <span class="CaptionEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span>        <span class="CaptionEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></span>      </div>    </div>    <tr>      <td>        <div class="InnertableContainer" >          <table style="width:100%;" ><tr><td>Guild with ID <b>'.$guild_id.'</b> has been deleted.</td></tr>          </table>        </div>  </table></div></td></tr><br/><center><form action="?subtopic=guilds" method=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
				}
				else
					$main_content .= '<p>Do you really want to delete this guild?</p>
					<form action="?subtopic=guilds&guild='.$guild_id.'&action=deletebyadmin" method="post">
						<input type="hidden" name="todo" value="save">
						<input type="submit" value="Yes, delete" class="btn pull-left">
					</form>
					
					<a href="?subtopic=guilds" class="btn pull-right">Back</a>';
			}
			else
				$guild_errors[] = 'You are not an admin!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t delete guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
		$main_content .= '<br/><center><form action="?subtopic=guilds" method=post><div class="BigButton" style="background-image:url('.$layout_name.'/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" ><div class="BigButtonOver" style="background-image:url('.$layout_name.'/images/buttons/sbutton_over.gif);" ></div><input class="ButtonText" type="image" name="Back" alt="Back" src="'.$layout_name.'/images/buttons/_sbutton_back.gif" ></div></div></form></center>';
	}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'changemotd')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild->getOwner()->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				if($_REQUEST['todo'] == 'save')
				{
					$motd = htmlspecialchars(substr(trim($_REQUEST['motd']),0,$config['site']['guild_motd_chars_limit']));
					$guild->set('motd', $motd);
					$guild->save();
					$saved = trUE;
				}
				$main_content .= '<center><h2>Change guild MOtd</h2></center>';
				if($saved)
					$main_content .= '<center><font color="red" size="3"><b>CHANGES HAS BEEN SAVED!</b></font></center><br>';
				$main_content .= 'Here you can change MOtd (Message of the Day, showed in game!) of your guild.<BR>';
				$main_content .= '<form enctype="multipart/form-data" action="?subtopic=guilds&guild='.$guild_id.'&action=changemotd" method="POST">
				<input type="hidden" name="todo" value="save" />
				    <textarea name="motd" cols="60" rows="3">'.$guild->get('motd').'</textarea><br>
				    (max. '.$config['site']['guild_motd_chars_limit'].' chars) <input type="submit" value="Save MOtd" class="btn" /></form><br>';
				$main_content .= '<br><center><a href="?subtopic=guilds&guild='.$guild_id.'" class="btn">Back</a></center>';
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors))
	{
		$main_content .= '<b>The Following Errors Have Occurred:</b><ul>';
		foreach($guild_errors as $guild_error)
			$main_content .= '<li>'.$guild_error.'</li>';
		$main_content .= '</ul>';
		$main_content .= '<br><center><a href="?subtopic=guilds&guild='.$guild_id.'" class="btn">Back</a></center>';
	}
}

//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'saveranks')
{
	$guild_id = (int) $_REQUEST['guild'];
	if(empty($guild_errors))
	{
		$guild = new Guild();
		$guild->load($guild_id);
		if(!$guild->isLoaded())
			$guild_errors[] = 'Guild with ID <b>'.$guild_id.'</b> doesn\'t exist.';
	}
	if(empty($guild_errors))
	{
		if($logged)
		{
			$guild_leader_char = $guild->getOwner();
			$rank_list = $guild->getGuildRanksList();
			$guild_leader = FALSE;
			$account_players = $account_logged->getPlayers();
			foreach($account_players as $player)
				if($guild_leader_char->getId() == $player->getId())
				{
					$guild_vice = trUE;
					$guild_leader = trUE;
					$level_in_guild = 3;
				}
			if($guild_leader)
			{
				foreach($rank_list as $rank)
				{
					$rank_id = $rank->getId();
					$name = $_REQUEST[$rank_id.'_name'];
					$level = (int) $_REQUEST[$rank_id.'_level'];
					if(check_rank_name($name))
						$rank->setName($name);
					else
						$ranks_errors[] = 'Invalid rank name. Please use only a-Z, 0-9 and spaces. Rank ID <b>'.$rank_id.'</b>.';
					if($level > 0 && $level < 4)
						$rank->setLevel($level);
					else
						$ranks_errors[] = 'Invalid rank level. Contact with admin. Rank ID <b>'.$rank_id.'</b>.';
					$rank->save();
				}
				if(!empty($ranks_errors))
				{
					$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
					foreach($ranks_errors as $guild_error)
						$main_content .= '<li>'.$guild_error.'</li>';
					$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
				}
				else
					header("Location: ?subtopic=guilds&action=manager&guild=".$guild_id);
			}
			else
				$guild_errors[] = 'You are not a leader of guild!';
		}
		else
			$guild_errors[] = 'You are not logged. You can\'t manage guild.';
	}
	if(!empty($guild_errors)) {
	$main_content .= '<div class="SmallBox" >  <div class="MessageContainer" >    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeLeftTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeRightTop" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="ErrorMessage" >      <div class="BoxFrameVerticalLeft" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="BoxFrameVerticalRight" style="background-image:url('.$layout_name.'/images/content/box-frame-vertical.gif);" /></div>      <div class="AttentionSign" style="background-image:url('.$layout_name.'/images/content/attentionsign.gif);" /></div><b>The Following Errors Have Occurred:</b><br/>';
	foreach($guild_errors as $guild_error) {
		$main_content .= '<li>'.$guild_error.'</li>';
	}
	$main_content .= '</div>    <div class="BoxFrameHorizontal" style="background-image:url('.$layout_name.'/images/content/box-frame-horizontal.gif);" /></div>    <div class="BoxFrameEdgeRightBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>    <div class="BoxFrameEdgeLeftBottom" style="background-image:url('.$layout_name.'/images/content/box-frame-edge.gif);" /></div>  </div></div><br>';
	}
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
if($action == 'cleanup_players')
{
	if($logged)
	{
		if($group_id_of_acc_logged >= $config['site']['access_admin_panel'])
		{
			$players_list = new DatabaseList('Player');
		}
		else
			$players_list = $account_logged->getPlayersList();
		if(count($players_list) > 0)
		{
			foreach($players_list as $player)
			{
				$player_rank = $player->getrank();
				if(!empty($player_rank))
				{
					if($player_rank->isLoaded())
					{
						$rank_guild = $player_rank->getGuild();
						if(!$rank_guild->isLoaded())
						{
							$player->setrank();
							$player->setGuildNick();
							$player->save();
							$changed_ranks_of[] = $player->getName();
							$deleted_ranks[] = 'ID: '.$player_rank->getId().' - '.$player_rank->getName();
							$player_rank->delete();
						}
					}
					else
					{
						$player->setrank();
						$player->setGuildNick('');
						$player->save();
						$changed_ranks_of[] = $player->getName();
					}
					
				}
			}
			$main_content .= "<b>Deleted ranks (this ranks guilds doesn't exist [bug fix]):</b>";
			if(!empty($deleted_ranks))
				foreach($deleted_ranks as $rank)
					$main_content .= "<li>".htmlspecialchars($rank);
			$main_content .= "<BR /><BR /><b>Changed ranks of players (rank or guild of rank doesn't exist [bug fix]):</b>";
			if(!empty($changed_ranks_of))
				foreach($changed_ranks_of as $name)
					$main_content .= "<li>".htmlspecialchars($name);
		}
		else
			$main_content .= "0 players found.";
	}
	else
		$main_content .= "You are not logged in.";
	$main_content .= "<center><h3><a href=\"?subtopic=guilds\">BACK</a></h3></center>";
}
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------//-----------------------------------------------------------------------------
	if($action == 'change_nick')
	{
		if($logged)
		{
			$player_n = $_REQUEST['name'];
			$new_nick = $_REQUEST['nick'];
			$player = new Player();
			$player->find($player_n);
			$player_from_account = FALSE;
			if(strlen($new_nick) <= 30)
			{
				if($player->isLoaded())
				{
					$account_players = $account_logged->getPlayersList();
					if(count($account_players))
					{
						foreach($account_players as $acc_player)
						{
							if($acc_player->getId() == $player->getId())
								$player_from_account = trUE;
						}
						if($player_from_account)
						{
							$player->setGuildNick($new_nick);
							$player->save();
							$main_content .= 'Guild nick of player <b>'.htmlspecialchars($player->getName()).'</b> changed to <b>'.htmlspecialchars($new_nick).'</b>.';
							$addtolink = '&action=show&guild='.$player->getrank()->getGuild()->getId();
						}
						else
							$main_content .= 'This player is not from your account.';
					}
					else
						$main_content .= 'This player is not from your account.';
				}
				else
					$main_content .= 'Unknow error occured.';
			}
			else
				$main_content .= 'Too long guild nick. Max. 30 chars, your: '.strlen($new_nick);
		}
		else
			$main_content .= 'You are not logged.';
		$main_content .= '<center><h3><a href="?subtopic=guilds'.$addtolink.'" class="btn">BACK</a></h3></center>';
	}