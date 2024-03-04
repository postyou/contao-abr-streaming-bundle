# Adaptive Bitrate Streaming using Video.js

This extension enables adaptive bitrate streaming with [Video.js](https://github.com/videojs/video.js) in contao. It contains a [source selector plugin](https://github.com/FreeTubeApp/videojs-http-source-selector) that provides "user-selectable options for adaptive http streams":

![Source Selection Screenshot](docs/source-selector-screenshot.png)

## Upgrade from version 1

In version 2 we switched from Mediaelement.js to Video.js and removed the `abrstreaming` content element. You can now use the `player` content element with the `ce_player.html5` template provided by this extension.
