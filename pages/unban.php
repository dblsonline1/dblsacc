<?php
$points_for_unban = 22;
$main_content .= '
        <section class="col-md-12 second">
        <div class="col-md-9 character">
            <div class="row">
                <div class="col-md-12 account-managment">
                    <p>Unban</p>
                    
                </div>
            </div>


<div class="col-md-11 your_characters">
    <div class="info_header">
        <p>Buy Unban</p>
    </div>
                <div class="row"><br>
<font color="white">

            ';


if($logged)
{
    $pp = $account_logged->getPremiumPoints();
    if($pp >= $points_for_unban)
    {
        if($action == 'unban')
        {
            if(isset($_POST['char_name']))
            {
                $player = new Player();
                $player->find($_POST['char_name']);
                if($player->isLoaded())
                {
                    $account = $player->getAccount();
                    if($account->isLoaded())
                    {
                        $account_bans = $SQL->query('SELECT `id` FROM `bans` WHERE `value` = ' . $account->getId() . ' AND `active` = 1 AND (`type` = 3 OR `type` = 5)')->fetchAll();
                        $remove = false;
                        $account_players = $account->getPlayersList();
                        if(empty($account_bans))
                        {
                            if($account_players->count() > 0)
                            {
                                foreach($account_players as $player)
                                {
                                    $player_bans = $SQL->query('SELECT `id` FROM `bans` WHERE `value` = ' . $player->getId() . ' AND `active` = 1 AND (`type` = 2 OR `type` = 3 OR `type` = 5)')->fetchAll();
                                    if(!empty($player_bans))
                                        $remove = true;
                                }
                            }
                        }
                        else
                            $remove = true;
                        if($remove)
                        {
                            if($account_players->count() > 0)
                                foreach($account_players as $player)
                                    $SQL->query('UPDATE `bans` SET `active` = 0 WHERE `value` = ' . $player->getId() . '  AND (`type` = 2 OR `type` = 5)');
                            $SQL->query('UPDATE `bans` SET `active` = 0 WHERE `value` = ' . $account->getId() . '  AND (`type` = 3 OR `type` = 5)');
                            $account_logged->setPremiumPoints($pp - $points_for_unban);
                            $account_logged->save();
                            $main_content .= 'Account has been unbanned.';
                        }
                        else
                            $main_content .= 'This account is not banned.';
                    }
                    else
                        $main_content .= 'Account of this player doesn\'t exist.';
                }
                else
                    $main_content .= 'Player ' . htmlspecialchars($_POST['char_name']) . ' doesn\'t exist.';
            }
            else
                $main_content .= 'No character selected.';
            $main_content .= '<br><br><div class="text-center"><a href="?subtopic=unban" class="btn">BACK</a></div>';
        }
        else
        {
            $main_content .= '<h4 class="red text-center">Unban cost ' . $points_for_unban . ' premium points!</h4><br />';
            $main_content .= 'Write nick of banned player [his account and all characters will be unbanned] that you want unban:<br><br><form action="?" method="post"><input type="hidden" name="action" value="unban" /><input type="hidden" name="subtopic" value="unban" />';
            $main_content .= '<font color="gold"><input type="text" name="char_name" value=""></font><br><br>
    <div class="col-md-3 buttons">
    <input type="submit" value="Unban" class="btn change-password">
    </div><br>
            </form>';
        }
    }
    else
        $main_content .= 'You need ' . $points_for_unban . ' premium points or more to unban.';
}
else
    $main_content .= '<b>Please login first.</b><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Login</a></div>';
$main_content .= '           </font>     </div>              
</div>';
?>

