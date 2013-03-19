<?php

//simple php script for curling url, parsing out the last-modified
// is cURL installed yet?
if ( !function_exists( 'curl_init' ) )
	die( 'Sorry cURL is not installed!' );

//get cli arguments
$arguments = array();
for( $i=1; $i<$argc; $i++ )
{
    parse_str($argv[$i],$tmp);
    $arguments = array_merge($arguments, $tmp);
}

//check for man
if( in_array( '--man', array_keys( $arguments ) ) )
{
	$man  = "Script is used to curl a url and give back the last modified date. extra option of html parsing currently enabled\n\n";
	$man .= "Execute: php site_info.php url=[URL] times=[TIMES] sleep[SECONDS]\n\n";
	$man .= "Params:\n";
	$man .= "\turl [ required ]: url of site to curl\n";
	$man .= "\ttimes [ optional ]: the amount of iterations to perform of the curl. default is one\n";
	$man .= "\tsleep [optional ]: amount of seconds before executing next iteration.\n\n";

	die( $man );
}

//setup loop
$i = !empty( $arguments['times'] ) ? $arguments['times'] : 1;
$sleep = !empty( $arguments['sleep'] ) ? $arguments['sleep'] : 10;	//sleep in seconds

for( $j=0; $j<=$i; $j++ )
{
 	//check for url, must have
	if( !in_array( 'url', array_keys( $arguments ) ) )
		die( 'must supply url as a parameter ( url=[request_url] )' );


    $ch = curl_init(); 
    curl_setopt( $ch, CURLOPT_URL, $arguments['url'] );
    curl_setopt( $ch, CURLOPT_HEADER, true );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
	
	//parse out some of the curl. edit as necessary for parts we want
    //last modified
    preg_match( '~^(Last.+?)$~ism', $output, $last_modified );
    // pass info
    preg_match_all('!<span[^>]+>SOON</span>!', $output, $soons);
    $soons = array_shift( $soons );		//since array of array of entries ( we only care about the first array )

    date_default_timezone_set( 'America/Toronto' );
    $message  = "\tChecked: " . date( 'Y-m-d H:i:s' ) . "\t\n";
    $message .= "\t" . $last_modified[1] . "\t\n";
    $message .= "\tSize of Soons: " . sizeof( $soons ) . "\t\n\n";	    

    echo $message;
    sleep( $sleep );
}
