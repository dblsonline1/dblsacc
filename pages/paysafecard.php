<?php
if(!defined('INITIALIZED'))
	exit;

if($logged)
{
	require_once('./custom_scripts/paysafecard/config.php');


	$main_content .='<section class="col-md-12 second">
		<div class="col-md-9 character">
			<div class="row">
				<div class="col-md-12 account-managment">
					<p>Buy Premium Points - REVE.DBNS.EU</p>
				</div>
			</div>


<div class="col-md-11 your_characters">
				<div class="info_header">
					<p>Paysafecard</p>
				</div>
				<div class="row">

			';




	$main_content .= "<center><br><br><img src='http://dbns.eu/images/psc.png' style='border:0' width='418' height='80' /></center><br><br>";

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
						foreach($psc_offers as $key => $offer)
						{
			$main_content.='<tr>
							<td><br>'. $offer['premium_points'] .'</td>
							<td><br>'. $offer['cost'] / 100 . ' PLN</td>
							<td>
							<form method="post" action="?subtopic=paysafecard_pay">
							<input type="hidden" name="id" value="' . $key . '">
							<div class="col-md-11 buttons"><input type="submit" value="Buy" class ="btn register-account"></div><br>
							</form>
							<br>
							</td>
							</tr>';
						}
						$main_content .='</tbody>
					</table>
				</div>
			</div>		';





	$main_content .= '</table></center><br><br><font color="white">Note:<br>Email is just for payment confirmation.<br>Email jest potrzebny tylko do potwierdzenia platnosci.</font><br><br>';
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

$main_content .='</div></div>';
