<head>
  <link href="main.css" rel="stylesheet" />

  <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
  <script src="ie8.min.js"></script>
</head>

<body>
  <video
    id="my-video"
    class="video-js"
    controls
    preload="auto"
    width="640"
    height="264"

    data-setup="{}"
  >
    <source src="<?=base_url()?>public/latestweight/files/video.m3u8" type="application/x-mpegURL"  />
   
    <p class="vjs-no-js">
      To view this video please enable JavaScript, and consider upgrading to a
      web browser that
      <a href="rtsp://wowzaec2demo.streamlock.net/vod/mp4:BigBuckBunny_175k.mov" target="_blank"
        >supports HTML5 video</a
      >
    </p>
  </video>

  <script src="<?=base_url()?>public/latestweight/files/main.js"></script>
    <script>
    
    var player = videojs('my-video', {liveui: true});
    </script>
</body>