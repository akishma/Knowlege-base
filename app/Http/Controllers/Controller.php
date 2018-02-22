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
    function index(Request $request)
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

        $id = \App\Features::where('data', $data->get('feature'))->where('feature_group',
            $group)->pluck('id')->all();
        if (!$id[0]) {
            dd(DB::getQueryLog());
        }

        $features_parents = DB::table('features_parents')->insert(['feature_id' => $id[0],
            'parent' => $data->get('parent')]);
        $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['features' =>
            '1']);
            return redirect('/');

    }
    function add_feature_parent($data){
                $features_parents = DB::table('features_parents')->insert(['feature_id' => $data->get('feature_parent'),
            'parent' => $data->get('parent')]);
        $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['features' =>
            '1']);
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
    function add_link($name, $data)
    {
        $object_name = '\App\\' . $name;
        $record = new $object_name;
        $record->first_id = $data->get($name);
        $record->second_id = $data->get('parent');
        $record->save();
        $object_name = '\App\\' . $name;
        $record = new $object_name;
        $record->second_id = $data->get($name);
        $record->first_id = $data->get('parent');
        $record->save();
        if ($name == 'link') {
            $parent_update = \App\Areas::where('id', $data->get('parent'))->update(['links' =>
                '1']);
            $parent_update = \App\Areas::where('id', $data->get('link'))->update(['links' =>
                '1']);
        }


        return redirect('/')->withCookie(cookie('laravel_session', '', -1));

    }
    function add_feature_ignore($name,$data){
        $record= new \App\Feature_ignore;
        $record->feature_id=$data->get($name);
        $record->area_id=$data->get('parent');
        $record->save();
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
        if ($request->get('feature_parent')) {
            self::add_feature_parent($request);
            return ($request->get('parent'));

        }
        if ($request->get('feature')) {
            self::add_feature($request);
            return ($request->get('parent'));

        }
        if ($request->file('media')) {

            self::add_media($request);
            return ($request->get('parent'));

        }
        if ($request->get('description')) {

            self::add_description($request);
            return ($request->get('parent'));

        }
        if ($request->get('link')) {

            self::add_link('link', $request);
            return ($request->get('parent'));

        }
        if ($request->get('link_ignore')) {

            self::add_link('link_ignore', $request);
            return ($request->get('parent'));

        }
        if ($request->get('feature_parent_ignore')) {

            self::add_feature_ignore('feature_parent_ignore', $request);
            return ($request->get('parent'));

        }
         if ($request->get('name')) {
            $res = self::verifyExist(['table' => 'areas', 'name' => $request->input('name')]);

            if ($res) {               
               return($request->get('parent'));
            }

            $record = new \App\Areas;
            $record->name = $request->get('name');
            if ($request->get('parent')) {
                $record->parent = $request->get('parent');
            }
            $record->save();
            return ($request->get('parent'));

        }
    }
    function verifyExist($data)
    {
        $res = DB::table($data['table'])->where('name', $data['name'])->first();
        return ($res);
    }
    function sinc_data()
    {
        $areas = \App\Areas::all();
        $features = \App\Features::all();
        $finded = array();
        foreach ($areas as $area) {
            $query = \App\Description::search($area->name)->get();
            if (Count($query) > 0) {
                foreach ($query as $key => $q) {
                    if ($area->id == $q->parent) {
                        unset($query[$key]);
                    }
                    $link = \App\Link::where('first_id', $area->id)->where('second_id', $q->parent)->
                        get();
                    $ignore = \App\Link_ignore::where('first_id', $area->id)->where('second_id', $q->
                        parent)->get();
                    if (Count($link) < 1 and Count($ignore) < 1) {
                        $name = \App\Areas::where('id', $q->parent)->pluck('name');
                        $q->name = $name[0];
                        $q->area = $area->name;
                        $q->id = $area->id;
                    } else {
                        unset($query[$key]);

                    }

                }
                if (Count($query) > 0) {
                    $finded['link'][$area->id] = $query;
                  //  if (count($finded, COUNT_RECURSIVE) > 6) {
                        //        return ($finded);
                  //  }
                }


            }


        }
        foreach($features as $feature){
            $query = \App\Description::search($feature->data)->get();
            if (Count($query) > 0) {
                foreach ($query as $key=>$q){
                    $parents=DB::table('features_parents')->where('feature_id',$feature->id)->where('parent',$q->parent)->get();
                    if(Count($parents)>0){
                        unset($query[$key]);
                    }
                    else{
                        $group=DB::table('feature_group')->where('id',$feature->feature_group)->pluck('name');
                        $name = \App\Areas::where('id', $q->parent)->pluck('name');
                        $q->name = $name[0];
                        $q->area = $feature->data;
                        $q->id = $feature->id;
                        $q->group=$group[0];
                    }
                    if (Count($query) > 0) {
                        $finded['features'][$feature->id] = $query;

                }
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
