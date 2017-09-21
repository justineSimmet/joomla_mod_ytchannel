<?php
/**
 * YT Channel Module Entry Point
 * 
 * @package    YTchannel
 * @subpackage Modules
 * @version   1.0.0
 * @author    Justine Simmet
 * @copyright (C) 2016 Justine Simmet
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
 
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
 
$channelId = modYtChannelHelper::getChannel($params);
$apiKey = modYtChannelHelper::getKey($params);
require JModuleHelper::getLayoutPath('mod_ytchannel');