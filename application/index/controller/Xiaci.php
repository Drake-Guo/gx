<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Xiaci as XiaciModel;
class Xiaci extends Controller
{
   
    /**
     * 瑕疵管理
     */
    public function index( $order1 = '1', $page1 = '1' ){
        $order1 = intval($order1);$page1 = intval($page1);
        $Xiaci_Model = new XiaciModel;
        if( $order1 === 2 ){
            $Xiacis = $Xiaci_Model->getXiaciListPage( 'status=1', 'xiaci_id asc', $page1, 15);
            $order1_url_str = 'order1/2/';
        }else{
            $Xiacis = $Xiaci_Model->getXiaciListPage( 'status=1', 'xiaci_id desc', $page1, 15);
            $order1_url_str = '';
        }

        if( is_array( $Xiacis ) ){
            $total_page = ceil( $Xiacis['total'] / $Xiacis['per_page'] );
            if( $page1 > $total_page ){
                $page1 = 1;
            }
            
            $page_html = create_page_html( $page1, $total_page, URL_PATH . 'gx/public/index/xiaci/index/', $order1_url_str, 'page1');
    
            $this->assign('Xiacis', $Xiacis['data']);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('page_html', $page_html);
            $this->assign('controller_name', 'xiaci');
            return $this->fetch();
        }else{

            $page_html = create_page_html( 1, 1, '', '', '');
            $this->assign('page_html', $page_html);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('controller_name', 'xiaci');
            return $this->fetch();
        }
    }

    public function add(){
        $this->assign('controller_name', 'xiaci');
        return $this->fetch();
    }

    public function doadd(){
        $xiaci_name = $_POST['xiaci_name'];
        if( !$xiaci_name ){
        	return $this->error('瑕疵不能为空！');
        }
        $Xiaci_Model = new XiaciModel;
        $xiaci_name_is_set = $Xiaci_Model->ckeckXiaciIsSet($xiaci_name);
        if( $xiaci_name_is_set ){
            return $this->error('此瑕疵已存在，请不要重复添加瑕疵！');
        }
        $result = $Xiaci_Model->addXiaci($xiaci_name);
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
        $Xiaci_Model = new XiaciModel;
        $xiaci = $Xiaci_Model->getXiaciById( $id );
        if( !$xiaci ){
            return $this->error('参数错误！');
        }
        $this->assign('xiaci', $xiaci);
        $this->assign('controller_name', 'xiaci');
        return $this->fetch();
    }

    public function doedit(){
        $id = intval($_POST['xiaci_id']);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        if( !$_POST['xiaci_name'] || $_POST['xiaci_name'] == ''){
            return $this->error('瑕疵不能为空！');
        }
        $Xiaci_Model = new XiaciModel;
        $xiaci = $Xiaci_Model->get( $id );
        if( !$xiaci ){
            return $this->error('参数错误');
        }
        $xiaci->xiaci_name = $_POST['xiaci_name'];
        $xiaci->update_time = time();
        if( $xiaci->save() ){
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
        $Xiaci_Model = new XiaciModel;
        $xiaci = $Xiaci_Model->get( $id );
        if( !$xiaci ){
            return $this->error('参数错误');
        }
        $xiaci->status = 0;
        $xiaci->update_time = time();
        if( $xiaci->save() ){
            return $this->success('删除成功！');
        }else{
            return $this->error('删除失败');
        }
    }

    public function add_test( $count = 50 ){
    	$xiaci = new XiaciModel;
    	$xiaci->add_test( $count );
    }

}
