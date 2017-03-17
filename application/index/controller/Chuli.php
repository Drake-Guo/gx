<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Chuli as ChuliModel;
class Chuli extends Controller
{
   
    /**
     * 处理管理
     */
    public function index( $order1 = '1', $page1 = '1' ){
        $order1 = intval($order1);$page1 = intval($page1);
        $Chuli_Model = new ChuliModel;
        if( $order1 === 2 ){
            $Chulis = $Chuli_Model->getChuliListPage( 'status=1', 'chuli_id asc', $page1, 15);
            $order1_url_str = 'order1/2/';
        }else{
            $Chulis = $Chuli_Model->getChuliListPage( 'status=1', 'chuli_id desc', $page1, 15);
            $order1_url_str = '';
        }

        if( is_array( $Chulis ) ){
            $total_page = ceil( $Chulis['total'] / $Chulis['per_page'] );
            if( $page1 > $total_page ){
                $page1 = 1;
            }
            
            $page_html = create_page_html( $page1, $total_page, URL_PATH . 'gx/public/index/chuli/index/', $order1_url_str, 'page1');
    
            $this->assign('Chulis', $Chulis['data']);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('page_html', $page_html);
            $this->assign('controller_name', 'chuli');
            return $this->fetch();
        }else{

            $page_html = create_page_html( 1, 1, '', '', '');
            $this->assign('page_html', $page_html);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('controller_name', 'chuli');
            return $this->fetch();
        }
    }

    public function add(){
        $this->assign('controller_name', 'chuli');
        return $this->fetch();
    }

    public function doadd(){
        $chuli_name = $_POST['chuli_name'];
        $chuli_price = $_POST['chuli_price'];
        if( !$chuli_name ){
        	return $this->error('特殊处理不能为空！');
        }
        if( !$chuli_price ){
            return $this->error('价格不能为空！');
        }
        $Chuli_Model = new ChuliModel;
        $chuli_name_is_set = $Chuli_Model->ckeckChuliIsSet($chuli_name);
        if( $chuli_name_is_set ){
            return $this->error('此特殊处理已存在，请不要重复添加！');
        }
        $result = $Chuli_Model->addChuli( $chuli_name, $chuli_price );
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
        $Chuli_Model = new ChuliModel;
        $chuli = $Chuli_Model->getChuliById( $id );
        if( !$chuli ){
            return $this->error('参数错误！');
        }
        $this->assign('chuli', $chuli);
        $this->assign('controller_name', 'chuli');
        return $this->fetch();
    }

    public function doedit(){
        $id = intval($_POST['chuli_id']);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        if( !$_POST['chuli_name'] || $_POST['chuli_name'] == ''){
            return $this->error('特殊处理不能为空！');
        }
        if( !$_POST['chuli_price'] || $_POST['chuli_price'] == '' || $_POST['chuli_price'] <= 0){
            return $this->error('价格不能为空或负数！');
        }
        $Chuli_Model = new ChuliModel;
        $chuli = $Chuli_Model->get( $id );
        if( !$chuli ){
            return $this->error('参数错误');
        }
        $chuli->chuli_name = $_POST['chuli_name'];
        $chuli->chuli_price = $_POST['chuli_price'];
        $chuli->update_time = time();
        if( $chuli->save() ){
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
        $Chuli_Model = new ChuliModel;
        $chuli = $Chuli_Model->get( $id );
        if( !$chuli ){
            return $this->error('参数错误');
        }
        $chuli->status = 0;
        $chuli->update_time = time();
        if( $chuli->save() ){
            return $this->success('删除成功！');
        }else{
            return $this->error('删除失败');
        }
    }

    public function add_test( $count = 50 ){
    	$chuli = new ChuliModel;
    	$chuli->add_test( $count );
    }

}
