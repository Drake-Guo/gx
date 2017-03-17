<?php
namespace app\index\model;
use think\Model;
use app\index\model\Chuli as ChuliModel;

class Chuli extends Model{
    //设置数据表（不含前缀）
    protected $name = 'chuli';

    /**
     * 根据主键查询一个特殊处理
     * 1.$olor_id 特殊处理主键
     */
    public function getChuliById( $chuli_id ){
        $chuli = ChuliModel::get($chuli_id);
        if( is_object($chuli) ){
            return $chuli->toArray();
        }else{
            return false;
        }
    }

    /**
     * 查询特殊处理是否存在
     * 1.$chuli_name 特殊处理名称
     */
    public function ckeckChuliIsSet( $chuli_name ){
        $chuli = ChuliModel::where(array('status'=>1,'chuli_name'=>$chuli_name))->find();

        if( is_object($chuli) ){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 查询特殊处理列表(分页)
     * 1.where 条件
     * 2.$order 排序方式
     * 3.$now_page 当前页数
     * 4.$page_size 分页大小
     * 5.$limit 限制
     */
    public function getChuliListPage( $where = '', $order = 'chuli_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
        $Chuli_Model = new ChuliModel;
        $chuli_list = $Chuli_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
        $chuli_list = $chuli_list->toArray();
        
        if( count( $chuli_list['data'] ) === 0 ||  empty( $chuli_list['data'] ) === true ){
            return false;
        }
        return $chuli_list;
    }

    /**
     * 添加特殊处理
     * 1.$name 特殊处理名称
     * 2.$price 价格
     */
    public function addChuli( $name = '' , $price = ''){
        if( !$name || !$price ){
            return false;
        }

        $chuli = new ChuliModel;
        $chuli->chuli_name = $name;
        $chuli->chuli_price = $price;
        $chuli->status = 1;
        $chuli->add_time = time();
        if( $result = $chuli->save() ){
            return $result;
        }else{
            return false;
        }
        
    }

    /**
     * 修改特殊处理
     * 1.$chuli_id 特殊处理主键
     * 2.$chuli_name 特殊处理名称
     */
    public function editChuli( $chuli_id = '', $chuli_name = '' ){
        if( !$chuli_id || !$chuli_name ){
            return false;
        }
        
        $chuli = ChuliModel::get($chuli_id);
        if( !is_object($chuli) ){
            return false;
        }
        $chuli->chuli_name = $chuli_name;
        $chuli->update_time = time();
        $result = $chuli->save();

        if( $result === 1 || $result === 0 ){
            return true;
        }else{
            return false;
        }
        
    }

    /**
     * 删除特殊处理
     * 1.$chuli_id 特殊处理主键
     */
    public function delChuli( $chuli_id = '' ){
        if( !$chuli_id ){
            return false;
        }
        $chuli = ChuliModel::get($chuli_id);
        if( is_object($chuli) ){
            return $chuli->toArray();
        }else{
            return false;
        }
        $chuli->status = '0';
        if( $result = $chuli->save() ){
            return $result;
        }else{
            return false;
        }
    }

    public function add_test( $count = 20 ){
        $chuli = new ChuliModel;
        $chulis = array();
        for( $i = 1; $i<=$count; $i++ ){
            $chulis[] = array('chuli_name'=>'特殊处理测试'.$i,'status'=>1,'add_time'=>time());
        }
        
        $result = $chuli->saveAll($chulis);
        v($result);
    }


    
}