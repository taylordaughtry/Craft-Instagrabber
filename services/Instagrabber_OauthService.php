<?php
namespace Craft;

class Instagrabber_OauthService extends BaseApplicationComponent
{
	protected $plugin;
	protected $settings;
	protected $client;
	protected $url;

	public function __construct()
	{
		$this->plugin = craft()->plugins->getPlugin('instagrabber');
		$this->settings = $this->plugin->getSettings();
		$this->client = new \Guzzle\Http\Client();
		$this->url = 'https://api.instagram.com/oauth/access_token';
	}

	public function connect($code)
	{
		$postData = $this->getPostData($code);

		$request = $this->client->post($this->url, null, $postData);

		try {
			$response = $request->send();

			if ($response->getStatusCode() === 200) {
				$data = $response->json()['user'];

				$settings = [
					'username' => $data['username'],
					'profile_picture' => $data['profile_picture'],
					'full_name' => $data['full_name'],
					'isConnected' => 1,
					'accessToken' => $response->json()['access_token'],
				];

				$this->updatePluginSettings($settings);

				return true;
			}

		} catch (\Exception $e) {
			throw new HttpException(400, 'Instagrabber couldn\'t connect to your account.');

			return false;
		}
	}

	public function disconnect()
	{

		$params = [
			'isConnected' => 0,
		];

		craft()->plugins->savePluginSettings($this->plugin, $params);

		craft()->userSession->setNotice('Account disconnected.');

		$this->redirect(UrlHelper::getCpUrl('settings/plugins/instagrabber'));
	}

	public function getPostData($code)
	{
		return $postData = [
			'client_id' => $this->settings->clientId,
			'client_secret' => $this->settings->clientSecret,
			'grant_type' => 'authorization_code',
			'redirect_uri' => craft()->getSiteUrl() . 'instagrabber/connect',
			'code' => $code,
		];
	}

	public function updatePluginSettings($settings)
	{
		craft()->plugins->savePluginSettings($this->plugin, $settings);
	}
}