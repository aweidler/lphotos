<?php

namespace Photos\Providers;

use Storage;
use Ijin82\Flysystem\Azure\AzureAdapter;
use League\Flysystem\Filesystem;
use MicrosoftAzure\Storage\Common\ServicesBuilder;
use Illuminate\Support\ServiceProvider;

class AzureServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('azure_blob', function ($app, $config) {
            $blobService = ServicesBuilder::getInstance()
                ->createBlobService($config['endpoint']);

            return new Filesystem(new AzureAdapter(
                $blobService,
                $config,
                (isset($config['root']) ? basename($config['root']) : null)
            ));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
