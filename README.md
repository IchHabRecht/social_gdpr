# TYPO3 Extension social_gdpr

[![Latest Stable Version](https://img.shields.io/packagist/v/ichhabrecht/social-gdpr.svg)](https://packagist.org/packages/ichhabrecht/social-gdpr)
[![Build Status](https://github.com/IchHabRecht/social_gdpr/actions/workflows/test.yml/badge.svg)](https://github.com/IchHabRecht/social_gdpr/actions)
[![StyleCI](https://styleci.io/repos/259281576/shield?branch=main)](https://styleci.io/repos/259281576)

Show social media after user confirmation

## Features

## Installation

Simply install the extension with Composer or the [Extension Manager](https://extensions.typo3.org/extension/social_gdpr/).

`composer require ichhabrecht/social-gdpr`

## Usage

@todo
Write instructions on how to activate the extension
by either including the typoscript or creating a record in the BE

Configure the extension in settings.php
```php
'social_gdpr' => [
    'osmPreview' => '0',
    'vimeoPreview' => '0',
    'youtubePreview' => '0',
],
```

## Run Unit Tests

```sh
composer install
php .Build/bin/phpunit -c phpunit.xml.dist
```

## Community

- Thanks to [elementare teilchen GmbH](https://www.elementare-teilchen.de) that sponsors the maintenance of this extension
