<?php
use Illuminate\Support\Facades\Storage;

function itemidentity($cate,$key){
      
       $ids = DB::table('prch_allitem_record')->orderBy('id', 'DESC')->first();
        if($ids == ''){

		 $id = 1;
        }else{
            $id = $ids->id+1;
        }
       if($id > 0 && $id < 10){
        return $barcode = $key.$cate.'0000'.$id;
       }else if ($id >9 && $id < 100){
          return $barcode = $key.$cate.'000'.$id;
       }else if($id >99 && $id < 1000){
       return $barcode = $key.$cate.'00'.$id;
       }else if($id >999 && $id < 10000){
       	return $barcode = $key.$cate.'0'.$id;
       }


}

function uploads($file){

    // $fileName = $file;  
    public_path('uploads');
    $name =  time().'_'.$file->getClientOriginalName();
    return $file->storeAs('public/uploads',$name);
}