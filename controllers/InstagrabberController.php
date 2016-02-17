<?php
namespace Craft;

class InstagrabberController extends BaseController
{
	protected $settings;

	protected $client;

	public function __construct()
	{
		$this->settings = craft()->plugins->getPlugin('instagrabber')->getSettings();

		$this->client = new \Guzzle\Http\Client();
	}

	public function actionConnect()
	{
		$code = craft()->request->getQuery('code');

		if (craft()->instagrabber_oauth->connect($code)) {
			craft()->userSession->setNotice('Account connected.');

			$this->redirect(UrlHelper::getCpUrl('settings/plugins/instagrabber'));
		}

		craft()->userSession->setError('Unable to connect.');

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