<?php
namespace app\index\model;
use think\Model;
use app\index\model\Brand as BrandModel;

class Brand extends Model{
	//设置数据表（不含前缀）
	protected $name = 'brand';

	/**
	 * 根据主键查询一个品牌
	 * 1.$olor_id 品牌主键
	 */
	public function getBrandById( $brand_id ){
		$brand = BrandModel::get($brand_id);
		if( is_object($brand) ){
			return $brand->toArray();
		}else{
			return false;
		}
	}

	/**
	 * 查询品牌是否存在
	 * 1.$brand_name 品牌名称
	 */
	public function ckeckBrandIsSet( $brand_name ){
		$brand = BrandModel::where(array('status'=>1,'brand_name'=>$brand_name))->find();

		if( is_object($brand) ){
			return true;
		}else{
			return false;
		}
	}


	/**
	 * 查询品牌列表(分页)
	 * 1.where 条件
	 * 2.$order 排序方式
	 * 3.$now_page 当前页数
	 * 4.$page_size 分页大小
	 * 5.$limit 限制
	 */
	public function getBrandListPage( $where = '', $order = 'brand_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
		$Brand_Model = new BrandModel;
		$brand_list = $Brand_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
		$brand_list = $brand_list->toArray();
		
		if( count( $brand_list['data'] ) === 0 ||  empty( $brand_list['data'] ) === true ){
			return false;
		}
		return $brand_list;
	}

	/**
	 * 添加品牌
	 * 1.$name 品牌名称
	 * 2.$rgb 品牌代码
	 */
	public function addBrand( $name = '' , $rgb = ''){
		if( !$name ){
			return false;
		}

		$brand = new BrandModel;
		$brand->brand_name = $name;
		if( $rgb !== '' && $rgb ){
			$brand->rgb = $rgb;
		}

		$brand->status = 1;
		$brand->add_time = time();
		if( $result = $brand->save() ){
			return $result;
		}else{
			return false;
		}
		
	}

	/**
	 * 修改品牌
	 * 1.$brand_id 品牌主键
	 * 2.$brand_name 品牌名称
	 */
	public function editBrand( $brand_id = '', $brand_name = '' ){
		if( !$brand_id || !$brand_name ){
			return false;
		}
		
		$brand = BrandModel::get($brand_id);
		if( !is_object($brand) ){
			return false;
		}
		$brand->brand_name = $brand_name;
		$brand->update_time = time();
		$result = $brand->save();

		if( $result === 1 || $result === 0 ){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 删除品牌
	 * 1.$brand_id 品牌主键
	 */
	public function delBrand( $brand_id = '' ){
		if( !$brand_id ){
			return false;
		}
		$brand = BrandModel::get($brand_id);
		if( is_object($brand) ){
			return $brand->toArray();
		}else{
			return false;
		}
		$brand->status = '0';
		if( $result = $brand->save() ){
			return $result;
		}else{
			return false;
		}
	}

	public function add_test( $count = 20 ){
		$brand = new BrandModel;
		$brands = array();
		for( $i = 1; $i<=$count; $i++ ){
			$brands[] = array('brand_name'=>'品牌测试'.$i,'status'=>1,'add_time'=>time());
		}
		
		$result = $brand->saveAll($brands);
		v($result);
	}


	
}