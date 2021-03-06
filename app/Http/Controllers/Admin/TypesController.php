<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TypesRequest;
use App\Model\Types;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

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
        if($de > 0){//判断是否有下级
            return response()->json(['success' => false,'data' => 'err']);
        }else{

            $types = Types::where('id','=',$id)->delete();
            if($types){
                // 清理冗余的关联信息
                Types::deleteRedundancies();
                return response()->json(['success'=>true,'msg'=>'删除成功']);
            }else{
                return response()->json(['success'=>false,'msg'=>'删除失败！']);
            }
        }
    }
    //批量删除
    public function delall(Request $request){
        //dump($request->all());
        $a = explode(',',$request->get('ids'));
        if(count($a) > 1){
            $da  = array();
            foreach ($a as $i){
                $xj = Types::where('parent','=',$i)->count();
                if($xj>0){
                    $da[] = $i;
                }
            }
            if(count($da)){
                return response()->json(['success' => false,'msg' => '请先删除子分类！']);
            }else{
               // $types = Types::where('id','in',$request->get('ids'))->delete();
                $types = \DB::delete('delete from types where id in ('.$request->get('ids').')');
                //dump($types);
                if($types){
                    // 清理冗余的关联信息
                    Types::deleteRedundancies();
                    return response()->json(['success'=>true,'msg'=>'批量删除成功']);
                }else{
                    return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
                }
            }
        }else{
            $xj = Types::where('parent','=',$a[0])->count();
            if($xj>0){
                return response()->json(['success' => false,'msg' => '请先删除子分类！']);
            }else{
                $types = Types::where('id','=',$a[0])->delete();
                if($types){
                    // 清理冗余的关联信息
                    Types::deleteRedundancies();
                    return response()->json(['success'=>true,'msg'=>'批量删除成功']);
                }else{
                    return response()->json(['success'=>false,'msg'=>'批量删除失败！']);
                }
            }
        }
    }

    //导入
    public function import(Request $request){
        //dump($request->all());
        if($request->file('file')){

            $file = $_FILES;
            $excel_file_path = $file['file']['tmp_name'];
            $types = '';

            Excel::load($excel_file_path, function($reader) use( &$types ){
                $reader = $reader->getSheet(0);
                $res = $reader->toArray();
                foreach ($res as $key => $value){
                    $type = $value[0] == "收入" ? 1:2;
                    $data = array(
                        'is_types' => $type,
                        'name' => $value[1],
                        'desc' => $value[2]
                    );
                    if($key > 0){
                        $types = Types::create($data);
                    }
                }
            });
            return Prompt($types,'导入数据','Admin/Types');
        }
    }
}
