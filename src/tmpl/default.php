<?php 
    // No direct access
    defined('_JEXEC') or die;

    JHtml::_('jquery.framework', false);

    //Insertion des assets
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::root()."media/mod_ytchannel/css/venobox.min.css");
    $document->addStyleSheet(JURI::root()."media/mod_ytchannel/css/ytchannel.min.css");
    JHtml::script(Juri::base() . 'media/mod_ytchannel/js/jquery-3.2.1.min.js');
    JHtml::script(Juri::base() . 'media/mod_ytchannel/js/venobox.min.js');
    JHtml::script(Juri::base() . 'media/mod_ytchannel/js/ytchannel.min.js');


?>
<div id="channel-container">
    <?php  
        foreach ($youtubeVideos as $video) {
        ?>
            <a class="venobox thumbnail" data-vbtype="video" href="http://youtu.be/<?php echo $video['id']; ?>" title="<?php echo $video['title']; ?>" data-autoplay="true" data-gall="channelGallery">
                <div>
                    <h4><?php echo $video['title']; ?></h4>
                    <p><span class="icon-upload"></span><i><?php echo $video['publication']; ?></i></p>
                </div>
                <img src="<?php echo Juri::base() . 'media/mod_ytchannel/icon/play-button.svg' ?>" class="play-icon">
                <img src="<?php echo $video['thumbnail']; ?>" class="vid-thumbnail">
            </a>
        <?php
        }
    ?>

</div>

<?php  
    $session = JFactory::getSession();
    if ($session->has('lazyLoad')) {
        $lazyLoad = $session->get('lazyLoad');
        $playlistId = $session->get('playlistId');
    ?>
        <div id="ytchannel-load-more">
            <p class="text-center">
                <a class="animate action-button" type="button" onclick="loadMore('<?php echo $lazyLoad; ?>', '<?php echo $playlistId; ?>', '<?php echo $apiKey; ?>', '<?php echo $countVideos; ?>')"><img src="<?php echo Juri::base() . 'media/mod_ytchannel/icon/more.svg' ?>"></a>
            </p>
        </div>
    <?php
    $session->clear('lazyLoad');
    $session->clear('playlistId');
    }
?>
