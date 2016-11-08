<?php

  if (isset($_GET['submit'])) {
    $dbhost = 'localhost';
    $dbname = 'yelp';

    $m = new MongoClient();
    //echo "Connected to Mongo successfully";
    $db = $m->$dbname;
    //echo "Database $dbname selected <br/>";
  }
?>

<html>
<head>
  <title>My Website</title>
  <link href="css/style.css" rel="stylesheet" />

  <!-- import bootsrap -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script href="js/bootstrap.min.js"></script>

  <!-- import gmaps -->
  <script type="text/javascript" src="js/gmaps.js"></script>
</head>
<body>
  <div class="row">
    <nav class="navbar navbar-fixed-top navbar-dark bg-primary">
      <div class="col-md-8">
        <a class="navbar-brand" href="index.html" id="cname">Company Name</a>
      </div>
      <div class="col-md-4">
        <form method="get" class="form-inline" id="search">
          <input type="text" placeholder="Keyword" name="key" class="form-control" />
          <input type="text" placeholder="City Name" name="city" class="form-control" />
          <button type="submit" name="submit" class="btn btn-success">Search</button>
        </form>
      </div>
    </nav>
  </div>
  <div class="row">
    <div class="container-fluid">
      <div class="col-md-6" id="results">
<?php
          if (!empty($_GET['key']) && !empty($_GET['city'])) {
            $keyWord = $_GET['key'];
            $cityName = $_GET['city'];
            $query = array('name' => new MongoRegex("/$keyWord/i"), 'city' => new MongoRegex("/$cityName/i"));
            $result = $db->business->find($query)->sort(array('stars' => 1));

            foreach ($result as $r) {
?>
              <div class='panel panel-default'>
                <table class='table table-bordered'>
                  <thead class='thead-inverse'>
                      <tr>
                        <th colspan='2'><?php echo $r['name'] ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Business Address</td>
                        <td><br/>
                          <strong><?php echo $r['full_address'] ?></strong>
                        </td>
                      </tr>
                      <tr>
                        <td align='justify'>Star Rating</td>
                        <td><br/>
                          <strong><?php echo $r['stars'] ?></strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
<?php
            }
          } else if (!empty($_GET['key'])) {
            $keyWord = $_GET['key'];
            $query = array('name' => new MongoRegex("/$keyWord/i"));
            $result = $db->business->find($query)->sort(array('stars' => 1));

            foreach ($result as $r) {
?>
                <div class='panel panel-default'>
                  <table class='table table-bordered'>
                    <thead class='thead-inverse'>
                      <tr>
                        <th colspan='2'><?php echo $r['name'] ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Business Address</td>
                        <td><br/>
                          <strong><?php echo $r['full_address'] ?></strong>
                        </td>
                      </tr>
                      <tr>
                        <td align='justify'>Star Rating</td>
                        <td><br/>
                          <strong><?php echo $r['stars'] ?></strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
<?php
            }
          } else if (!empty($_GET['city'])) {
            $cityName = $_GET['city'];
            $query = array('city' => new MongoRegex("/$cityName/i"));
            $result = $db->business->find($query)->sort(array('stars' => 1));

            foreach ($result as $r) {
?>
              <div class="col-md-12" id="results">
                <div class='panel panel-default'>
                  <table class='table table-bordered'>
                    <thead class='thead-inverse'>
                      <tr>
                        <th colspan='2'><?php echo $r['name'] ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Business Address</td>
                        <td><br/>
                          <strong><?php echo $r['full_address'] ?></strong>
                        </td>
                      </tr>
                      <tr>
                        <td align='justify'>Star Rating</td>
                        <td><br/>
                          <strong><?php echo $r['stars'] ?></strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
<?php
            }
          }
?>
      </div>
      <div class="col-6-md" id="results">
<?php
        //put in the google maps code here
?>
      </div>
    </div>
  </div>
</body>
</html>
