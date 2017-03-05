<?php

function getdarausingcurl($method, $url, $header, $data, $json){
    if( $method == 1 ){
        $method_type = 1; // 1 = POST
    }else{
        $method_type = 0; // 0 = GET
    }
 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_HEADER, 0);

    if( $header !== 0 ){
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }

    curl_setopt($curl, CURLOPT_POST, $method_type);
 
    if( $data !== 0 ){
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
 
    $response = curl_exec($curl);

    if( $json == 0 ){
        $json = $response;
    }else{
        $json = json_decode($response, true);
    }

    curl_close($curl);
 
    return $json;
}

?>
<?php

// Script By Kshitij Soni  
//Youtube channel : https://www.youtube.com/channel/UCOWT2JvRnSMVSboxvccZBFQ
//Youtube video (login with instagram) : https://www.youtube.com/watch?v=Hi-wVYiJ5fk

session_start();



include 'config.php'; // include app data

$session_data =  $_SESSION;
$user_id =  $session_data['user_info']['data']['id'];
$access_token =  $session_data['access_token'];
/* Get User popular media  */

$method = 0; // method = 1, because we want GET method

$url = "https://api.instagram.com/v1/users/$user_id/media/recent/?access_token=$access_token";

$header = 0; // header = 0, because we do not have header

$data = 0; // because we want GET method


$json = 0; // json = 1, because we want JSON response

$json_data  = getdarausingcurl($method, $url, $header, $data, $json);

$get_recent_media = json_decode($json_data);

?>

<div class="container">
    <header class="clearfix">
        <img src="assets/instagram.png" alt="Instagram logo">

        <h1>Instagram <span>recent photos</span></h1>
    </header>
    <div class="main">
        <ul class="grid">
            <?php
            foreach ($get_recent_media->data as $media) {
                $content = '<li>';
                // output media
                if ($media->type === 'video') {
                    // video
                    $poster = $media->images->low_resolution->url;
                    $source = $media->videos->standard_resolution->url;
                    $content .= "<video class=\"media video-js vjs-default-skin\" width=\"250\" height=\"250\" poster=\"{$poster}\"
                           data-setup='{\"controls\":true, \"preload\": \"auto\"}'>
                             <source src=\"{$source}\" type=\"video/mp4\" />
                           </video>";
                } else {
                    // image
                    $image = $media->images->low_resolution->url;
                    $content .= "<img class=\"media\" src=\"{$image}\"/>";
                }
                // create meta section
                $avatar = $media->user->profile_picture;
                $username = $media->user->username;
                $comment = (!empty($media->caption->text)) ? $media->caption->text : '';
                $content .= "<div class=\"content\">
                           <div class=\"avatar\" style=\"background-image: url({$avatar})\"></div>
                           <p>{$username}</p>
                           <div class=\"comment\">{$comment}</div>
                         </div>";
                // output media
                echo $content . '</li>';
            }
            ?>
        </ul>
       
        <footer>
            <p>
             <b>Script By Kshitij Soni </b>
                <a href="https://www.youtube.com/channel/UCOWT2JvRnSMVSboxvccZBFQ"> Youtube channel</a> 
                <a href="https://www.youtube.com/watch?v=Hi-wVYiJ5fk">Youtube video (login with instagram)</a> 

                Now check the output . So we can get recent post of user .
                Thanks for watching .
                Like share and subscribe .
            </p>
        </footer>
    </div>
</div>
<?php exit; ?>