<?php  
//ini_set('display_errors','Off');
ini_set('display_errors','On');
error_reporting(E_ALL ^ E_NOTICE);
 
 
/*    The query parameters that all queries have:
*/
$key_Private = 'kNyrDRwY8HWlJtVfPKUqKXTdk8wnrR+01Qbq0Suq';
$associate_id = 'kennethlucius-20';
$amz_host = 'webservices.amazon';
$amz_locale = '.com'; //locale is separate from host for ease of changing locale
 
/*    Has the Submit Button been pressed?
*/
$query_submitted = false;
$xml_response = '';
if(count($_POST)>1 && $_POST['submitButton']=='SubmitQuery') {
    //set $querytxt to lines suitable for the textarea
    //set $parameters to an array suitable for the sign_query();
    read_query_text($_POST['qdeftxt']);
    $query_submitted = true;
}
 
/*    If no query parameters have been sent, make up some to start
*/
if(! $querytxt) {
    //use the last one, saved in a cookie
    if($_COOKIE['query'])
        read_query_text($_COOKIE['query']);
 
    //or a default set if that didn't work
    if(! $querytxt)
        read_query_text("Operation = ItemSearch
                        SearchIndex = Electronics
                        Keywords = apple mac
                        ResponseGroup = ItemAttributes,BrowseNodes");
}
 
if(count($parameters)>0) {
    // send parameters to query signer and get a URL
    $url = sign_query($parameters);
 
    // Put it in a link for display
    $urlparts = str_replace('&', '<br />&', $url);
    $urlparts = str_replace('?', '<br />?', $urlparts);
    $link = '<a href="'.$url.'" target="paapiResult">'.$urlparts.'</a>';
 
    // Query the API if the button was pressed
    if($query_submitted) {
        $xml_response = query_api($url);
    }
}
else {
    $url = '';
    $link = 'No URL defined.';
}
 
/*    set the query in a cookie so it will remember your last query 
*/
setcookie('query', $querytxt);
 
 
 
/*    What follows is HTML page with two embedded PHP print statements:
    1) the signed URL
    2) the XML response from the API
*/
?><html xml:lang="en" lang="en" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Copyright (c) Kenneth Lucius.
*
* This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License.
*
*    http://creativecommons.org/licenses/by-nc-sa/3.0/
*
-->
    <title>PA API: Define</title>
 
    <style type="text/css">
        #xmltext p {
            padding:0;
            margin:0;
        }
        #xmltext p.item {
            background-color:#def;
        }
        #xmltext p.error {
            background-color:#fdd;
        }
 
        span.xtag { 
            color: #66a;
        }
        span.xclose { 
            color: #aaa;
        }
    </style>
</head>
 
<body><h2>The Query Definition:</h2>
 
<form action="/index.php" id="querydef" method="post"><textarea name="qdeftxt" cols="80" rows="10"><?php print($querytxt); ?></textarea><br />
<input type="submit" name="submitButton" id="submitButton" value="SubmitQuery">
</form>
 
<h2>The Query URL:</h2>
<?php  print("<p>$link</p>"); ?>
 
