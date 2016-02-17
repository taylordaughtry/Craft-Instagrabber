<?php
namespace Craft;

class InstagrabberController extends BaseController
{
	protected $settings;

	public function __construct()
	{
		$this->settings = craft()->plugins->getPlugin('instagrabber')->getSettings();

        $this->client = new \Guzzle\Http\Client();
	}

    public function actionConnect()
    {
        $url = 'https://api.instagram.com/oauth/access_token';

    	$postData = [
    		'client_id' => $this->settings->clientId,
    		'client_secret' => $this->settings->clientSecret,
    		'grant_type' => 'authorization_code',
    		'redirect_uri' => craft()->getSiteUrl() . 'instagrabber/connect',
    		'code' => $code,
    	];

    	$request = $client->post($url, null, $postData);

    	try {
			$response = $request->send();

			// If we got a successful return, tell Craft to save that setting.
			if ($response->getStatusCode() === 200) {
				$data = $response->json()['user'];

				$instagrabber = craft()->plugins->getPlugin('instagrabber');

				$settings = [
					'username' => $data['username'],
					'profile_picture' => $data['profile_picture'],
					'full_name' => $data['full_name'],
					'isConnected' => 1,
				];

				craft()->plugins->savePluginSettings($instagrabber, $settings);
			}

		} catch (\Exception $e) {
			throw new HttpException(400, 'Instagrabber couldn\'t connect to your account.');
		}

		craft()->userSession->setNotice('Account connected.');

		$this->redirect(UrlHelper::getCpUrl('settings/plugins/instagrabber'));
    }

    public function actionDisconnect()
    {
    	$instagrabber = craft()->plugins->getPlugin('instagrabber');

    	$params = [
    		'isConnected' => 0,
    	];

    	craft()->plugins->savePluginSettings($instagrabber, $params);

    	craft()->userSession->setNotice('Account disconnected.');

		$this->redirect(UrlHelper::getCpUrl('settings/plugins/instagrabber'));
    }
}