<?php
if(!defined('INITIALIZED'))
	exit;

$error_Message = "";
if(!$logged)
	if($action == "logout")
		$main_content .= '<article><div class="heading"><center>Logout</center></div></article><div class="">You have logged out. <a href="?subtopic=accountmanagement">Log in</a> again to view your account.</div>';
	else
	{
		if(isset($isTryingToLogin))
		{
			switch(Visitor::getLoginState())
			{
				case Visitor::LOGINSTATE_NO_ACCOUNT:
					$error_Message = 'Error: Account with that name doesn\'t exist.';
					break;
				case Visitor::LOGINSTATE_WRONG_PASSWORD:
					$error_Message = 'Error: Wrong password to account.';
					break;
			}
		}
		$main_content .= '
<div class="col-md-6 login-panel-left text-center">
                <h3>Revelations Dragon Ball</h3>
                <div>Create an account and start Your journey in the world of Dragon Ball!</div>
                <a href="?subtopic=createaccount">Register</a>
                <ul>
                    <li>Best Dragon Ball OTS in the world</li>
                    <li>Huge community and lot of fun just waiting for You</li>
                    <li>Create an account now and get a chance to win PLAYSTATION 4</li>
                </ul>
        </div>
            <div class="col-md-6 login-panel-right no-padding">
		<h3><div id="login-form-button" class="inline-block gold" onclick="showForm(this, \'login\')">Login</div> / <div class="inline-block"  id="recovery-form-button" onclick="showForm(this, \'recovery\')">Account Recovery</div></h3>
			  <div>
                    <form id="login" action="?subtopic=accountmanagement" method="post">
                        <input type="text" placeholder="Your Account" name="account_login" size="30" maxlength="10" class="width-420 margin-bottom-20 login-input login-account-input"/>
                        <input type="password" placeholder="Password" name="password_login" size="30" maxlength="29" class="width-420 margin-bottom-20 login-input login-password-input"/>
						<br><br>
						<b><font color="orange">'.$error_Message.'</font></b>
						
						<br>
                        <input type="submit" class="form-submit" value="Login"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                    <form id="recovery" class="hidden" action="?subtopic=lostaccount" method="post">
						<font color="a9a9a9">
						If you want to recover Your account You need either an e-mail assigned to the account or Recovery Key.<br>
						If You do not have any of these, we can\'t help You in recovering Your account.
						Remember that Administration is not responsible for any account losses, so please make sure that it is safe and don\'t share Your password with anyone..<br><br>
						
						
						</font><br>		
						  <input type="submit" class="form-submit" value="Next step"/>
			
                    </form>
                </div>
            </div>
		
		
		';
}
else
{
	if($action == "")
	{
		$account_reckey = $account_logged->getCustomField("key");
		if($account_logged->getPremDays() > 0)
			$account_status = '<b><font color="green">Premium Account, '. $account_logged->getPremDays() .' days left</font></b>';
		else
			$account_status = '<b><font color="red">Free Account</font></b>';
		if(empty($account_reckey))
			$account_registred = '<b><font color="red">No</font></b>';
		else
			if($config['site']['generate_new_reckey'] && $config['site']['send_emails'])
				$account_registred = '<b><font color="green">Yes ( <a href="?subtopic=accountmanagement&action=newreckey"> Buy new Rec key </a> )</font></b>';
			else
				$account_registred = '<b><font color="green">Yes</font></b>';
		$account_created = $account_logged->getCreateDate();
		$account_email = $account_logged->getEMail();
		$account_email_new_time = $account_logged->getCustomField("email_new_time");
		if($account_email_new_time > 1)
			$account_email_new = $account_logged->getCustomField("email_new");
		$account_rlname = $account_logged->getRLName();
		$account_location = $account_logged->getLocation();
		if($account_logged->isBanned())
			if($account_logged->getBanTime() > 0)
				$welcome_msg = '<font color="red">Your account is banished until '.date("j F Y, G:i:s", $account_logged->getBanTime()).'!</font>';
			else
				$welcome_msg = '<font color="red">Your account is banished FOREVER!</font>';
		$main_content .= '<div class="accmanage-logged-topPlace"><br>
		<font color="white">ACCOUNT MANAGMENT</font>
			<form action="?subtopic=accountmanagement&action=logout" method="post">										
			<input type="submit" class="form-submit-fixed" value="Logout"/>										
			</form>
			<br>
		</div>
		';
		$main_content .= '<p class="text-center">' . $welcome_msg . '</p>';
		//if account dont have recovery key show hint
		if(empty($account_reckey))
			$main_content .= '
			<div class="general-info-small">
				<div class="general-text">
				<p>CREATE YOUR RECOVERY KEY AND SAVE IT! WE CANT GIVE YOUR ACCOUNT BACK IF YOU GOT HACKED! Click on "Register Account" and get your free recovery key today!</p>
				</div>
			</div>';
		if($account_email_new_time > 1)
			if($account_email_new_time < time())
				$account_email_change = '<br>(You can accept <strong>'.htmlspecialchars($account_email_new).'</strong> as a new email.)';
			else
			{
				$account_email_change = ' <br>You can accept <b>new e-mail after '.date("j F Y", $account_email_new_time).".</b>";
				$main_content .= '
				<p>A request has been submitted to change the email address of this account to <b>'.htmlspecialchars($account_email_new).'</b>. After <b>'.date("j F Y, G:i:s", $account_email_new_time).'</b> you can accept the new email address and finish the process. Please cancel the request if you do not want your email address to be changed! Also cancel the request if you have no access to the new email address!<p>
				<div class="text-center"><a href="?subtopic=accountmanagement&amp;action=changeemail" class="btn">Cancel</a></div><br>';
			}
		$main_content .= '
			<div class="general-info-main">
				<div class="general-info-main text-handler">
					<div class="general-info-text-handler-text">
						<p>General Information</p>
					</div>
				</div>

				<div id="wrapper">
				<table>
				 <tr>
					<td>Email address</td>
					<td>'.htmlspecialchars($account_email).'</td>
				</tr>
				<tr>
					<td>Created</td>
					<td>'.date("j F Y, G:i:s", $account_created).'</td>
				</tr>
				<tr>
					<td>Last login</td>
					<td>'.date("j F Y, G:i:s", time()).'</td>
				</tr>
				<tr>
					<td>Account status</td>
					<td>'.$account_status.'</td>
				</tr>
				<tr>
					<td>Registered</td>
					<td>'.$account_registred.'</td>
				</tr>
				<tr>
					<td>Premium points</td>
					<td>'.$account_logged->getCustomField('premium_points').'</td>
				</tr>
				</table>
				</div>
				
			</div>			
		
		';
		
		
		$main_content .= '<br>
			<div class="general-main-public">
				<div class="col-md-4">
							<div id="wrapper2">
								<table>
								 <tr>
									<td>Real Name</td>
									<td>'.$account_rlname.'</td>
								</tr>
								<tr>
									<td>Location</td>
									<td>'.$account_location.'</td>
								</tr>
								</table>
							</div>
				</div>
			</div>
		';
		$main_content .= '<br>

			<article><div class="heading"><center>Characters</center></div></article>
			<table class="table table-bordered">
				<tr>
					<th>Name</th>
					<th>Level</th>
					<th>Status</th>
					<th></th>
				</tr>';
		$account_players = $account_logged->getPlayersList();
		foreach($account_players as $account_player)
		{
			$player_number_counter++;
			$main_content .= '<tr><td>' . $player_number_counter . '.&#160;' . htmlspecialchars($account_player->getName());
			if($account_player->isDeleted())
				$main_content .= '<font color="red"><strong> [DELETED]</strong></font><a href="?subtopic=accountmanagement&action=undelete&name='.urlencode($account_player->getName()).'"> &gt;&gt;UNDELETE&lt;&lt;</a>';
			$main_content .= '</td><td><nobr>'.$account_player->getLevel().' '.htmlspecialchars($vocation_name[$account_player->getPromotion()][$account_player->getVocation()]).'</nobr></td>';
			if(!$account_player->isOnline())
				$main_content .= '<td style="width: 62px"><font color="red"><strong>Offline</strong></font></td>';
			else
				$main_content .= '<td style="width: 62px"><font color="green"><strong>Online</strong></font></td>';
			$main_content .= '<td style="width: 36px">[<a href="?subtopic=accountmanagement&action=changecomment&name='.urlencode($account_player->getName()).'">Edit</a>]</td></tr>';
		}
		$main_content .= '</table>';
		$main_content .= '<a href="?subtopic=accountmanagement&amp;action=createcharacter"><button>Create</button></a>
	
			<a href="?subtopic=accountmanagement&amp;action=deletecharacter"><button>Delete</button></a>
			<div class="clear"></div>';
	}
//########### CHANGE PASSWORD ##########
	if($action == "changepassword") {
		$new_password = trim($_POST['newpassword']);
		$new_password2 = trim($_POST['newpassword2']);
		$old_password = trim($_POST['oldpassword']);
		if(empty($new_password) && empty($new_password2) && empty($old_password))
		{
			$main_content .= '<div class="title">Change password</div>
				<form action="?subtopic=accountmanagement&action=changepassword" method="post">
				<table class="table tableSpacing">
					<tr>
						<td>New password</td>
						<td><input type="password" name="newpassword" maxlength="29"></td>
					</tr>
					<tr>
						<td>New password again</td>
						<td><input type="password" name="newpassword2" size="30" maxlength="29"></td>
					</tr>
					<tr>
						<td>Current password</td>
						<td><input type="password" name="oldpassword" size="30" maxlength="29"></td>
					</tr>
					<tr>
						<td><input type="submit" value="Change" class="btn"></td>
						<td class="pull-right"><a href="?subtopic=accountmanagement">Back</a></td>
					</tr>
				</table>
				</form>';
		}
		else
		{
			if(empty($new_password) || empty($new_password2) || empty($old_password))
			{
				$show_msgs[] = "Please fill in form.";
			}
			if($new_password != $new_password2)
			{
				$show_msgs[] = "The new passwords do not match!";
			}
			if(empty($show_msgs))
			{
				if(!check_password($new_password))
				{
					$show_msgs[] = "New password contains illegal chars (a-z, A-Z and 0-9 only!) or lenght.";
				}
				if(!$account_logged->isValidPassword($old_password))
				{
					$show_msgs[] = "Current password is incorrect!";
				}
			}
			if(!empty($show_msgs))
			{
				//show errors
				$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
				foreach($show_msgs as $show_msg) {
					$main_content .= '<li>'.$show_msg.'</li>';
				}
				$main_content .= '</ul>';
				$main_content .= '<div class="title">Change password</div>
							<form action="?subtopic=accountmanagement&action=changepassword" method="post">
							<table class="table tableSpacing">
								<tr>
									<td>New password</td>
									<td><input type="password" name="newpassword" maxlength="29"></td>
								</tr>
								<tr>
									<td>New password again</td>
									<td><input type="password" name="newpassword2" size="30" maxlength="29"></td>
								</tr>
								<tr>
									<td>Current password</td>
									<td><input type="password" name="oldpassword" size="30" maxlength="29"></td>
								</tr>
								<tr>
									<td><input type="submit" value="Change" class="btn"></td>
									<td class="pull-right"><a href="?subtopic=accountmanagement" class="btn">Back</a></td>
								</tr>
							</table>
							</form>';
			}
			else
			{
				$org_pass = $new_password;
				$account_logged->setPassword($new_password);
				$account_logged->save();
				$main_content .= '<div class="title">Password changed</div>Your password has been changed.';
				if($config['site']['send_emails'] && $config['site']['send_mail_when_change_password'])
				{
					$mailBody = '
						<html>
						<body>
							<h3>Password to account changed!</h3>
							<p>You or someone else changed password to your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a>.</p>
							<p>New password: <b>'.htmlspecialchars($org_pass).'</b></p>
						</body>
						</html>';
					$mail = new PHPMailer();
					if ($config['site']['smtp_enabled'])
					{
						$mail->IsSMTP();
						$mail->Host = $config['site']['smtp_host'];
						$mail->Port = (int)$config['site']['smtp_port'];
						$mail->SMTPAuth = $config['site']['smtp_auth'];
						$mail->Username = $config['site']['smtp_user'];
						$mail->Password = $config['site']['smtp_pass'];
					}
					else
					{
						$mail->IsMail();
						$mail->IsHTML(true);
						$mail->From = $config['site']['mail_address'];
						$mail->AddAddress($account_logged->getEMail());
						$mail->Subject = $config['server']['serverName']." - Changed password";
						$mail->Body = $mailBody;
						if($mail->Send())
							$main_content .= '<br /><small>Your new password was send on email address <b>'.htmlspecialchars($account_logged->getEMail()).'</b>.</small>';
						else
							$main_content .= '<br /><small>An error occorred while sending email with password!</small>';
					}
				}
				$main_content .= '<br><br><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
				$_SESSION['password'] = $new_password;
			}
		}
	}
//############# CHANGE E-MAIL ###################
	if($action == "changeemail")
	{
		$account_email_new_time = $account_logged->getCustomField("email_new_time");
		if($account_email_new_time > 10) {$account_email_new = $account_logged->getCustomField("email_new"); }
		if($account_email_new_time < 10)
		{
			if($_POST['changeemailsave'] == 1)
			{
				$account_email_new = trim($_POST['new_email']);
				$post_password = trim($_POST['password']);
				if(empty($account_email_new))
				{
					$change_email_errors[] = "Please enter your new email address.";
				}
				else
				{
					if(!check_mail($account_email_new))
					{
						$change_email_errors[] = "E-mail address is not correct.";
					}
				}
				if(empty($post_password))
				{
					$change_email_errors[] = "Please enter password to your account.";
				}
				else
				{
					if(!$account_logged->isValidPassword($post_password))
					{
						$change_email_errors[] = "Wrong password to account.";
					}
				}
				if(empty($change_email_errors))
				{
					$account_email_new_time = time() + $config['site']['email_days_to_change'] * 24 * 3600;
					$account_logged->set("email_new", $account_email_new);
					$account_logged->set("email_new_time", $account_email_new_time);
					$account_logged->save();
					$main_content .= '<div class="title">Change email address</div>
					You have requested to change your email address to <b>'.htmlspecialchars($account_email_new).'</b>. The actual change will take place after <b>'.date("j F Y, G:i:s", $account_email_new_time).'
					<br><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
				}
				else
				{
					//show errors
					$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
					foreach($change_email_errors as $change_email_error)
					{
						$main_content .= '<li>'.$change_email_error.'</li>';
					}
					$main_content .= '</ul>';
					//show form
					$main_content .= '<div class="title">Change email address</div>Please enter your password and the new email address. Make sure that you enter a valid email address which you have access to. <b>For security reasons, the actual change will be finalised after a waiting period of '.$config['site']['email_days_to_change'].' days.</b><br /><br />
					<form action="?subtopic=accountmanagement&action=changeemail" method="post">
						<input type="hidden" name=changeemailsave value=1>
						<table class="table tableSpacing">
							<tr>
								<td>New email adress</td>
								<td><input name="new_email" value="" maxlength="50"></td>
							</tr>
							<tr>
								<td>Password</td>
								<td><input type="password" name="password" maxlength="29"></td>
							</tr>
							<tr>
								<td><input type="submit" value="Submit" class="btn"></td>
								<td class="pull-right"><a href="?subtopic=accountmanagement" class="btn">Back</a></td>
							</tr>
						</table>
					</form>';
				}
			}
			else
			{
				$main_content .= '<div class="title">Change email address</div>Please enter your password and the new email address. Make sure that you enter a valid email address which you have access to. <b>For security reasons, the actual change will be finalised after a waiting period of '.$config['site']['email_days_to_change'].' days.</b><br /><br />
					<form action="?subtopic=accountmanagement&action=changeemail" method="post">
						<input type="hidden" name=changeemailsave value=1>
						<table class="table tableSpacing">
							<tr>
								<td>New email adress</td>
								<td><input name="new_email" value="" maxlength="50"></td>
							</tr>
							<tr>
								<td>Password</td>
								<td><input type="password" name="password" maxlength="29"></td>
							</tr>
							<tr>
								<td><input type="submit" value="Submit" class="btn"></td>
								<td class="pull-right"><a href="?subtopic=accountmanagement" class="btn">Back</a></td>
							</tr>
						</table>
					</form>';
			}
		}
		else
		{
			if($account_email_new_time < time())
			{
				if($_POST['changeemailsave'] == 1)
				{
					$account_logged->set("email_code", "");
					$account_logged->set("email_new", "");
					$account_logged->set("email_new_time", 0);
					$account_logged->setEmail($account_email_new);
					$account_logged->save();
					$main_content .= '
					<div class="title">Email Address Change Accepted</div>
					<p>You have accepted <b>'.htmlspecialchars($account_logged->getEmail()).'</b> as your new email adress.</p>
					<div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
				}
				else
				{
					$main_content .= '<div class="title">Email Address Change Accepted</div>
					<p>Do you accept <b>'.htmlspecialchars($account_email_new).'</b> as your new email adress?</p>
					<table class="table tableSpacing">
						<tr>
							<td>
								<form action="?subtopic=accountmanagement&action=changeemail" method="post">
									<input type="hidden" name="changeemailsave" value=1>
									<input type="submit" class="btn" value="Agree">
								</form>
							</td>
							<td class="pull-right">
								<form action="?subtopic=accountmanagement&action=changeemail" method="post">
									<input type="hidden" name="emailchangecancel" value=1>
									<input type="submit" class="btn" value="Cancel">
								</form>
							</td>
						</tr>
					</table>';
				}
			}
			else
			{
				$main_content .= '<div class="title">Change of email address</div>
				<p>A request has been submitted to change the email address of this account to: <b><br><br>'.htmlspecialchars($account_email_new).'<br></b>
				<br>The actual change will take place on <b>'.date("j F Y, G:i:s", $account_email_new_time).'</b>.
				<br>If you do not want to change your email address, please click on "Cancel".</p>
				<form action="?subtopic=accountmanagement&action=changeemail" method="post">
					<input type="hidden" name="emailchangecancel" value=1>
					<table class="table tableSpacing">
						<tr>
							<td><input class="btn" type="submit" name="Cancel" value="Cancel" alt="Cancel"></td>
							<td class="pull-right"><a class="btn" href="?subtopic=accountmanagement">Back</a></td>
						</tr>
					</table>
				</form>';
			}
		}
		if($_POST['emailchangecancel'] == 1)
		{
			$account_logged->set("email_new", "");
			$account_logged->set("email_new_time", 0);
			$account_logged->save();
			$main_content = '
			<div class="title">Email Address Change Cancelled</div>
			<p>Your request to change the email address of your account has been cancelled.<br>The email address will not be changed.</p>
			<br><div class="text-center"><a class="btn" href="?subtopic=accountmanagement">Back</a></div>';
		}
	}
//########### CHANGE PUBLIC INFORMATION (about account owner) ######################
	if($action == "changeinfo") {
		$main_content .= '<div class="title">Change public information</div>';
		$new_rlname = htmlspecialchars(trim($_POST['info_rlname']));
		$new_location = htmlspecialchars(trim($_POST['info_location']));
		if($_POST['changeinfosave'] == 1) {
		//save data from form
			$account_logged->set("rlname", $new_rlname);
			$account_logged->set("location", $new_location);
			$account_logged->save();
			$main_content .= '<p>Your public information has been changed.</p><br><div class="text-center"><a class="btn" href="?subtopic=accountmanagement">Back</a></div>';		
		}
		else
		{
		//show form
			$account_rlname = $account_logged->getCustomField("rlname");
			$account_location = $account_logged->getCustomField("location");
			$main_content .= '
			<p>Here you can tell other players about yourself. This information will be displayed alongside the data of your characters. If you do not want to fill in a certain field, just leave it blank.<p>
			<form action="?subtopic=accountmanagement&action=changeinfo" method=post>
				<input type="hidden" name="changeinfosave" value="1">
				<table class="table tableSpacing">
					<tr>
						<td>Real name</td>
						<td><input name="info_rlname" value="'.$account_rlname.'" size="30" maxlength="50"></td>
					</tr>
					<tr>
						<td>Location</td>
						<td><input name="info_location" value="'.$account_location.'" size="30" maxlength="50"></td>
					</tr>
					<tr>
						<td><input class="btn" type="submit" value="Submit"></td>
						<td class="pull-right"><a class="btn" href="?subtopic=accountmanagement">Back</a></td>
					</tr>
				</table>
			</form>';
		}
	}
//############## GENERATE RECOVERY KEY ###########
	if($action == "registeraccount")
	{
		$main_content = '<div class="title">Generate recovery key</div>';
		$reg_password = trim($_POST['reg_password']);
		$old_key = $account_logged->getCustomField("key");
		if($_POST['registeraccountsave'] == "1")
		{
			if($account_logged->isValidPassword($reg_password))
			{
				if(empty($old_key))
				{
					$dontshowtableagain = 1;
					$acceptedChars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
					$max = strlen($acceptedChars)-1;
					$new_rec_key = NULL;
					// 10 = number of chars in generated key
					for($i=0; $i < 10; $i++) {
						$cnum[$i] = $acceptedChars{mt_rand(0, $max)};
						$new_rec_key .= $cnum[$i];
					}
					$account_logged->set("key", $new_rec_key);
					$account_logged->save();
					$main_content .= '
						<p>Thank you for registering your account! You can now recover your account if you have lost access to the assigned email address by using the following</p>
						<br>
						<font size="5" style="text-center"><strong>Recovery Key: '.htmlspecialchars($new_rec_key).'</strong></font>
						<br><br><br>
						<ul>
							<li>Write down this recovery key carefully.</li>
							<li>Store it at a safe place!</li>
						</ul>';
						if($config['site']['send_emails'] && $config['site']['send_mail_when_generate_reckey'])
							{
							$mailBody = '
							<html>
							<body>
								<h3>New recovery key!</h3>
								<p>You or someone else generated recovery key to your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a>.</p>
								<p>Recovery key: <b>'.htmlspecialchars($new_rec_key).'</b></p>
							</body>
						</html>';
						$mail = new PHPMailer();
						if ($config['site']['smtp_enabled'])
						{
						$mail->IsSMTP();
						$mail->Host = $config['site']['smtp_host'];
						$mail->Port = (int)$config['site']['smtp_port'];
						$mail->SMTPAuth = $config['site']['smtp_auth'];
						$mail->Username = $config['site']['smtp_user'];
						$mail->Password = $config['site']['smtp_pass'];
						}
						else
						$mail->IsMail();
						$mail->IsHTML(true);
						$mail->From = $config['site']['mail_address'];
						$mail->AddAddress($account_logged->getEMail());
						$mail->Subject = $config['server']['serverName']." - recovery key";
						$mail->Body = $mailBody;
						if($mail->Send())
						$main_content .= '
						<br />
						<small>Your recovery key were send on email address <b>'.htmlspecialchars($account_logged->getEMail()).'</b>.</small>';
						else
						$main_content .= '
						<br />
						<small>An error occorred while sending email with recovery key! You will not receive e-mail with this key.</small>';
						}
						$main_content .= '<div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
				}
				else
					$reg_errors[] = 'Your account is already registred.';
			}
			else
				$reg_errors[] = 'Wrong password to account.';
		}
		if($dontshowtableagain != 1)
		{
			//show errors if not empty
			if(!empty($reg_errors))
			{
				$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
				foreach($reg_errors as $reg_error)
					$main_content .= '<li>'.$reg_error.'</li>';
				$main_content .= '</ul>';
	}
	//show form
	$main_content .= '
	<form action="?subtopic=accountmanagement&action=registeraccount" method="post">
		<input type="hidden" name="registeraccountsave" value="1">
		<table class="table tableSpacing">
			<tr>
				<td>Password</td>
				<td><input type="password" name="reg_password" maxlength="29"></td>
			</tr>
			<tr>
				<td><input type="submit" value="Generate" class="btn"></td>
				<td><a class="btn pull-right" href="?subtopic=accountmanagement">Back</a></td>
			</tr>
		</table>
	</form>';
	}
	}
	//############## GENERATE NEW RECOVERY KEY ###########
	if($action == "newreckey")
	{
	$main_content .= '<div class="title">Generate new recovery key</div>';
	$reg_password = trim($_POST['reg_password']);
	$reckey = $account_logged->getCustomField("key");
	if((!$config['site']['generate_new_reckey'] || !$config['site']['send_emails']) || empty($reckey))
	$main_content .= 'You cant get new rec key';
	else
	{
	$points = $account_logged->getCustomField('premium_points');
	if($_POST['registeraccountsave'] == "1")
	{
	if($account_logged->isValidPassword($reg_password))
	{
	if($points >= $config['site']['generate_new_reckey_price'])
	{
	$dontshowtableagain = 1;
	$acceptedChars = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
	$max = strlen($acceptedChars)-1;
	$new_rec_key = NULL;
	// 10 = number of chars in generated key
	for($i=0; $i < 10; $i++) {
	$cnum[$i] = $acceptedChars{mt_rand(0, $max)};
	$new_rec_key .= $cnum[$i];
	}
		$mailBody = '
		<html>
		<body>
			<h3>New recovery key!</h3>
			<p>You or someone else generated recovery key to your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a>.</p>
			<p>Recovery key: <b>'.htmlspecialchars($new_rec_key).'</b></p>
		</body>
	</html>';
	$mail = new PHPMailer();
	if ($config['site']['smtp_enabled'])
	{
	$mail->IsSMTP();
	$mail->Host = $config['site']['smtp_host'];
	$mail->Port = (int)$config['site']['smtp_port'];
	$mail->SMTPAuth = $config['site']['smtp_auth'];
	$mail->Username = $config['site']['smtp_user'];
	$mail->Password = $config['site']['smtp_pass'];
	}
	else
	$mail->IsMail();
	$mail->IsHTML(true);
	$mail->From = $config['site']['mail_address'];
	$mail->AddAddress($account_logged->getEMail());
	$mail->Subject = $config['server']['serverName']." - new recovery key";
	$mail->Body = $mailBody;
	if($mail->Send())
	{
	$account_logged->set("key", $new_rec_key);
	$account_logged->set("premium_points", $account_logged->get("premium_points")-$config['site']['generate_new_reckey_price']);
	$account_logged->save();
	$main_content .= '
	<br />Your recovery key were send on email address
	<b>'.htmlspecialchars($account_logged->getEMail()).'</b> for '.$config['site']['generate_new_reckey_price'].' premium points.';
	}
	else
	$main_content .= '
	<br />An error occorred while sending email (
	<b>'.htmlspecialchars($account_logged->getEMail()).'</b> ) with recovery key!<br>Recovery key not changed. Try again.';
		$main_content .= '<br><br><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
	}
					else
						$reg_errors[] = 'You need '.$config['site']['generate_new_reckey_price'].' premium points to generate new recovery key. You have <strong>'.$points.'<strong> premium points.';
		}
		else
		$reg_errors[] = 'Wrong password to account.';
		}
		if($dontshowtableagain != 1)
		{
		//show errors if not empty
		if(!empty($reg_errors))
		{
			$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
			foreach($reg_errors as $reg_error)
				$main_content .= '<li>'.$reg_error.'</li>';
			$main_content .= '</ul>';
		}
				//show form
		$main_content .= '<strong>Do not use email on: o2/onet/wp because they dont work with our sending system!</strong>
			<br><strong><font color="red">New recovery key cost '.$config['site']['generate_new_reckey_price'].' Premium Points.</font>
			You have '.$points.' premium points.<br>You will receive e-mail with this recovery key.</strong>
			<form action="?subtopic=accountmanagement&action=newreckey" method="post">
				<input type="hidden" name="registeraccountsave" value="1">
				<table class="table tableSpacing">
					<tr>
						<td>Password</td>
						<td><input type="password" name="reg_password" maxlength="29"></td>
					</tr>
					<tr>
						<td><input type="submit" value="Submit" class="btn"></td>
						<td class="pull-right"><a href="?subtopic=accountmanagement" class="btn">Back</a></td>
					</tr>
				</table>';
		}
	}
	}
//###### CHANGE CHARACTER COMMENT ######
	if($action == "changecomment")
	{
		$player_name = $_REQUEST['name'];
		$new_comment = htmlspecialchars(substr(trim($_POST['comment']),0,2000));
		$new_hideacc = (int) $_POST['accountvisible'];
		if(check_name($player_name))
		{
			$player = new Player();
			$player->find($player_name);
			if($player->isLoaded())
			{
				$player_account = $player->getAccount();
				if($account_logged->getId() == $player_account->getId())
				{
					if($_POST['changecommentsave'] == 1)
					{
						$player->set("hide_char", $new_hideacc);
						$player->set("comment", $new_comment);
						$player->save();
						$main_content .= '
						<div class="title">Character Information Changed</div>
						<p>The character information has been changed.<p>
						<div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
					}
					else
					{
						$main_content .= '
							<div class="title">Edit Character Information</div>
							<form action="?subtopic=accountmanagement&action=changecomment" method="post">
								<input type="hidden" name="name" value="'.htmlspecialchars($player->getName()).'">
								<input type="hidden" name="changecommentsave" value="1">
								<table class="table tableSpacing">
									<tr>
										<td>Name</td>
										<td>'.htmlspecialchars($player_name).'</td>
									</tr>
									<tr>
										<td>Hide account</td>
										<td>';
						if($player->getCustomField("hide_char") == 1)
							$main_content .= '<input  style="width:10%" type="checkbox" name="accountvisible" value="1" checked="checked">';
						else
							$main_content .= '<input  style="width:10%" type="checkbox" name="accountvisible" value="1">';
						$main_content .= '
										</td>
									</tr>
									<tr>
										<td>Comment</td>
										<td><textarea name="comment" rows="10" wrap="virtual">'.$player->getCustomField("comment").'</textarea><br>[max. length: 2000 chars, 50 lines (ENTERs)]</td>
									</tr>
									<tr>
										<td><input type="submit" value="Submit" class="btn"></td>
										<td class="pull-right"><a href="?subtopic=accountmanagement" class="btn">Back</a></td>
									</tr>
								</table>
							</form>';
					}
				}
				else
				{
					$main_content .= "Error. Character <b>".htmlspecialchars($player_name)."</b> is not on your account.";
				}
			}
			else
			{
				$main_content .= "Error. Character with this name doesn't exist.";
			}
		}
		else
		{
			$main_content .= "Error. Name contain illegal characters.";
		}
	}
//### DELETE character from account ###
	if($action == "deletecharacter")
	{
		$main_content .= '<div class="title">Delete character</div>';
		$player_name = trim($_POST['delete_name']);
		$password_verify = trim($_POST['delete_password']);
		if($_POST['deletecharactersave'] == 1)
		{
			if(!empty($player_name) && !empty($password_verify))
			{
				if(check_name($player_name))
				{
					$player = new Player();
					$player->find($player_name);
					if($player->isLoaded())
					{
						$player_account = $player->getAccount();
						if($account_logged->getId() == $player_account->getId())
						{
							if($account_logged->isValidPassword($password_verify))
							{
								if(!$player->isOnline())
								{
									//dont show table "delete character" again
									$dontshowtableagain = 1;
									//delete player
									$player->set('deleted', 1);
									$player->save();
									$main_content .= 'The character <b>'.htmlspecialchars($player_name).'</b> has been deleted.<br><br><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></dov>';
								}
								else
									$delete_errors[] = 'This character is online.';
							}
							else
							{
								$delete_errors[] = 'Wrong password to account.';
							}
						}
						else
						{
							$delete_errors[] = 'Character <b>'.htmlspecialchars($player_name).'</b> is not on your account.';
						}
					}
					else
					{
						$delete_errors[] = 'Character with this name doesn\'t exist.';
					}
				}
				else
				{
					$delete_errors[] = 'Name contain illegal characters.';
				}
			}
			else
			{
			$delete_errors[] = 'Character name or/and password is empty. Please fill in form.';
			}
		}
		if($dontshowtableagain != 1)
		{
			if(!empty($delete_errors))
			{
				$main_content .= '<strong>The Following Errors Have Occurred:</strong><ul>';
				foreach($delete_errors as $delete_error)
				{
					$main_content .= '<li>' . $delete_error;
				}
				$main_content .= '</ul>';
			}
			$main_content .= '
				<form action="?subtopic=accountmanagement&action=deletecharacter" method="post">
					<input type="hidden" name="deletecharactersave" value="1">
					<table class="table tableSpacing">
						<tr>
							<td>Character name</td>
							<td><input name="delete_name" value="" size="30" maxlength="29"></td>
						</tr>
						<tr>
							<td>Password</td>
							<td><input type="password" name="delete_password" size="30" maxlength="29"></td>
						</tr>
						<tr>
							<td><input type="submit" value="Delete" class="btn"></td>
							<td class="pull-right"><a href="?subtopic=accountmanagement" class="btn">Back</a></td>
						</tr>
					</table>';
		}
	}
	//### UNDELETE character from account ###
	if($action == "undelete")
	{
	$player_name = trim($_GET['name']);
	if(!empty($player_name))
	{
	if(check_name($player_name))
	{
	$player = new Player();
	$player->find($player_name);
	if($player->isLoaded())
	{
	$player_account = $player->getAccount();
	if($account_logged->getId() == $player_account->getId())
	{
	if(!$player->isOnline())
	{
	$player->set('deleted', 0);
	$player->save();
	$main_content .= 'The character <strong>'.htmlspecialchars($player_name).'</strong> has been undeleted.<br><br><div class="text-center"><a href="?subtopic=accountmanagement" class="btn">Back</a></div>';
	}
	else
	$delete_errors[] = 'This character is online.';
	}
	else
	$delete_errors[] = 'Character <b>'.htmlspecialchars($player_name).'</b> is not on your account.';
	}
	else
	$delete_errors[] = 'Character with this name doesn\'t exist.';
	}
	else
	$delete_errors[] = 'Name contain illegal characters.';
	}
	}
	//## CREATE CHARACTER on account ###
	if($action == "createcharacter")
	{
		$main_content .= '<article><div class="heading">Create character</div></article>';
	if(count($config['site']['worlds']) > 1)
	{
	if(isset($_REQUEST['world']))
	$world_id = (int) $_REQUEST['world'];
	}
	else
	$world_id = 0;
	if(!isset($world_id))
	{
	$main_content .= 'Before you can create character you must select world: ';
	foreach($config['site']['worlds'] as $id => $world_n)
	$main_content .= '<br><a href="?subtopic=accountmanagement&action=createcharacter&world='.$id.'">- '.htmlspecialchars($world_n).'</a>';
	$main_content .= '<br><a href="?subtopic=accountmanagement" class="btn">BACK</a>';
	}
	else
	{
	$main_content .= '
	<script type="text/javascript">
		var nameHttp;
		function checkName()
		{
			if(document.getElementById("newcharname").value=="")
			{
				document.getElementById("name_check").innerHTML = \'<b><font color="red">Please enter new character name.</font></b>\';
				return;
			}
			nameHttp=GetXmlHttpObject();
			if (nameHttp==null)
			{
				return;
			}
			var newcharname = document.getElementById("newcharname").value;
			var url="?subtopic=ajax_check_name&name=" + newcharname + "&uid="+Math.random();
			nameHttp.onreadystatechange=NameStateChanged;
			nameHttp.open("GET",url,true);
			nameHttp.send(null);
		}
		function NameStateChanged()
		{
			if (nameHttp.readyState==4)
			{
				document.getElementById("name_check").innerHTML=nameHttp.responseText;
			}
		}
	</script>';
	$newchar_name = ucwords(strtolower(trim($_POST['newcharname'])));
	$newchar_sex = $_POST['newcharsex'];
	$newchar_vocation = $_POST['newcharvocation'];
	$newchar_town = $_POST['newchartown'];
	if($_POST['savecharacter'] != 1)
	{
	if($account_logged->getPlayersList()->count() >= $config['site']['max_players_per_account'])
		$main_content .= '<b><font color="red"> You have maximum number of characters per account on your account. Delete one before you make new.</font></b><br><br>';
	$main_content .= '<form action="?subtopic=accountmanagement&action=createcharacter" method="post">
		<input type="hidden" name="world" value="'.$world_id.'">
		<input type="hidden" name=savecharacter value="1">
		<table class="table tableSpacing">
			<tr>
				<td>Name</td>
				<td>
					<input id="newcharname" name="newcharname" onkeyup="checkName();" value="'.htmlspecialchars($newchar_name).'" maxlength="29">
					<br><font size="1" face="verdana,arial,helvetica"><div id="name_check">Please enter your character name.</div></font>
				</td>
			</tr>
			<tr>
				<td>Sex</td>
				<td>
					<input type="radio" name="newcharsex" value="1" style="width: 15%">Male
					<input type="radio" name="newcharsex" value="0" style="width: 15%">Female
				</td>
			</tr>
		</table>';
	$maxCountVoc = count($config['site']['newchar_vocations'][$world_id]);
	if($maxCountVoc > 1) {
		$main_content .= '<table class="table tableSpacing"><tr><th>Select your vocation</th></tr>';
		$currentVocCol = 1;
		foreach($config['site']['newchar_vocations'][$world_id] as $char_vocation_key => $sample_char)
		{
			if(($currentVocCol > 2 && $currentVocCol % 3 == 1))
				$main_content .= '<tr>';
			$main_content .= '<td style="width: 33.3%"><input type="radio" name="newcharvocation" style="width: 15%" value="'.$char_vocation_key.'" ';
			if($newchar_vocation == $char_vocation_key)
				$main_content .= ' checked="checked" ';
			$main_content .= '>'.htmlspecialchars($vocation_name[0][$char_vocation_key]).'</td>';
			if($currentVocCol % 3 == 0 || $currentVocCol == $maxCountVoc)
				$main_content .= "</tr>";
			$currentVocCol++;
		}
		$main_content .= '</table>';
	}
	if(count($config['site']['newchar_towns'][$world_id]) > 1) {
		$main_content .= '<table class="table tableSpacing"><tr><th>Select your city</th></tr>';
		foreach($config['site']['newchar_towns'][$world_id] as $town_id)
		{
			$main_content .= '<tr><td><input type="radio" name="newchartown" value="'.$town_id.'" ';
			if($newchar_town == $town_id)
				$main_content .= ' checked="checked" ';
			$main_content .= '>'.htmlspecialchars($towns_list[$world_id][$town_id]).'</td></tr>';
		}
		$main_content .= '</table>';
	}
	$main_content .= '
		<input type="submit" value="Create" class="btn">
		<a href="?subtopic=accountmanagement"><button>Back</button></a>
		</form>';
	}
	else
	{
	if(empty($newchar_name))
	$newchar_errors[] = 'Please enter a name for your character!';
	if(empty($newchar_sex) && $newchar_sex != "0")
	$newchar_errors[] = 'Please select the sex for your character!';
	if(count($config['site']['newchar_vocations'][$world_id]) > 1)
	{
	if(empty($newchar_vocation))
	$newchar_errors[] = 'Please select a vocation for your character.';
	}
	else
	$newchar_vocation = $config['site']['newchar_vocations'][$world_id][0];
	if(count($config['site']['newchar_towns'][$world_id]) > 1)
	{
	if(empty($newchar_town))
	$newchar_errors[] = 'Please select a town for your character.';
	}
	else
	$newchar_town = $config['site']['newchar_towns'][$world_id][0];
	if(empty($newchar_errors))
	{
	if(!check_name_new_char($newchar_name))
	$newchar_errors[] = 'This name contains invalid letters, words or format. Please use only a-Z, - , \' and space.';
	if($newchar_sex != 1 && $newchar_sex != "0")
	$newchar_errors[] = 'Sex must be equal <b>0 (female)</b> or <b>1 (male)</b>.';
	if(count($config['site']['newchar_vocations'][$world_id]) > 1)
	{
	$newchar_vocation_check = FALSE;
	foreach($config['site']['newchar_vocations'][$world_id] as $char_vocation_key => $sample_char)
	if($newchar_vocation == $char_vocation_key)
	$newchar_vocation_check = TRUE;
	if(!$newchar_vocation_check)
	$newchar_errors[] = 'Unknown vocation. Please fill in form again.';
	}
	else
	$newchar_vocation = 0;
	}
	if(empty($newchar_errors))
	{
	$check_name_in_database = new Player();
	$check_name_in_database->find($newchar_name);
	if($check_name_in_database->isLoaded())
	$newchar_errors[] .= 'This name is already used. Please choose another name!';
	$number_of_players_on_account = $account_logged->getPlayersList()->count();
	if($number_of_players_on_account >= $config['site']['max_players_per_account'])
	$newchar_errors[] .= 'You have too many characters on your account <b>('.$number_of_players_on_account.'/'.$config['site']['max_players_per_account'].')</b>!';
	}
	if(empty($newchar_errors))
	{
	$char_to_copy_name = $config['site']['newchar_vocations'][$world_id][$newchar_vocation];
	$char_to_copy = new Player();
	$char_to_copy->find($char_to_copy_name);
	if(!$char_to_copy->isLoaded())
	$newchar_errors[] .= 'Wrong characters configuration. Try again or contact with admin. ADMIN: Edit file config/config.php and set valid characters to copy names. Character to copy <b>'.htmlspecialchars($char_to_copy_name).'</b> doesn\'t exist.';
	}
	if(empty($newchar_errors))
	{
	// load items and skills of player before we change ID
	$char_to_copy->getItems()->load();
	$char_to_copy->loadSkills();
	if($newchar_sex == "0")
	$char_to_copy->setLookType(136);
	$char_to_copy->setID(null); // save as new character
	$char_to_copy->setLastIP(0);
	$char_to_copy->setLastLogin(0);
	$char_to_copy->setLastLogout(0);
	$char_to_copy->setName($newchar_name);
	$char_to_copy->setAccount($account_logged);
	$char_to_copy->setSex($newchar_sex);
	$char_to_copy->setTown($newchar_town);
	$char_to_copy->setPosX(0);
	$char_to_copy->setPosY(0);
	$char_to_copy->setPosZ(0);
	$char_to_copy->setWorldID((int) $world_id);
	$char_to_copy->setCreateIP(Visitor::getIP());
	$char_to_copy->setCreateDate(time());
	$char_to_copy->setSave(); // make character saveable
	$char_to_copy->save(); // now it will load 'id' of new player
	if($char_to_copy->isLoaded())
	{
	$char_to_copy->saveItems();
	$char_to_copy->saveSkills();
	$main_content .= '<p>The character <b>'.htmlspecialchars($newchar_name).'</b> has been created.<br />Please select the outfit when you log in for the first time.<br /><br /><b>See you on '.$config['server']['serverName'].'!</p>
		<div class="text-center">
			<a href="?subtopic=accountmanagement" class="btn">Back</a>
		</div>';
	}
	else
	{
	echo "Error. Can\'t create character. Probably problem with database. Try again or contact with admin.";
	exit;
	}
	}
	else
	{ 
		$main_content .= '<strong>The Following Errors Have Occurred:</strong><br><ul>';
		foreach($newchar_errors as $newchar_error)
			$main_content .= '<li>'.$newchar_error.'</li>';
		$main_content .= '</ul>';
		$main_content .= '<form action="?subtopic=accountmanagement&action=createcharacter" method="post">
		<input type="hidden" name="world" value="'.$world_id.'">
		<input type="hidden" name=savecharacter value="1">
		<table class="table tableSpacing">
			<tr>
				<td>Name</td>
				<td>
					<input id="newcharname" name="newcharname" onkeyup="checkName();" value="'.htmlspecialchars($newchar_name).'" maxlength="29">
					<br><font size="1" face="verdana,arial,helvetica"><div id="name_check">Please enter your character name.</div></font>
				</td>
			</tr>
			<tr>
				<td>Sex</td>
				<td>
					<input type="radio" name="newcharsex" value="1" style="width: 15%">Male
					<input type="radio" name="newcharsex" value="0" style="width: 15%">Female
				</td>
			</tr>
		</table>';
		$maxCountVoc = count($config['site']['newchar_vocations'][$world_id]);
		if($maxCountVoc > 1) {
			$main_content .= '<table class="table tableSpacing"><tr><th>Select your vocation</th></tr>';
			$currentVocCol = 1;
			foreach($config['site']['newchar_vocations'][$world_id] as $char_vocation_key => $sample_char)
			{
				if(($currentVocCol > 2 && $currentVocCol % 3 == 1))
					$main_content .= '<tr>';
				$main_content .= '<td style="width: 33.3%"><input type="radio" name="newcharvocation" style="width: 15%" value="'.$char_vocation_key.'" ';
				if($newchar_vocation == $char_vocation_key)
					$main_content .= ' checked="checked" ';
				$main_content .= '>'.htmlspecialchars($vocation_name[0][$char_vocation_key]).'</td>';
				if($currentVocCol % 3 == 0 || $currentVocCol == $maxCountVoc)
					$main_content .= "</tr>";
				$currentVocCol++;
			}
			$main_content .= '</table>';
		}
		if(count($config['site']['newchar_towns'][$world_id]) > 1) {
			$main_content .= '<table class="table tableSpacing"><tr><th>Select your city</th></tr>';
			foreach($config['site']['newchar_towns'][$world_id] as $town_id)
			{
				$main_content .= '<tr><td><input type="radio" name="newchartown" value="'.$town_id.'" ';
				if($newchar_town == $town_id)
					$main_content .= ' checked="checked" ';
				$main_content .= '>'.htmlspecialchars($towns_list[$world_id][$town_id]).'</td></tr>';
			}
			$main_content .= '</table>';
		}
		$main_content .= '
			<input type="submit" value="Create" class="btn">
			<a href="?subtopic=accountmanagement"><button>Back</button></a>
			</form>';
	}
	}
	}
	}
	}