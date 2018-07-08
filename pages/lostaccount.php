<?php
if(!defined('INITIALIZED'))
	exit;



$main_content .= $defaultContentLostAcccount;

if($config['site']['send_emails'])
{
	if($action == '')
	{
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
						<h3>
							<div id="login-form-button" class="inline-block gold">Lost Account</div> 
						</h3>
						
					<form action="?subtopic=lostaccount&action=step1" onsubmit="return validate_form(this)" method="post">
						<input type="text" placeholder="Character name" name="nick" value="" size="40" class="width-420 margin-bottom-20 login-input login-account-input"/>
						<font color="a9a9a9"><b>What do you want?</b></font><br>
					<div class="radios">
						  <div>
							<input id="radio1" type="radio" name="action_type" value="email" checked="checked"><label for="radio1"><span><span></span></span><font color="a9a9a9">Send me new password and my account name to account e-mail adress.</font></label>
						  </div>
						  <div>
							<input id="radio2" type="radio" name="action_type" value="reckey"><label for="radio2"><span><span></span></span><font color="a9a9a9">I got recovery key and want set new password and e-mail adress to my account.</font></label>
						  </div>
					</div>
					<font color="a9a9a9" size="1">The Lost Account Interface can help you to get back your account name and password. </font><br>
                        <input type="submit" class="form-submit" value="Submit"/>
					</form>
					</div>
		';
		

	}
	elseif($action == 'step1' && $_REQUEST['action_type'] == '')
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Please select action.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

';
	elseif($action == 'step1' && $_REQUEST['action_type'] == 'email')
	{
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('next_email') < time())
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
						<h3>
							<div id="login-form-button" class="inline-block gold">Lost Accountt</div> 
						</h3>
						
						<form action="?subtopic=lostaccount&action=sendcode" method="post">
						    <input type=hidden name="character" value="">
							<input type=text name="nick" value="'.htmlspecialchars($nick).'" size="40" readonly="readonly" class="width-420 margin-bottom-20 login-input login-account-input"/><br>
							<input type="text" placeholder="E-mail" name="email" value="" size="40" class="width-420 margin-bottom-20 login-input login-account-input" id="account_name"/>
							<input type="submit" class="form-submit-fixed" value="Submit"/>							
						</form>
					</div>';
				else
				{
					$insec = $account->getCustomField('next_email') - time();
					$minutesleft = floor($insec / 60);
					$secondsleft = $insec - ($minutesleft * 60);
					$timeleft = $minutesleft.' minutes '.$secondsleft.' seconds';
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Account of selected character (<b>'.htmlspecialchars($nick).'</b>) received e-mail in last '.ceil($config['site']['email_lai_sec_interval'] / 60).' minutes. You must wait '.$timeleft.' before you can use Lost Account Interface again.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
					';
				}
			}
			else
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
			
			';
		}
		else
			$main_content .= '	<div class="col-md-6 login-panel-left text-center">
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Invalid player name format. If you have other characters on account try with other name.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
			';

	}
	elseif($action == 'sendcode')
	{
		$email = $_REQUEST['email'];
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('next_email') < time())
				{
					if($account->getEMail() == $email)
					{
						$acceptedChars = '123456789zxcvbnmasdfghjklqwertyuiop';
						$newcode = NULL;
						for($i=0; $i < 30; $i++) {
							$cnum[$i] = $acceptedChars{mt_rand(0, 33)};
							$newcode .= $cnum[$i];
						}
						$mailBody = '
<html>
<body>
    <h3>Your account name and password!</h3>
    <p>You or someone else requested new password for  your account on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a> with this e-mail.</p>
    <p>Account name: '.htmlspecialchars($account->getName()).'</p>
    <p>Password: <i>You will set new password when you press on link.</i></p>
    <br />
    <p>Press on link to set new password. This link will work until next >new password request< in Lost Account Interface.</p>
    <p><a href="'.$config['server']['url'].'/?subtopic=lostaccount&action=checkcode&code='.urlencode($newcode).'&character='.urlencode($nick).'">'.$config['server']['url'].'/?subtopic=lostaccount&action=checkcode&code='.urlencode($newcode).'&character='.urlencode($nick).'</a></p>
    <p>or open page: <i>'.$config['server']['url'].'/?subtopic=lostaccount&action=checkcode</i> and in field "code" write <b>'.htmlspecialchars($newcode).'</b></p>
    <br /><p>If you don\'t want to change password to your account just delete this e-mail.
    <p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
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
						$mail->AddAddress($account->getCustomField('email'));
						$mail->Subject = $config['server']['serverName']." - Link to >set new password to account<";
						$mail->Body = $mailBody;
						if($mail->Send())
						{
							$account->set('email_code', $newcode);
							$account->set('next_email', (time() + $config['site']['email_lai_sec_interval']));
							$account->save();
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Link with informations needed to set new password has been sent to account e-mail address. You should receive this e-mail in 15 minutes. Please check your inbox/spam directory.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>						
							';
						}
						else
						{
							$account->set('next_email', (time() + 60));
							$account->save();
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: An error occorred while sending email! Try again or contact with admin.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>	';
							
						
						}
					}
					else
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error:Invalid e-mail to account of character <b>'.htmlspecialchars($nick).'</b>. Try again.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>				
				';
				}
				else
				{
					$insec = $account->getCustomField('next_email') - time();
					$minutesleft = floor($insec / 60);
					$secondsleft = $insec - ($minutesleft * 60);
					$timeleft = $minutesleft.' minutes '.$secondsleft.' seconds';
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Account of selected character (<b>'.htmlspecialchars($nick).'</b>) received e-mail in last '.ceil($config['site']['email_lai_sec_interval'] / 60).' minutes. You must wait '.$timeleft.' before you can use Lost Account Interface again.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>	
					
					';
				}
			}
			else
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>	
			';
		}
		else
			$main_content .= '
		<a href="?subtopic=lostaccount" class="form-submit">Back</a>
		Invalid player name format. If you have other characters on account try with other name.';
	}
	elseif($action == 'step1' && $_REQUEST['action_type'] == 'reckey')
	{
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
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
						<h3>
							<div id="login-form-button" class="inline-block gold">Lost Accountt</div> 
						</h3>
						
						<form action="?subtopic=lostaccount&action=step2" method="post">
						    <input type=hidden name="character" value="">
							<input type=text name="nick" value="'.htmlspecialchars($nick).'" size="40" readonly="readonly" class="width-420 margin-bottom-20 login-input login-account-input"/><br>
							<input type="text" placeholder="Recovery key" name="key" value="" size="40" class="width-420 margin-bottom-20 login-input login-account-input" id="reckey"/>
							<input type="submit" class="form-submit-fixed" value="Submit"/>							
						</form>
					</div>

';
				}
				else
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Account of this character has no recovery key.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>	';
			}
			else
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>	
			';
		}
		else
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
		<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
			  <div>
                    <form id="temp" action="?subtopic=lostaccount" method="post">
						<br><br>
						<b><font color="orange">Error: Invalid player name format. If you have other characters on account try with other name.</font></b>
						
						<br><br><br><br><br>
						
                        <input type="submit" class="form-submit" value="Back"/>
                        <div class="pull-right social">
                            Find us on: 
                            <a href="https://www.facebook.com/dbnewstory/?fref=ts">
                                <img src="layouts/main/images/fb-social.png"/>
                            </a>
                        </div>
                    </form>
                </div>
            </div>	
		';
	}
	elseif($action == 'step2')
	{
		$rec_key = trim($_REQUEST['key']);
		$nick = $_REQUEST['nick'];
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					if($account_key == $rec_key)
					{
						$main_content .= '
<script type="text/javascript">
    function validate_required(field,alerttxt)
    {
        with (field)
        {
            if (value==null||value==""||value==" ")
            {alert(alerttxt);return false;}
            else {return true}
        }
    }
    function validate_email(field,alerttxt)
    {
        with (field)
        {
            apos=value.indexOf("@");
            dotpos=value.lastIndexOf(".");
            if (apos<1||dotpos-apos<2)
            {alert(alerttxt);return false;}
            else {return true;}
        }
    }
    function validate_form(thisform)
    {
        with (thisform)
        {
            if (validate_required(email,"Please enter your e-mail!")==false)
            {email.focus();return false;}
            if (validate_email(email,"Invalid e-mail format!")==false)
            {email.focus();return false;}
            if (validate_required(passor,"Please enter password!")==false)
            {passor.focus();return false;}
            if (validate_required(passor2,"Please repeat password!")==false)
            {passor2.focus();return false;}
            if (passor2.value!=passor.value)
            {alert(\'Repeated password is not equal to password!\');return false;}
            }
        }
</script>';
$tempErrorMSG = '';
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
						<h3>
							<div id="login-form-button" class="inline-block gold">Lost Accountt</div> 
						</h3>
						
						<form action="?subtopic=lostaccount&action=step3" onsubmit="return validate_form(this)" method=post>
						    <input type="hidden" name="character" value="">
							<input type="text" name="nick" value="'.htmlspecialchars($nick).'" size="40" readonly="readonly" class="width-420 margin-bottom-20 login-input login-account-input"/><br>
							<input type="password" placeholder="Password" name="passor" value="" size="40" class="width-420 margin-bottom-20 login-input login-account-input" id="passor"/>
							<input type="password" placeholder="Repeat Password" name="passor2" value="" size="40" class="width-420 margin-bottom-20 login-input login-account-input" id="passor2"/>
							<input type="text" placeholder="New e-mail address" name="email" value="" size="40" class="width-420 margin-bottom-20 login-input login-account-input" id="email"/>
							<input type="hidden" name="key" value="'.htmlspecialchars($rec_key).'">
							<input type="submit" class="form-submit-fixed" value="Submit"/>							
						</form>
					</div>
';
					}
					else
						$tempErrorMSG .= 'Wrong recovery key!';
				}
				else
					$tempErrorMSG .= 'Account of this character has no recovery key!';
			}
			else
				$tempErrorMSG .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$tempErrorMSG .= 'Invalid player name format. If you have other characters on account try with other name.';
		
	if($tempErrorMSG != '') 
		{
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
				<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
					  <div>
							<form id="temp" action="?subtopic=lostaccount" method="post">
								<br><br>
								<b><font color="orange">Error: '.$tempErrorMSG.'</font></b>
								
								<br><br><br><br><br>
								
								<input type="submit" class="form-submit" value="Back"/>
								<div class="pull-right social">
									Find us on: 
									<a href="https://www.facebook.com/dbnewstory/?fref=ts">
										<img src="layouts/main/images/fb-social.png"/>
									</a>
								</div>
							</form>
						</div>
					</div>
		';
		}
		
	}

	elseif($action == 'step3')
	{
		$rec_key = trim($_REQUEST['key']);
		$nick = $_REQUEST['nick'];
		$new_pass = trim($_REQUEST['passor']);
		$new_email = trim($_REQUEST['email']);
		$tempRKMessage = '';
		if(check_name($nick))
		{
			$player = new Player();
			$account = new Account();
			$player->find($nick);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				$account_key = $account->getCustomField('key');
				if(!empty($account_key))
				{
					if($account_key == $rec_key)
					{
						if(check_password($new_pass))
						{
							if(check_mail($new_email))
							{
								$account->setEMail($new_email);
								$account->setPassword($new_pass);
								$account->set('email_code', '');
								$account->save();

				
				
				
                if($account->getCustomField('next_email') < time())
                {
                $mailBody = '
                <html>
                <body>
                    <h3>Your account name and new password!</h3>
                    <p>Changed password and e-mail to your account in Lost Account Interface on server <a href="'.$config['server']['url'].'"><b>'.$config['server']['serverName'].'</b></a></p>
                    <p>Account name: <b>'.htmlspecialchars($account->getName()).'</b></p>
                    <p>New password: <b>'.htmlspecialchars($new_pass).'</b></p>
                    <p>E-mail: <b>'.htmlspecialchars($new_email).'</b> (this e-mail)</p>
                    <br />
                    <p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
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
            $mail->AddAddress($account->getCustomField('email'));
            $mail->Subject = $config['server']['serverName']." - New password to your account";
            $mail->Body = $mailBody;
            if($mail->Send())
            {
            $tempRKMessage .= '<br /><small>
                Sent e-mail with your account name and password to new e-mail. You should receive this e-mail in 15 minutes. You can login now with new password!';
                }
                else
                {
                $tempRKMessage .= '<br /><small>
                    An error occorred while sending email! You will not receive e-mail with this informations.';
                    }
                    }
                    else
                    {
                    $tempRKMessage .= '<br /><small>
                        You will not receive e-mail with this informations.';
                        }
                        $tempRKMessage .= '<input type=hidden name="account_login" value="'.$account->getId().'">
                        <input type=hidden name="password_login" value="'.htmlspecialchars($new_pass).'">

						
						';
							}
							else
								$tempRKMessage .= 'Wrong e-mail format.';
						}
						else
							$tempRKMessage .= 'Wrong password format. Use only a-Z, A-Z, 0-9';
					}
					else
						$tempRKMessage .= 'Wrong recovery key!';
				}
				else
					$tempRKMessage .= 'Account of this character has no recovery key!';
			}
			else
				$tempRKMessage .= 'Player or account of player <b>'.htmlspecialchars($nick).'</b> doesn\'t exist.';
		}
		else
			$tempRKMessage .= 'Invalid player name format. If you have other characters on account try with other name.';
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
				<h3><div id="login-form-button" class="inline-block gold ">Lost Account</div> </h3>
					  <div>
							<form action="?subtopic=accountmanagement" onsubmit="return validate_form(this)" method=post>
							<input type=hidden name="character" value="">							
								<br><br>
								<b><font color="green">Account name: '.htmlspecialchars($account->getName()).'</font></b>	<br>
								<b><font color="green">New password: '.htmlspecialchars($new_pass).'</font></b>	<br>
								<b><font color="green">New email: '.htmlspecialchars($new_email).'</font></b>	<br>
								';
								if($tempRKMessage != '') {
									$main_content .= '<font color="orange"><b>';
									$main_content .= $tempRKMessage;	
									$main_content .= '</b></font></small><br>';
								};
								
								$main_content .='
								
								<input type="submit" class="form-submit" value="Back"/>
								<div class="pull-right social">
									Find us on: 
									<a href="https://www.facebook.com/dbnewstory/?fref=ts">
										<img src="layouts/main/images/fb-social.png"/>
									</a>
								</div>
							</form>
						</div>
					</div>	
		';
	}
	elseif($action == 'checkcode')
	{
		$code = trim($_REQUEST['code']);
		$character = trim($_REQUEST['character']);
		if(empty($code) || empty($character))
			$main_content .= 'Please enter code from e-mail and name of one character from account. Then press Submit.<br>
<form action="?subtopic=lostaccount&action=checkcode" method=post>
    <table cellspacing=1 cellpadding=4 border=0 width=100%>
        <tr><td bgcolor="'.$config['site']['vdarkborder'].'" class=white><b>Code & character name</b></td></tr>
        <tr>
            <td bgcolor="'.$config['site']['darkborder'].'">
                Your code:&nbsp;<input type=text name="code" value="" size="40" )><br />
                Character:&nbsp;<input type=text name="character" value="" size="40" )><br />
            </td>
        </tr>
    </table>
    <br>
    <table cellspacing=0 cellpadding=0 border=0 width=100%>
        <tr>
            <td>
                <center>
                    <input type="submit" class="btn" value="Submit">
                </center>
            </td>
        </tr>
