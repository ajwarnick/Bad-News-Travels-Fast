<?php

header("Content-Type:application/json");

if( !empty($_GET['url']) ){
    $url = $_GET["url"];
    $html = getArticle($url);
    $obj = [
      "url"=>$url,
      "html"=>base64_encode($html)
    ];
    response(200,"Yes Response",$obj);
}else{

    // https://lite.cnn.com/
    // response(401,"Invalid Auth",NULL);

    // $url = "https://lite.cnn.com/2026/01/06/politics/funding-child-care-democratic-states";
    response(200,"Error",NULL);
}


function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);
  error_log('Your message here');
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}

function getArticle($url){
    $context = stream_context_create(
			array(
				"http" => array(
					'method' 	=> 	"GET",
					'header'	=>	"Accept-language: en\r\n" .
              						"Cookie: foo=bar\r\n" .  // check function.stream-context-create on php.net
              						"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad 
        )
			)
	);
    $html = file_get_contents($url, false, $context);
    return $html;
}