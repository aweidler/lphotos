<?php

namespace Photos\Adapters;

use Ijin82\Flysystem\Azure\AzureAdapter;
use League\Flysystem\Config as FlyConfig;

class AzurePhotosAdapter extends AzureAdapter{

	/**
	 * {@inheritdoc}
	 * @override
	 */
	public function getUrl($file)
	{
		return $this->fsConfig['blob_service_url'] 
		. $this->pathSeparator 
		. $this->container 
		. $this->pathSeparator 
		. $this->pathPrefix 
		. $file;
	}

	protected function getOptionsFromConfig(FlyConfig $config){
		$options = parent::getOptionsFromConfig($config);

		// Error in options config, must fix
		if($this->fsConfig['mimetype']){
			$options->setBlobContentType($this->fsConfig['mimetype']);
		}

		return $options;
	}

}