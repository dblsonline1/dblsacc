<?php
// TEN SKRYPT TYLKO ODBIERA WIADOMOSCI Z HOMEPAY
// NIE POWINIEN BYC DOSTEPNY DLA NIKOGO SPOZA IP HOMEPAY

function check_ip()
{
	if(empty($_SERVER['REMOTE_ADDR']))
		return false;

	if(ini_get('allow_url_fopen') != 1)
		return gethostbyname("get.homepay.pl") == $_SERVER['REMOTE_ADDR'];

	$handle = fopen('http://get.homepay.pl/index.htm', 'r');
	$data = trim(stream_get_contents($handle));
	fclose($handle);
	return in_array($_SERVER['REMOTE_ADDR'], explode(',', $data));
}

require_once('custom_scripts/paysafecard/config.php');

if(!check_ip()) die;
if(!isset($_POST['json']) || empty($_POST['json'])) die;
$json = json_decode($_POST['json']);

$jsonresult = array();

foreach($json as $payment)
{
	$file = fopen('custom_scripts/paysafecard/reported_ids/' . $payment->psc_merchant_data . '.log', "w");
	
	$result = $SQL->query('SELECT * FROM `PSC_Orders` WHERE `orderControl` = ' . $payment->psc_merchant_data . ' LIMIT 1');
	
	if(is_object($result)) {
		fwrite($file, "IS OBJECT");
		$result = $result->fetch();
		
		fwrite($file, var_export($result, true));
		
		// Points not given
		if($result and $result['status'] == 0) {
			fwrite($file, "STATUS");
			$account = new Account($result['accountID']);
			if($account->isLoaded())
			{
				$order = $psc_offers[$result['orderType']];
				$account->setPremiumPoints($account->getPremiumPoints() + $order['premium_points']);
				$account->save();
			}
			
			$SQL->exec('UPDATE `PSC_Orders` SET `status`=3 WHERE `orderControl`=' . $payment->psc_merchant_data);
		}
	}
	
	fclose($file);
	
	array_push($jsonresult, array(
		'psc_id' => $payment->psc_id,
		'psc_return' => 1
	));
}

echo json_encode($jsonresult);
exit();