<?php

namespace Codewize\SquareupSocialite;

use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Square\Environments;

class SocialiteSquareUpProvider extends AbstractProvider implements ProviderInterface
{
  const URL = Environments::Production->value;

  /**
   * The separating character for the requested scopes.
   *
   * @var string
   */
  protected $scopeSeparator = ' ';

  /**
   * The scopes being requested.
   *
   * @var array
   */
  protected $scopes = [
    'MERCHANT_PROFILE_READ',
    'PAYMENTS_READ',
    'ORDERS_READ',
    'INVOICES_READ',
    // 'SETTLEMENTS_READ',
    // 'BANK_ACCOUNTS_READ',
    // 'ITEMS_READ',
    // 'ORDERS_READ',
    // 'EMPLOYEES_READ',
    // 'TIMECARDS_READ'
  ];

  /**
   * {@inheritdoc}
   */
  protected function getAuthUrl($state)
  {
    return $this->buildAuthUrlFromBase(self::URL . '/oauth2/authorize', $state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getTokenUrl()
  {
    return self::URL . '/oauth2/token';
  }

  /**
   * Get the access token for the given code.
   *
   * @param  string $code
   * @return string
   */
  public function getAccessToken($code)
  {
    $url = $this->getTokenUrl();
    $data = $this->getTokenFields($code);

    $response = $this->getHttpClient()->post($url, [
      'json' => $data,
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Client ' . $this->clientSecret,
      ],
    ]);

    return $this->parseAccessToken($response->getBody());
  }

  /**
   * Get the POST fields for the token request.
   *
   * @param  string $code
   * @return array
   */
  protected function getTokenFields($code)
  {
    return parent::getTokenFields($code);
  }

  /**
   * {@inheritdoc}
   */
  protected function getUserByToken($token)
  {
    $response = $this->getHttpClient()->get(self::URL . '/v1/me', [
      'headers' => [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $token,
      ],
    ]);

    return json_decode($response->getBody(), true);
  }

  /**
   * {@inheritdoc}
   */
  protected function mapUserToObject(array $user)
  {
    return (new User)->setRaw($user)->map([
      'id' => array_get($user, 'id'),
      'name' => array_get($user, 'name'),
      'email' => array_get($user, 'email'),
    ]);
  }
}
