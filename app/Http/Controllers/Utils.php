<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;


class Utils extends Controller
{
    public static function DecodeBase64File($data, $fileName){
        $file = str_replace(self::DataTypeExt($fileName), '', $data);
        $file = str_replace(' ', '+', $file);
        return base64_decode($file);
    }

    public static function DataTypeExt($fileName){
        return 'data:image/png,base64,';
    }

    public static function JoinPaths($firstPath, $secondsPath)
    {
        if (str_ends_with($firstPath,'/')) {
            return $firstPath.$secondsPath;
        }
        return $firstPath.'/'.$secondsPath;
    }
}
