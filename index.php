<?php

  // Run this file to put *all* the data up every month

  // Socrata Engine
  require_once("socrata.php");

  // Your credentials
  $root_url = "";
  $app_token = "";
  $database_id = "";
  $username = "";
  $password = "";
  $response = NULL;

  // Create a new authenticated client - include your own email address (username) and password
  $socrata = new Socrata($root_url, $app_token, $username, $password);

  // Post (upsert) the data //

  // Get the current year and month
  $current_date = date('Y-m') . '-01';

  // Create date range to grab data from
  $begin = strtotime($current_date . ' -6 months');
  $begin = new DateTime( date('r', $begin) );
  $end = new DateTime( $current_date );
  $end = $end->modify( '+1 month' ); 
  $interval = new DateInterval('P1M');
  $daterange = new DatePeriod($begin, $interval, $end);

  // Loop through each month in the data range
  foreach ($daterange as $date) { 

    // Get the raw data from the source
    $raw_data = file_get_contents('http://data.police.uk/api/crimes-street/all-crime?poly=51.43679175906548,-2.537071721655479:51.3840416929049,-2.693332146933394:51.33580603567935,-2.722335982183581:51.28053702063033,-2.63378054454681:51.26769052673961,%20-2.4655663254478890:51.31645836802215,-2.269721466895822:51.44412549355397,-2.2767188218925220:51.43883340410438,-2.527320958128073:51.43679175906548,-2.53707172165547&date=' . $date->format('Y-m'));

    // Decode the data
    $decoded_data = json_decode($raw_data, true);

    $formatted_rows_2 = array();

    // Loop through the decoded data
    foreach ($decoded_data as $data_row) {
      $formatted_row_2 = array(
        "crime_id" => $data_row['id'],
        "month" => $data_row['month'],
        "crime_category" => $data_row['category'], 
        "location" => '(' . $data_row['location']['latitude'] . ',' . $data_row['location']['longitude'] . ')',
        "street_name" => $data_row['location']['street']['name'],
        "location_type" => $data_row['location_type'],
        "location_subtype" => $data_row['location_subtype'],
        "outcome_status_category" => $data_row['outcome_status']['category'],
        "outcome_status_date" => $data_row['outcome_status']['date']
      );

      // Add the data to the array
      array_push($formatted_rows_2, $formatted_row_2);
    }

    // Re-encode the array as JSON and upsert it to Socrata
    $response = $socrata->post("/resource/" . $database_id, json_encode($formatted_rows_2));

    // Output some info to the browser
    echo 'Getting data for ' . $date->format('Y-m') . '... Done!<br />';
  }

  // Output when finished
  echo '<br /> All data imported!';
  
?>