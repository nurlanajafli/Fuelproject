<?php

namespace console\controllers;

use common\enums\FuelCardDataProvider;
use common\helpers\Path;
use common\models\FuelcardAccountConfig;
use gftp\drivers\SftpDriver;
use gftp\FtpComponent;
use yii\console\Controller;

class ComdataController extends Controller
{
    public function actionFetch()
    {
        /** @var FuelcardAccountConfig $row */
        if ($row = FuelcardAccountConfig::findOne(['type' => FuelCardDataProvider::COMDATA])) {
            $ftp = new FtpComponent([
                'driverOptions' => [
                    'class' => SftpDriver::class,
                    'user' => $row->config['Username'],
                    'pass' => $row->config['Password'],
                    'host' => 'transfer.comdata.com',
                    'port' => '50039',
                    'timeout' => 120
                ]
            ]);
//            $ftp->chdir('Inbox');
//            $files = $ftp->ls();
//            $path = Path::join(dirname(dirname(__DIR__)), 'fuel_providers', 'comdata');
//            foreach ($files as $file) {
//                if (!$file->isDir && $file->filename != '.' && $file->filename != '..') {
//                    $ftp->get($file->filename, Path::join($path, $file->filename), FTP_BINARY);
//                }
//            }
            $ftp->chdir('../outgoing');
            $files = $ftp->ls();
            $path = Path::join(dirname(dirname(__DIR__)), 'fuel_providers', 'comdata' . DIRECTORY_SEPARATOR . 'outgoing');
            foreach ($files as $file) {
                if (!$file->isDir && $file->filename != '.' && $file->filename != '..') {
                    echo "processsing " . $file->filename . "\n";
                    $ftp->get($file->filename, Path::join($path, $file->filename), FTP_BINARY);
                }
            }
            $ftp->close();
        }
    }
}	