</form></TABLE></TABLE>';
		else
		{
			$player = new Player();
			$account = new Account();
			$player->find($character);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('email_code') == $code)
				{
					$main_content .= '
<script type="text/javascript">
    function validate_required(field,alerttxt)
    {
        with (field)
        {
            if (value==null||value==""||value==" ")
            {alert(alerttxt);return false;}
            else {return true}
        }
    }

    function validate_form(thisform)
    {
        with (thisform)
        {
            if (validate_required(passor,"Please enter password!")==false)
            {passor.focus();return false;}
            if (validate_required(passor2,"Please repeat password!")==false)
            {passor2.focus();return false;}
            if (passor2.value!=passor.value)
            {alert(\'Repeated password is not equal to password!\');return false;}
            }
        }
</script>
					Please enter new password to your account and repeat to make sure you remember password.<br>
<form action="?subtopic=lostaccount&action=setnewpassword" onsubmit="return validate_form(this)" method=post>
    <input type=hidden name="character" value="'.htmlspecialchars($character).'">
    <input type=hidden name="code" value="'.htmlspecialchars($code).'">
    <table cellspacing=1 cellpadding=4 border=0 width=100%>
        <tr><td bgcolor="'.$config['site']['vdarkborder'].'" class=white><b>Code & account name</b></td></tr>
        <tr>
            <td bgcolor="'.$config['site']['darkborder'].'">
                New password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=password id="passor" name="passor" value="" size="40" )><br />
                Repeat new password:&nbsp;<input type=password id="passor2" name="passor2" value="" size="40" )><br />
            </td>
        </tr>
    </table>
    <br>
    <table cellspacing=0 cellpadding=0 border=0 width=100%>
        <tr>
            <td>
                <center>
                    <input type="submit" value="Submit" class="btn">
                </center>
            </td>
        </tr>
</form></TABLE></TABLE>';
				}
				else
					$error= 'Wrong code to change password.';
			}
			else
				$error = 'Account of this character or this character doesn\'t exist.';
		}
		if(!empty($error))
					$main_content .= '<font color="red"><b>'.$error.'</b></font><br />Please enter code from e-mail and name of one character from account. Then press Submit.<br>
