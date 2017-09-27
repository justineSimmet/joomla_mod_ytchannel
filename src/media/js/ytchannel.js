/*
 * YT Channel Module
 * version: 1.5
 * @requires jQuery >= 3.2.1
 *
 * Copyright 2017 Justine Simmet - justine.simmet@gmail.com
 *
 */
function loadMore(nextPageToken, playlistId, apiKey, countVideos){
  var getUrl = window.location;
  var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
  var apiYoutube = 'https://www.googleapis.com/youtube/v3/';
  var params = {'part':'snippet', 'playlistId': playlistId, 'maxResults': countVideos, 'key': apiKey }
  var url = apiYoutube+'playlistItems?'+jQuery.param(params)+'&pageToken='+nextPageToken;

  $.getJSON(url).done(function(data){
    $.each( data.items, function( i, item ){
      i = i*400;
      var published = new Date(item.snippet.publishedAt);
      var video = {
        'id' : item.snippet.resourceId.videoId,
        'title' : item.snippet.title,
        'thumbnail' : item.snippet.thumbnails.standard.url,
        'publication' : published.toLocaleString()
      };
      var newVideo = '<a class="venobox" data-vbtype="video" href="http://youtu.be/'+video.id+'" title="'+video.title+'" data-autoplay="true" data-gall="channelGallery">'
                    +'<div>'
                    +'<h4>'+video.title+'</h4>'
                    +'<p><span class="icon-upload"></span><i>'+video.publication+'</i></p>'
                    +'</div>'
                    +'<img src="'+baseUrl+'/media/mod_ytchannel/icon/play-button.svg" class="play-icon">'
                    +'<img src="'+video.thumbnail+'" class="vid-thumbnail">'
                    +'</a>';
      $('#channel-container').append($(newVideo).hide().delay(i).fadeIn(800));
      jQuery('.venobox').venobox();
    })
    if (data.nextPageToken !== undefined && data.nextPageToken !== null) {
      var newButton = '<p class="text-center"><a class="animate action-button" type="button" onclick="loadMore(\''+data.nextPageToken+'\', \''+playlistId+'\', \''+apiKey+'\', \''+countVideos+'\')"><img src="'+baseUrl+'/media/mod_ytchannel/icon/more.svg"></a></p>'
      $('#ytchannel-load-more').html(newButton);
    }
    else{
      $('#ytchannel-load-more').remove();
    }

  });
}

$(document).ready(function(){
    jQuery('.venobox').venobox();
});