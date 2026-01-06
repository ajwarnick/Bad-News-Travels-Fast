<?php

header("Content-Type:application/json");

if(!empty($_GET['url']) && $_GET["key"] == "TxqaJFtRyHvrV4388x!MI7A^5kjWnK"){

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
	$url = $_GET["url"];
  $html = file_get_contents($url, false, $context);
  
	if( !false ){
    $dom = new DOMDocument();
    $internalErrors = libxml_use_internal_errors(true);
    $dom->loadHTML($html);

    $script = $dom->getElementsByTagName('script');
    $remove = [];
    foreach($script as $item)
    {
      $remove[] = $item;
    }

    foreach ($remove as $item)
    {
      $item->parentNode->removeChild($item); 
    }
    $html = $dom->saveHTML();

    $obj = [
      "url"=>$url,
      "html"=>base64_encode($html)
    ];
    //echo $html;
    response(200,"Yes Response",$obj);


	} else {
    response(200,"Error",NULL);
	}
	
} else {
	response(401,"Invalid Auth",NULL);
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

?>


