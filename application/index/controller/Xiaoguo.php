<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Xiaoguo as XiaoguoModel;
class Xiaoguo extends Controller
{
   
    /**
     * 洗后效果管理
     */
    public function index( $order1 = '1', $page1 = '1' ){
        $order1 = intval($order1);$page1 = intval($page1);
        $Xiaoguo_Model = new XiaoguoModel;
        if( $order1 === 2 ){
            $Xiaoguos = $Xiaoguo_Model->getXiaoguoListPage( 'status=1', 'xiaoguo_id asc', $page1, 15);
            $order1_url_str = 'order1/2/';
        }else{
            $Xiaoguos = $Xiaoguo_Model->getXiaoguoListPage( 'status=1', 'xiaoguo_id desc', $page1, 15);
            $order1_url_str = '';
        }

        if( is_array( $Xiaoguos ) ){
            $total_page = ceil( $Xiaoguos['total'] / $Xiaoguos['per_page'] );
            if( $page1 > $total_page ){
                $page1 = 1;
            }
            
            $page_html = create_page_html( $page1, $total_page, URL_PATH . 'gx/public/index/xiaoguo/index/', $order1_url_str, 'page1');
    
            $this->assign('Xiaoguos', $Xiaoguos['data']);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('page_html', $page_html);
            $this->assign('controller_name', 'xiaoguo');
            return $this->fetch();
        }else{

            $page_html = create_page_html( 1, 1, '', '', '');
            $this->assign('page_html', $page_html);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('controller_name', 'xiaoguo');
            return $this->fetch();
        }
    }

    public function add(){
        $this->assign('controller_name', 'xiaoguo');
        return $this->fetch();
    }

    public function doadd(){
        $xiaoguo_name = $_POST['xiaoguo_name'];
        if( !$xiaoguo_name ){
        	return $this->error('洗后效果不能为空！');
        }
        $Xiaoguo_Model = new XiaoguoModel;
        $xiaoguo_name_is_set = $Xiaoguo_Model->ckeckXiaoguoIsSet($xiaoguo_name);
        if( $xiaoguo_name_is_set ){
            return $this->error('此洗后效果已存在，请不要重复添加洗后效果！');
        }
        $result = $Xiaoguo_Model->addXiaoguo($xiaoguo_name);
        if( $result ){
            return $this->success('添加成功！');
        }else{
            return $this->error('添加失败！');
        }
    }

    public function edit( $id = 0 ){
        $id = intval($id);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        $Xiaoguo_Model = new XiaoguoModel;
        $xiaoguo = $Xiaoguo_Model->getXiaoguoById( $id );
        if( !$xiaoguo ){
            return $this->error('参数错误！');
        }
        $this->assign('xiaoguo', $xiaoguo);
        $this->assign('controller_name', 'xiaoguo');
        return $this->fetch();
    }

    public function doedit(){
        $id = intval($_POST['xiaoguo_id']);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        if( !$_POST['xiaoguo_name'] || $_POST['xiaoguo_name'] == ''){
            return $this->error('洗后效果不能为空！');
        }
        $Xiaoguo_Model = new XiaoguoModel;
        $xiaoguo = $Xiaoguo_Model->get( $id );
        if( !$xiaoguo ){
            return $this->error('参数错误');
        }
        $xiaoguo->xiaoguo_name = $_POST['xiaoguo_name'];
        $xiaoguo->update_time = time();
        if( $xiaoguo->save() ){
            return $this->success('修改成功！','index');
        }else{
            return $this->error('修改失败');
        }
    }

    public function del( $id ){
        $id = intval($id);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        $Xiaoguo_Model = new XiaoguoModel;
        $xiaoguo = $Xiaoguo_Model->get( $id );
        if( !$xiaoguo ){
            return $this->error('参数错误');
        }
        $xiaoguo->status = 0;
        $xiaoguo->update_time = time();
        if( $xiaoguo->save() ){
            return $this->success('删除成功！');
        }else{
            return $this->error('删除失败');
        }
    }

    public function add_test( $count = 50 ){
    	$xiaoguo = new XiaoguoModel;
    	$xiaoguo->add_test( $count );
    }

}
