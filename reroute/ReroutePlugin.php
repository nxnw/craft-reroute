<?php

namespace Craft;

class ReroutePlugin extends BasePlugin
{
	public function getName()
	{
		return Craft::t('Reroute');
	}

	public function getVersion()
	{
		return '1.0.2';
	}

	public function getDeveloper()
	{
		return 'Trevor Davis';
	}

	public function getDeveloperUrl()
	{
		return 'http://trevordavis.net';
	}

	public function hasCpSection()
	{
		return true;
	}

	public function registerCpRoutes()
	{
		return array(
			'reroute\/new' => 'reroute/_form',
			'reroute\/(?P<rerouteId>\d+)' => 'reroute/_form',
		);
	}

	public function init() {
		if(craft()->request->isSiteRequest()) {
			$url = craft()->request->getUrl();
			$reroute = craft()->reroute->getByUrl($url);

			if ($reroute) {
                $query = parse_url($url);
                if( isset($query['query']) ) {
                    craft()->request->redirect($reroute['newUrl'].'?'.$query['query'], true, $reroute['method']);
                } else {
                    if(strlen($query['path']) > 1) {
                        $path = array_values(array_filter(explode('/', $query['path'])));
                        unset($path[0]);

                        craft()->request->redirect($reroute['newUrl'].'/'.implode('/', $path), true, $reroute['method']);

                    } else {
                        craft()->request->redirect($reroute['newUrl'], true, $reroute['method']);

                    }
                }
			}
		}
	}
}