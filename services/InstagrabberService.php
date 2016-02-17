<?php
namespace Craft;

class InstagrabberService extends BaseApplicationComponent
{
	protected $settings;

	public function __construct()
	{
		$this->settings = craft()->plugins->getPlugin('instagrabber')->getSettings();
	}
}