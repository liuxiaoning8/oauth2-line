This package provides Line OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Requirements

The following versions of PHP are supported.

* PHP 7.0
* PHP 7.1
* PHP 7.2
* PHP 7.3
* PHP 7.4

This package uses [OpenID Connect][openid-connect] to authenticate users with Line accounts.

To use this package, it will be necessary to have a Line client ID and channel ID. These are referred to as {line-client-id} and {line-channel-id} in the documentation.

Please follow the [Line instructions][oauth-setup] to create the required credentials.

[openid-connect]: https://developers.line.biz/en/docs/line-login/web/integrate-line-login-v2/
[oauth-setup]: https://developers.line.biz/en/docs/line-login/getting-started/

## Installation

To install, use composer:

```
composer require xmugenx/oauth2-line
```
