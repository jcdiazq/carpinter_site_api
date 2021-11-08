<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;

use function GuzzleHttp\Promise\exception_for;

class FtpController extends Controller
{
    private $ftp;
    private $filePath = '/images';

    public function __construct()
    {
        try{
            $this->ftp = Storage::createFtpDriver([
                'driver' => 'ftp',
                'host' => env('FTP_HOST'),
                'username' => env('FTP_USERNAME'),
                'password' => env('FTP_PASSWORD'),
                'port' => env('FTP_PORT'),
                'passive' => env('FTP_PASSIVE'),
                'ignorePassiveAddress' => true,
            ]);
            // $this->ftp->ftp_connect();
        }
        catch(Exception $e){
            return 'Error en la conexión con el FTP'. $e->getMessage();
        }

    }

    public function getAllFileName(){
        try {
            $fileNameArray = $this->ftp->allFiles();
            return 'Recovery Files: '. implode(",", $fileNameArray);
        } catch (Exception $e) {
            return 'Wrong Trying Recovery Files'. $e->getMessage();
        }
    }

    public function PutFileOnFtp($filePath, $fileName, $contents, &$message=''){
        try {
            $path = $filePath.$fileName;
            if ($this->ftp->put($path, $contents)) {
                return true;
            }else {
                throw new Exception("Error load File in Storege", 1);
            }
        } catch (Exception $e) {
            $message = 'Wrong loading File:'. $fileName. ' Message: '.$e->getMessage();
            return false;
        }
    }

    public function FtpDeleteFile($filePath, $fileName, &$message=''){
        try {
            $path = $filePath.$fileName;
            if ($this->ftp->exists($path)) {
                throw new Exception("Error File Not Found in Storege", 1);
            }
            if ($this->ftp->delete($path)) {
                return true;
            }else {
                throw new Exception("Error Deleting File", 1);
            }
            return true;
        } catch (Exception $e) {
            $message = 'Wrong Removing File:'.$fileName. ' Message: '.$e->getMessage();
            return false;
        }
    }
}
