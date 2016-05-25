<?php
//Access Key ID: AKIAIA42C3D7FWFKL7SA
//Secret Access Key: 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

$awsaccess = "AKIAIA42C3D7FWFKL7SA";
$saccess = "c6LOTJTPo2XvjMtpX7qCSknrsddU0O7I2ZuWD9gx";
$assocTag = "chuc09e-20";
    
$stringtosign = "GET\nwebservices.amazon.com\n/onca/xml\n";
$timestamp = rawurlencode(gmdate('Y-m-d\TH:i:s\Z'));
$timestamp = rawurlencode(gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()));
$timestamp = rawurlencode(gmdate('Y-m-d\TH:i:s\Z'));

$base = "http://webservices.amazon.com/onca/xml?";

$xurl = "AWSAccessKeyId=".$awsaccess
        . "&AssociateTag=".$assocTag
        . "&ItemId=B004HO6I4M"
        . "&Operation=ItemLookup"
        . "&ResponseGroup=Images"
        . "&Service=AWSECommerceService"
        //. "&IdType=ASIN&"
        . "&Timestamp=".$timestamp
        . "&Version=2011-08-01";

$stringtosign .= $xurl;
$signature = "&Signature=" . urlencode(base64_encode(hash_hmac("sha256", $stringtosign, $saccess, true)));
$stringtosign .= $signature;
$url = $base . $xurl . $signature;

//header("Location: $url");
echo $url;
echo "\n<br>\n \n<br>\n";
echo $stringtosign;
/*
///////////////////////////////
// Construct the string to sign 
$url_string = implode("&", $url_parts); 
$string_to_sign = "GET\necs.amazonaws.com\n/onca/xml\n" . $url_string; 
// Sign the request 
$signature = hash_hmac("sha256", $string_to_sign, AWS_SECRET_ACCESS_KEY, TRUE); 
// Base64 encode the signature and make it URL safe 
$signature = urlencode(base64_encode($signature)); 
$url = $base_url . '?' . $url_string . "&Signature=" . $signature; 
return ($url); } $searchTerm = $_REQUEST['searchTerm']; 
*/

//$url = amazon_get_signed_url($searchTerm); 
//Below is just sample request dispatch and response parsing for example purposes. 
//$response = file_get_contents($url); $parsed_xml = simplexml_load_string($response); $result = array(); foreach($parsed_xml->Items->Item as $current){ if($current->ItemAttributes->ProductGroup == 'Music') { $item = array( 'track_id' => implode(((array)$current->ASIN), ', '), 'source' => 1, 'track_name' => implode(((array)$current->ItemAttributes->Title), ', '), 'track_url' => implode(((array)$current->DetailPageURL), ', '), 'artist_name' => implode(((array)$current->ItemAttributes->Artist), ', '), 'artist_url' => '', 'collection_name' => '', 'collection_url' => '', 'genre' => '', ); $result[] = $item; } } {/syntaxhighlighter}


    