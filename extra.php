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
		<meta charset="utf-8">
		<title>J.A.C. Reviews - <?php echo addslashes($_GET['name']) ?></title>
		<link href="CSS/style.css" rel="stylesheet"/>
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Tangerine">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script href="js/bootstrap.min.js"></script>

		<script type="text/javascript" src="js/gmaps.js"></script>
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=AIzaSyDAzxePfRURHOZ9FJgI1EHqWs_r3s_ypVo"></script>
		<script type="text/javascript">
			<?php
				$lat= 0;
				$lng= 0;

				$address= urlencode($_GET['address']);
				$url= 'http://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&sensor=false';
				$geocode= file_get_contents($url);
				$results= json_decode($geocode, true);

				if($results['status']=='OK'){
					$lat= $results['results'][0]['geometry']['location']['lat'];
					$lng= $results['results'][0]['geometry']['location']['lng'];
				}
			?>
			$(document).ready(function(){
				var streetView= GMaps.createPanorama({
					el: '#streetview',
					lat: '<?php echo $lat ?>',
					lng: '<?php echo $lng ?>',
				});
				<?php
					$list= "";

          $bid = $_GET['id'];
					$query= array('business_id' => new MongoRegex("/$bid/"));
					$result= $db->review->find($query);


					foreach($result as $r){
						// $user= "";
						// $uQuery= array('user_id' => $r['user_id']);
						// $userSet= $db->user->find($uQuery);
						// foreach(userSet as $u){ $user= $u['name']; }

						$list.= "<div class='panel panel-primary'>";
						$list.= "<table class='table table-bordered'>";
						$list.= "<thead class='thead-inverse'>";
						$list.= "<tr><th class='well' colspan='2'>".$r['stars']."</th></tr></thead>";
						$list.= "<tbody><tr><td>".$r['text']."</td></tr></table></div>";
					}

					echo "$('#reviews').html(\"".$list."\");\n";
				?>
			});
		</script>
	</head>
	<body>
	<div class="container-fluid">
		<div class="row transparent">
			<nav class="navbar navbar-fixed-top navbar-custom">
				<div class="col-md-12">
					<a class="navbar-brand" href="index.php" id="cname"><font>J.A.C.Reviews <?php echo $_GET['name'] ?></font></a>
				</div>
				<div class>
			</nav>
		</div>
		<div class="row">
			<div class="col-md-12" id="info">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#reviews">Reviews</a></li>
					<li><a data-toggle="tab" href="#streetview">Streetview</a></li>
				</ul>
				<div class="tab-content">
					<div id="reviews" class="tab-pane fade in active" style="width: 600px; height: 550px"></div>
					<div id="streetview" class="tab-pane fade" style="width: 600px; height: 550px"></div>
				</div>
			</div>
		</div>
	</body>
</html>
