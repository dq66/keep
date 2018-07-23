<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TypesRequest;
use App\Model\Types;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\Type;

class TypesController extends Controller
{
    public function index()
    {
        return view("types.index");
    }
    public function sel(Request $request){
        $rows= $request->get('limit');
        $page = $request->get('pageNumber');
        $px = $request->get('order');
        $name = $request->get('sort');
        $search = $request->get('search');
        $count= Types::where('parent','=',0)->count();
        if(!isset($rows) and !isset($page)){
            $data = Types::where('parent','=',0)->get();
        }else if(isset($search)){//判断是否是搜索查询
            $a = Types::where('parent','=',0)->where('name','like','%'.$search.'%')->paginate($rows,['*'],1,$page)->toArray();
            $data=$a['data'];
            $count= Types::where('parent','=',0)->where('name','like','%'.$search.'%')->count();
        }else if(isset($name)){//判断是否要排序
            $b =  Types::where('parent','=',0)->orderBy($name,$px)->paginate($rows,['*'],1,$page)->toArray();
            $data = $b['data'];
        }else{
            $c = Types::where('parent','=',0)->paginate($rows,['*'],1,$page)->toArray();
            $data = $c['data'];
        }
        //dump($data['data']);

        return response()->json(['data'=>$data,'total'=>$count]);
    }

    //ajax查询所有大类
    public function ajaxtypes($type){
        $types = Types::where(['is_types' => $type,'parent' => 0])->get();
        return response()->json(['success' => true,'data' => $types]);
    }
    //ajax查询所有小类
    public function xt($ty){
        $xtypes = Types::where('is_types','=',$ty)
            ->where('parent','>',0)->get();

        return response()->json(['success' => true,'data' => $xtypes]);
    }
    //大类下的小类
    public function types_xl($parent){
        $xls = Types::where('parent','=',$parent)->get();
        return response()->json(['success' => true, 'data' => $xls]);
    }

    public function create(TypesRequest $request)
    {
        $ty=Types::create($request->post());

        return Prompt($ty,'添加数据','Admin/Types');

    }
    //修改类别
    public function edit(Request $request){
        if($request->isMethod('post')){
            //dd($request->all());
            $input = $request->except(['_token']);
            $types = Types::where('id','=',$request->id)->update($input);
            // 修复树关联
            Types::find($request->id)->perfectTree();
            // 清理冗余的关联信息
            Types::deleteRedundancies();

            return Prompt($types,'编辑数据','Admin/Types');

        }else{
            $types = Types::find($request->id);
            return response()->json(['success'=>true,'data'=>$types]);
        }
    }
    //删除类别
    public function del($id)
    {
        $de = Types::where("parent", "=", $id)->count();
        if($de > 0){
            return response()->json(['success' => false,'data' => 'err']);
        }else{

            $types = Types::where('id','=',$id)->delete();
            if($types){
                // 清理冗余的关联信息
                Metas::deleteRedundancies();
                return response()->json(['success'=>true]);
            }else{
                return response()->json(['success'=>false]);
            }
        }
    }
}
