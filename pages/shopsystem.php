<?php
if(!defined('INITIALIZED'))
    exit;
 
if($config['site']['shop_system'])
{
    if($logged)
    {
        $user_premium_points = $account_logged->getCustomField('premium_points');
    }
    else
    {
        $user_premium_points = 'Login first';
    }
    function getItemByID($id)
    {
        $id = (int) $id;
        $SQL = $GLOBALS['SQL'];
        $data = $SQL->query('SELECT * FROM '.$SQL->tableName('z_shop_offer').' WHERE '.$SQL->fieldName('id').' = '.$SQL->quote($id).';')->fetch();
        if($data['offer_type'] == 'item')
        {
            $offer['id'] = $data['id'];
            $offer['type'] = $data['offer_type'];
            $offer['item_id'] = $data['itemid1'];
            $offer['item_count'] = $data['count1'];
            $offer['points'] = $data['points'];
            $offer['description'] = $data['offer_description'];
            $offer['name'] = $data['offer_name'];
        }
        elseif($data['offer_type'] == 'container')
        {
            $offer['id'] = $data['id'];
            $offer['type'] = $data['offer_type'];
            $offer['container_id'] = $data['itemid1'];
            $offer['container_count'] = $data['count1'];
            $offer['item_id'] = $data['itemid2'];
            $offer['item_count'] = $data['count2'];
            $offer['points'] = $data['points'];
            $offer['description'] = $data['offer_description'];
            $offer['name'] = $data['offer_name'];
        }
        return $offer;
    }
 
    function getOfferArray()
    {
        $offer_list = $GLOBALS['SQL']->query('SELECT * FROM '.$GLOBALS['SQL']->tableName('z_shop_offer').';');
        $i_item = 0;
        $i_container = 0;
        while($data = $offer_list->fetch())
        {
            if($data['offer_type'] == 'item')
            {
                $offer_array['item'][$i_item]['id'] = $data['id'];
                $offer_array['item'][$i_item]['item_id'] = $data['itemid1'];
                $offer_array['item'][$i_item]['item_count'] = $data['count1'];
                $offer_array['item'][$i_item]['points'] = $data['points'];
                $offer_array['item'][$i_item]['description'] = $data['offer_description'];
                $offer_array['item'][$i_item]['name'] = $data['offer_name'];
                $i_item++;
            }
            elseif($data['offer_type'] == 'container')
            {
                $offer_array['container'][$i_container]['id'] = $data['id'];
                $offer_array['container'][$i_container]['container_id'] = $data['itemid1'];
                $offer_array['container'][$i_container]['container_count'] = $data['count1'];
                $offer_array['container'][$i_container]['item_id'] = $data['itemid2'];
                $offer_array['container'][$i_container]['item_count'] = $data['count2'];
                $offer_array['container'][$i_container]['points'] = $data['points'];
                $offer_array['container'][$i_container]['description'] = $data['offer_description'];
                $offer_array['container'][$i_container]['name'] = $data['offer_name'];
                $i_container++;
            }
        }
        return $offer_array;
    }
    if(($action == '') or ($action == 'item') or ($action == 'container'))
    {
        unset($_SESSION['viewed_confirmation_page']);
        $offer_list = getOfferArray();
 
        if(empty($action))
        {
            if(count($offer_list['item']) > 0)
                $action = 'item';
            elseif(count($offer_list['container']) > 0)
                $action = 'container';
        }
 
        function selectcolor($value)
        {
            if($GLOBALS['action'] == $value)
                return '#505050; color: #FFFFFF';
            else
                return '#303030; color: #aaaaaa';
        }
 

		
        //show list of items offers
		if((count($offer_list['item']) > 0) and ($action == 'item'))
		{
			
			foreach($offer_list['item'] as $item)
            {
				$main_content .= '

				<div class="shop-item" onmouseover="setHover(this)"  onmouseout="unsetHover(this)">
                <div>
                    <img src="images/items/' . $item['item_id'] . '.gif" class="pull-left margin-right-5"/> 
                    <div class="points">'.$item['points'].' POINTS</div>
				';
				$buyMSG = "BUY";
				$buyLINK = "#";
				if($logged)
				{
					if($user_premium_points < $item['points'])
						{
							$needPoints = $item['points'] - $user_premium_points;
							$tempString = "points";
								if($needPoints == 1)
								{
									$tempString = "point";
								}
							$main_content .= '<div class="buy">You need <font color="#ffff00"><b>'.$needPoints.'</b></font> '.$tempString.' more.</div>';
							
							$buyMSG = "BUY POINTS";
							$buyLINK = ' <a href="?subtopic=buypoints" class="buy-item">BUY POINTS</a>'; //link do kupna pkt
						}
						else
						{
							$buyMSG = "BUY";
							$buyLINK = '
							<form action="?subtopic=shopsystem&action=select_player" method="POST" name="itemform_'.$item['id'].'">
							<input type="hidden" name="buy_id" value="'.$item['id'].'">
							<a href="" onClick="itemform_'.$item['id'].'.submit();return false;" class="buy-item">'.$buyMSG.'</a>
							</form>							
							';
							
							$main_content .= '<div class="buy">You have <font color="#34ea00"><b>enough</b></font> points.</div>';
						}
				}		
				
				$main_content .='
                </div>
                <div class="clearfix"></div>
                <div class="text-center margin-top-70">
				';
				if(!$logged)
                {
                    $main_content .= '<a href="?subtopic=accountmanagement" class="buy-item">LOGIN</a>';
                }
                else
                {
                    $main_content .= $buyLINK;
                }
				
				$main_content .='	
                    <div class="item-title">'.htmlspecialchars($item['name']).'</div>
                    <div class="item-subtitle">'.htmlspecialchars($item['description']).'</div>
					</div>
				</div>
				';
			}

			
		}
		
	/*	
	$user_premium_points
        if((count($offer_list['item']) > 0) and ($action == 'item'))
        {
            $main_content .= '<table class="table table-bordered"><tr><td width="9%" align="center"><b>Picture</b></td><td width="350" align="left"><b><center>Description</center></b></td><td width="250" align="center"><b>Select product</b></td></tr>';
            foreach($offer_list['item'] as $item)
            {
                if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
                $main_content .= '<tr bgcolor="'.$bgcolor.'"><td align="center"><img src="images/items/' . $item['item_id'] . '.gif"></td><td><center><b>'.htmlspecialchars($item['name']).'</b> (<font color="gold">'.$item['points'].' points</font>)<br />'.htmlspecialchars($item['description']).'</center></td><td align="center">';
                if(!$logged)
                {
                    $main_content .= '<b>Login to buy</b>';
                }
                else
                {
                    $main_content .= '<form action="?subtopic=shopsystem&action=select_player" method="POST" name="itemform_'.$item['id'].'"><input type="hidden" name="buy_id" value="'.$item['id'].'"><div class="navibutton"><a href="" onClick="itemform_'.$item['id'].'.submit();return false;"><b>BUY</b></a></div></form>';
                }
                $main_content .= '</td></tr>';
            }
            $main_content .= '</table>';
        }
	*/
    }
	
	
	
    if($action == 'select_player')
    {
        unset($_SESSION['viewed_confirmation_page']);
        if(!$logged) {
            $errormessage .= 'Please login first.';
        }
        else
        {
            $buy_id = (int) $_REQUEST['buy_id'];
            if(empty($buy_id))
            {
                $errormessage .= 'Please <a href="?subtopic=shopsystem">select item</a> first.';
            }
            else
            {
                $buy_offer = getItemByID($buy_id);
                if(isset($buy_offer['id'])) //item exist in database
                {
                    if($user_premium_points >= $buy_offer['points'])
                    {
						$main_content .= '
						<div class="normal-panel-center">
						<font color="orange">SELECTED OFFER</font>
								<div align="center">
									<div class="normal-panel-box">										
										<br>
										<img src="images/items/' . $buy_offer['item_id'] . '.gif"/> 
										<br>
										'.htmlspecialchars($buy_offer['name']).'
										<br>
										<div class="item-subtitle">
										'.htmlspecialchars($buy_offer['description']).'
										</div>
										<br><font color="white">
										'.htmlspecialchars($buy_offer['points']).'</font>
										<br>
										POINTS
										<div class="item-subtitle">
										You have <font color="#34ea00"><b>'.$user_premium_points.'</b></font> points.
										</div>
									</div>
									<br><br>
									SELECT PLAYER
									<br>
									
									
									
									<div class="normal-panel-box select-player">	
									
									<form action="?subtopic=shopsystem&action=confirm_transaction" method="post">
									<input type="hidden" name="buy_id" value="'.$buy_id.'">
										<div class="normal-panel-select-text">									
											<select name="buy_name">
												';
											$players_from_logged_acc = $account_logged->getPlayersList();
											if(count($players_from_logged_acc) > 0)
											{
												foreach($players_from_logged_acc as $player)
												{
													$main_content .= '<option>'.htmlspecialchars($player->getName()).'</option>';
												}
											}
												$main_content .= '
											</select>
										</div>						 
									<input type="submit" class="form-submit-fixed" value="SEND"/>										
									</form>
									
										<br>
									</div>
									<br><br>
										SEND TO OTHER PLAYER
										<br>
									<div class="normal-panel-box other-player">
										<form action="?subtopic=shopsystem&action=confirm_transaction" method="post">
										<input type="hidden" name="buy_id" value="'.$buy_id.'">
										<input type="text" placeholder="Player Name" class="width-420 margin-bottom-5 login-input login-account-input" id="buy_name" name="buy_name" maxlength=18/>
										<input type="submit" class="form-submit-fixed" value="SEND"/>										
									</form>
									</div>
									

									
								</div>				
						</div>
						<br><br><br><br><br><br><br><br><br><br>
						';									
                    }
                    else
                    {
                        $errormessage .= 'For this item you need <b>'.$buy_offer['points'].'</b> points. You have only <b>'.$user_premium_points.'</b> premium points. Please <a href="?subtopic=shopsystem">select other item</a> or buy premium points.';
                    }
                }
                else
                {
                    $errormessage .= 'Offer with ID <b>'.$buy_id.'</b> doesn\'t exist. Please <a href="?subtopic=shopsystem">select item</a> again.';
                }
            }
        }
        if(!empty($errormessage))
        {
            $main_content .= '
			<br><br><br><br>
			<div class="normal-panel-center error">
					<font color="orange">ERROR:
					'.$errormessage.'</b>
					</font></div>';
        }
    }
    elseif($action == 'confirm_transaction')
    {
        if(!$logged)
        {
            $errormessage .= 'Please login first.';
        }
        else
        {
            $buy_id = (int) $_POST['buy_id'];
            $buy_name = trim($_POST['buy_name']);
            $buy_from = trim($_POST['buy_from']);
            if(empty($buy_from))
            {
                $buy_from = 'Anonymous';
            }
            if(empty($buy_id))
            {
                $errormessage .= 'Please <a href="?subtopic=shopsystem">select item</a> first.';
            }
            else
            {
                if(!check_name($buy_from))
                {
                    $errormessage .= 'Invalid nick ("from player") format. Please <a href="?subtopic=shopsystem&action=select_player&buy_id='.$buy_id.'">select other name</a> or contact with administrator.';
                }
                else
                {
                    $buy_offer = getItemByID($buy_id);
                    if(isset($buy_offer['id'])) //item exist in database
                    {
                        if($user_premium_points >= $buy_offer['points'])
                        {
                            if(check_name($buy_name))
                            {
                                $buy_player = new Player();
                                $buy_player->find($buy_name);
                                if($buy_player->isLoaded())
                                {
                                    $buy_player_account = $buy_player->getAccount();
                                    if($_SESSION['viewed_confirmation_page'] == 'yes' && $_POST['buy_confirmed'] == 'yes')
                                    {
                                        if($buy_offer['type'] == 'item')
                                        {
                                            $sql = 'INSERT INTO '.$SQL->tableName('z_ots_comunication').' ('.$SQL->fieldName('id').','.$SQL->fieldName('name').','.$SQL->fieldName('type').','.$SQL->fieldName('action').','.$SQL->fieldName('param1').','.$SQL->fieldName('param2').','.$SQL->fieldName('param3').','.$SQL->fieldName('param4').','.$SQL->fieldName('param5').','.$SQL->fieldName('param6').','.$SQL->fieldName('param7').','.$SQL->fieldName('delete_it').') VALUES (NULL, '.$SQL->quote($buy_player->getName()).', '.$SQL->quote('login').', '.$SQL->quote('give_item').', '.$SQL->quote($buy_offer['item_id']).', '.$SQL->quote($buy_offer['item_count']).', '.$SQL->quote('').', '.$SQL->quote('').', '.$SQL->quote('item').', '.$SQL->quote($buy_offer['name']).', '.$SQL->quote('').', '.$SQL->quote(1).');';
                                            $SQL->query($sql);
                                            $save_transaction = 'INSERT INTO '.$SQL->tableName('z_shop_history_item').' ('.$SQL->fieldName('id').','.$SQL->fieldName('to_name').','.$SQL->fieldName('to_account').','.$SQL->fieldName('from_nick').','.$SQL->fieldName('from_account').','.$SQL->fieldName('price').','.$SQL->fieldName('offer_id').','.$SQL->fieldName('trans_state').','.$SQL->fieldName('trans_start').','.$SQL->fieldName('trans_real').') VALUES ('.$SQL->lastInsertId().', '.$SQL->quote($buy_player->getName()).', '.$SQL->quote($buy_player_account->getId()).', '.$SQL->quote($buy_from).',  '.$SQL->quote($account_logged->getId()).', '.$SQL->quote($buy_offer['points']).', '.$SQL->quote($buy_offer['name']).', '.$SQL->quote('wait').', '.$SQL->quote(time()).', '.$SQL->quote(0).');';
                                            $SQL->query($save_transaction);
                                            $bought = 'UPDATE `z_shop_offer` SET `bought` = bought + 1 WHERE `id` ='.$buy_offer['id'].';';
                                            $SQL->query($bought); 
                                            $account_logged->setCustomField('premium_points', $user_premium_points-$buy_offer['points']);
                                            $user_premium_points = $user_premium_points - $buy_offer['points'];
										
										//LICZNIK KUPIONYCH ITEMOW
											$SQL->query('UPDATE sms_checker SET bought = bought + 1;');
                                            $main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
                                                <TR><TD ALIGN=left CLASS=white><B>Item added!</B></TD></TR>
                                                <TR><TD ALIGN=left><b>'.htmlspecialchars($buy_offer['name']).'</b> added to player <b>'.htmlspecialchars($buy_player->getName()).'</b> items (he will get this items after relog) for <b>'.$buy_offer['points'].' premium points</b> from your account.<br />Now you have <b>'.$user_premium_points.' premium points</b>.<br /><a href="?subtopic=shopsystem">GO TO MAIN SHOP SITE</a></TD></TR>
                                                </table><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                                        }
                                        elseif($buy_offer['type'] == 'container')
                                        {
                                            $sql = 'INSERT INTO '.$SQL->tableName('z_ots_comunication').' ('.$SQL->fieldName('id').','.$SQL->fieldName('name').','.$SQL->fieldName('type').','.$SQL->fieldName('action').','.$SQL->fieldName('param1').','.$SQL->fieldName('param2').','.$SQL->fieldName('param3').','.$SQL->fieldName('param4').','.$SQL->fieldName('param5').','.$SQL->fieldName('param6').','.$SQL->fieldName('param7').','.$SQL->fieldName('delete_it').') VALUES (NULL, '.$SQL->quote($buy_player->getName()).', '.$SQL->quote('login').', '.$SQL->quote('give_item').', '.$SQL->quote($buy_offer['item_id']).', '.$SQL->quote($buy_offer['item_count']).', '.$SQL->quote($buy_offer['container_id']).', '.$SQL->quote($buy_offer['container_count']).', '.$SQL->quote('container').', '.$SQL->quote($buy_offer['name']).', '.$SQL->quote('').', '.$SQL->quote(1).');';
                                            $SQL->query($sql);
                                            $save_transaction = 'INSERT INTO '.$SQL->tableName('z_shop_history_item').' ('.$SQL->fieldName('id').','.$SQL->fieldName('to_name').','.$SQL->fieldName('to_account').','.$SQL->fieldName('from_nick').','.$SQL->fieldName('from_account').','.$SQL->fieldName('price').','.$SQL->fieldName('offer_id').','.$SQL->fieldName('trans_state').','.$SQL->fieldName('trans_start').','.$SQL->fieldName('trans_real').') VALUES ('.$SQL->lastInsertId().', '.$SQL->quote($buy_player->getName()).', '.$SQL->quote($buy_player_account->getId()).', '.$SQL->quote($buy_from).',  '.$SQL->quote($account_logged->getId()).', '.$SQL->quote($buy_offer['points']).', '.$SQL->quote($buy_offer['name']).', '.$SQL->quote('wait').', '.$SQL->quote(time()).', '.$SQL->quote(0).');';
                                            $SQL->query($save_transaction);
                                            $bought = 'UPDATE `z_shop_offer` SET `bought` = bought + 1 WHERE `id` ='.$buy_offer['id'].';';
                                            $SQL->query($bought); 
                                            $account_logged->setCustomField('premium_points', $user_premium_points-$buy_offer['points']);
                                            $user_premium_points = $user_premium_points - $buy_offer['points'];
                                            $main_content .= '
											
											
											<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
                                                <TR><TD ALIGN=left CLASS=white><B>Container of items added!</B></TD></TR>
                                                <TR><TD ALIGN=left><b>'.htmlspecialchars($buy_offer['name']).'</b> added to player <b>'.htmlspecialchars($buy_player->getName()).'</b> items (he will get this container with items after relog) for <b>'.$buy_offer['points'].' premium points</b> from your account.<br />Now you have <b>'.$user_premium_points.' premium points</b>.<br /><a href="?subtopic=shopsystem">GO TO MAIN SHOP SITE</a></TD></TR>
                                                </table>';
                                        }
                                    }
                                    else
                                    {
                                        $set_session = TRUE;
                                        $_SESSION['viewed_confirmation_page'] = 'yes';
                                        $main_content .= '
										<div class="normal-panel-center confirm">
											<font color="orange">Confirm Transaction</font>
											<div align="center">
												<div class="normal-panel-box">										
													<br>
													<img src="images/items/' . $buy_offer['item_id'] . '.gif"/> 
													<br>
													'.htmlspecialchars($buy_offer['name']).'
													<br>
													<div class="item-subtitle">
													'.htmlspecialchars($buy_offer['description']).'
													</div>
													<br><font color="white">
													'.htmlspecialchars($buy_offer['points']).'</font>
													<br>
													POINTS
													<div class="item-subtitle">
													You have <font color="#34ea00"><b>'.$user_premium_points.'</b></font> points.
													</div>
													<br>
													FOR PLAYER: <font color="white">'.htmlspecialchars($buy_player->getName()).'</font><br>
													
													
													<form action="?subtopic=shopsystem&action=confirm_transaction" method="post">
														<input type="hidden" name="buy_confirmed" value="yes">
														<input type="hidden" name="buy_id" value="'.$buy_id.'">
													    <input type="hidden" name="buy_from" value="'.htmlspecialchars($buy_from).'">
													    <input type="hidden" name="buy_name" value="'.htmlspecialchars($buy_name).'">
														<input type="submit" class="form-submit-fixed" value="Accept"/>												
													</form>
													
													 
													<form action="?subtopic=shopsystem" method="post">
														<input type="submit" class="form-submit-fixed" value="Cancel"/>												
													</form>
												</div>
											</div>
										</div>
                                        ';
                                    }
                                }
                                else
                                {
                                    $errormessage .= 'Player with name <b>'.htmlspecialchars($buy_name).'</b> doesn\'t exist. Please <a href="?subtopic=shopsystem&action=select_player&buy_id='.$buy_id.'">select other name</a>.';
                                }
                            }
                            else
                            {
                                $errormessage .= 'Invalid name format. Please <a href="?subtopic=shopsystem&action=select_player&buy_id='.$buy_id.'">select other name</a> or contact with administrator.';
                            }
                        }
                        else
                        {
                            $errormessage .= 'For this item you need <b>'.$buy_offer['points'].'</b> points. You have only <b>'.$user_premium_points.'</b> premium points. Please <a href="?subtopic=shopsystem">select other item</a> or buy premium points.';
                        }
                    }
                    else
                    {
                        $errormessage .= 'Offer with ID <b>'.$buy_id.'</b> doesn\'t exist. Please <a href="?subtopic=shopsystem">select item</a> again.';
                    }
                }
            }
        }
        if(!empty($errormessage))
        {
             $main_content .= '
			<br><br><br><br>
			<div class="normal-panel-center error">
					<font color="orange">ERROR:
					'.$errormessage.'</b>
					</font></div>';
        }
        if(!$set_session)
        {
            unset($_SESSION['viewed_confirmation_page']);
        }
    }
    elseif($action == 'show_history')
    {
        if(!$logged)
        {
            $errormessage .= 'Please login first.';
        }
        else
        {
            $items_history_received = $SQL->query('SELECT * FROM '.$SQL->tableName('z_shop_history_item').' WHERE '.$SQL->fieldName('to_account').' = '.$SQL->quote($account_logged->getId()).' OR '.$SQL->fieldName('from_account').' = '.$SQL->quote($account_logged->getId()).';');
            if(is_object($items_history_received))
            {
                foreach($items_history_received as $item_received)
                {
                    if($account_logged->getId() == $item_received['to_account'])
                        $char_color = 'green';
                    else
                        $char_color = 'red';
                    $items_received_text .= '<tr bgcolor="'.$config['site']['lightborder'].'"><td><font color="'.$char_color.'">'.htmlspecialchars($item_received['to_name']).'</font></td><td>';
                    if($account_logged->getId() == $item_received['from_account'])
                        $items_received_text .= '<i>Your account</i>';
                    else
                        $items_received_text .= htmlspecialchars($item_received['from_nick']);
                    $items_received_text .= '</td><td>'.htmlspecialchars($item_received['offer_id']).'</td><td>'.date("j F Y, H:i:s", $item_received['trans_start']).'</td>';
                    if($item_received['trans_real'] > 0)
                        $items_received_text .= '<td>'.date("j F Y, H:i:s", $item_received['trans_real']).'</td>';
                    else
                        $items_received_text .= '<td><b><font color="red">Not realized yet.</font></b></td>';
                    $items_received_text .= '</tr>';
                }
            }
            $main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
                <TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'"></TD></TR>
                <TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" ALIGN=left CLASS=white><center><B>Transactions History</B></center></TD></TR>
                <TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'"></TD></TR>
                </table><br>';
                 
            if(!empty($items_received_text))
            {
                $main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
                    <TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" ALIGN=left CLASS=white colspan="5"><B>Item Transactions</B></TD></TR>
                    <tr bgcolor="'.$config['site']['darkborder'].'"><td><b>To:</b></td><td><b>From:</b></td><td><b>Offer name</b></td><td><b>Bought on page</b></td><td><b>Received on OTS</b></td></tr>
                    '.$items_received_text.'
                    </table><br />';
            }
            if(empty($items_received_text))
                $errormessage .= 'You did not buy/receive any item.';
        }
        if(!empty($errormessage))
        {
             $main_content .= '
			<br><br><br><br>
			<div class="normal-panel-center error">
					<font color="orange">ERROR:
					'.$errormessage.'</b>
					</font>';
        }
    }
}
else
    $main_content .= '<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4>
    <TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" ALIGN=center CLASS=white ><B>Shop Information</B></TD></TR>
    <TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><center>Shop is currently closed. [to admin: edit it in \'config/config.php\']</TD></TR>
    </table>';

