<?php
if(!defined('INITIALIZED'))
	exit;

//CREATE ACCOUNT FORM PAGE
if($action == "")
{
	$main_content .= '
<script type="text/javascript">

    var accountHttp;

    function checkAccount()
    {
        if(document.getElementById("account_name").value=="")
        {
            document.getElementById("acc_name_check").innerHTML = \'<font color="red">Please enter account name.</font>\';
            return;
        }
        accountHttp=GetXmlHttpObject();
        if (accountHttp==null)
        {
            return;
        }
        var account = document.getElementById("account_name").value;
        var url="?subtopic=ajax_check_account&account=" + account + "&uid="+Math.random();
        accountHttp.onreadystatechange=AccountStateChanged;
        accountHttp.open("GET",url,true);
        accountHttp.send(null);
    }

    function AccountStateChanged()
    {
        if (accountHttp.readyState==4)
        {
            document.getElementById("acc_name_check").innerHTML=accountHttp.responseText;
        }
    }

    var emailHttp;

    //sprawdza czy dane konto istnieje czy nie
    function checkEmail()
    {
        if(document.getElementById("email").value=="")
        {
            document.getElementById("email_check").innerHTML = \'<font color="red">Please enter e-mail.</font>\';
            return;
        }
        emailHttp=GetXmlHttpObject();
        if (emailHttp==null)
        {
            return;
        }
        var email = document.getElementById("email").value;
        var url="?subtopic=ajax_check_email&email=" + email + "&uid="+Math.random();
        emailHttp.onreadystatechange=EmailStateChanged;
        emailHttp.open("GET",url,true);
        emailHttp.send(null);
    }

    function EmailStateChanged()
    {
        if (emailHttp.readyState==4)
        {
            document.getElementById("email_check").innerHTML=emailHttp.responseText;
        }
    }

    function validate_required(field,alerttxt)
    {
        with (field)
        {
            if (value==null||value==""||value==" ")
            {
                alert(alerttxt);
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    function validate_email(field,alerttxt)
    {
        with (field)
        {
            apos=value.indexOf("@");
            dotpos=value.lastIndexOf(".");
            if (apos<1||dotpos-apos<2)
            {
                alert(alerttxt);
                return false;
            }
            else
            {
                return true;
            }
        }
    }

    function validate_form(thisform)
    {
        with (thisform)
        {
            if(validate_required(account_name,"Please enter name of new account!")==false)
            {
                account_name.focus();
                return false;
            }
            if(validate_required(email,"Please enter your e-mail!")==false)
            {
                email.focus();
                return false;
            }
            if(validate_email(email,"Invalid e-mail format!")==false)
            {
                email.focus();
                return false;
            }
            if(verifpass==1)
            {
                if(validate_required(passor,"Please enter password!")==false)
                {
                    passor.focus();
                    return false;
                }
                if (validate_required(passor2,"Please repeat password!")==false)
                {
                    passor2.focus();
                    return false;
                }
                if(passor2.value!=passor.value)
                {
                    alert(\'Repeated password is not equal to password!\');
					return false;
                }
            }
            if(verifya==1)
            {
                if (validate_required(verify,"Please enter verification code!")==false)
                {
                    verify.focus();return false;
                }
            }
            if(rules.checked==false)
            {
                alert(\'To create account you must accept server rules!\');
				return false;
            }
        }
    }
</script>';

$main_content .= '
            <div class="col-md-6 login-panel-left text-center">
                <h3>Revelations Dragon Ball</h3>
                <div>Creating Account...</div>
				<br>
                <ul>
					<li>Your account name can only contain letters a-Z and numbers 0-9</li>
                    <li>Make sure your password is safe</li>
                    <li>E-mail is really important, it can help You to recover Your account</li>
                    <li>Secure Your account with Recovery Key</li>
                    <li>Don\'t share your account\'s informations (account number/password etc.) with other players</li>
                    <li>The staff is not responsible for loss of items or accounts. The only responsible is the users themselves</li>
                    <li>By creating an account You accept the RULES</li>
                    <li>We reserve all rights to user accounts, objects and characters</li>
                </ul>
            </div>
					<div class="col-md-6 login-panel-right no-padding">
						<h3>
							<div id="login-form-button" class="inline-block gold">Create Account</div> 
						</h3>
						
						<form action="?subtopic=createaccount&action=saveaccount" onsubmit="return validate_form(this)" method="post">
							<script type="text/javascript">var verifya=1;</script>
							<script type="text/javascript">var verifpass=1;</script>
							<input type="text" placeholder="Account name" class="width-420 margin-bottom-20 login-input login-account-input" id="account_name" name="reg_name" onkeyup="checkAccount();" maxlength=18/>
							<input type="password" placeholder="Password" class="width-420 margin-bottom-20 login-input login-password-input" id="passor" name="reg_password" maxlength=50/>
							<input type="password" placeholder="Repeat password" class="width-420 margin-bottom-20 login-input login-password-input" id="passor2" name="reg_password2" maxlength=50/>
							<input type="text" placeholder="E-mail" class="width-420 margin-bottom-20 login-input login-email-input" id="email" name="reg_email" onkeyup="checkEmail();" maxlength=50/>
							<input type="text" placeholder="Verification Code " class="width-420 margin-bottom-20 login-input login-password-input" name="reg_code" size=30 maxlength=50/>
							<input type="submit" class="form-submit-fixed" value="Register"/>
							<img src="?subtopic=imagebuilder&image_refresher='.mt_rand(1,99999).'" border="0" alt="Image Verification is missing, please contact the administrator" align="right" hspace="15">
							
						</form>

					</div>
	';
/*
			if(!$config['site']['create_account_verify_mail'])
				$main_content .= '
					<script type="text/javascript">var verifpass=1;</script>
					<tr>
						<td width="150">Password: </td>
						<td colspan="2"><input type="password" id="passor" name="reg_password" value="" size=30 maxlength=50></td>
					</tr>
					<tr>
						<td width="150">Repeat password: </td>
						<td colspan="2"><input type="password" id="passor2" name="reg_password2" value="" size=30 maxlength=50></td>
					</tr>';
			else
				$main_content .= '<script type="text/javascript">var verifpass=0;</script>';
			
			if($config['site']['verify_code'])
				$main_content .= '
				<script type="text/javascript">var verifya=1;</script>
				<tr>
					<td width="150">Verification Code: </td>
					<td colspan="2"><input id="verify" name="reg_code" value="" size=30 maxlength=50></td>
					<td><img src="?subtopic=imagebuilder&image_refresher='.mt_rand(1,99999).'" border="0" alt="Image Verification is missing, please contact the administrator"></td>
					<tr>

			</tr>
				</tr>';
			else
				$main_content .= '<script type="text/javascript">var verifya=0;</script>';
*/
			
}
//CREATE ACCOUNT PAGE (save account in database)
if($action == "saveaccount")
{
	$reg_name = strtoupper(trim($_POST['reg_name']));
	$reg_email = trim($_POST['reg_email']);
	$reg_password = trim($_POST['reg_password']);
	$reg_code = trim($_POST['reg_code']);
	//FIRST check
	//check e-mail
	if(empty($reg_name))
		$reg_form_errors[] = "Please enter account name.";
	elseif(!check_account_name($reg_name))
		$reg_form_errors[] = "Invalid account name format. Use only A-Z and numbers 0-9.";
	if(empty($reg_email))
		$reg_form_errors[] = "Please enter your email address.";
	else
	{
		if(!check_mail($reg_email))
			$reg_form_errors[] = "E-mail address is not correct.";
	}
	if($config['site']['verify_code'])
	{
		//check verification code
		$string = strtoupper($_SESSION['string']);
		$userstring = strtoupper($reg_code);
		session_destroy();
		if(empty($string))
			$reg_form_errors[] = "Information about verification code in session is empty.";
		else
		{
			if(empty($userstring))
				$reg_form_errors[] = "Please enter verification code.";
			else
			{
				if($string != $userstring)
					$reg_form_errors[] = "Verification code is incorrect.";
			}
		}
	}
	//check password
	if(empty($reg_password) && !$config['site']['create_account_verify_mail'])
		$reg_form_errors[] = "Please enter password to your new account.";
	elseif(!$config['site']['create_account_verify_mail'])
	{
		if(!check_password($reg_password))
			$reg_form_errors[] = "Password contains illegal chars (a-z, A-Z and 0-9 only!) or lenght.";
	}
	//SECOND check
	//check e-mail address in database
	if(empty($reg_form_errors))
	{
		if($config['site']['one_email'])
		{
			$test_email_account = new Account();
			//load account with this e-mail
			$test_email_account->findByEmail($reg_email);
			if($test_email_account->isLoaded())
				$reg_form_errors[] = "Account with this e-mail address already exist in database.";
		}
		$account_db = new Account();
		$account_db->find($reg_name);
		if($account_db->isLoaded())
			$reg_form_errors[] = 'Account with this name already exist.';
	}
	// ----------creates account-------------(save in database)
	if(empty($reg_form_errors))
	{
		//create object 'account' and generate new acc. number
		if($config['site']['create_account_verify_mail'])
		{
			$reg_password = '';
			for ($i = 1; $i <= 6; $i++)
				$reg_password .= mt_rand(0,9);
		}
		$reg_account = new Account();
		// saves account information in database
		$reg_account->setName($reg_name);
		$reg_account->setPassword($reg_password);
		$reg_account->setEMail($reg_email);
		$reg_account->setGroupID(1);
		$reg_account->setCreateDate(time());
		$reg_account->setCreateIP(Visitor::getIP());
		$reg_account->setFlag(Website::getCountryCode(long2ip(Visitor::getIP())));
		if(isset($config['site']['newaccount_premdays']) && $config['site']['newaccount_premdays'] > 0)
		{
			$reg_account->set("premdays", $config['site']['newaccount_premdays']);
			$reg_account->set("lastday", time());
		}
		$reg_account->save();
		//show information about registration
		if($config['site']['send_emails'] && $config['site']['create_account_verify_mail'])
		{
			$mailBody = '
<html>
<body>
    <h3>Your account name and password!</h3>
    <p>You or someone else registred on server <a href="'.$config['server']['url'].'">'.htmlspecialchars($config['server']['serverName']).'</a> with this e-mail.</p>
    <p>Account name: '.htmlspecialchars($reg_name).'</p>
    <p>Password: '.htmlspecialchars(trim($reg_password)).'</p>
    <br />
    <p>After login you can:</p>
    <li>Create new characters
    <li>Change your current password
    <li>Change your current e-mail
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
			$mail->AddAddress($reg_email);
			$mail->Subject = $config['server']['serverName']." - Registration";
			$mail->Body = $mailBody;
			if($mail->Send())
			{
				$main_content .= 'Your account has been created. Check your e-mail. See you in Tibia!<br><br>';
				$main_content .= '<table>
    <tr><td bgcolor="'.$config['site']['vdarkborder'].'" class=white>Account Created</td></tr>
    <tr>
        <td bgcolor="'.$config['site']['darkborder'].'">
            <table border=0 cellpadding=1>
                <tr>
                    <td>
                        <br>Your account name is '.$reg_name.'.
                        <br><i>You will receive e-mail ('.htmlspecialchars($reg_email).') with your password.</i><br>';
                        $main_content .= 'You will need the account name and your password to play on '.htmlspecialchars($config['server']['serverName']).'.
                        Please keep your account name and password in a safe place and
                        never give your account name or password to anybody.<br><br>';
                        $main_content .= '<br /><small>
                            These informations were send on email address '.htmlspecialchars($reg_email).'. Please check your inbox/spam folder.';
                            }
                            else
                            {
                            $main_content .= '<br /><small>An error occorred while sending email! Account not created. Try again.</small>';
                            $reg_account->delete();
                            }
                            }
                            else
                            {
							$main_content .= '
							 <div class="col-md-6 login-panel-left text-center">
								<h3>Revelations Dragon Ball</h3>
								<div>Creating Account...</div>
								<br>
								<ul>
					<li>Your account name can only contain letters a-Z and numbers 0-9</li>
                    <li>Make sure your password is safe</li>
                    <li>E-mail is really important, it can help You to recover Your account</li>
                    <li>Secure Your account with Recovery Key</li>
                    <li>Don\'t share your account\'s informations (account number/password etc.) with other players</li>
                    <li>The staff is not responsible for loss of items or accounts. The only responsible is the users themselves</li>
                    <li>By creating an account You accept the RULES</li>
                    <li>We reserve all rights to user accounts, objects and characters</li>
								</ul>
							</div>
						<div class="col-md-6 login-panel-right no-padding">
						<h3>
							<div id="login-form-button" class="inline-block gold">Account created</div> 
						</h3>
						
						<ul><font color="white">
							<li>Your account has been created.<br> Now you can login and create your first character. See you in Tibia!</li>
							<li>Your account name is '.htmlspecialchars($reg_name).'<br></li>
							<li>You will need the account name and your password to play on '.htmlspecialchars($config['server']['serverName']).'.
                                                    Please keep your account name and password in a safe place and
                                                    never give your account name or password to anybody.<br><br>
							</li>
							</font><br><br><center>
							<a href="?subtopic=accountmanagement">
							  <img src="images/buttons/play-1.png" height="86" width="257" 
							  />
							</a></center>
						</ul>
							';
						 if($config['site']['send_emails'] && $config['site']['send_register_email'])
							{
								$mailBody = '
								<html>
								<body>
									<h3>Your account name and password!</h3>
									<p>You or someone else registred on server <a href="'.$config['server']['url'].'">'.htmlspecialchars($config['server']['serverName']).'</a> with this e-mail.</p>
									<p>Account name: '.htmlspecialchars($reg_name).'</p>
									<p>Password: '.htmlspecialchars(trim($reg_password)).'</p>
									<br />
									<p>After login you can:</p>
									<li>Create new characters
									<li>Change your current password
									<li>Change your current e-mail
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
										$mail->AddAddress($reg_email);
										$mail->Subject = $config['server']['serverName']." - Registration";
										$mail->Body = $mailBody;
									if($mail->Send())
										$main_content .= '<br /><small>
										These informations were send on email address '.htmlspecialchars($reg_email).'.';
								else
										$main_content .= '<br /><small>An error occorred while sending email ('.htmlspecialchars($reg_email).')!</small>';
								}
                            }
                        }
                        else
                        {
							//SHOW ERRORs if data from form is wrong
							$main_content .= '
            <div class="col-md-6 login-panel-left text-center">
                <h3>Revelations Dragon Ball</h3>
                <div>Creating Account...</div>
				<br>
                <ul>
					<li>Your account name can only contain letters a-Z and numbers 0-9</li>
                    <li>Make sure your password is safe</li>
                    <li>E-mail is really important, it can help You to recover Your account</li>
                    <li>Secure Your account with Recovery Key</li>
                    <li>Don\'t share your account\'s informations (account number/password etc.) with other players</li>
                    <li>The staff is not responsible for loss of items or accounts. The only responsible is the users themselves</li>
                    <li>By creating an account You accept the RULES</li>
                    <li>We reserve all rights to user accounts, objects and characters</li>
                </ul>
            </div>
					<div class="col-md-6 login-panel-right no-padding">
						<h3>
							<div id="login-form-button" class="inline-block gold">List of occured errors</div> 
						</h3>
						
						<ul>
	';
							//$main_content .= 'List of occured errors: <br><ul>';
							foreach($reg_form_errors as $show_msg)
							{
								$main_content .= '<li><font color="white">'.$show_msg.'</font></li>';
							}
							$main_content .= '
							</ul>
								<form action="?subtopic=createaccount" method="post">
									<input type="submit" class="btn" name="Back" alt="Back" value="Back">
								</form></div>';
						}
}