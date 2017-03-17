<?php
namespace app\index\model;
use think\Model;
use app\index\model\CCategory as CCategoryModel;
use app\index\model\PCategory as PCategoryModel;

class CCategory extends Model{
	//设置数据表（不含前缀）
	protected $name = 'c_category';

	/**
	 * 获取所有大类
	 */
	public function getAllPCategory(  ){
		$p_categorys2 = PCategoryModel::where('status=1')->select();
		$p_categorys = array();
		foreach($p_categorys2 as $p_category){
			$p_category = $p_category->toArray();
			$p_categorys[] = $p_category;
		}
		return $p_categorys;
	}

	/**
	 * 根据主键查询一个小类
	 * 1.$c_category_id 小类主键
	 */
	public function getCCategoryById( $c_category_id ){
		$c_category = CCategoryModel::get($c_category_id);
		if( is_object($c_category) ){
			return $c_category->toArray();
		}else{
			return false;
		}
	}

	/**
	 * 查询小类是否存在
	 * 1.$c_category_name 名称
	 */
	public function ckeckCCategoryIsSet( $c_category_name ){
		$p_category = CCategoryModel::where(array('status'=>1,'c_category_name'=>$c_category_name))->find();

		if( is_object($p_category) ){
			return true;
		}else{
			return false;
		}
	}


	/**
	 * 查询小类列表(分页)
	 * 1.where 条件
	 * 2.$order 排序方式
	 * 3.$now_page 当前页数
	 * 4.$page_size 分页大小
	 * 5.$limit 限制
	 */
	public function getCCategoryListPage( $where = '', $order = 'c_category_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
		$CCategory_Model = new CCategoryModel;
		$c_category_list = $CCategory_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
		$c_category_list = $c_category_list->toArray();
		
		if( count( $c_category_list['data'] ) === 0 ||  empty( $c_category_list['data'] ) === true ){
			return false;
		}
		return $c_category_list;
	}

	/**
	 * 添加小类
	 * 1.p_category_id 关联[大类]id
	 * 2.$name 小类名称
	 * 3.$c_category_price 小类价格
	 */
	public function addCCategory( $p_category_id = '', $name = '' , $c_category_price = ''){
		if( !$p_category_id || !$name || !$c_category_price ){
			return false;
		}

		$c_category = new CCategoryModel;
		$c_category->p_category_id = $p_category_id;
		$c_category->c_category_name = $name;
		$c_category->c_category_price = $c_category_price;
		$c_category->add_time = time();
		if( $result = $c_category->save() ){
			return $result;
		}else{
			return false;
		}
		
	}

	/**
	 * 修改小类
	 * 1.$c_category_id 小类主键
	 * 2.p_category_id 关联[大类]id
	 * 3.$c_category_name 小类名称
	 * 4.$c_category_price 小类价格
	 */
	public function editCCategory( $c_category_id = '', $p_category_id = '', $c_category_name = '' , $c_category_price = ''){
		if( !$c_category_id || !$p_category_id || !$c_category_name || !$c_category_price ){
			return false;
		}
		
		$c_category = CCategoryModel::get($c_category_id);
		if( !is_object($c_category) ){
			return false;
		}
		$c_category->p_category_id = $p_category_id;
		$c_category->c_category_name = $c_category_name;
		$c_category->c_category_price = $c_category_price;
		$c_category->update_time = time();
		$result = $c_category->save();

		if( $result === 1 || $result === 0 ){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 删除小类
	 * 1.$c_category_id 小类主键
	 */
	public function delCCategory( $c_category_id = '' ){
		if( !$c_category_id ){
			return false;
		}
		$c_category = CCategoryModel::get($c_category_id);
		if( is_object($c_category) ){
			return $c_category->toArray();
		}else{
			return false;
		}
		$c_category->status = '0';
		if( $result = $c_category->save() ){
			return $result;
		}else{
			return false;
		}
	}


	
}