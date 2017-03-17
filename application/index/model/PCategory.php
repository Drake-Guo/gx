<?php
namespace app\index\model;
use think\Model;
use app\index\model\PCategory as PCategoryModel;

class PCategory extends Model{
	//设置数据表（不含前缀）
	protected $name = 'p_category';

	/**
	 * 根据主键查询大类
	 * 1.$p_category_id 大类主键
	 */
	public function getPCategoryById( $p_category_id ){
		$p_category = PCategoryModel::get($p_category_id);
		if( is_object($p_category) ){
			return $p_category->toArray();
		}else{
			return false;
		}
	}

	/**
	 * 根据名称查询大类
	 * 1.$p_category_name 名称
	 */
	public function getPCategoryByName( $p_category_name ){
		$p_category = PCategoryModel::getByPCategoryName($p_category_name);
		if( is_object($p_category) ){
			return $p_category->toArray();
		}else{
			return false;
		}
	}

	/**
	 * 查询大类是否存在
	 * 1.$p_category_name 名称
	 */
	public function ckeckPCategoryIsSet( $p_category_name ){
		$p_category = PCategoryModel::where(array('status'=>1,'p_category_name'=>$p_category_name))->find();

		if( is_object($p_category) ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 查询大类列表(分页)
	 * 参数：
	 * 1.where 条件
	 * 2.$order 排序方式
	 * 3.$now_page 当前页数
	 * 4.$page_size 分页大小
	 * 5.$limit 限制
	 */
	public function getPCategoryListPage( $where = '', $order = 'p_category_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
		$PCategory_Model = new PCategoryModel;
		$p_category_list = $PCategory_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
		$p_category_list = $p_category_list->toArray();
		
		if( count( $p_category_list['data'] ) === 0 ||  empty( $p_category_list['data'] ) === true ){
			return false;
		}
		return $p_category_list;
	}

	/**
	 * 添加大类
	 * 参数：
	 * 1.$name 大类名称
	 */
	public function addPCategory( $name = '' ){
		if( !$name ){
			return false;
		}

		$p_category = new PCategoryModel;
		$p_category->p_category_name = $name;
		$p_category->add_time = time();
		if( $result = $p_category->save() ){
			return $result;
		}else{
			return false;
		}
		
	}

	/**
	 * 修改大类
	 * 参数：
	 * 1.$p_category_id 大类主键
	 * 2.$p_category_name 大类名称
	 */
	public function editPCategory( $p_category_id = '', $p_category_name = '' ){
		if( !$p_category_id || !$p_category_name ){
			return false;
		}
		
		$p_category = PCategoryModel::get($p_category_id);
		if( !is_object($p_category) ){
			return false;
		}
		$p_category->p_category_name = $p_category_name;
		$p_category->update_time = time();
		$result = $p_category->save();

		if( $result === 1 || $result === 0 ){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 删除大类
	 * 1.$p_category_id 大类主键
	 */
	public function delPCategory( $p_category_id = '' ){
		if( !$p_category_id ){
			return false;
		}
		$p_category = PCategoryModel::get($p_category_id);
		if( !is_object($p_category) ){
			return false;
		}
		$p_category->status = '0';
		if( $result = $p_category->save() ){
			return $result;
		}else{
			return false;
		}
	}


	
}