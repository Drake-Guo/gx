<?php
namespace app\index\model;
use think\Model;
use app\index\model\Shouyi as ShouyiModel;
use app\index\model\User as UserModel;
use app\index\model\Clothes as ClothesModel;
use app\index\model\CCategory as CCategoryModel;
use app\index\model\PCategory as PCategoryModel;
use app\index\model\Color as ColorModel;
use app\index\model\Brand as BrandModel;
use app\index\model\Xiaci as XiaciModel;
use app\index\model\Xiaoguo as XiaoguoModel;
use app\index\model\Chuli as ChuliModel;
use app\index\model\Orders as OrdersModel;

class Shouyi extends Model{
	//设置数据表（不含前缀）
	protected $name = 'orders';

	public function telIsExist( $tel = '' ){
		if( $tel == '' || $tel <= 0 ){
			return false;
		}
		$User_Model = new UserModel;
		$result = $User_Model->getUserByTel($tel);
		if( is_array($result) ){
			return $result;
		}else{
			return false;
		}
	}

	public function idIsExist( $id = '' ){
		$id = intval($id);
		if( $id <= 0 ){
			return false;
		}

		$result = UserModel::get($id);
		if( $result != null ){
			$result = $result->toArray();
		}else{
			return false;
		}

		if( is_array($result) ){
			return $result;
		}else{
			return false;
		}
	}

	// public function createNewUser( $tel = '' ){
	// 	$tel = intval($tel);
	// 	if( $tel <= 0 ){
	// 		return false;
	// 	}
	// 	$User_Model = new UserModel;
	// 	$result = $User_Model->addUserByTel($tel);
	// 	if( $result ){
	// 		return $result;
	// 	}else{
	// 		return false;
	// 	}
	// }


	public function createNewUser( $user = array() ){
		$User_Model = new UserModel;
		$result = $User_Model->create($user);

		return $result;

	}


	public function getCCategoryList( $id ){
		$categoryList_o = CCategoryModel::where(['status'=>1,'p_category_id'=>$id])->order('c_category_id desc')->select();
		if( $categoryList_o ){
			$categoryList = array();
			foreach ($categoryList_o as $key => $val) {
				$categoryList[] = $val->toArray();
			}
			return $categoryList;
		}else{
			return false;
		}

	}

	public function getPCategoryList(){
		$categoryList_o = PCategoryModel::where(['status'=>1])->order('p_category_id desc')->select();
		if( $categoryList_o ){
			$categoryList = array();
			foreach ($categoryList_o as $key => $val) {
				$categoryList[] = $val->toArray();
			}
			return $categoryList;
		}else{
			return false;
		}

	}

	public function getColorList(){
		$colorList_o = ColorModel::where(['status'=>1])->order('color_id desc')->select();
		if( $colorList_o ){
			$colorList = array();
			foreach ($colorList_o as $key => $val) {
				$colorList[] = $val->toArray();
			}
			return $colorList;
		}else{
			return false;
		}

	}

	public function getBrandList(){
		$brandList_o = BrandModel::where(['status'=>1])->order('brand_id desc')->select();
		if( $brandList_o ){
			$brandList = array();
			foreach ($brandList_o as $key => $val) {
				$brandList[] = $val->toArray();
			}
			return $brandList_o;
		}else{
			return false;
		}

	}

	public function getXiaciList(){
		$xiaciList_o = XiaciModel::where(['status'=>1])->order('xiaci_id desc')->select();
		if( $xiaciList_o ){
			$xiaciList = array();
			foreach ($xiaciList_o as $key => $val) {
				$xiaciList[] = $val->toArray();
			}
			return $xiaciList_o;
		}else{
			return false;
		}

	}

	public function getXiaoguoList(){
		$XiaoguoList_o = XiaoguoModel::where(['status'=>1])->order('xiaoguo_id desc')->select();
		if( $XiaoguoList_o ){
			$xiaoguoList = array();
			foreach ($XiaoguoList_o as $key => $val) {
				$xiaciList[] = $val->toArray();
			}
			return $XiaoguoList_o;
		}else{
			return false;
		}

	}

	public function getChuliList(){
		$ChuliList_o = ChuliModel::where(['status'=>1])->order('chuli_id desc')->select();
		if( $ChuliList_o ){
			$chuliList = array();
			foreach ($ChuliList_o as $key => $val) {
				$chuliList[] = $val->toArray();
			}
			return $ChuliList_o;
		}else{
			return false;
		}

	}

	/**
	 * 插入订单
	 * 1.$orders 包含订单数据的数组
	 */
	public function addOrders( $orders ){
		$Orders_Model = new OrdersModel;
		$id = $Orders_Model->insertGetId( $orders );
		return $id;
	}

	/**
	 * 插入衣物
	 * 1.$clothe 包含衣物数据的数组
	 */
	public function addClothes( $clothe ){
		$Clothes_Model = new ClothesModel;
		$id = $Clothes_Model->insertGetId( $clothe );
		return $id;
	}


	/**
	 * 设置订单编号：
	 * 获取orders表的自增ID，并判断ID前缀是否为今天？
	 * 是：不做改变
	 * 否：将自增ID改为今天的前缀
	 */
	public function setOrdersId( ){
		$Orders_Model = new OrdersModel;
		$auto_id = $Orders_Model->query( 'select auto_increment from information_schema.tables where table_name = "orders";' );
		$auto_id = $auto_id[0]['auto_increment'];
		//截取自增id前8
		//v($auto_id);
		
		//当前的前缀
		$now_pre = substr($auto_id , 0 , 8);
		//今天的前缀
		$today_pre = date('Ymd', time());
		if( $now_pre == $today_pre ){
			return ;
		}else{
			$today_id = $today_pre."0001";
			$Orders_Model->query( "alter table orders auto_increment = {$today_id};" );
			return ;
		}
		
	}

	
}