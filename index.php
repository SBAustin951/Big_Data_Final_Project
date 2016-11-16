<?php
  $dbhost = 'localhost';
  $dbname = 'yelp';

  $m = new MongoClient();
  //echo "Connected to Mongo successfully";
  $db = $m->$dbname;
  //echo "Database $dbname selected <br/>";
?>

<html>
<head>
  <title>J.A.C. Reviews</title>
  <link href="css/style.css" rel="stylesheet" />
  <!-- import bootsrap -->
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script href="js/bootstrap.min.js"></script>
  <!-- import gmaps -->
  <script type="text/javascript" src="js/gmaps.js"></script>
</head>
<body style="background-image:url(http://cdn.paper4pc.com/images/bubbles-wallpaper-5.jpg)">
  <div class="row">
    <nav class="navbar navbar-fixed-top navbar-custom">
      <div class="col-md-6">
        <a class="navbar-brand" href="index.php" id="cname"><font color="white" >J.A.C. Reviews</font></a>
      </div>
      <div class="col-md-6">
        <form method="get" class="form-inline" id="search">
          <input type="text" placeholder="Keyword" name="key" class="form-control" />
          <input type="number" placeholder="Star Rating" name="stars" step="any" min="0.0" max="5.0" class="form-control" />
          <input type="text" placeholder="City Name" name="city" class="form-control" />
          <button type="submit" name="submit" class="btn btn-success">Search</button>
        </form>
      </div>
    </nav>
  </div>
  <div class="row">
    <div class="container-fluid" style="opacity: .7">
      <div style="height: 80%; margin-left: 10px; margin-right: 10px" class="col-md-6 jumbotron" style="width: 80%; border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px; opacity: " id="results">
        <?php
          if (isset($_GET['submit'])) {
            if (empty($_GET['key']) && empty($_GET['city']) && empty($_GET['stars'])) {
              echo "<p class='alert alert-warning' id='warning'>You Must Input At Least One Search Value</p>";
            } else {
              $keyWord = $_GET['key'];
              $cityName = $_GET['city'];
              $rating = $_GET['stars'];
              $query = array('name' => new MongoRegex("/$keyWord/i"), 'city' => new MongoRegex("/$cityName/i"), 'stars' => array('$gte' => (float)$rating));
              $result = $db->business->find($query)->sort(array('stars' => 1));

              foreach ($result as $r) {
                echo "<div class='panel panel-default'>";
                echo "<table class='table table-bordered'>";
                echo "<thead class='thead-inverse well'>";
                echo "<tr><th colspan='2'>".$r['name']."</th></tr></thead>";
                echo "<tbody><tr><td>Business Address</td>";
                echo "<td><br/><strong>".$r['full_address']."</strong></td></tr>";
                echo "<tr><td align='justify'>Star Rating</td>";
                echo "<td><br/><strong>".$r['stars']."</strong></td></tr>";
                echo "<tr><td align='justify'>Link:</td>";
                echo "<td><br/><strong><a href='https://twitter.com/search?q=".$keyWord."'>https://twitter.com/search?q=".$keyWord."</a></strong></td>";
                echo "</tr><tr><td align='justify'>Reviews</td>";
                echo "<td><br/><form method='post'><button type='submit' name='view-reviews' class='form-control btn btn-success'>View Reviews</button></form>";
                echo "</td></tr></tbody></table></div>";
              }
            }
          }
        ?>
      </div>
    </div>
  </div>
</body>
</html>
