<?php
if(!defined('INITIALIZED'))
    exit;

$changeNameCost = 6;
$main_content .= '
        <section class="col-md-12 second">
        <div class="col-md-9 character">
            <div class="row">
                <div class="col-md-12 account-managment">
                    <p>Change Name</p>
                    
                </div>
            </div>


<div class="col-md-11 your_characters">
    <div class="info_header">
        <p>Change Name</p>
    </div>
                <div class="row"><br>
<font color="white">

            ';


if($logged)
{
    if($account_logged->getCustomField('premium_points') >= $changeNameCost)
    {
        if($action == "")
        {
            echo '<div class="text-center" style="color:red;font-weight:bold">CHANGE NAME COSTS ' . $changeNameCost . ' PREMIUM POINTS!</div><br />';
            echo '<form action="" mathod="post"><table class="table table-striped">';
            echo '<input type="hidden" name="subtopic" value="changename">';
            echo '<input type="hidden" name="action" value="change">';
            echo '<tr><td><b>Select player<br>
            <img src="http://media.dbns.eu/player.png" heght="22" width="22"></br>
             </b></td><td><select name="player_id">';
            $account_players = $account_logged->getPlayersList();
            foreach($account_players as $player)
            {
                echo '<option value="' . $player->getID() . '">' . htmlspecialchars($player->getName()) . '</option>';
            }
            echo '</select></td></tr>';
            echo '<tr><td><b>New name </b><br>
            <img src="http://media.dbns.eu/dane.png" heght="22" width="22"></br></td><td><font color="gold"><input type="text" name="new_name" value=""></font> </td></tr>';
            echo '<tr class="text-center">
            <td colspan="2"><input type="submit" value="Change name" class="btn edit-character"></td>
            </tr>';
            echo '</table></form>';
        }
        elseif($action == "change")
        {
            $newchar_errors = array();
            $newchar_name = ucwords(strtolower(trim($_REQUEST['new_name'])));
            if(empty($newchar_name))
                $newchar_errors[] = 'Please enter a new name for your character!';
            if(!check_name_new_char($newchar_name))
                $newchar_errors[] = 'This name contains invalid letters, words or format. Please use only a-Z, - , \' and space.';
            $check_name_in_database = new Player();
            $check_name_in_database->find($newchar_name);
            if($check_name_in_database->isLoaded())
                $newchar_errors[] = 'This name is already used. Please choose another name!';

            $charToEdit = new Player($_REQUEST['player_id']);
            if(!$charToEdit->isLoaded())
                $newchar_errors[] = 'This player does not exist.';
            if($charToEdit->isOnline())
                $newchar_errors[] = 'This player is ONLINE. Logout first.';
            elseif($account_logged->getID() != $charToEdit->getAccountID())
                $newchar_errors[] = 'This player is not on your account.';

            if(empty($newchar_errors))
            {
                echo 'Name of character <b>' . htmlspecialchars($charToEdit->getName()) . '</b> changed to <b>' . htmlspecialchars($newchar_name) . '</b>';
                $charToEdit->setName($newchar_name);
                $charToEdit->save();
                $account_logged->setCustomField('premium_points', $account_logged->getCustomField('premium_points') - $changeNameCost);
            }
            else
            {
                echo 'Some errors occured:<br />';
                foreach($newchar_errors as $e)
                {
                    echo '<li>' . $e . '</li>';
                }
                echo '<br /><div class="text-center"><a href="?subtopic=changename" class="btn"><b>BACK</b></a></div>';
            }
        }
    }
    else
        echo 'You don\'t have premium points. You need ' . $changeNameCost . '.';
}
else
    echo 'You must login first.<br><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Login</a></div>';

