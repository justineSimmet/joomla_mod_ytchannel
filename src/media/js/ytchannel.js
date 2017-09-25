function loadMore(nextPageToken, playlistId, apiKey, countVideos){
  var apiYoutube = 'https://www.googleapis.com/youtube/v3/';
  var params = {'part':'snippet', 'playlistId': playlistId, 'maxResults': countVideos, 'key': apiKey }
  var url = apiYoutube+'playlistItems?'+jQuery.param(params)+'&pageToken='+nextPageToken;

  $.getJSON(url).done(function(data){
    console.log(data);
    $.each( data.items, function( i, item ){
      var video = {
        'id' : item.snippet.resourceId.videoId,
        'title' : item.snippet.title,
        'thumbnail' : item.snippet.thumbnails.standard.url,
        'publication' : item.snippet.publishedAt
      };
      var newVideo = '<a class="venobox" data-vbtype="video" href="http://youtu.be/'+video.id+'" title="'+video.title+'" data-autoplay="true" data-gall="channelGallery">'
                    +'<div>'
                    +'<h4>'+video.title+'</h4>'
                    +'<p><i>'+video.publication+'</i></p>'
                    +'</div>'
                    +'<img src="'+video.thumbnail+'">'
                    +'</a>';

      $('#channel-container').append(newVideo);
      jQuery('.venobox').venobox();
    })
  if (data.nextPageToken.length > 0) {
    var newButton = '<p class="text-center"><a class="btn btn-primary" type="button" onclick="loadMore(\''+data.nextPageToken+'\', \''+playlistId+'\', \''+apiKey+'\', \''+countVideos+'\')">en voir plus...</a></p>'
    $('#load-more').html(newButton);
  }
  else{
    $('#load-more').remove();
  }

  });
}

$(document).ready(function(){
    $('.venobox').venobox();
});