<h2>The Response:</h2>
<div id="xmltext">
<?php print($xml_response); ?>
</div>
</body>
</html>
 
 
 
 
<?php
/*    Now for the functions used above.
 
    First, a function to parse the textarea submittal
*/
function read_query_text($txt) {
    // parse to $parameters: an array for query signer
    // parse to $querytxt: a string to display in the textarea
    global $querytxt, $parameters;
    $lines = explode("\n", $txt);
    $querytxt = '';
    $parameters = array();
    foreach($lines as $line) {
        $q = explode('=', $line, 2);
        if(! $q) continue;
        $k = trim($q[0]);
        if(! $k) continue;
        if($q[1]) {
            $v = trim($q[1]);
            $parameters[$k] = $v;
            $querytxt .= "$k = $v\n";
        }
        else {
            $querytxt .= trim($k)."\n";
        }
    }
}
 
 
/*    The main function: Accept query parameters
    and return a signed URL.
*/
function sign_query($parameters) {
    global $key_Public, $key_Private, $associate_id, $amz_host, $amz_locale;
    //sanity check
    if(! $parameters) return '';
 
    /* create an array that contains url encoded values
       like "parameter=encoded%20value" 
       USE rawurlencode !!! */
    $encoded_values = array();
    foreach($parameters as $key=>$val) {
        $encoded_values[$key] = rawurlencode($key) . '=' . rawurlencode($val);
    }
 
    /* add the parameters that are needed for every query
       if they do not already exist */
    if(! $encoded_values['AssociateTag'])
        $encoded_values['AssociateTag']= 'AssociateTag='.rawurlencode($associate_id);
    if(! $encoded_values['AWSAccessKeyId'])
        $encoded_values['AWSAccessKeyId'] = 'AWSAccessKeyId='.rawurlencode($key_Public);
    if(! $encoded_values['Service'])
        $encoded_values['Service'] = 'Service=AWSECommerceService';
    if(! $encoded_values['Timestamp'])
        $encoded_values['Timestamp'] = 'Timestamp='.rawurlencode(gmdate('Y-m-d\TH:i:s\Z'));
    if(! $encoded_values['Version'])
        $encoded_values['Version'] = 'Version=2011-08-01';
 
    /* sort the array by key before generating the signature */
    ksort($encoded_values);
 
 
    /* set the server, uri, and method in variables to ensure that the 
       same strings are used to create the URL and to generate the signature */
    $server = $amz_host.$amz_locale;
    $uri = '/onca/xml'; //used in $sig and $url
    $method = 'GET'; //used in $sig
 
 
    /* implode the encoded values and generate signature
       depending on PHP version, tildes need to be decoded
       note the method, server, uri, and query string are separated by a newline */
    $query_string = str_replace("%7E", "~", implode('&',$encoded_values));   
    $sig = base64_encode(hash_hmac('sha256', "{$method}\n{$server}\n{$uri}\n{$query_string}", $key_Private, true));
 
    /* build the URL string with the pieces defined above
       and add the signature as the last parameter */
    $url = "http://{$server}{$uri}?{$query_string}&Signature=" . str_replace("%7E", "~", rawurlencode($sig));
    return $url;
}
 
 
/*    This function accepts a signed URL, queries the API,
    and returns XML formatted for display in HTML
*/
function query_api($url) {
    if(! url) return false;
 
    $sleepsecs = 1;
 
    //prepare a CURL object
    $ch = curl_init($url );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 7);
 
    $xml = curl_exec($ch);
        /*response 200 is all good
        $info = curl_getinfo($ch);
        if($info['http_code']==200) {
            break;
        }
        //response 503 probably means too many queries too fast
        elseif($info['http_code'] == 503) {
            $sleepsecs *= 2;
        }
        */
    //$sxml = simplexml_load_string($contents);
 
    return format_XML_string($xml);
}
 
 
 
/*    This function accepts an XML string and 
    returns it in HTML form
    formatted with indents and minor coloring
*/
// Take the API response and prepare it for display
function format_XML_string($xml) {
    if(! $xml) return false;
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
 
    //The API sometimes returns an HTML error page
    //so we have to check it manually
    if(substr($xml,0,5)=='<?xml') {
        $dom->loadXML($xml);
        $xmlstring = $dom->saveXML();
    }
    else {
        $dom->loadHTML($xml);
        $xmlstring = $dom->saveHTML();
    }
 
    //if something went wrong, just dump the xml string
    if(! $xmlstring) return htmlspecialchars($xml);
 
    $lines = explode("\n", $xmlstring);
    $ret = '';
    $itemcount=0;
    $isError=false;
    foreach($lines as $line) {
        $s = preg_replace("/<(.+?)>/", '<span class="xtag">&lt;$1></span>', $line);
        $css = ' style="padding-left:'.strspn($s, '     ').'em;"';
        if(strpos($s, '&lt;Errors>')) {
            $isError = true;
        }
        elseif($isError) {
            if(strpos($s, '&lt;/Errors>')) {
                $isError = false;
                $class = '';
            }
            else
                $class = ' class="Error"';
        }
        elseif(strpos($s, '&lt;Item>')) {
            $class = ' class="Item"';
            $s .= ' — [Item # '. ++$itemcount . '] —';
        }
        else
            $class = '';
        $ret .= '<p'.$class.$css.'>'.$s."</p>\n";
    }
    $ret = str_replace('<span class="xtag">&lt;/', '<span class="xclose">&lt;/', $ret);
    return $ret;
 
}