<?php
#use CFF_Utils;

isset($_REQUEST['pageid']) ? $pageid = $_REQUEST['pageid'] : $pageid = '';

//Use the token from the shortcode
$shortcode_token = false;
if( isset($_REQUEST['at']) ){

    $at = $_REQUEST['at'];
    // $shortcode_token = $at;

    if (strpos($at, '02Sb981f26534g75h091287a46p5l63') !== false) {
        $at = str_replace("02Sb981f26534g75h091287a46p5l63","",$at);
    }
    $shortcode_token = $at;

    // if( strpos($at, ':') !== false ){
    //     $shortcode_token = cffDecodeToken($at,$pageid);        
    // }
}

function cffDecodeToken($at,$pageid){
    $access_token_pieces = explode(",", $at);
    $access_token_multiple = array();
    $shortcode_token = '';

    foreach ( $access_token_pieces as $at_piece ) {
        $access_token_split = explode(":", $at_piece);
        $token_only = trim($access_token_split[1]);
        $page_id_only = str_replace("%20","",$access_token_split[0]);

        //Find the token which matches the Page ID passed in
        if( $page_id_only == $pageid ){
            if (strpos($token_only, '02Sb981f26534g75h091287a46p5l63') !== false) {
                $token_only = str_replace("02Sb981f26534g75h091287a46p5l63","",$token_only);
            }
            $shortcode_token = $token_only;
        }
    }
    return $shortcode_token;
}



//If displaying albums from a group then get the User Access Token from the DB
$token_from_db = false;
$cff_connected_accounts = false;
if( ( (isset($usegrouptoken) && $usegrouptoken != false) || $useowntoken ) && !$shortcode_token ){
	if ( ! function_exists( 'cff_get_wp_config_path' ) ) {

		function cff_get_wp_config_path() {
			$base = dirname( __FILE__ );
			$path = false;
			if ( @file_exists( dirname( dirname( $base ) ) . "/wp-config.php" ) ) {
				$path = dirname( dirname( $base ) ) . "/wp-config.php";
			} else if ( @file_exists( dirname( dirname( dirname( $base ) ) ) . "/wp-config.php" ) ) {
				$path = dirname( dirname( dirname( $base ) ) ) . "/wp-config.php";
			} else {
				$path = false;
			}
			if ( $path != false ) {
				$path = str_replace( "\\", "/", $path );
			}

			return $path;
		}
	}

    $config_path = cff_get_wp_config_path();
    $check_path = realpath($config_path);
    if($check_path && $config_path){
    	if ( ! defined( 'SHORTINIT' ) ) {
		    define( 'SHORTINIT', true );
	    }
        require_once( $config_path );

        $table_name = $wpdb->prefix . "options";
        $sql_query = "SELECT * FROM " . $table_name . " WHERE option_name = 'cff_access_token'";
        $results = $wpdb->get_results( $sql_query, ARRAY_A );
        $token_from_db = $results[0]['option_value'];

        $sql_query = "SELECT * FROM " . $table_name . " WHERE option_name = 'cff_connected_accounts'";
        $results = $wpdb->get_results( $sql_query, ARRAY_A );
        $cff_connected_accounts = $results[0]['option_value'];
    }

}

//Set the kind of token to use
$access_token = '';

if( $shortcode_token ){
    $access_token = $shortcode_token;
} else {

    //If not using a token directly in the shortcode then next check for one in connected accounts
    if( $cff_connected_accounts ){
        //Get from connected account
        $cff_connected_accounts = json_decode( str_replace('\"','"', $cff_connected_accounts) );
        if( isset( $cff_connected_accounts->{ $pageid } ) ) $access_token = $cff_connected_accounts->{ $pageid }->{'accesstoken'};
    }

    //If nothing in connected accounts then use main token from settings
    if( $token_from_db && ( $access_token == '' || is_null($access_token) ) ){
        $access_token = $token_from_db;

        if ( strpos($access_token, ':') !== false ) {

            //Define the array
            $access_token_multiple = array();

            function splitToken($at_piece, $access_token_multiple=false){
                $access_token_split = explode(":", $at_piece);

                ( count($access_token_split) > 1 ) ? $token_only = trim($access_token_split[1]) : $token_only = '';

                if (strpos($token_only, '02Sb981f26534g75h091287a46p5l63') !== false) {
                    $token_only = str_replace("02Sb981f26534g75h091287a46p5l63","",$token_only);
                }

                $access_token_multiple[ trim($access_token_split[0]) ] = $token_only;
                return $access_token_multiple;
            }

            //If there are multiple tokens then split them up
            if( strpos($access_token, ',') !== false ){
                $access_token_pieces = explode(",", $access_token);
                foreach ( $access_token_pieces as $at_piece ) {
                    $access_token_multiple = splitToken($at_piece, $access_token_multiple);
                }
            } else {
            //Otherwise just create a 1 item array
                $access_token_multiple = splitToken($access_token);
            }
            //Assign the tokens
            $access_token = $access_token_multiple;


            //Check to see if there's a token for this ID and if so then use it
            if( isset($access_token_multiple[$page_id]) ) $access_token = $access_token_multiple[$page_id];

            //If it's an array then that means there's no token assigned to this Page ID, so get the first token from the array and use that for this ID
            if( is_array($access_token) ){

                //Check whether the first item in the array is a single access token with no ID assigned
                foreach ($access_token as $key => $value) {
                    break;
                }
                if( strlen($key) > 50 ){
                    $access_token = $key;

                //If it's not a single access token and it has the ID:token format then use the token from that first item
                } else {
                    $access_token = reset($access_token);
                }
            }

        } else {
            //Replace the encryption string in the Access Token
            if (strpos($access_token, '02Sb981f26534g75h091287a46p5l63') !== false) {
                $access_token = str_replace("02Sb981f26534g75h091287a46p5l63","",$access_token);
            }
        }

    }

}

if ( ! function_exists( 'cff_fetchUrl' ) ) {
	function cff_fetchUrl($url){
		//Can we use cURL?
		if(is_callable('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 20);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate,sdch');
			$feedData = curl_exec($ch);
			curl_close($ch);
			//If not then use file_get_contents
		} elseif ( ini_get('allow_url_fopen') == 1 || ini_get('allow_url_fopen') === TRUE ) {
			$feedData = @file_get_contents($url);
			//Or else use the WP HTTP API
		} else {
			$request = new WP_Http;
			$response = $request->request($urls, array('timeout' => 60, 'sslverify' => false));
			if( is_wp_error( $response ) ) {
				//Don't display an error, just use the Server config Error Reference message
				echo '';
			} else {
				$feedData = wp_remote_retrieve_body($response);
			}
		}

		return $feedData;
	}
}

?>