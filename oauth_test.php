<?php
session_start();

$api_url = 'https://www.cryptohopper.com';
$redirect_url = 'http://localhost/code-samples/oauth_test.php';

$app_key = $_SESSION['app_key'];
$app_secret = $_SESSION['app_secret'];

if(isset($_POST['app_key']) && isset($_POST['app_secret'])){
	if(!empty($_POST['app_key']) && !empty($_POST['app_secret'])){
		$app_key = $_POST['app_key'];
		$app_secret = $_POST['app_secret'];
		$_SESSION['app_key'] = $app_key;
		$_SESSION['app_secret'] = $app_secret;
	}else{
		$app_key = 'IZJEke43t5Aw9wOps9pBB6bJZcv3H88ns6Wbikpl1uW2HZ3Z8ejNchcLJWiUzGQV';
		$app_secret = 'wHs2xDe7ax8UObJuWsJ1t8WJWpQb9OZ8g8pBAN2tFpuZKGv8LGRAWPs2egWBlFxa';
		$_SESSION['app_key'] = $app_key;
		$_SESSION['app_secret'] = $app_secret;
	}
	$_SESSION['method'] = $_POST['method'];
	$_SESSION['state'] = $_POST['state'];

	$path = '/oauth/'.$_SESSION['method'].'?app_key='.$app_key.'&state='.urlencode($_SESSION['state']).'&redirect_uri='.urlencode($redirect_url);
	$signature = hash_hmac('sha512', $path, $app_secret);
	header('Location: '.$api_url.$path.'&signature='.$signature);
}

$data = '';
if(is_array($_GET) && !empty($_GET)){
	$data = json_encode($_GET, JSON_PRETTY_PRINT);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>API Test</title><!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#defaultNavbar1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
      <a class="navbar-brand" href="#">Cryptohopper OAuth Tester</a></div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="defaultNavbar1">

      <ul class="nav navbar-nav navbar-right">
        <li><a href="https://www.cryptohopper.com" target="_blank">Website</a></li>
        <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">API<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="https://www.cryptohopper.com/developers" target="_blank">Developers</a></li>
            <li><a href="https://www.cryptohopper.com/api-reference" target="_blank">API Reference</a></li>
            <li><a href="https://www.cryptohopper.com/developer-apps" target="_blank">Apps</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!-- /.navbar-collapse -->
  </div>
  <!-- /.container-fluid -->
</nav>
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
       <form class="form-horizontal" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
        <div class="form-group">
			<label class="col-sm-2 control-label">App key</label>
			<div class="col-sm-10">
					<input type="password" class="form-control" name="app_key" value="<?php echo $app_key;?>">
			</div>
		  </div>
        <div class="form-group">
			<label class="col-sm-2 control-label">App secret</label>
			<div class="col-sm-10">
					<input type="password" class="form-control" name="app_secret" value="<?php echo $app_secret;?>">
			</div>
		  </div>
        <div class="form-group">
			<label class="col-sm-2 control-label">Operation</label>
			<div class="col-sm-10">
				<select class="form-control" name="method">
					<option value="access_tokens" <?php if(!$_SESSION['method'] || $_SESSION['method'] == 'access_tokens'){echo 'selected';}?>>access_tokens</option>
				</select>
			</div>
		  </div>
		    <div class="form-group">
			<label class="col-sm-2 control-label">State</label>
			<div class="col-sm-10">
				<textarea class="form-control" name="state" rows="5" cols="20"><?php echo $_SESSION['state'];?></textarea>
			</div>
		  </div>
		   <div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			  <button type="submit" class="btn btn-default">Submit</button>
			</div>
		  </div>

      </form>
    </div>
  </div>
  <hr>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
      <h1 class="text-center">API Test result</h1>
    </div>
  </div>
</div>
<div class="container">
	<?php

if(!empty($data)){


	echo '<h3>Endpoint: <strong>'.$_SESSION['method'].'</strong></h3>';
	echo '<h3>State: <strong>'.$_SESSION['state'].'</strong></h3><br><hr>';
	echo '<h3>Result</h3><br><br><pre>';
	echo $data;
	echo '</pre><br><br>';
}else{
	echo '<p class="bg-info" style="padding:20px;">No API test results to show.</p>';

}// POST
	?>
	<hr>
  <div class="row">
    <div class="text-center col-md-6 col-md-offset-3">
      <p>Copyright &copy; 2018 &middot; All Rights Reserved &middot; <a href="http://www.cryptohopper.com/" >Cryptohopper</a></p>
    </div>
  </div>
  <hr>
</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</body>
</html>