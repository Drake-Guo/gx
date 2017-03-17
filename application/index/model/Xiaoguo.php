<?php
namespace app\index\model;
use think\Model;
use app\index\model\Xiaoguo as XiaoguoModel;

class Xiaoguo extends Model{
    //设置数据表（不含前缀）
    protected $name = 'xiaoguo';

    /**
     * 根据主键查询一个洗后效果
     * 1.$olor_id 洗后效果主键
     */
    public function getXiaoguoById( $xiaoguo_id ){
        $xiaoguo = XiaoguoModel::get($xiaoguo_id);
        if( is_object($xiaoguo) ){
            return $xiaoguo->toArray();
        }else{
            return false;
        }
    }

    /**
     * 查询洗后效果是否存在
     * 1.$xiaoguo_name 洗后效果名称
     */
    public function ckeckXiaoguoIsSet( $xiaoguo_name ){
        $xiaoguo = XiaoguoModel::where(array('status'=>1,'xiaoguo_name'=>$xiaoguo_name))->find();

        if( is_object($xiaoguo) ){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 查询洗后效果列表(分页)
     * 1.where 条件
     * 2.$order 排序方式
     * 3.$now_page 当前页数
     * 4.$page_size 分页大小
     * 5.$limit 限制
     */
    public function getXiaoguoListPage( $where = '', $order = 'xiaoguo_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
        $Xiaoguo_Model = new XiaoguoModel;
        $xiaoguo_list = $Xiaoguo_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
        $xiaoguo_list = $xiaoguo_list->toArray();
        
        if( count( $xiaoguo_list['data'] ) === 0 ||  empty( $xiaoguo_list['data'] ) === true ){
            return false;
        }
        return $xiaoguo_list;
    }

    /**
     * 添加洗后效果
     * 1.$name 洗后效果名称
     * 2.$rgb 洗后效果代码
     */
    public function addXiaoguo( $name = '' , $rgb = ''){
        if( !$name ){
            return false;
        }

        $xiaoguo = new XiaoguoModel;
        $xiaoguo->xiaoguo_name = $name;
        if( $rgb !== '' && $rgb ){
            $xiaoguo->rgb = $rgb;
        }

        $xiaoguo->status = 1;
        $xiaoguo->add_time = time();
        if( $result = $xiaoguo->save() ){
            return $result;
        }else{
            return false;
        }
        
    }

    /**
     * 修改洗后效果
     * 1.$xiaoguo_id 洗后效果主键
     * 2.$xiaoguo_name 洗后效果名称
     */
    public function editXiaoguo( $xiaoguo_id = '', $xiaoguo_name = '' ){
        if( !$xiaoguo_id || !$xiaoguo_name ){
            return false;
        }
        
        $xiaoguo = XiaoguoModel::get($xiaoguo_id);
        if( !is_object($xiaoguo) ){
            return false;
        }
        $xiaoguo->xiaoguo_name = $xiaoguo_name;
        $xiaoguo->update_time = time();
        $result = $xiaoguo->save();

        if( $result === 1 || $result === 0 ){
            return true;
        }else{
            return false;
        }
        
    }

    /**
     * 删除洗后效果
     * 1.$xiaoguo_id 洗后效果主键
     */
    public function delXiaoguo( $xiaoguo_id = '' ){
        if( !$xiaoguo_id ){
            return false;
        }
        $xiaoguo = XiaoguoModel::get($xiaoguo_id);
        if( is_object($xiaoguo) ){
            return $xiaoguo->toArray();
        }else{
            return false;
        }
        $xiaoguo->status = '0';
        if( $result = $xiaoguo->save() ){
            return $result;
        }else{
            return false;
        }
    }

    public function add_test( $count = 20 ){
        $xiaoguo = new XiaoguoModel;
        $xiaoguos = array();
        for( $i = 1; $i<=$count; $i++ ){
            $xiaoguos[] = array('xiaoguo_name'=>'洗后效果测试'.$i,'status'=>1,'add_time'=>time());
        }
        
        $result = $xiaoguo->saveAll($xiaoguos);
        v($result);
    }


    
}