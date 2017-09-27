<?php
/**
 * Helper class for YT Channel module
 * 
 * @package    YTchannel
 * @subpackage Modules
 * @version   1.5.0
 * @author    Justine Simmet
 * @copyright (C) 2017 Justine Simmet
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

class ModYtChannelHelper
{

    /**
     * Retrieves the Youtube channel id
     *
     * @param   array  $params An object containing the module parameters
     * @access public
     * @return string  the channel id declare by the users
     */  
    public static function getChannel($params)
    {
        return $params->get('channel_id');
    }

    /**
     * Retrieves the Youtube API key
     *
     * @param   array  $params An object containing the module parameters
     * @access public
     * @return string  the api key declare by the users
     */  
    public static function getKey($params)
    {
        return $params->get('api_key');
    }

    /**
     * Define the number of max videos display at first
     *
     * @param   array  $params An object containing the module parameters
     * @access public
     * @return string  the number choose by the user or by default 5
     */  
    public static function getCount($params)
    {
        if (is_numeric($params->get('number_vid'))) {
            return $params->get('number_vid');
        }
        else{
            return 5;
        }
    }

    /**
      * Fetch videos data from YouTube Api
      * 
      * @param string $channelId 
      * @param string $apiKey 
      * @param string $countVideos 
      * @return array All the videos informations avaible on the YouTube Channel
      */ 
    public static function getVideos($channelId, $apiKey, $countVideos)
    {
        // Url from where to call the YouTube API
        $apiYoutube = 'https://www.googleapis.com/youtube/v3/';

        //List of parameters to ask
        $parameters = [
            'id'   => $channelId,
            'part' => 'contentDetails',
            'key'  => $apiKey
        ];

        // Construct the request to the API
        $url = $apiYoutube . 'channels?' . http_build_query($parameters);

        // Execute the request
        $return = file_get_contents($url);

        // Decode the request in an array
        $response = json_decode($return, true);

        // If the request is false or empty
        // stop the function and send an alert message
        if ($return == false || $response['pageInfo']['totalResults'] == 0) {
            return JFactory::getApplication()->enqueueMessage(JText::_( 'MOD_YTCHANNEL_ERROR_FIRST_CALL' ), 'error');
        }
        else{

            // Get the data of the playlist channel
            $playlist = $response['items'][0]['contentDetails']['relatedPlaylists']['uploads'];

            // Initialise new parameters
            $parameters = [
                'part'       => 'snippet',
                'playlistId' => $playlist,
                'maxResults' => $countVideos,
                'key'        => $apiKey
            ];

            // Construct the new request to the API
            $url = $apiYoutube . 'playlistItems?' . http_build_query($parameters);

            // Execute the new request
            $return = file_get_contents($url);

            if ($return == false) {
                return JFactory::getApplication()->enqueueMessage(JText::_( 'MOD_YTCHANNEL_ERROR_SECOND_CALL' ), 'error');
            }
            else{
                $response = json_decode($return, true);
        
                $videos = array();

                // Treat the response items to formate the return data as an array
                foreach ($response['items'] as $video) {
                    // YouTube Video ID
                    $videoId = $video['snippet']['resourceId']['videoId'];

                    // YouTube Video Title
                    $videoTitle = $video['snippet']['title'];

                    // YouTube Video Publication date
                    // $published = JFactory::getDate($video['snippet']['publishedAt']);
                    $atTrad = JText::_( 'MOD_YTCHANNEL_TIME_AT' );
                    $videoPublication = JFactory::getDate($video['snippet']['publishedAt'])->format('d/m/Y '.$atTrad.' H:i:s');

                    // YouTube Video HighRes Thumbnail
                    if (!empty($video['snippet']['thumbnails']['standard']['url'])) {
                        $videoThumbnail = $video['snippet']['thumbnails']['standard']['url'];
                    }
                    else{
                        $videoThumbnail = $video['snippet']['thumbnails']['default']['url'];
                    }

                    // All the extract information are stored in an array $video
                    $video = array();

                    $video['id']          = $videoId;
                    $video['title']       = $videoTitle;
                    $video['thumbnail']   = $videoThumbnail;
                    $video['publication'] = $videoPublication;

                    // Then the array $video is insert in the array $videos
                    $videos[] =  $video;
                };


                // If it still have more videos to load, the nextPageToken will be stored
                // in the variable lazyLoad
                if (isset($response['nextPageToken'])) {
                    $session = JFactory::getSession();
                    $session->set('lazyLoad', $response['nextPageToken']);
                    $session->set('playlistId', $playlist);
                }

                // Return an array containing the max number of videos asked by the user
                return $videos;
            }
        }
    }

}