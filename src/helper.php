<?php
/**
 * Helper class for YT Channel module
 * 
 * @package    YTchannel
 * @subpackage Modules
 * @version   1.0.0
 * @author    Justine Simmet
 * @copyright (C) 2016 Justine Simmet
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

class ModYtChannelHelper
{
    /**
     * Retrieves the Youtube channel id
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */  
    public static function getChannel($params)
    {
        return $params->get('channel_id');
    }

    /**
     * Retrieves the Youtube API key
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */  
    public static function getKey($params)
    {
        return $params->get('api_key');
    }

}