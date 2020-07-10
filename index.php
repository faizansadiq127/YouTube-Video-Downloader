<?php 

if(isset($_POST['submitURL'])){

// Load download class 
include_once 'functions.php'; 
$handler = new Download(); 
 
// video url of youtube
$youtubeURL = $_POST['youtubeURL']; 
 
// if URL valid 
if(!empty($youtubeURL) && !filter_var($youtubeURL, FILTER_VALIDATE_URL) === false){

    $downloader = $handler->getDownloader($youtubeURL); 
    
    $downloader->setUrl($youtubeURL); 
      
    if($downloader->hasVideo()){ 
        $videoDownloadLink = $downloader->getVideoDownloadLink();
        $videoTitle = $videoDownloadLink[0]['title']; 
        $videoQuality = $videoDownloadLink[1]['quality']; 
        $videoFormat = $videoDownloadLink[2]['format']; 
        $videoFileName = strtolower(str_replace(' ', '_', $videoTitle)).'.mp4'; 
        $downloadURL = $videoDownloadLink[0]['url']; 
        $fileName = preg_replace('/[^A-Za-z0-9.\_\-]/', '', basename($videoFileName)); 
        
        if(!empty($downloadURL)){ 
            //Header for force download 
            header("Cache-Control: public"); 
            header("Content-Description: File Transfer"); 
            header("Content-Disposition: attachment; filename=$fileName"); 
            
            header("Content-Transfer-Encoding: binary"); 
             
            // Read the file 
            readfile($downloadURL); 
        } 

    }else{ 
        // error Massage
        $statusMsg = "Video not found try another One";
        $msgClass = 'error';
    } 
}else{ 
    // error Massage
    $statusMsg = "URL not valid please enter valid URL";
        $msgClass = 'error'; 
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Download Youtube Video</title>
</head>
<body>
    <?php if(!empty($statusMsg)){ ?>
    <div class="massage">
        <p class="statusMsg">
            <?php
                    echo !empty($msgClass)?$msgClass:'';
            ?>
            <?php
                    echo !empty($statusMsg)?$statusMsg:'';
            ?>
        </p>
    </div>
    <?php } ?>

    <!-- Form for Download Video -->
    <form action="" method="post">
        <div class="form">
            <label for="">Enter URL</label>
            <input type="text" name="youtubeURL" value="<?php echo !empty($youtubeURL)?$youtubeURL:''; ?>" placeholder="URL">
            <input type="submit" value="Download" name="submitURL">
        </div>
    </form>
</body>
</html>
