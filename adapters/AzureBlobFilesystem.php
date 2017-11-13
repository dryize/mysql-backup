<?php

namespace app\adapters;


use BackupManager\Filesystems\Filesystem;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Azure\AzureAdapter;
use MicrosoftAzure\Storage\Common\ServicesBuilder;

/**
 * Created by prabath.
 * Date: 11/13/17
 * Time: 15:11
 */
class AzureBlobFilesystem implements Filesystem
{

    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'azure-blob';
    }

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config)
    {
        $endpoint = sprintf('DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
            $config['account-name'], $config['api-key']);
        $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($endpoint);

        $az = new AzureAdapter($blobRestProxy, $config['container']);
        return new Flysystem($az);
    }
}