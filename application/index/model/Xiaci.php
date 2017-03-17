<?php
namespace app\index\model;
use think\Model;
use app\index\model\Xiaci as XiaciModel;

class Xiaci extends Model{
    //设置数据表（不含前缀）
    protected $name = 'xiaci';

    /**
     * 根据主键查询一个瑕疵
     * 1.$olor_id 瑕疵主键
     */
    public function getXiaciById( $xiaci_id ){
        $xiaci = XiaciModel::get($xiaci_id);
        if( is_object($xiaci) ){
            return $xiaci->toArray();
        }else{
            return false;
        }
    }

    /**
     * 查询瑕疵是否存在
     * 1.$xiaci_name 瑕疵名称
     */
    public function ckeckXiaciIsSet( $xiaci_name ){
        $xiaci = XiaciModel::where(array('status'=>1,'xiaci_name'=>$xiaci_name))->find();

        if( is_object($xiaci) ){
            return true;
        }else{
            return false;
        }
    }


    /**
     * 查询瑕疵列表(分页)
     * 1.where 条件
     * 2.$order 排序方式
     * 3.$now_page 当前页数
     * 4.$page_size 分页大小
     * 5.$limit 限制
     */
    public function getXiaciListPage( $where = '', $order = 'xiaci_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
        $Xiaci_Model = new XiaciModel;
        $xiaci_list = $Xiaci_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
        $xiaci_list = $xiaci_list->toArray();
        
        if( count( $xiaci_list['data'] ) === 0 ||  empty( $xiaci_list['data'] ) === true ){
            return false;
        }
        return $xiaci_list;
    }

    /**
     * 添加瑕疵
     * 1.$name 瑕疵名称
     * 2.$rgb 瑕疵代码
     */
    public function addXiaci( $name = '' , $rgb = ''){
        if( !$name ){
            return false;
        }

        $xiaci = new XiaciModel;
        $xiaci->xiaci_name = $name;
        if( $rgb !== '' && $rgb ){
            $xiaci->rgb = $rgb;
        }

        $xiaci->status = 1;
        $xiaci->add_time = time();
        if( $result = $xiaci->save() ){
            return $result;
        }else{
            return false;
        }
        
    }

    /**
     * 修改瑕疵
     * 1.$xiaci_id 瑕疵主键
     * 2.$xiaci_name 瑕疵名称
     */
    public function editXiaci( $xiaci_id = '', $xiaci_name = '' ){
        if( !$xiaci_id || !$xiaci_name ){
            return false;
        }
        
        $xiaci = XiaciModel::get($xiaci_id);
        if( !is_object($xiaci) ){
            return false;
        }
        $xiaci->xiaci_name = $xiaci_name;
        $xiaci->update_time = time();
        $result = $xiaci->save();

        if( $result === 1 || $result === 0 ){
            return true;
        }else{
            return false;
        }
        
    }

    /**
     * 删除瑕疵
     * 1.$xiaci_id 瑕疵主键
     */
    public function delXiaci( $xiaci_id = '' ){
        if( !$xiaci_id ){
            return false;
        }
        $xiaci = XiaciModel::get($xiaci_id);
        if( is_object($xiaci) ){
            return $xiaci->toArray();
        }else{
            return false;
        }
        $xiaci->status = '0';
        if( $result = $xiaci->save() ){
            return $result;
        }else{
            return false;
        }
    }

    public function add_test( $count = 20 ){
        $xiaci = new XiaciModel;
        $xiacis = array();
        for( $i = 1; $i<=$count; $i++ ){
            $xiacis[] = array('xiaci_name'=>'瑕疵测试'.$i,'status'=>1,'add_time'=>time());
        }
        
        $result = $xiaci->saveAll($xiacis);
        v($result);
    }


    
}