<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Brand as BrandModel;
class Brand extends Controller
{
   
    /**
     * 品牌管理
     */
    public function index( $order1 = '1', $page1 = '1' ){
        $order1 = intval($order1);$page1 = intval($page1);
        $Brand_Model = new BrandModel;
        if( $order1 === 2 ){
            $Brands = $Brand_Model->getBrandListPage( 'status=1', 'brand_id asc', $page1, 15);
            $order1_url_str = 'order1/2/';
        }else{
            $Brands = $Brand_Model->getBrandListPage( 'status=1', 'brand_id desc', $page1, 15);
            $order1_url_str = '';
        }

        if( is_array( $Brands ) ){
            $total_page = ceil( $Brands['total'] / $Brands['per_page'] );
            if( $page1 > $total_page ){
                $page1 = 1;
            }
            
            $page_html = create_page_html( $page1, $total_page, URL_PATH . 'gx/public/index/brand/index/', $order1_url_str, 'page1');
    
            $this->assign('Brands', $Brands['data']);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('page_html', $page_html);
            $this->assign('controller_name', 'brand');
            return $this->fetch();
        }else{

            $page_html = create_page_html( 1, 1, '', '', '');
            $this->assign('page_html', $page_html);
            $this->assign('page1', $page1);
            $this->assign('order1', $order1);
            $this->assign('controller_name', 'brand');
            return $this->fetch();
        }
    }

    public function add(){
        $this->assign('controller_name', 'brand');
        return $this->fetch();
    }

    public function doadd(){
        $brand_name = $_POST['brand_name'];
        if( !$brand_name ){
        	return $this->error('品牌不能为空！');
        }
        $Brand_Model = new BrandModel;
        $brand_name_is_set = $Brand_Model->ckeckBrandIsSet($brand_name);
        if( $brand_name_is_set ){
            return $this->error('此品牌已存在，请不要重复添加品牌！');
        }
        $result = $Brand_Model->addBrand($brand_name);
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
        $Brand_Model = new BrandModel;
        $brand = $Brand_Model->getBrandById( $id );
        if( !$brand ){
            return $this->error('参数错误！');
        }
        $this->assign('brand', $brand);
        $this->assign('controller_name', 'brand');
        return $this->fetch();
    }

    public function doedit(){
        $id = intval($_POST['brand_id']);
        if( $id <= 0 ){
            return $this->error('参数错误！');
        }
        if( !$_POST['brand_name'] || $_POST['brand_name'] == ''){
            return $this->error('品牌不能为空！');
        }
        $Brand_Model = new BrandModel;
        $brand = $Brand_Model->get( $id );
        if( !$brand ){
            return $this->error('参数错误');
        }
        $brand->brand_name = $_POST['brand_name'];
        $brand->update_time = time();
        if( $brand->save() ){
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
        $Brand_Model = new BrandModel;
        $brand = $Brand_Model->get( $id );
        if( !$brand ){
            return $this->error('参数错误');
        }
        $brand->status = 0;
        $brand->update_time = time();
        if( $brand->save() ){
            return $this->success('删除成功！');
        }else{
            return $this->error('删除失败');
        }
    }

    public function add_test( $count = 50 ){
    	$brand = new BrandModel;
    	$brand->add_test( $count );
    }

}
