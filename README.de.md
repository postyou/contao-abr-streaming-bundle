[switch to english version](https://github.com/postyou/contao-abr-streaming-bundle/blob/master/README.md)
# contao-abr-streaming
Adaptive Bitrate Streaming über Mediaelement.js mit MPEG-DASH und HLS Manifesten

Contao-Extension für Contao 4

## Anmerkungen
Um diese Erweiterung zu nutzen, muss das `js_medialement` Template im Layout eingebunden sein. Die Erweiterung wird dieses dann intern mit dem eigenen `js_mediaelement_dash` Template überschreiben. Wer das Template bearbeiten will, muss also in Contao -> Templates `js_mediaelement_dash` aufrufen.

Wenn mehr als eine Videodatei angegeben wurde, werden die Quellen in folgender Reihenfolge geordnet: mpd, m3u8, mp4, m4v, mov, wmv, webm, ogv.

## Benutzung
![screenshot](https://github.com/postyou/contao-abr-streaming-bundle/blob/master/readme_img/Element.png)

* Neues Inhaltselement `ABR - Streaming` anlegen
* MPEG-Dash Manifest hinzufügen (z.B. `playlist.mpd`)
* HLS Manifest hinzufügen (z.B. `playlist.m3u8`)
* Fallback Videoquellen hinzufügen (z.B. `mp4, m4v, mov, wmv, webm, ogv`)

## Mediaelement.js Plugin
Durch diese Erweiterung kann man das Feature `qualityselection` nutzen, mit dem im Player zwischen den hinterlegten Qualitätsstufen gewechselt werden kann. Dies funktioniert auf ähnliche Weise wie im gewohnten Youtube Player.

```js
var player = new MediaElementPlayer(e[i], {
    features: ['playpause', 'current', 'progress', 'duration', 'volume', 'qualityselection', 'fullscreen']
});
```

![screenshot](https://github.com/postyou/contao-abr-streaming-bundle/blob/master/readme_img/Plugin.png)

## Beispielerstellung von einem MPEG-DASH Manifest
```sh
# 1080p - 4800k bitrate
x264 --output intermediate_4800k.264 --fps 24 --preset slow \
--bitrate 4800 --vbv-maxrate 9600 --vbv-bufsize 19200 --min-keyint 96 --keyint 96 \
--scenecut 0 --no-scenecut --pass 1 --video-filter "resize:width=1920,height=1080" input.mp4
# 720p - 2400k bitrate
x264 --output intermediate_2400k.264 --fps 24 --preset slow \
--bitrate 2400 --vbv-maxrate 4800 --vbv-bufsize 9600 --min-keyint 96 --keyint 96 \
--scenecut 0 --no-scenecut --pass 1 --video-filter "resize:width=1280,height=720" input.mp4
# 540p - 1400k bitrate
x264 --output intermediate_1400k.264 --fps 24 --preset slow \
--bitrate 1400 --vbv-maxrate 2800 --vbv-bufsize 5600 --min-keyint 96 --keyint 96 \
--scenecut 0 --no-scenecut --pass 1 --video-filter "resize:width=960,height=540" input.mp4
# AAC audio only - 128k bitrate
ffmpeg -y -threads 0 -i input.mp4 -vn -c:a aac -b:a 128k audio_128k.m4a

#add created raw videos to mp4 containers
MP4Box -add intermediate_4800k.264 -fps 24 intermediate_4800k.mp4
MP4Box -add intermediate_2400k.264 -fps 24 intermediate_2400k.mp4
MP4Box -add intermediate_1400k.264 -fps 24 intermediate_1400k.mp4

# Create MPEG-DASH files
MP4Box -dash 4000 -frag 4000 -rap -segment-name '$RepresentationID$/segment_' -url-template -out playlist.mpd \
intermediate_4800k.mp4:id=1080 intermediate_2400k.mp4:id=720 intermediate_1400k.mp4:id=540 audio.m4a:id=audio
```

## Nützliche Links
* [majamee/alpine-dash-hls](https://github.com/majamee/alpine-dash-hls)
* [Bitmovin dash content generation](https://bitmovin.com/mp4box-dash-content-generation-x264/)
* [squidpickles/mpd-to-m3u8](https://github.com/squidpickles/mpd-to-m3u8)
