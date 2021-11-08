<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\FtpController;
use App\Http\Controllers\Utils;
use Illuminate\Http\Request;
use App\Models\Photo;
use Exception;

class PhotoController extends ResponseBaseController
{
    private const BASEPATH = 'images/';

    public function ShowAll(){
        $resultAll = Photo::all();
        try{
            return $this->sendResponse($resultAll, 'Successfull');
        }catch (Exception $e) {
            return $this->sendError('Error Recovery Data',['error'=>'Error Recovery Data']);
        }
    }

    public function SavePhoto(Request $request){
        $photo = new Photo();
        $photo->name = $request->name;
        $photo->description = $request->description;
        $photo->size = $request->size;
        $photo->path = $request->path;
        $photo->fileName = $request->fileName;
        $photo->albums_id = $request->albums_id;
        $fileContents = Utils::DecodeBase64File($request->image, $request->fileName);
        $connectionFtp = New FtpController;
        $message = '';
        $resultPutFileOnFtp = $connectionFtp->PutFileOnFtp($request->path, $request->fileName, $fileContents, $message);
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

    public function DeletePhoto(Request $request){
        $photo = $this->SearchPhotoObject($request->id);
        $directory = Utils::JoinPaths(self::BASEPATH,$photo->path);
        $this->DeleteFtpPhoto($directory, $photo->fileName);
        $photo->delete();
        return $this->sendResponse(['File_Name'=>$request->filename], 'Photo Deleted Successfull');
    }

    public function DeleteFtpPhoto($directory, $file){
        $message = '';
        $connectionFtp = New FtpController;
        $resultFtpDeleteFile = $connectionFtp->FtpDeleteFile($directory, $file, $message);
        if (!$resultFtpDeleteFile) {
            return $this->sendError('Error Deleting Storage Photo', ['error'=>'Error Deleting Storage Photo','exception'=>$message]);
        }
    }

    Public function FindPhotoById(Request $request){
        try {
            $resultJson = $this->SearchPhotoObject($request->id)->toJson();
            return $this->sendResponse($resultJson, 'Recovery Data Photo Successfull');
        } catch (Exception $e) {
            return $this->sendResponse(['id'=>$request->id], ['Error'=>'Wrong searching id '.$e->getMessage()]);
        }
    }

    public function SearchPhotoObject($id)
    {
        $photo = New Photo();
        return $photo->where('id','=',$id);
    }
}
