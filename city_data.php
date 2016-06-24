<?php
	include 'config.php';
	include_once 'libs/simple_html_dom.php';
	
	$db_table_mesta = 'mesta';
	$db_table_urady = 'urady';
	
	header('Content-Type: text/html; charset=utf-8');
	
	$query = "SELECT * FROM {$db_table_mesta}";
	
	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute();	
	} catch (PDOException $ex) {
		die("DB error1");
	}
	$row = $stmt->fetch();
	
	if (!$row) {
		
		$main_page = file_get_html('http://www.e-obce.sk/zoznam_vsetkych_obci.html?strana=0');
		
		$city_list = $main_page->find('div#telo')[0]->find('table')[0]->find('table')[0]->find('tr')[0]->childNodes(2)->find('table')[0];
		//echo $city_list;
		$k = 0;
		
		foreach(($city_list->find('a')) as $city) {
			$k++;
			
			$ref = $city->href;
			
			$city_page = file_get_html($ref);
			$city_tables = $city_page->find('div#telo')[0]->find('table')[0]->find('table')[0]->find('tr')[0]->childNodes(2);
			
			$city_table1 = $city_tables->getElementsByTagName('table')[0];
			//city nazov
			$city_nazov_element = $city_table1->getElementsByTagName('tr')[0]->getElementsByTagName('h1')[0]->plaintext;
			$words = count(explode(' ', $city_nazov_element));
			$city_nazov = "";
			for($i=1;$i<$words;$i++) {
				$city_nazov .= explode(' ', $city_nazov_element)[$i] . ' ';
			}
			//city erb
			$city_erb = $city_table1->childNodes(2)->getElementsByTagName('img')[0]->src;
			//city tel
			$city_tel = $city_table1->childNodes(2)->childNodes(3)->getElementsByTagName('td')[0]->plaintext;
			//city fax
			$city_fax = $city_table1->childNodes(3)->childNodes(2)->plaintext;
			//city email
			$city_email = $city_table1->childNodes(4)->childNodes(2)->childNodes(0)->plaintext;
			//city web
			$city_web = $city_table1->childNodes(5)->childNodes(2)->childNodes(0)->plaintext;
			//city urad
			$city_urad['ulica'] = $city_table1->childNodes(4)->childNodes(0)->plaintext;
			$urad_adresa = $city_table1->childNodes(5)->childNodes(0)->plaintext;
			$city_urad['mesto'] = explode(' ', $urad_adresa)[2];
			$city_urad['psc'] = explode(' ', $urad_adresa)[0] . ' ' . explode(' ', $urad_adresa)[1];
			
			$city_table2 = $city_tables->getElementsByTagName('table')[3];
			// city starosta
			$city_starosta = $city_table2->childNodes(7)->childNodes(1)->plaintext;
			
			//ulozenie erbu lokalne
			$erb_filename = 'erb' . $k . '.gif';
			$erbs_folder = 'erbs/' . $erb_filename;
			
			$ch = curl_init($city_erb);
			$fp = fopen($erbs_folder, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);
			
			//ziskanie suradnic mestskeho uradu
			$address = $city_urad['ulica'] . ',' . ($urad_adresa) . ',SK';
			$url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . (urlencode(utf8_encode($address))) . "&key={$google_apikey}";
			$geolocation = json_decode(file_get_contents($url));

			$city_urad['lat'] = $geolocation->results[0]->geometry->location->lat;
			$city_urad['lng'] = $geolocation->results[0]->geometry->location->lng;
			$lat = $city_urad['lat'];
			$lng = $city_urad['lng'];
			
			//ulozenie udajov do databazy
			$myquery1 = "INSERT INTO {$db_table_urady} (ulica, mesto, psc) VALUES (:ulica, :mesto, :psc)";
			$query_params1 = array(
				'ulica' => $city_urad['ulica'],
				'mesto' => $city_urad['mesto'],
				'psc' => $city_urad['psc']);
				
			try {
				$stmt1 = $db->prepare(($myquery1));
				$result1 = $stmt1->execute($query_params1);	
			} catch (PDOException $ex) {
				die("DB error");
			}
			
			$myquery2 = "INSERT INTO {$db_table_mesta} (nazov, starosta, urad, tel, fax, email, web, erb, lat, lng) 
					VALUES (:nazov, :starosta, last_insert_id(), :tel, :fax, :email, :web, :erb, :lat, :lng)";
			$query_params2 = array(
				'nazov' => $city_nazov,
				'starosta' => $city_starosta,
				'tel' => $city_tel,
				'fax' => $city_fax,
				'email' => $city_email,
				'web' => $city_web,
				'erb' => $erbs_folder,
				'lat' => $lat,
				'lng' => $lng);
				
			try {
				$stmt2 = $db->prepare(($myquery2));
				$result2 = $stmt2->execute($query_params2);	
			} catch (PDOException $ex) {
				die("DB error2:<br>" . $ex);
			}
			
			/*
			echo $city_nazov . '<br>';
			if ($k == 20)
				break;*/
		}
	}
	
	echo "<br>Data successfuly loaded.";
?>