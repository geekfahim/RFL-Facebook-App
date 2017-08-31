<?php
	
	session_start();
	$id = $_GET['id']; //echo$id;
	$_SESSION["myId"] = $id;
	
	
	
	require_once __DIR__ . '/Facebook/autoload.php';
		$fb = new Facebook\Facebook([
		'app_id' => '1614004935574973',
		'app_secret' => '4cd3d9ca776a1c56a402c9217f6e6570',
		'default_graph_version' => 'v2.8',
	]); 

	$helper = $fb->getRedirectLoginHelper();
	$permissions = [ 'email','publish_actions']; // optional
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
			$loginUrl = $helper->getLoginUrl('https://rflplastics.info/test.php', $permissions);
			//echo "<script>window.top.location.href='".$loginUrl."'</script>";
			echo "<script>window.top.location.href='https://facebook.com/photo.php?fbid=".$response['id']."&makeprofile=1'</script>";

			exit;
		}
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}
	try {
		// message must come from the user-end
		$id=$_SESSION["myId"];echo$id;
		$response = $request->getGraphNode()->asArray();
		$id=$response['id'];//echo$id;
		$data = ['source' => $fb->fileToUpload('user_image/'.$id.'.png'), 'message' => ''];
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
	//echo $response['id'];
	header('Location:http://facebook.com/me/');
  	// Now you can redirect to another page and use the
  	// access token from $_SESSION['facebook_access_token']
} else {
	$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl('https://rflplastics.info/test.php', $permissions);
	echo "<script>window.top.location.href='".$loginUrl."'</script>";

	
}