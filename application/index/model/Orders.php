<?php
namespace app\index\model;
use think\Model;
use app\index\model\Orders as OrdersModel;
use app\index\model\Clothes as ClothesModel;
use app\index\model\Sxtm as SxtmModel;


class Orders extends Model{
	//设置数据表（不含前缀）
	protected $name = 'orders';

	

	/**
	 * 查询订单列表(分页)
	 * 1.where 条件
	 * 2.$order 排序方式
	 * 3.$now_page 当前页数
	 * 4.$page_size 分页大小
	 * 5.$limit 限制
	 */
	public function getOrdersListPage( $where = '', $order = 'orders_id desc', $now_page = 1, $page_size = 15 , $limit = '' ){

		$Orders_Model = new OrdersModel;
		$orders_list = $Orders_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
		$orders_list = $orders_list->toArray();
		if( count( $orders_list['data'] ) === 0 ||  empty( $orders_list['data'] ) === true ){
			return false;
		}

		return $orders_list;
	}
	/**
	 * 获取当前水洗条码
	 */
	public function getSxtm(){

		$Sxtm_Model = new SxtmModel;
		$Sxtms = $Sxtm_Model->get(1);
		$Sxtms = $Sxtms->toArray();
		//$Sxtms = $Sxtms[0]->toArray();
		return $Sxtms['sxtm'];
	}

	/**
	 * 修改当前水洗条码
	 */
	public function setSxtm( $sxtm = '' ){
		$sxtm = intval($sxtm);
		if( $sxtm <= 0 ){
			$sxtm = 1;
		}
		$Sxtm_Model = new SxtmModel;
		$Sxtm_M = $Sxtm_Model->get(1);
		$Sxtm_M->sxtm = $sxtm;
		$Sxtm_M->save();
		$Sxtm_M = $Sxtm_M->toArray();
		return $Sxtm_M;
	}


	/**
	 * 根据orders_id获取衣物列表
	 * 1.orders_id 关联订单id
	 */
	public function getClothesListByOrdersId( $orders_id ){
		$Clothes_Model = new ClothesModel;
		$clothes = $Clothes_Model->where('orders_id=' . $orders_id )->select();
		if( is_array($clothes) && count( $clothes )){
			
		}else{
			return '';
		}
		$clothes = oToa($clothes);
		return $clothes;
	}


	/**
	 * 获取单个衣物
	 * 1.clothes_id 衣物id
	 */
	public function getClothes( $clothes_id ){
		$clothes_id = intval($clothes_id);
		if( $clothes_id <= 0 ){
			return false;
		}
		$Clothes_Model = new ClothesModel;
		$clothes = $Clothes_Model->get( $clothes_id );
		
		if( is_object($clothes) ){
			$clothes = $clothes->toArray();
		}else{
			return null;
		}
		
		return $clothes;
	}

	/**
	 * 修改单个衣物
	 * 1.clothes_id 衣物id
	 * 2.修改字段
	 * 3.字段值
	 */
	public function editClothes( $clothes_id = '', $field = '', $val = ''){
		$clothes_id = intval($clothes_id);
		if( $clothes_id <= 0 ){
			return null;
		}
		$clothes = ClothesModel::get($clothes_id);
		if( is_object($clothes) ){
			
		}else{
			return null;
		}
		$clothes->$field = $val;
		$clothes->save();
		$clothes = $clothes->toArray();

		return $clothes;
	}
	
	/**
	 * 一键生成水洗条码
	 * 1.orders_id 订单id
	 * 2.$sxtm 起始水洗条码
	 */
	public function createSxtms( $orders_id = '', $sxtm = ''){
		if( $orders_id <= 0 ){
			return null;
		}
		$sxtm = intval($sxtm);
		if( $sxtm <= 0 ){
			return null;
		}

		$orders = OrdersModel::get($orders_id);
		if( !is_object($orders) ){
			return null;
		}

		$clothes = ClothesModel::where('orders_id='.$orders_id)->select();
		$clothes = oToa($clothes);
		if( count( $clothes ) <= 0 ){
			return null;
		}
		foreach( $clothes as $key => $val  ){
			$this->editClothes( $val['clothes_id'], 'shuixinum', $sxtm );
			$sxtm++;
		}
		$this->setSxtm($sxtm);

		return true;
	}

	/**
	 * 收取单个衣物
	 * 1.orders_id 订单id
	 */
	public function shouquClothes( $clothes_id = '' ){
		$clothes_id = intval($clothes_id);
		if( $clothes_id <= 0 ){
			return null;
		}

		$clothes = ClothesModel::get($clothes_id);


		if( !is_object($clothes) ){
			return null;
		}

		$orders = OrdersModel::get($clothes->orders_id);
		if( !is_object($orders) ){
			return null;
		}
		$this->editClothes( $clothes_id, 'status', 2 );

		$clothes = ClothesModel::where('orders_id='.$orders['orders_id'])->select();
		$clothes = oToa($clothes);
		if( count( $clothes ) > 0 && is_array($clothes) ){
			$status = 1;
			foreach( $clothes as $key => $val ){
				if( $val['status'] != 2 && $val['status'] != 3 ){
					$status = 0;
				}
			}
		}

		if( $status == 1 ){
			$orders->status = 1;
			$orders->save();
		}
		
		return true;
	}

	/**
	 * 一键收取所有衣物
	 * 1.orders_id 订单id
	 */
	public function shouquAllClothes( $orders_id = '' ){
		if( $orders_id <= 0 ){
			return null;
		}

		$orders = OrdersModel::get($orders_id);
		if( !is_object($orders) ){
			return null;
		}

		$clothes = ClothesModel::where('orders_id='.$orders_id)->select();
		$clothes = oToa($clothes);

		if( count( $clothes ) >= 0 && is_array( $clothes )){
			foreach( $clothes as $key => $val  ){
				$this->editClothes( $val['clothes_id'], 'status', 2 );
			}
		}


		$orders->status = 1;
		$orders->save();
		return true;
	}

	
	
}