<?php

function normalize_mac($mac)
	{
	  // Poista pisteet
	  $mac =  str_replace(".", "", $mac);

	  // replace dashes with colons
	  $mac =  str_replace("-", ":", $mac);

	  // counting colons
	  $colon_count = substr_count ($mac , ":");

	  // insert enough colons if none exist
	  if ($colon_count == 0)
	  {
		$mac =  substr_replace($mac, ":", 2, 0);
		$mac =  substr_replace($mac, ":", 5, 0);
		$mac =  substr_replace($mac, ":", 8, 0);
		$mac =  substr_replace($mac, ":", 11, 0);
		$mac =  substr_replace($mac, ":", 14, 0);
		$mac =  substr_replace($mac, ":", 17, 0);
		$mac =  substr_replace($mac, ":", 20, 0);
	  }

	  // Muunna isoiksi kirjaimiksi
	  $mac = strtoupper($mac);

	  return $mac;
	}
	
?>