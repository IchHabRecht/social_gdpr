# embetty-server-php

Alternative to [heise/embetty-server](https://github.com/heiseonline/embetty-server) written in plain old PHP that cannot be used as a drop-in replacement.

## Usage

Supported routes (replace `ID` with a video's identifier):

- ``/video/vimeo/ID``
- ``/video/youtube/ID``
- ``/version``

## How It Works

The script acts as a proxy by requesting a thumbnail of the video for the given identifier and forwarding the binary response to the browser if headers indicate an image. This includes cache headers, but not cookies or similar. The script tries to return "404 Not Found" for cases of unexpected reponse data.

## Development

For local tests or development start a php server:

```bash
php -S localhost:8000
```

and open in a browser:

- [/version](http://localhost:8000/version)
- [/video/youtube/ID](http://localhost:8000/video/youtube/Jw4KDz0kbJY)
- [/video/vimeo/ID](http://localhost:8000/video/vimeo/9558160)

## License

[MIT](LICENSE)
