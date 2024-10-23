# Videos for Craft CMS 5.x

Connect to YouTube & Vimeo and publish social videos on your website.

## Requirements

This plugin requires Craft CMS 5.0.0+.

## Installation

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project

# tell Composer to load the plugin
composer require boundstate/craft-videos

# tell Craft to install the plugin
./craft plugin/install videos
```

## Configuration

### config/videos.php

```php
<?php
return [
    // cache API requests
    'enableCache' => true,
    // https://php.net/dateinterval.construct
    'cacheDuration' => 'PT15M',
    // OAuth provider options
    'oauthProviderOptions' => [
        'youtube' => [
            'clientId' => '000000000000-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx.apps.googleusercontent.com',
            'clientSecret' => 'xxxxxxxxxxxxxxxxxxxxxxxx'
        ],
        'vimeo' => [
            'clientId' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx',
            'clientSecret' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
        ],
    ],
    // videos per page in the explorer
    'videosPerPage' => 30,
];
```

## Resources

- [Connect Vimeo](docs/connect-vimeo.md)
- [Connect YouTube](docs/connect-youtube.md)
- [Twig Variables](docs/twig-variables.md)
- [Video Field](docs/video-field.md)
- [Video Model](docs/video-model.md)

