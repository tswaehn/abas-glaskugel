<?php

function getOverdueOrders() {
	global $pdo;
	$orders = array();
	
	$sql = 'SELECT article_id, nummer, such, termconfirmed FROM ' . DB_ORDERS . ' WHERE termconfirmed != ""';
	//$result = $pdo->query( $sql);
	$result = dbExecute( $sql );
	
	if ($result) {
		$i = 0;
		$now = time();
		foreach($result as $item ) {
			$i++;
			if ($item['article_id'] < 1) {
				//avoid weird orders with article "-2"
				continue;
			}
					
			//extract timestamp from "date"
			$tmpdate = explode('.', $item['termconfirmed']);
			$day = $tmpdate[0];
			$month = $tmpdate[1];
			$year = $tmpdate[2];
			//$timestamp = strtotime($item['termconfirmed']);
			$timestamp = mktime(0, 0, 0, $month, $day, $year);
			
			//var_dump($item);
			//var_dump($timestamp);
			if ($timestamp < $now) {
				$orders[$timestamp + $i] = $item;
				//$orders[$item['nummer']] = $item;
			}
			
		}
	} 
	ksort($orders);
	return $orders;
}


echo '<h3>überfällige Bestellungen:</h3>';


$orders = getOverdueOrders();

if (empty($orders)) {
	echo 'keine Bestellungen gefunden';
} else {
	echo '<table>';
	echo '<tr><th>Artikel</th><th>Bestellung</th><th>Lieferant</th><th>Liefertermin</th></tr>';
	
	foreach ($orders as $order) {
		
		$artNr = $order['article_id'];
		$bestellung = $order['nummer'];
		$lieferant = $order['such'];
		$termin = $order['termconfirmed'];
		
		$article = getArticle($artNr)->fetch();
		$link = "?action=article&article_id=".$article["article_id"];
		
		echo '<tr>';
		echo '<td><a href="'.$link.'">'.$article['nummer'].' '.$article['such'].'</a></td>';
		echo "<td>$bestellung</td><td>$lieferant</td><td>$termin</td>";
		echo '</tr>';
	}
	
	
	echo "</table>";
}
?>