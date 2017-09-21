<?php 
// No direct access
defined('_JEXEC') or die; ?>

<?php
JHtml::_('jquery.framework', false);

//Insertion des assets
$document = JFactory::getDocument();
$document->addScript(JURI::root()."modules/mod_ytchannel/tmpl/assets/js/jquery-3.2.1.min.js");
$document->addStyleSheet(JURI::root()."modules/mod_ytchannel/tmpl/assets/css/ytchannel.css");
$document->addStyleSheet(JURI::root()."modules/mod_ytchannel/tmpl/assets/css/magnific-popup.css");
$document->addScript(JURI::root()."modules/mod_ytchannel/tmpl/assets/js/ytchannel.js");
$document->addScript(JURI::root()."modules/mod_ytchannel/tmpl/assets/js/magnific-popup.min.js");
$document->addStyleSheet("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
/*****
 *
 * Recupèration des vidéos Youtube
 *
 */
    $apiYoutube = 'https://www.googleapis.com/youtube/v3/';

    //Paramètres pour récupérer le contenu de la chaîne Youtube
    $parameters = [
        'id'=> $channelId,
        'part'=> 'contentDetails',
        'key'=> $apiKey
    ];

    // Génère la requête vers l'API Youtube
    $url = $apiYoutube . 'channels?' . http_build_query($parameters);

    // file_get_contents -> exécute la requête vers l'API Youtube
    $return = file_get_contents($url);
    $response = json_decode($return, true);

    if ($return == false || $response['pageInfo']['totalResults'] == 0) {
        // Si le retour est négatif ou vide, la fonction se stope et un message d'erreur est envoyé à l'utilisateur
        return JFactory::getApplication()->enqueueMessage('Une erreur a eu lieu durant la récupération de la chaîne vidéo. Vérifiez que les services de YouTube fonctionnent correctement. Si l\'erreur persiste, rapprochez vous d\'un administrateur.', 'error');
    }
    else{        
        // Récupère les données de la playlist de la chaîne
        $playlist = $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

        // Initialise les paramètres pour récupérer les données des vidéos de la playlist
        $parameters = [
            'part'=> 'snippet',
            'playlistId' => $playlist,
            'maxResults'=> '50',
            'key'=> $apiKey
        ];
        
        $url = $apiYoutube . 'playlistItems?' . http_build_query($parameters);

        $return = file_get_contents($url);
        if ($return == false) {
            // Si le retour est négatif, la fonction se stope et un message d'erreur est envoyé à l'utilisateur
            return JFactory::getApplication()->enqueueMessage('Une erreur a eu lieu durant la récupération des vidéos. Vérifiez que les services de YouTube fonctionnent correctement. Si l\'erreur persiste, rapprochez vous d\'un administrateur.', 'error');
        }
        else{
            $response = json_decode($return, true);
        
            $videos = [];

            // Traitement des données retour afin de stocker les urls des miniatures
            // et les id de chaque vidéo présente sur la chaîne Youtube
            foreach ($response['items'] as $video) {
                $videoId = $video['snippet']['resourceId']['videoId'];
                $videoTitle = $video['snippet']['title'];
                $videoPublication = $video['snippet']['publishedAt'];
                $videoThumbnail = $video['snippet']['thumbnails']['high']['url'];
                $video = [];
                $video['id'] = $videoId;
                $video['title'] = $videoTitle;
                $video['thumbnail'] = $videoThumbnail;
                $video['publication'] = $videoPublication;
                $videos[] =  $video;
            };

            // Si la chaîne contient plus de 50 vidéos, exécute la requête
            // tant que toutes les vidéos ne sont pas stockées dans $videos
            while(isset($response['nextPageToken'])){
                $nextUrl = $url . '&pageToken=' . $response['nextPageToken'];
                $response = json_decode(file_get_contents($nextUrl), true);
                foreach($response['items'] as $video){
                    $videoId = $video['snippet']['resourceId']['videoId'];
                    $videoTitle = $video['snippet']['title'];
                    $videoPublication = $video['snippet']['publishedAt'];
                    $videoThumbnail = $video['snippet']['thumbnails']['high']['url'];
                    $video = [];
                    $video['id'] = $videoId;
                    $video['title'] = $videoTitle;
                    $video['thumbnail'] = $videoThumbnail;
                    $video['publication'] = $videoPublication;
                    $videos[] =  $video;
                }
            }

        }
    }

?>

<div id="ytchannel-container">
    
    <?php
        foreach ($videos as $video) {
        ?>

        <div class="ytchannel-content" data-mfp-src="https://www.youtube.com/watch?v=<?= $video['id'];?>">
            <div class="ytchannel-intro">
                <h5><?= (strlen($video['title'])> 60)?substr($video['title'], 0,57).'...': $video['title'] ?></h5>
                <p>Publiée le <?= JHtml::_('date', $video['publication']) ?></p>
            </div>
            <div class="ytchannel-video">
                <img src="<?= $video['thumbnail']; ?>" alt="<?= $video['title'] ;?>">
                <div class="ytchannel-hover-container">
                </div>
                <p class="ytchannel-play"><span class="fa fa-play-circle-o"></span><br />Regarder la vidéo</p>
            </div>
        </div>

        <?php
        }
    ?>

</div>