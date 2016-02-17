<?php
namespace Craft;

class InstagrabberPlugin extends BasePlugin
{
	public function getName()
	{
		 return 'Instagrabber';
	}

	public function getDescription()
	{
		return 'Integrate Instagram into your client sites.';
	}

	public function getVersion()
	{
		return '1.0.0';
	}

	public function getSchemaVersion()
	{
		return '1.0.0';
	}

	public function getDeveloper()
	{
		return 'Taylor Daughtry';
	}

	public function getDeveloperUrl()
	{
		return 'https://github.com/taylordaughtry';
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/taylordaughtry/craft-instagrabber';
	}

	public function getReleaseFeedUrl()
	{
		return '';
	}

	public function getIconPath()
	{
		return craft()->path->getPluginsPath().'instagrabber/resources/icon.svg';
	}

	public function getSettingsHtml()
	{
		return craft()->templates->render('instagrabber/settings', [
			'settings' => $this->getSettings()
		]);
	}

	protected function defineSettings()
	{
		return [
			'clientId' => array(AttributeType::String, 'default' => ''),
			'clientSecret' => array(AttributeType::String, 'default' => ''),
			'isConnected' => array(AttributeType::Number, 'default' => 0),
			'username' => array(AttributeType::String, 'default' => 0),
			'profile_picture' => array(AttributeType::String, 'default' => 0),
			'full_name' => array(AttributeType::String, 'default' => 0),
			'accessToken' => array(AttributeType::String, 'default' => ''),
		];
	}

	public function registerSiteRoutes()
	{
		return [
			'instagrabber/connect' => ['action' => 'instagrabber/connect'],
		];
	}
}