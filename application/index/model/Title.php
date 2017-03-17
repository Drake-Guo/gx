<?php
namespace app\index\model;
use think\Model;
use app\index\model\Title as TitleModel;

class Title extends Model{
	//设置数据表（不含前缀）
	protected $name = 'clothes_title';

	/**
	 * 查询标题列表
	 * 1.where 条件
	 */
	public function getTitleList( $szm = 'A'){
		$Title_Model = new TitleModel;
		$title_list = $Title_Model->where(array('shouzimu'=>$szm,'status'=>1))->order('add_time desc')->select();
		$title_list = oToa($title_list);

		return $title_list;
	}

	/**
	 * 添加标题名称
	 * 1.$title 标题名称
	 */
	public function addTitle( $title = '' ){
		if( !$title ){
			return false;
		}
		if( !getfirstchar($title) ){
			return false;
		}
		$Title = new TitleModel;
		$Title->title = $title;
		$Title->shouzimu = getfirstchar($title);
		$Title->add_time = time();
		if( $result = $Title->save() ){
			return $result;
		}else{
			return false;
		}
		
	}

	/**
	 * 修改标题
	 * 1.$id 标题主键
	 * 2.$title 标题名称
	 */
	public function editTitle( $id = '', $title = '' ){
		if( !$id || !$title ){
			return false;
		}
		if( !getfirstchar($title) ){
			return false;
		}

		$Title = TitleModel::get($id);
		if( !is_object($Title) ){
			return false;
		}
		$Title->title = $title;
		$Title->shouzimu = getfirstchar($title);
		$Title->update_time = time();
		$result = $Title->save();

		if( $result === 1 || $result === 0 ){
			return true;
		}else{
			return false;
		}
		
	}

	/**
	 * 删除标题
	 * 1.$title_id 标题主键
	 */
	public function delTitle( $title_id = '' ){
		if( !$title_id ){
			return false;
		}
		$title = TitleModel::get($title_id);
		if( is_object($title) ){
			return $title->toArray();
		}else{
			return false;
		}
		$title->status = '0';
		if( $result = $title->save() ){
			return $result;
		}else{
			return false;
		}
	}

	/**
	 * 获取所有存在的首字母
	 */
	public function getAllShouzimu(){
		$Title_Model = new TitleModel;
		$shouzimus = $Title_Model->column('shouzimu');
		$titles = $Title_Model->where(['status'=>1])->order('add_time desc')->select();$titles = oToa($titles);
		if( count($shouzimus) <= 0){
			return array();
		}
		//有数据的字母的数组
		//$allZimu = array_unique($shouzimus);asort($allZimu);
		$titles2 = array();
		foreach( $titles as $key => $val){
			$titles2[$val['shouzimu']][] = array('id'=>$val['clothes_title_id'],'title'=>$val['title']);
		}
		ksort($titles2);
		//v($titles2);
		//$allZimu = array_count_values($shouzimus);
		//asort($allZimu);

		return $titles2;
	}
}