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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script href="js/bootstrap.min.js"></script>
  <!-- import gmaps -->
  <script type="text/javascript" src="js/gmaps.js"></script>
  <script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDAzxePfRURHOZ9FJgI1EHqWs_r3s_ypVo"></script>
  <script type="text/javascript">
	$(document).ready(function(){
		<?php
			if(isset($_GET['submit'])){
				if(empty($_GET['key']) && empty($_GET['city']) && empty($_GET['stars'])){
					echo "<p class='alert alert-warning' id='warning'>You Must Input At Least One Search Value</p>";
				} else{
					if(empty($_GET['city'])){
						$lat= 51.5073346;
						$lng= -0.1276831;
					} else{
						$address= urlencode($_GET['city']);
						$url= 'http://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false';
						$geocode= file_get_contents($url);
						$results= json_decode($geocode, true);
			
						if($results['status']=='OK'){
							$lat= $results['results'][0]['geometry']['location']['lat'];
							$lng= $results['results'][0]['geometry']['location']['lng'];
						}
					}
				?>
					var map= new GMaps({
						div: '#map',
						lat: <?php echo $lat ?>,
						lng: <?php echo $lng ?>,
					});
				<?php
					$keyWord= $_GET['key'];
					$cityName= $_GET['city'];
					$rating= $_GET['stars'];
					$query= array('name' => new MongoRegex("/$keyWord/i"), 'city' => new MongoRegex("/$cityName/i"), 	'stars' => array('$gte' => (float)$rating));
					$result= $db->business->find($query)->sort(array('stars' => 1));

					$list= "";
					foreach($result as $r){
						$address= addslashes(str_replace(array("\r","\n"), " ", $r['full_address']));
						
						$list.= "<div class='panel panel-default'>";
						$list.= "<table class='table table-bordered'>";
						$list.= "<thead class='thead-inverse'>";
						$list.= "<tr><th class='well' colspan='2'>".$r['name']."</th></tr></thead>";
						$list.= "<tbody><tr><td>Business Address</td>";
						$list.= "<td><br/><strong>".$address."</strong></td></tr>";
						$list.= "<tr><td align='justify'>Star Rating</td>";
						$list.= "<td><br/><strong>".$r['stars']."</strong></td></tr>";
						$list.= "<tr><td align='justify'>Link:</td>";
						$list.= "<td><br/><strong><a href='https://twitter.com/search?q=".addslashes($r['name'])."'>https://twitter.com/search?q=".addslashes($r['name'])."</a></strong></td>";
						$list.= "</tr></tbody></table></div>";
					?>
						map.addMarker({
							lat: <?php echo $r['latitude'] ?>,
							lng: <?php echo $r['longitude'] ?>,
							title: '<?php echo addslashes($r['name']) ?>',
							infoWindow:{
								content: '<p><?php echo addslashes($r['name']) ?><br><?php echo $r['stars'] ?> Stars<br><?php echo $address ?></p>'
							}
						});
					<?php
					}
					echo "$('#results').html(\"".$list."\");\n";
				}
			}
		?>
	});
  </script>
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
      <div style="height: 80%; padding-left: 10px; padding-right: 10px" class="col-md-6 jumbotron" style="width: 80%; border-radius: 10px;-moz-border-radius:10px;-webkit-border-radius:10px; opacity: " id="results"></div>
	  <div class="col-md-6" id="map_container">
		<h2 id="map_header">
			<?php
				if(empty($_GET['key']) && empty($_GET['city']) && empty($_GET['stars'])){}
				else{ echo $_GET['key']." in ".$_GET['city']." with at least ".$_GET['stars']." stars"; }
			?>
		</h2>
		<div id="map"></div>
	  </div>
    </div>
  </div>
</body>
</html>
