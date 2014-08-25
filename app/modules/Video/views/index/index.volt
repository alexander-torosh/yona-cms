<div id="video">
    <iframe width="720" height="450" src="//www.youtube.com/embed/{{ video.getYoutubeHash() }}?rel=0" frameborder="0" allowfullscreen></iframe>
</div>

{{ partial('../../modules/Video/views/index/partial/videos',['videos': videos, 'activeVideo': video.getId()]) }}