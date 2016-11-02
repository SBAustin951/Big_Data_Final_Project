<?php
  $dbhost = 'localhost';
  $dbname = 'yelp';

  $m = new MongoClient();
  echo "Connected to Mongo successfully";
  $db = $m -> $dbname;
  echo "Database $dbname selected <br/>";

  ini_set('max_execution_time', '-1');

  $data = fopen('../data/yelp_academic_dataset_review.json', 'r') or die("Couldn't handle");
  if($data) {
    while(!feof($data)) {
      $line = fgets($data, 4096);
      $db->review->save(json_decode($line, true));

      echo "<pre>";
      print_r("$data");
      echo "</pre>";
    }
  }
?>
