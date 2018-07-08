<?php
if(!defined('INITIALIZED'))
	exit;

if($logged)
{
	require_once('./custom_scripts/paypal/config.php');

		$main_content .='<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Buy Premium Points - REVE.DBNS.EU</p>
				</div>
			</div>


<div class="col-md-11 your_characters">
				<div class="info_header">
					<p>PayPal</p>
				</div>
				<div class="row">

			';

			$main_content .= "<center><br><br><img src='http://logok.org/wp-content/uploads/2014/05/PayPal-logo-20071.png' style='border:0' width='350' height='150' /></center><br><br>";

	echo '<font color = "white">Automatic PayPal shop system</div></article></h2><br><b>Here are the steps you need to make:</b><br>
	1. You need a valid creditcard <b>or</b> a PayPal account with a required amount of money.<br>
	2. Choose how many points you want buy.<br />
	3. Click on the donate/buy button.<br>
	4. Make a transaction on PayPal.<br>
	5. After the transaction points will be automatically added to your account.<br>
	6. Go to Item shop and use your points.</font></b><br /><br />';


	$main_content .= '	<div class="col-md-11 your_characters">
		
				<div class="row">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Points</th>
								<th>Price</th>
								<th>Order</th>
							</tr>
						</thead>
						<tbody>';
						foreach($paypals as $paypal)
						{
			$main_content.='
							<tr>
							<td>'. $paypal['premium_points'] .'</td>
							<td>'. $paypal['money_amount'] .' ' . $paypal['money_currency'] . '</td>


							<td>
							<form method="post" action="https://www.paypal.com/cgi-bin/webscr">
							<input type="hidden" name="id" value="' . $key . '">
							<div class="col-md-11 buttons"><input type="submit" value="Buy" class ="btn register-account"></div><br>
								<input type="hidden" name="cmd" value="' . $paypal_payment_type . '">
								<input type="hidden" name="business" value="' . $paypal['mail'] . '">
								<input type="hidden" name="item_name" value="' . htmlspecialchars($paypal['name']) . '">
								<input type="hidden" name="custom" value="' . $account_logged->getID() . '">
								<input type="hidden" name="amount" value="' . htmlspecialchars($paypal['money_amount']) . '">
								<input type="hidden" name="currency_code" value="' . htmlspecialchars($paypal['money_currency']) . '">
								<input type="hidden" name="no_note" value="0">
								<input type="hidden" name="no_shipping" value="1">
								<input type="hidden" name="notify_url" value="' . $paypal_report_url . '">
								<input type="hidden" name="return" value="' . $paypal_return_url . '">
								<input type="hidden" name="rm" value="0">
							</form>
							<br>
							</td>

							</tr>';
						}
						$main_content .='</tbody>
					</table>
				</div>
			</div>		';

}
else
{
	$main_content .= '<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Buy Premium Points - REVE.DBNS.EU</p>
				</div>
			</div>
						<div class="col-md-11 warning">
				<p>You must login first</p>

			</div>

<br>
	<div class="col-md-11 buttons">
		<a href="?subtopic=accountmanagement" class="btn change-password">Login</a>

	</div>
			';
}