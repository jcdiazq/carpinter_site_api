<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function GuzzleHttp\Promise\exception_for;

class FtpController extends Controller
{
    protected $ftp;

    public function __construct()
    {
        try{
            $this->ftp = Storage::createFtpDriver([
                'driver' => 'ftp',
                'host' => 'siteFtp',
                'username' => 'bob',
                'password' => '12345',
                'port' => '21',
                'passive' => false,
                // 'ignorePassiveAddress' => true,
            ]);
            $this->ftp->ftp_connect();
        }
        catch(Exception $e){
            return 'Error en la conexión con el FTP'. $e->getMessage();
        }

    }

    public function getAllFileName(){
        try {
            $fileNameArray = $this->ftp->allFiles();
            return 'Archivos Recuperados: '. implode(",", $fileNameArray);
        } catch (Exception $e) {
            return 'Error Intentando Recuperar el Nombre de los Archivos'. $e->getMessage();
        }
    }
}
