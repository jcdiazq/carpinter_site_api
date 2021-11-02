<?php

namespace App\Http\Controllers\Api;

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
        $photo->albums_id = $request->albums_id;

        try{
            $resutlSave = $photo->save();
            return $this->sendResponse($resutlSave, 'Photo Save Successfull');
        } catch (Exception $e) {
            return $this->sendError('Error Save Photo',['error'=>'Error Save Photo']);
        }

    }
}
