<?php

return [
    'adminEmail' => 'admin@example.com',
    'stores' => [
        'local' => [
            'type' => 'Local',
            'root' => '/tmp'
        ],
        'azure-blob' => [
            'type' => 'azure-blob',
            'account-name' => '',
            'api-key' => '',
            'container' => ''
        ],
        's3' => [
            'type' => 'AwsS3',
            'key'    => '',
            'secret' => '',
            'region' => 'us-east-1',
            'version' => 'latest',
            'bucket' => '',
            'root'   => '',
        ],
        'gcs' => [
            'type' => 'Gcs',
            'key'    => '',
            'secret' => '',
            'bucket' => '',
            'root'   => '',
        ],
        'rackspace' => [
            'type' => 'Rackspace',
            'username' => '',
            'key' => '',
            'container' => '',
            'zone' => '',
            'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
            'root' => '',
        ],
        'dropbox' => [
            'type' => 'Dropbox',
            'token' => '',
            'key' => '',
            'secret' => '',
            'app' => '',
            'root' => '',
        ],
        'ftp' => [
            'type' => 'Ftp',
            'host' => '',
            'username' => '',
            'password' => '',
            'port' => 21,
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
            'root' => '',
        ],
        'sftp' => [
            'type' => 'Sftp',
            'host' => '',
            'username' => '',
            'password' => '',
            'port' => 21,
            'timeout' => 10,
            'privateKey' => '',
            'root' => '',
        ],
    ]
];
