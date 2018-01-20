<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use \AlexeyMezenin\LaravelRussianSlugs\SlugsTrait;
use App\Main_claster;
use App\Claster;
use App\Objecte;
use App\Feature;
use App\Link;
use App\Media;
use App\Entity;
use App\test;

class Controller extends BaseController
{
    //  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function index()
    {    

      //  $main_claster=DB::table('Main_claster')->where('id', 1)->first();
        $main_clasters=\App\Main_claster::pluck('name', 'id');
        $data['main_claster']=array();
    //    foreach($main_clasters as $main_claster){
            $data['main_claster']= $main_clasters;
    //    }
        $data['clasters']=\App\Main_claster::find(1)->clasters()->get(); 
        return view('main', $data);
    }

    function get_clasters(Request $request){
        $object_name='App\\'.$request->get('type');
        $clasters=$object_name::where('parent',$request->get('id'))->pluck('name', 'id');
        return response()->json($clasters);
    }



    function newdata(Request $request)
    {
        $res = self::verifyExist(['table' => $request->get('type'), 'name' => $request->input('name')]);
    //    $data['res'] = $res;
        if ($res) {
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));
        } else {
            $object_name = 'App\\' . $request->get('type');
            $record = new $object_name;
            $record->name = $request->input('name');
            if($request->get('type')!=='main_claster'){
                $record->parent= $request->get('parent');   
            }
            $record->save();
            $entity = new \App\Entity;
            $entity->entity_id=$record->id;
            $entity->type=$request->get('type');
            $entity->save();
            $update = $object_name::find($record->id);
            $update->entity_id = $entity->id;
            $update->save();
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));

        }
    }
    function verifyExist($data)
    {
        $res = DB::table($data['table'])->where('name', $data['name'])->first();
        return ($res);
    }
          

        
            function getdata(Request $request){
        $table=$request->get('table');
        $type=$request->get('type');
        $data=array();
        if($table){
            $child='App\\'.$request->get('table');
            $child_data=$child::where('parent', $request->get('id'))->get(); 
            $data[$table]=$child_data;  
        }
        else{
            $type='subject';    
        }
        $object_path='App\\'.$type;      
        $object_data=$object_path::where('id', $request->get('id'))->get();
        $data['test']=$object_data;
        $additional_data=['feature','media','description'];
            foreach ($additional_data as $addon){
                if($object_data[0]->$addon){
                    $addon_data=DB::table($addon)->where('entity_id', $object_data[0]->entity_id)->get();                
                    $data['parent'][$addon]=$addon_data;    
                }
            }
            
            return response()->json($data);
        }
    
}
