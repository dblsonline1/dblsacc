<?php
if(!defined('INITIALIZED'))
	exit;

if($logged)
{
	require_once('./custom_scripts/paysafecard/config.php');
	
	$orderID = $_POST['id'];
	$offer = $psc_offers[$orderID];
	
	if(empty($offer)) {
		echo 'Error. Unknown offer.';
	} else {
		list($usec, $sec) = explode(" ", microtime());
		$token = $account_logged->getID() . substr($usec, -8) . $sec;

		$data = array(
			'uid' => $psc_uid,
			'public_key' => $psc_public_key,
			'amount' => $offer['cost'],
			'label' => "DBNS.EU",
			'control' => $token,
			'success_url' => urlencode($psc_success_url),
			'failure_url' => urlencode($psc_failure_url),
			'notify_url' => urlencode($psc_notify_url)
		);
		$data['crc'] = md5(join('', $data) . $psc_private_key);
		
		$SQL->query('INSERT INTO `PSC_Orders` (orderControl, orderType, status, accountID, orderTime) VALUES ('.$token.','.$orderID.',0,'.$account_logged->getID().',NOW())');
		
			// Link
		echo '<form method="post" name="paysafecard" action="https://ssl.homepay.pl/paysafecard/">';
		foreach($data as $field => $value)
		{
			echo '<input type="hidden" name="' . $field . '" value="' . $value . '">';
		}
		echo '</form>';
		
		echo '
<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Buy Premium Points - REVE.DBNS.EU</p>
				</div>
			</div>
						<div class="col-md-11 warning">
				<p>Za chwile zostaniesz przekierowany do strony wpłaty.</p>
		<p>Jeśli przekierowanie nie nastąpi w ciągu kilku sekund, kliknij <a href="#" onclick="document.paysafecard.submit(); return false;">tutaj</a>.</p>
		<script>setTimeout(function() { document.paysafecard.submit(); }, 2000);</script>

			</div>

<br>
	</div></section>


		';
	}
}
else
{
	echo 'You must login first.';
}