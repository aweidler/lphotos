<?php

namespace Photos\Adapters;

use Ijin82\Flysystem\Azure\AzureAdapter;

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

}