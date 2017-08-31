<?php
	$h = 0;
	session_start();
	require_once __DIR__ . '/Facebook/autoload.php';
	$fb = new Facebook\Facebook([
		'app_id' => '1614004935574973',
		'app_secret' => '4cd3d9ca776a1c56a402c9217f6e6570',
		'default_graph_version' => 'v2.8',
	]); 

	$helper = $fb->getRedirectLoginHelper();
	$permissions = [ 'email','publish_actions']; // optional

	# checking all the error or response
	try {
		$accessToken = $helper->getAccessToken();
	} catch (Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch (Facebook\Exceptions\FacebookSDKException $e) {

	}


	$loginUrl = $helper->getLoginUrl('https://rflplastics.info/valentine.php', $permissions);
	$_SESSION['fb_access_token'] = (string)$accessToken;

	// validating the access token
	try {

		$response = $fb->get('/me?fields=id,name,email,birthday,hometown', $accessToken);
		$requestPicture = $fb->get('/me/picture?redirect=false&width=320&height=320', $accessToken); //getting user picture
		$picture = $requestPicture->getGraphUser();

	} catch (Facebook\Exceptions\FacebookResponseException $e) {
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch (Facebook\Exceptions\FacebookSDKException $e) {

	}

if(isset($_POST['submit'])){
	$val = $_POST['submit'];
	$val=trim($val,".'");
	header('Location:test.php?id='.$val.'');

}


?>

<!DOCTYPE HTML>
<html lang="en-US">
	<head>
        <!--=============== Meta and Title  ===============-->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RFL Plastics Happy Valentines</title>
		<!-- favicon icon -->
		<link rel="shortcut icon" href="https://rflplastics.info/favicon.ico" type="image/x-icon">
		<link rel="icon" href="https://rflplastics.info/favicon.ico" type="image/x-icon">		
            <!-- Animate css -->
		<link rel="stylesheet" href="css/font-awesome.min.css" media="all" />   <!-- Font awesome css --> 					<!-- Slicknav css -->
		<link rel="stylesheet" href="css/bootstrap.min.css"/>  					<!-- Bootstrap -->			<!-- Switcher css -->				
		<link rel="stylesheet" href="css/style1.css"/>		
		<link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet">		<!-- Style css -->	<!-- Preloder css -->
	<!--[if lt IE 8]><link rel="stylesheet" href="assets/blueprint-css/ie.css" type="text/css" media="screen, projection"><![endif]-->
	</head>
	<body>
		<div id="canvas-holder">
			<canvas id="demo-canvas"></canvas>
		</div>
			<div class=""> <!-- Home area-->
				<div class="container">
					<h4 style="color:#fff; font-size: 28px;">RFL Plastics Happy Valentines</h4>
				</div>
				<hr>
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							<div class="col-sm-6 col-sm-offset-3">
								<div class="box">
									<!--<img src="img/watermark.png" alt="" /> <!-- Here is your logo -->
									<br>
									<?php
										if((!$picture['url'])) echo'<img src="img/layer.jpg" alt=""  width="325px"/> <br>';
									?>
									<?php if(!$picture['url']){
										echo '<br><a href="' . htmlspecialchars($loginUrl) . '"'.'class="btn btn-info btn-block btn-xl page-scroll maskbtn">
											<i class="fa fa-facebook"></i> &nbsp;&nbsp; Login With Facebook
											</a>'; 
									}
									
									
										?>
										
										<?php
										if (!isset($accessToken)) {
											if ($helper->getError()) {
												header('HTTP/1.0 401 Unauthorized');
												#echo "Error: " . $helper->getError() . "\n";
												#echo "Error Code: " . $helper->getErrorCode() . "\n";
												#echo "Error Reason: " . $helper->getErrorReason() . "\n";
												#echo "Error Description: " . $helper->getErrorDescription() . "\n";
												#echo 'You Must Have to Provide FB Permission';
												header('Location: https://rflplastics.info');
											} else {
												header('HTTP/1.0 400 Bad Request');
												//echo 'Bad request';
												//echo 'Please Login First!'; #echo 'Bad request';
											}
											//exit;

										}


										$user = $response->getGraphUser();
										$h = 1;
										//echo ' ' . $user['name'];
										//echo '<br>Email: ' . $user['email'];
										//echo '<br>Id: ' . $user['id'];


										$img = __DIR__ . '/user_image/' . $user['id'] . '.png';
										//file_put_contents($img, file_get_contents($picture['url']));

										imagepng(imagecreatefromstring(file_get_contents($picture['url'])), $img);

										//echo $picture['url'];
										$watermark = imagecreatefrompng('watermark.png');
										$watermark_width = imagesx($watermark);
										$watermark_height = imagesy($watermark);

										$image = imagecreatefrompng($img);
										$dest_x = 0;    // X-axis
										$dest_y = 0;    // Y-axis

										// since you are using 100% alpha, why bother with that param
										imagecopy($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height);
										//header('content-type: image/jpeg');
										imagejpeg($image, $img, 100);          #img is image file name with directory
										$myImage = 'user_image/' . $user['id'] . '.png';
										$lol=$user['id'];
										$_SESSION['userImage']=$myImage;
										imagedestroy($image);
										imagedestroy($watermark);

										session_unset();


										?>

										<?php

										if ($picture['url']) {
											$h = 1;
											echo "<img src='" . $myImage . "' width='325px' class='img-thumbnail'/>";
											//echo'<a href="'.$myImage.' download>';
											//echo '<a href="' . $myImage . '"  class="btn btn-info btn-block btn-xl page-scroll maskbtn" Post On Facebook><i class="fa fa-share"></i> &nbsp;&nbsp;Post On Facebook</a>'. '</br>';
											echo '</br>';
											echo '<a href="' . $myImage . '"  class="btn btn-info btn-block btn-xl page-scroll 		     maskbtn" download><i class="fa fa-download"></i> &nbsp;&nbsp;Download</a>';


										}
										?>

										<form action="" method="post">
											</br>
											<button type="submit" name="submit" value="'.<?echo $lol;?>.'" class="btn btn-info btn-block btn-xl page-scroll maskbtn">&nbsp;Post to Facebook</button>
										</form>

									
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="container">
					<div class="row">
						<div class="col-sm-12">
							Developed By:
														
						</div>
					</div>
				</div>


			
			</div>	<!-- //Home area-->
			
		<script src="js/jquery-1.9.1.min.js"></script>	              	<!-- Main js file -->				<!-- Carousel js file -->
		<script src="js/canvas.js"></script>     							<!-- Switcher js file -->

	</body>
</html>


<?php

	function myFunction(){
	
		/*session_start();
		require_once __DIR__ . '/Facebook/autoload.php';
		$fb = new Facebook\Facebook([
			'app_id' => '1614004935574973',
			'app_secret' => '4cd3d9ca776a1c56a402c9217f6e6570',
			'default_graph_version' => 'v2.8',
		]); */

		$helper = $fb->getRedirectLoginHelper();
		$permissions = ['email', 'publish_actions']; // optional
	try {
		if (isset($_SESSION['facebook_access_token'])) {
		$accessToken = $_SESSION['facebook_access_token'];
		} else {
			$accessToken = $helper->getAccessToken();
		}
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	 }
	if (isset($accessToken)) {
		if (isset($_SESSION['facebook_access_token'])) {
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		} else {
			$_SESSION['facebook_access_token'] = (string) $accessToken;
			// OAuth 2.0 client handler
			$oAuth2Client = $fb->getOAuth2Client();
			// Exchanges a short-lived access token for a long-lived one
			$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
			$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		}
		
		// validating the access token
		try {
			$request = $fb->get('/me');
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			if ($e->getCode() == 190) {
				unset($_SESSION['facebook_access_token']);
				$helper = $fb->getRedirectLoginHelper();
				$loginUrl = $helper->getLoginUrl('https://rflplastics.info/valentine.php', $permissions);
				echo "<script>window.top.location.href='".$loginUrl."'</script>";
				exit;
			}
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		try {
			// message must come from the user-end
			$data = ['source' => $fb->fileToUpload(__DIR__.'/valentine.png'), 'message' => 'my photo'];
			$request = $fb->post('/me/photos', $data);
			$response = $request->getGraphNode()->asArray();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}
		echo $response['id'];
		// Now you can redirect to another page and use the
		// access token from $_SESSION['facebook_access_token']
	} else {
		$helper = $fb->getRedirectLoginHelper();
		$loginUrl = $helper->getLoginUrl('https://rflplastics.info/valentine.php', $permissions);
		echo "<script>window.top.location.href='".$loginUrl."'</script>";

	}
	
	
	
	}


?>