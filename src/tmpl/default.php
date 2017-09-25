<?php 
    // No direct access
    defined('_JEXEC') or die;

    JHtml::_('jquery.framework', false);

    //Insertion des assets
    $document = JFactory::getDocument();
    $document->addStyleSheet(JURI::root()."media/mod_ytchannel/css/venobox.min.css");
    $document->addStyleSheet(JURI::root()."media/mod_ytchannel/css/ytchannel.css");
    JHtml::script(Juri::base() . 'media/mod_ytchannel/js/jquery-3.2.1.min.js');
    JHtml::script(Juri::base() . 'media/mod_ytchannel/js/venobox.min.js');
    JHtml::script(Juri::base() . 'media/mod_ytchannel/js/ytchannel.js');


?>
<div id="channel-container">
    <?php  
        foreach ($youtubeVideos as $video) {
        ?>
            <a class="venobox" data-vbtype="video" href="http://youtu.be/<?php echo $video['id']; ?>" title="<?php echo $video['title']; ?>" data-autoplay="true" data-gall="channelGallery">
                <div>
                    <h4><?php echo $video['title']; ?></h4>
                    <p><i><?php echo JFactory::getDate($video['publication'])->format(' d/m/Y - H:i'); ?></i></p>
                </div>
                <img src="<?php echo $video['thumbnail']; ?>">
            </a>
        <?php
        }
    ?>

</div>

<?php  
    $session = JFactory::getSession();
    if (!is_null($session->get('lazyLoad'))) {
        $lazyLoad = $session->get('lazyLoad');
        $playlistId = $session->get('playlistId');
    ?>
        <div id="load-more">
            <p class="text-center"><a class="btn btn-primary" type="button" onclick="loadMore('<?php echo $lazyLoad; ?>', '<?php echo $playlistId; ?>', '<?php echo $apiKey; ?>', '<?php echo $countVideos; ?>')">en voir plus...</a></p>
        </div>
    <?php
    }
?>
