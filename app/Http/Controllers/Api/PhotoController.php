<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\FtpController;
use Illuminate\Http\Request;
use App\Models\Photo;
use Exception;

class PhotoController extends ResponseBaseController
{
    public function showAll(){
        $resultAll = Photo::all();
        try{
            return $this->sendResponse($resultAll, 'Successfull');
        }catch (Exception $e) {
            return $this->sendError('Error Recovery Data',['error'=>'Error Recovery Data']);
        }
    }

    public function savePhoto(Request $request){
        $photo = new Photo();
        $photo->name = $request->name;
        $photo->description = $request->description;
        $photo->size = $request->size;
        $photo->path = $request->path;
        $photo->fileName = $request->fileName;
        $photo->albums_id = $request->albums_id;

        $connectionFtp = New FtpController;
        $message = '';
        $resultPutFileOnFtp = $connectionFtp->PutFileOnFtp($request->fileName, $message);
        if (!$resultPutFileOnFtp) {
            return $this->sendError('Error Save Photo', ['error'=>'Error in Photo Storage', 'exception'=>$message]);
        }

        try{
            $resutlSave = $photo->save();
            return $this->sendResponse($resutlSave, 'Photo Save Successfull');
        } catch (Exception $e) {
            return $this->sendError('Error Save Photo',['error'=>'Error Save Photo']);
        }

    }

    public function deletePhoto($request){
        $message = '';
        $connectionFtp = New FtpController;
        $resultFtpDeleteFile = $connectionFtp->FtpDeleteFile($request->fileName, $message);
        if (!$resultFtpDeleteFile) {
            return $this->sendError('Error Deleting Storage Photo', ['error'=>'Error Deleting Storage Photo'],['exception'=>$message]);
        }


        $photo = new Photo();
        $photo->where('id','=',$request->id)->delete();
    }
}