<form action="?subtopic=lostaccount&action=checkcode" method=post>
    <table cellspacing=1 cellpadding=4 border=0 width=100%>
        <tr><td bgcolor="'.$config['site']['vdarkborder'].'" class=white><b>Code & character name</b></td></tr>
        <tr>
            <td bgcolor="'.$config['site']['darkborder'].'">
                Your code:&nbsp;<input type=text name="code" value="" size="40" )><br />
                Character:&nbsp;<input type=text name="character" value="" size="40" )><br />
            </td>
        </tr>
    </table>
    <br>
    <table cellspacing=0 cellpadding=0 border=0 width=100%>
        <tr>
            <td>
                <center>
                    <input type="submit" value="Submit" class="btn">
                </center>
            </td>
        </tr>
</form></TABLE></TABLE>';
	}
	elseif($action == 'setnewpassword')
	{
		$newpassword = $_REQUEST['passor'];
		$code = $_REQUEST['code'];
		$character = $_REQUEST['character'];
		$main_content .= '';
		if(empty($code) || empty($character) || empty($newpassword))
			$main_content .= '<font color="red"><b>Error. Try again.</b></font><br />Please enter code from e-mail and name of one character from account. Then press Submit.<br>
<br>
<form action="?subtopic=lostaccount&action=checkcode" method=post>
    <table cellspacing=0 cellpadding=0 border=0 width=100%>
        <tr>
            <td>
                <center>
                    <input type="submit" value="Submit" class="btn">
                </center>
            </td>
        </tr>
</form></TABLE></TABLE>';
		else
		{
			$player = new Player();
			$account = new Account();
			$player->find($character);
			if($player->isLoaded())
				$account = $player->getAccount();
			if($account->isLoaded())
			{
				if($account->getCustomField('email_code') == $code)
				{
					if(check_password($newpassword))
					{
						$account->setPassword($newpassword);
						$account->set('email_code', '');
						$account->save();
						$main_content .= 'New password to your account is below. Now you can login.<br>
<input type="hidden" name="character" value="'.htmlspecialchars($character).'">
<table cellspacing=1 cellpadding=4 border=0 width=100%>
    <tr><td bgcolor="'.$config['site']['vdarkborder'].'" class=white><b>Changed password</b></td></tr>
    <tr>
        <td bgcolor="'.$config['site']['darkborder'].'">
            New password:&nbsp;<b>'.htmlspecialchars($newpassword).'</b><br />
            Account name:&nbsp;&nbsp;&nbsp;<i>(Already on your e-mail)</i><br />';
            $mailBody = '
            <html>
            <body>
                <h3>Your account name and password!</h3>
                <p>Changed password to your account in Lost Account Interface on server <a href="'.$config['server']['url'].'"><b>'.htmlspecialchars($config['server']['serverName']).'</b></a></p>
                <p>Account name: <b>'.htmlspecialchars($account->getName()).'</b></p>
                <p>New password: <b>'.htmlspecialchars($newpassword).'</b></p>
                <br />
                <p><u>It\'s automatic e-mail from OTS Lost Account System. Do not reply!</u></p>
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
        $mail->AddAddress($account->getCustomField('email'));
        $mail->Subject = $config['server']['serverName']." - New password to your account";
        $mail->Body = $mailBody;
        if($mail->Send())
        {
        $main_content .= '<br /><small>
            New password work! Sent e-mail with your password and account name. You should receive this e-mail in 15 minutes. You can login now with new password!';
            }
            else
            {
            $main_content .= '<br /><small>
                New password work! An error occorred while sending email! You will not receive e-mail with new password.';
                }
                $main_content .= '
    </td>
</tr>
</table>
<br>
<table cellspacing=0 cellpadding=0 border=0 width=100%>
    <tr>
        <td>
            <center>
                <form action="?subtopic=accountmanagement" method=post>
                    <input type="submit" value="Submit" class="btn">
				</form>
            </center>
        </td>
    </tr>
</table></TABLE>';
					}
					else
						$error= 'Wrong password format. Use only a-z, A-Z, 0-9.';
				}
				else
					$error= 'Wrong code to change password.';
			}
			else
				$error = 'Account of this character or this character doesn\'t exist.';
		}
		if(!empty($error))
					$main_content .= '<font color="red"><b>'.$error.'</b></font><br />Please enter code from e-mail and name of one character from account. Then press Submit.<br>
<form action="?subtopic=lostaccount&action=checkcode" method=post>
    <table cellspacing=1 cellpadding=4 border=0 width=100%>
        <tr><td bgcolor="'.$config['site']['vdarkborder'].'" class=white><b>Code & character name</b></td></tr>
        <tr>
            <td bgcolor="'.$config['site']['darkborder'].'">
                Your code:&nbsp;<input type=text name="code" value="" size="40" )><br />
                Character:&nbsp;<input type=text name="character" value="" size="40" )><br />
            </td>
        </tr>
    </table>
    <br>
    <table cellspacing=0 cellpadding=0 border=0 width=100%>
        <tr>
            <td>
                <center>
                    <input type="submit" value="Submit" class="btn">
                </center>
            </td>
        </tr>
</form></TABLE></TABLE>';
	}
}
else
	$main_content .= '<b>Account maker is not configured to send e-mails, you can\'t use Lost Account Interface. Contact with admin to get help.</b>';