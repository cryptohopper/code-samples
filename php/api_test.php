<?php

$access_token = '';

if(isset($_POST['access-token'])){
    if(!empty($_POST['access-token'])){
        $access_token = $_POST['access-token'];
    }
}
$api_url = 'https://api.cryptohopper.com';


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
            <a class="navbar-brand" href="#">Cryptohopper API Tester</a></div>
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
                    <label class="col-sm-2 control-label">Access token</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" name="access-token" value="<?php echo $access_token;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Operation</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo $api_url;?>/v1/</span>
                            <input type="text" class="form-control" name="operation" value="<?php if(isset($_POST['operation'])){ echo $_POST['operation'];}?>">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Method</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="method">
                            <option value="GET" <?php if(isset($_POST['method']) && $_POST['method'] == 'GET'){echo 'selected';}?>>GET</option>
                            <option value="POST" <?php if(isset($_POST['method']) && $_POST['method'] == 'POST'){echo 'selected';}?>>POST</option>
                            <option value="PATCH" <?php if(isset($_POST['method']) && $_POST['method'] == 'PATCH'){echo 'selected';}?>>PATCH</option>
                            <option value="DELETE" <?php if(isset($_POST['method']) && $_POST['method'] == 'DELETE'){echo 'selected';}?>>DELETE</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">Data</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="data" rows="5" cols="20"><?php if(isset($_POST['data'])){ echo $_POST['data'];}?></textarea>
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

    if(isset($_POST['operation']) && isset($_POST['method'])){

        $operation = $_POST['operation'];
        $method = $_POST['method'];

        $path = '/v1/'.$operation;

        $headers = array(
            'access-token: '.$access_token,
        );

        $data_string = $_POST['data'];

        $ch = curl_init($api_url.$path);

        if($method == 'POST'){
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($data_string);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }elseif($method == 'GET'){
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        }elseif($method == 'PATCH'){
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($data_string);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }elseif($method == 'DELETE'){
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($data_string);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        $result_json = json_decode($result, true);
        if(is_array($result_json)){
            $result_json = json_encode($result_json, JSON_PRETTY_PRINT);
        }

        echo '<h3>Endpoint: <strong>'.$operation.'</strong></h3>';
        echo '<h3>Method: <strong>'.$method.'</strong></h3>';
        echo '<h3>Status code: <strong>'.$httpcode.'</strong></h3><br><hr>';
        echo '<h3>Result</h3><br><br><pre>';
        if(!empty($result_json)){
            echo $result_json;
        }else{
            echo $result;
        }
        echo '</pre><br><br>';
        if(!empty($error)){
            echo '<h3>Error</h3><br><br>';
            echo $error;
        }
        curl_close($ch);
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