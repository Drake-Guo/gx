<?php
namespace app\index\model;
use think\Model;
use app\index\model\Color as ColorModel;

class Color extends Model{
	//设置数据表（不含前缀）
	protected $name = 'color';

	/**
	 * 根据主键查询一个颜色
	 * 1.$olor_id 颜色主键
	 */
	public function getColorById( $color_id ){
		$color = ColorModel::get($color_id);
		if( is_object($color) ){
			return $color->toArray();
		}else{
			return false;
		}
	}

	/**
	 * 查询颜色是否存在
	 * 1.$color_name 颜色名称
	 */
	public function ckeckColorIsSet( $color_name ){
		$color = ColorModel::where(array('status'=>1,'color_name'=>$color_name))->find();

		if( is_object($color) ){
			return true;
		}else{
			return false;
		}
	}


	/**
	 * 查询颜色列表(分页)
	 * 1.where 条件
	 * 2.$order 排序方式
	 * 3.$now_page 当前页数
	 * 4.$page_size 分页大小
	 * 5.$limit 限制
	 */
	public function getColorListPage( $where = '', $order = 'color_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){
		$Color_Model = new ColorModel;
		$color_list = $Color_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
		$color_list = $color_list->toArray();
		
		if( count( $color_list['data'] ) === 0 ||  empty( $color_list['data'] ) === true ){
			return false;
		}
		return $color_list;
	}

	/**
	 * 添加颜色
	 * 1.$name 颜色名称
	 * 2.$rgb 颜色代码
	 */
	public function addColor( $name = '' , $rgb = ''){
		if( !$name ){
			return false;
		}

		$color = new ColorModel;
		$color->color_name = $name;
		if( $rgb !== '' && $rgb ){
			$color->rgb = $rgb;
		}

		$color->status = 1;
		$color->add_time = time();
		if( $result = $color->save() ){
			return $result;
		}else{
			return false;
		}
		
	}

	/**
	 * 修改颜色
	 * 1.$color_id 颜色主键
	 * 2.$color_name 颜色名称
	 */
	public function editColor( $color_id = '', $color_name = '' ){
		if( !$color_id || !$color_name ){
			return false;
		}
		
		$color = ColorModel::get($color_id);
		if( !is_object($color) ){
			return false;
		}
		$color->color_name = $color_name;
		$color->update_time = time();
		$result = $color->save();

		if( $result === 1 || $result === 0 ){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 删除颜色
	 * 1.$color_id 颜色主键
	 */
	public function delColor( $color_id = '' ){
		if( !$color_id ){
			return false;
		}
		$color = ColorModel::get($color_id);
		if( is_object($color) ){
			return $color->toArray();
		}else{
			return false;
		}
		$color->status = '0';
		if( $result = $color->save() ){
			return $result;
		}else{
			return false;
		}
	}

	public function add_test( $count = 20 ){
		$color = new ColorModel;
		$colors = array();
		for( $i = 1; $i<=$count; $i++ ){
			$colors[] = array('color_name'=>'测试'.$i,'status'=>1,'add_time'=>time());
		}
		
		$result = $color->saveAll($colors);
		v($result);
	}


	
}