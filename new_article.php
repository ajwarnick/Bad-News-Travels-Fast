<?php

header("Content-Type:application/json");

if(!empty($_GET['url']) && $_GET["key"] == "TxqaJFtRyHvrV4388x!MI7A^5kjWnK"){
	$url = $_GET["url"];
  $html = file_get_contents($url);
  
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
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}

?>


