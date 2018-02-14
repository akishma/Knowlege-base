<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
//use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;
use \AlexeyMezenin\LaravelRussianSlugs\SlugsTrait;
use App\Main_claster;
use App\Claster;
use App\Objecte;
use App\Feature;
use App\Link;
use App\Media;
use App\Areas;
use App\test;

class Controller extends BaseController
{
    //  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    function index()
    {

        //  $main_claster=DB::table('Main_claster')->where('id', 1)->first();
        $main_clasters = \App\Areas::where('parent', null)->pluck('name', 'id');
        $data['main_claster'] = array();
        $data['main_claster'] = $main_clasters;
        $data['clasters'] = \App\Areas::where('parent', 1)->get();
        return view('main', $data);
    }

    function get_clasters(Request $request)
    {
        $object_name = 'App\Areas';
        $clasters = $object_name::where('parent', $request->get('id'))->pluck('name',
            'id');
        return response()->json($clasters);
    }

    function add_feature($data)
    {
        $record = new \App\Features;
        $record->data = $data->get('feature');

        if ($data->get('new_feature_group')) {


            $res = self::verifyExist(['table' => 'feature_group', 'name' => $data->get('new_feature_group')]);
            if ($res) {

                $record->feature_group = $res->id;
            } else {
                $record->feature_group = DB::table('feature_group')->insertGetId(['name' => $data->
                    get('new_feature_group')]);
            }
            $group = $record->feature_group;
            $res = $record->save();
        } else {
            $group = $data->get('feature_groups');
            $id = \App\Features::where('data', $data->get('feature'))->where('feature_group',
                $data->get('feature_groups'))->pluck('id')->all();
            if (!$id) {
                $record->feature_group = $data->get('feature_groups');

                $res = $record->save();
            }

        }

        $id = \App\Features::where('data', $data->get('feature'))->where('feature_group', $group)->pluck('id')->all();
        if(!$id[0]){
            dd(DB::getQueryLog());
        }

        $features_parents = DB::table('features_parents')->insert(['feature_id' => $id[0], 'parent' => $data->get('parent')]);
        $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['features' => '1']);
       
    }
    function get_groups()
    {
        return Response()->json(DB::table('feature_group')->get());
    }

    function get_areas()
    {
        return Response()->json(DB::table('areas')->where('parent', '>', '1')->get());
    }

    function add_description($data)
    {
        $record = new \App\Description;
        $record->data = $data->get('description');
        $record->parent = $data->get('parent');
        $record->save();
        $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['description' =>
            '1']);
        return redirect('/')->withCookie(cookie('laravel_session', '', -1));
    }
    function add_link($data)
    {
        $record = new \App\Link;
        $record->first_id = $data->get('links');
        $record->second_id = $data->get('parent');
        $record->save();
        $record = new \App\Link;
        $record->second_id = $data->get('links');
        $record->first_id = $data->get('parent');
        $record->save();
        $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['links' =>
            '1']);
        $parent_update = \App\Areas::where('id', $data->get('links'))->update(['links' =>
            '1']);
        return redirect('/')->withCookie(cookie('laravel_session', '', -1));
    }

    function add_media($data)
    {
        $image = $data->file('media');
        $destinationPath = public_path('/images');
        $name = $image->getClientOriginalName();
        $res = DB::table('media')->where('data', $name)->where('parent', $data->get('parent'))->
            first();
        if (!$res) {
            $image->move($destinationPath, $name);
            $record = new \App\Media;
            $record->data = '<img src="images/' . $name . '"/>';
            $record->parent = $data->get('parent');
            $record->save();
            $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['media' =>
                '1']);
        }
        return redirect('/')->withCookie(cookie('laravel_session', '', -1));
        //  return view('debug', $data->file('media'));
    }

    function newdata(Request $request)
    {

        if ($request->get('feature')) {


            self::add_feature($request);
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));

        }
        if ($request->file('media')) {

            self::add_media($request);
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));

        }
        if ($request->get('description')) {

            self::add_description($request);
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));

        }
        if ($request->get('links')) {

            self::add_link($request);
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));

        } else {
            $res = self::verifyExist(['table' => 'areas', 'name' => $request->input('name')]);

            if ($res) {
                return redirect('/')->withCookie(cookie('laravel_session', '', -1));
            }


            $table = 'areas';
            $data = $request->input('name');
            $object_name = 'App\\' . $table;
            $record = new $object_name;
            $record->name = $data;
            if ($request->get('parent')) {
                $record->parent = $request->get('parent');
            }
            $record->save();
            return redirect('/')->withCookie(cookie('laravel_session', '', -1));

        }
    }
    function verifyExist($data)
    {
        $res = DB::table($data['table'])->where('name', $data['name'])->first();
        return ($res);
    }
    function sinc_data(){
        $areas=\App\Areas::all();      
        $finded=array();        
        foreach ($areas as $area){            
          //  $links=\App\Link::where('first_id',$area->id)->get();
            $query=\App\Description::search($area->name)->get();       
          //  foreach ($links as $link){
              //  $query=$query."->where('parent','<>',".$link->second_id.")";

         //   }
          //  $query=$query->get();
           $query=\App\Description::search($area->name)->get();
            if(Count($query)>0){
                foreach($query as $key=>$q){                    
                    $link=\App\Link::where('first_id',$area->id)->where('second_id',$q->parent)->get();
                    if(Count($link)<1){
                        $name=\App\Areas::where('id',$q->parent)->pluck('name');
                        $q->name=$name[0];
                        $q->area=$area->name;                          
                    }
                    else{
                       unset($query[$key]);
                        
                    }
                   
                }
                if(Count($query)>0){                    
                    $finded['links'][$area->id] =$query;  
                }
               
                    
            }
            
            
        }
        
        return ($finded);
    }
    function features_rel(Request $request)
    {
        $data = DB::table('features_parents')->where('feature_id', $request->get('id'))->
            join('features', 'features_parents.feature_id', '=', 'features.id')->join('areas',
            'features_parents.parent', '=', 'areas.id')->get();
        foreach ($data as $attr) {
            $attr->data = $attr->name;
            $parent_name = \App\Areas::where('id', $attr->parent)->get();
            $attr->name = $parent_name[0]->name;
            unset($attr->feature_id);
        }
        return Response()->json($data);
    }

    function getdata(Request $request)
    {
        $data = array();
        $data['children'] = \App\Areas::where('parent', $request->get('id'))->get();
        $object_data = \App\Areas::where('id', $request->get('id'))->get();
        $data['object'] = $object_data;
        $additional_data = ['features', 'media', 'description', 'links'];
        foreach ($additional_data as $addon) {
            if ($object_data[0]->$addon) {
                if ($addon == 'features') {

                    $addon_data = DB::table('features_parents')->where('parent', $request->get('id'))->
                        join('features', 'features_parents.feature_id', '=', 'features.id')->join('feature_group',
                        'features.feature_group', '=', 'feature_group.id')->get();

                } else {
                    if ($addon == 'links') {
                        $addon_data = DB::table($addon)->where('first_id', $request->get('id'))->join('areas',
                            'links.second_id', '=', 'areas.id')->get();
                        foreach ($addon_data as $attr) {
                            $attr->data = $attr->name;
                            $parent_name = \App\Areas::where('id', $attr->parent)->get();
                            $attr->name = $parent_name[0]->name;
                        }

                    } else {
                        $addon_data = DB::table($addon)->where('parent', $request->get('id'))->get();
                        
                    }

                }

                $data['parent'][$addon] = $addon_data;
            }
        }

        return response()->json($data);
    }

}
