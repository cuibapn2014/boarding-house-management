<?php

namespace App\Http\Controllers;

use App\Models\BoardingHouseFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardingHouseFileController extends Controller
{
    //
    public function destroy($id) 
    {
        $boardingHouseFile = BoardingHouseFile::find($id);

        if(! $boardingHouseFile) {
            return $this->responseError('File không tồn tại hoặc đã bị xoá!');
        }

        try{
            DB::transaction(function () use($boardingHouseFile) {
                cloudinary()->destroy($boardingHouseFile->public_id);
                $boardingHouseFile->delete();
            });
        } catch(\Exception $ex) {
            return $this->responseError();
        }

        return $this->responseSuccess('Xoá tệp tin thành công!');
    }
}
