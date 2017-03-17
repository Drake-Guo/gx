<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Color as ColorModel;
class Color extends Controller
{
   
    /**
     * 颜色管理
     */
    public function index( $order1 = '1', $page1 = '1' ){
        $order1 = intval($order1);$page1 = intval($page1);
        $Color_Model = new ColorModel;
        if( $order1 === 2 ){
            $Colors = $Color_Model->getColorListPage( 'status=1', 'color_id asc', $page1, 15);
            $order1_url_str = 'order1/2/';
        }else{
            $Colors = $Color_Model->getColorListPage( 'status=1', 'color_id desc', $page1, 15);
            $order1_url_str = '';
        }

        if( is_array( $Colors ) ){
            $total_page = ceil( $Colors['total'] / $Colors['per_page'] );
            if( $page1 > $total_page ){
                $page1 = 1;
            }
            
            $page_html = create_page_html( $page1, $total_page, URL_PATH . 'gx/public/index/color/index/', $order1_url_str, 'page1');
    
            $this->assign('Colors', $Colors['data']);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('page_html', $page_html);
            $this->assign('controller_name', 'color');
            return $this->fetch();
        }else{

            $page_html = create_page_html( 1, 1, '', '', '');
            $this->assign('page_html', $page_html);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('controller_name', 'color');
            return $this->fetch();
        }
    }

    public function add(){
        $this->assign('controller_name', 'color');
        return $this->fetch();
    }

    public function doadd(){
        $color_name = $_POST['color_name'];
        if( !$color_name ){
        	return $this->error('颜色不能为空！');
        }
        $Color_Model = new ColorModel;
        $color_name_is_set = $Color_Model->ckeckColorIsSet($color_name);
        if( $color_name_is_set ){
            return $this->error('此颜色已存在，请不要重复添加颜色！');
        }
        $result = $Color_Model->addColor($color_name);
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
        $Color_Model = new ColorModel;
        $color = $Color_Model->getColorById( $id );
        if( !$color ){
            return $this->error('参数错误！');
        }
        $this->assign('color', $color);
        $this->assign('controller_name', 'color');
        return $this->fetch();
    }

    public function doedit(){
        $id = intval($_POST['color_id']);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        if( !$_POST['color_name'] || $_POST['color_name'] == ''){
            return $this->error('颜色不能为空！');
        }
        $Color_Model = new ColorModel;
        $color = $Color_Model->get( $id );
        if( !$color ){
            return $this->error('参数错误');
        }
        $color->color_name = $_POST['color_name'];
        $color->update_time = time();
        if( $color->save() ){
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
        $Color_Model = new ColorModel;
        $color = $Color_Model->get( $id );
        if( !$color ){
            return $this->error('参数错误');
        }
        $color->status = 0;
        $color->update_time = time();
        if( $color->save() ){
            return $this->success('删除成功！');
        }else{
            return $this->error('删除失败');
        }
    }

    public function add_test( $count = 50 ){
    	$color = new ColorModel;
    	$color->add_test( $count );
    }

}
