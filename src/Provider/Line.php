<?php

namespace XmugenX\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Line extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var string If set, this will be sent to line as the "response_type" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $response_type;

    /**
     * @var string If set, this will be sent to line as the "client_id" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $client_id;

    /**
     * @var string If set, this will be sent to line as the "redirect_uri" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $redirect_uri;

    /**
     * @var string If set, this will be sent to line as the "state" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $state;

    /**
     * @var array List of scopes that will be used for authentication.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $scopes = [];

    /**
     * @var string If set, this will be sent to line as the "nonce" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $nonce;

    /**
     * @var string If set, this will be sent to line as the "prompt" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $prompt;

    /**
     * @var string If set, this will be sent to line as the "max_age" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $max_age;

    /**
     * @var string If set, this will be sent to line as the "ui_locales" parameter.
     * @link https://developers.line.biz/en/docs/line-login/web/integrate-line-login/
     */
    protected $ui_locales;

    public function getBaseAuthorizationUrl()
    {
        return 'https://access.line.me/oauth2/v2.1/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://api.line.me/oauth2/v2.1/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.line.me/v2/profile';
    }

    protected function getAuthorizationParameters(array $options)
    {

        if (empty($options['prompt']) && $this->prompt) {
            $options['prompt'] = $this->prompt;
        }

        // The "approval_prompt" option MUST be removed to prevent conflicts with non-empty "prompt".
        if (!empty($options['prompt'])) {
            $options['approval_prompt'] = null;
        }

        // Default scopes MUST be included for OpenID Connect.
        // Additional scopes MAY be added by constructor or option.
        $scopes = array_merge($this->getDefaultScopes(), $this->scopes);

        if (!empty($options['scope'])) {
            $scopes = array_merge($scopes, $options['scope']);
        }

        $options['scope'] = array_unique($scopes);

        return parent::getAuthorizationParameters($options);
    }

    protected function getDefaultScopes()
    {
        // "openid" MUST be the first scope in the list.
        return [
            'openid',
            'profile',
        ];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {

        // @codeCoverageIgnoreStart
        if (empty($data['error'])) {
            return;
        }
        // @codeCoverageIgnoreEnd

        $code = 0;
        $error = $data['error'];

        if (is_array($error)) {
            $code = $error['code'];
            $error = $error['message'];
        }

        throw new IdentityProviderException($error, $code, $data);
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new LineUser($response);
    }
}
