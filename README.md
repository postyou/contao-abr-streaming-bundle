# Adaptive Bitrate Streaming using Video.js

[![](https://img.shields.io/packagist/v/postyou/contao-abr-streaming-bundle.svg)](https://packagist.org/packages/postyou/contao-abr-streaming-bundle)
[![](https://img.shields.io/badge/video.js-v8.12.0-blue.svg)](https://github.com/videojs/video.js/releases)
[![](https://img.shields.io/packagist/l/postyou/contao-abr-streaming-bundle.svg)](https://packagist.org/packages/postyou/contao-abr-streaming-bundle)
[![](https://img.shields.io/packagist/dt/postyou/contao-abr-streaming-bundle.svg)](https://packagist.org/packages/postyou/contao-abr-streaming-bundle)

This extension enables adaptive bitrate streaming with [Video.js](https://github.com/videojs/video.js) in contao. It contains a [source selector plugin](https://github.com/FreeTubeApp/videojs-http-source-selector) that provides "user-selectable options for adaptive http streams":

![Source Selection Screenshot](docs/source-selector-screenshot.png)

## Upgrade from version 1

In version 2, we switched from Mediaelement.js to Video.js and removed the content element `abrstreaming` and the template `js_mediaelement_dash`. You should now use the content element `player` with the template `ce_player.html5` provided by this extension.
