<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Orders as OrdersModel;


class Orders extends Controller
{
    public function index( $where1 = '', $where2 = '', $where3 = '', $order1 = '1', $page1 = '1' )
    {
    	$order1 = intval($order1);$page1 = intval($page1);
        $Orders_Model = new OrdersModel;

        //url 参数链
        $url_str = '';
        //order  排序
        if( $order1 === 2 ){
            $order = '`orders_id` asc';
            $url_str = 'order1/2/';
        }else{
            $order = '`orders_id` desc';
            $url_str = '';
        }



        //$Orders = $Orders_Model->getOrdersListPage( '', 'orders_id asc', $page1, 15);

        //$Orders = $Orders_Model->getOrdersListPage( '', 'orders_id desc', $page1, 15);


        //v($Orders);
        
        //where 订单状态, 开始时间, 结束时间
        //订单状态 status=1 已完成  payway=1/2/3 已付款 payway=0赊账
        $where = '';

        if( $where2 ){
            $where2 = strtotime($where2) ? strtotime($where2) : intval($where2);
        }
        if( $where3 ){
            $where3 = strtotime($where3) ? strtotime($where3) : intval($where3);
        }
        if( $where2 && $where3 && !( $where2 <= $where3 ) ){
            return $this->error('开始时间不能小于结束时间！');
        }
        if( $where2 ){
        	$where2 = date('Y-m-d', $where2);
        	$url_str .= 'where2/' . $where2 . '/';
        }else{
        	$where2 = '';
        }
        if( $where3 ){
            $where3 = date('Y-m-d', $where3);
            $url_str .= 'where3/' . $where3 . '/';
        }else{
        	$where3 = '';
        }


        //-------构造$where
        if( $where1 == 1 ){
       		//已完成
       		$where .= 'status=1' . ' and ';
       		$url_str .= 'where1/1/';
        }elseif( $where1 == 2 ){
        	//赊账
        	$where .= 'payway=0' . ' and ';
        	$url_str .= 'where1/2/';
        }elseif( $where1 == 3 ){
        	//已付款
        	$where .= 'payway=1 or payway=2 or payway=3' . ' and ';
        	$url_str .= 'where1/3/';
        }
        if( $where2 ){
        	$where .= 'add_time >= ' . strtotime($where2) . ' and ';
        }
        if( $where3 ){
        	$where .= 'add_time <= ' . strtotime($where3) . ' and ';
        }
        if( strlen($where) >4 ){
        	$where = qu($where,4);
        }


        if( intval($page1) > 0 ){
            $page = intval($page1);
        }else{
            $page = 1;
        }

        $Orders = $Orders_Model->getOrdersListPage( $where, $order , $page1, 2);

        if( is_array( $Orders ) ){
        	$total_page = ceil( $Orders['total'] / $Orders['per_page'] );
        }else{
        	$total_page = 1;
        }
        

    	//给视图传参
        $js_val_arr = 'var val_arr = [';
        $js_val_arr .= $where1 ? "'where1/" . $where1 . "/'," : "'',";
        $js_val_arr .= $where2 ? "'where2/" . $where2 . "/'," : "'',";
        $js_val_arr .= $where3 ? "'where3/" . $where3 . "/'," : "'',";
        $js_val_arr .= $order1 ? "'order1/" . $order1 . "/'," : "'',";
        $js_val_arr .= $page1 ? "'page1/" . $page1 . "/'," : "'',";
        if( $js_val_arr{(strlen($js_val_arr)-1)} === ',' ){
            $js_val_arr = substr($js_val_arr, 0, -1);//截取字符串
        }
        $js_val_arr .= '];';
        //设置$where1 的值
        $where1 = $where1 ? $where1 : '';

        $page_html = create_page_html( $page1, $total_page, URL_PATH . 'gx/public/index/orders/index/', $url_str, 'page1');
        $this->assign('js_val_arr', $js_val_arr);
        $this->assign('Orders', $Orders['data']);
        $this->assign('where1', $where1);
        $this->assign('where2', $where2);
        $this->assign('where3', $where3);
        $this->assign('page1', $page1);
        $this->assign('order1', $order1);
        $this->assign('page_html', $page_html);
        $this->assign('controller_name', 'orders');
        return $this->fetch();
    }
    public function details( $id = '')
    {
    	if( $id <= 0 ){
    		return $this->error('参数错误',URL_PATH . 'gx/public/index/orders/index/');
    	}
    	$Orders_Model = new OrdersModel;
    	$order = $Orders_Model::get($id);
    	if( is_object($order) ){
    		$order = $order->toArray();
    	}else{
    		return $this->error('参数错误',URL_PATH . 'gx/public/index/orders/index/');
    	}
    	
    	
    	$clothes = $Orders_Model->getClothesListByOrdersId( $id );

    	if( $order['status'] == 1 ){
    		$orders_is_wc = 1;
    	}else{
    		$orders_is_wc = 0;
    	}

	
    	
    	//v($order);

    	$sxtm = $Orders_Model->getSxtm();
    	$this->assign('orders_is_wc', $orders_is_wc);
    	$this->assign('sxtm', $sxtm);
    	$this->assign('order', $order);
    	$this->assign('clothes', $clothes);
    	$this->assign('controller_name', 'orders');
    	return $this->fetch();
    }

    public function fukuan( $id = '' , $payway = 1){
    	if( $id <= 0 ){
    		return $this->error('参数错误',URL_PATH . 'gx/public/index/orders/index/');
    	}
    	$payway = intval($payway);
    	if( $payway != 1 && $payway != 2 && $payway != 3 ){
    		return $this->error('参数错误',URL_PATH . 'gx/public/index/orders/index/');
    	}

    	$Orders_Model = new OrdersModel;
    	$order = $Orders_Model::get($id);
    	if( is_object($order) ){
    		$order = $order->toArray();
    	}else{
    		return $this->error('参数错误',URL_PATH . 'gx/public/index/orders/index/');
    	}
    	if( $order['payway'] != 0){
    		return $this->error('该订单已经付款！',URL_PATH . 'gx/public/index/orders/details/id/' . $id);
    	}
    	$order = $Orders_Model::get($id);
    	$order->payway = $payway;
    	$order->save();
    	return $this->success('收款成功！',URL_PATH . 'gx/public/index/orders/details/id/' . $id);
    }
    /**
	 * 生成一个水洗条码 
	 * 1.$id 衣物ID
	 * 2.可选，自定义的水洗条码
	 */
    public function createSxtm( $id = '', $sxtm = 0 ){
    	$id = intval($id);
    	if( $id <= 0 ){
    		return $this->error('该衣物不存在！');
    	}
    	$Orders_Model = new OrdersModel;
    	$clothes = $Orders_Model->getClothes( $id );
    	if( !$clothes ){
    		return $this->error('该衣物不存在！');
    	}

    	if( $clothes['shuixinum'] > 0 ){
    		return $this->error('该衣物已经生成水洗条码！', URL_PATH . 'gx/public/index/orders/details/id/' . $clothes['orders_id']);
    	}

    	//判断水洗条码是否非法
    	$sxtm = intval($sxtm);
    	if( $sxtm <= 0 ){
    		return $this->error('水洗条码必须大于0！');
    	}
    	$new_clothes = $Orders_Model->editClothes( $id , 'shuixinum' , $sxtm);

    	$now_sxtm = $Orders_Model->setSxtm( $sxtm+1 );

    	return $this->success('生成水洗条码成功！', URL_PATH . 'gx/public/index/orders/details/id/' . $new_clothes['orders_id']);
    }

    /**
	 * 一键生成水洗条码 
	 * 1.$id 订单ID
	 * 2.$sxtm 水洗条码
	 */
    public function createSxtms( $id = '', $sxtm = 0 ){
    	if( $id <= 0 ){
    		return $this->error('该订单不存在！');
    	}
    	$sxtm = intval($sxtm);
    	if( $sxtm <= 0 ){
    		return $this->error('水洗条码必须大于0！');
    	}
    	$Orders_Model = new OrdersModel;

    	$result = $Orders_Model->createSxtms( $id, $sxtm );
    	if( $result ){
    		return $this->success('生成水洗条码成功！', URL_PATH . 'gx/public/index/orders/details/id/' . $id);
    	}else{
    		return $this->error('该订单不存在！');
    	}
    	
    }


    /**
	 * 收取单个衣物
	 * 1.$id 衣物ID
	 */
    public function shouquClothes( $id = ''){
    	if( $id <= 0 ){
    		return $this->error('该衣物不存在！');
    	}

    	$Orders_Model = new OrdersModel;

    	$result = $Orders_Model->shouquClothes( $id );

    	if( $result ){
    		return $this->success('收取衣物成功！');
    	}else{
    		return $this->error('该订单不存在！');
    	}

    	
    }

    /**
	 * 收取该订单的所有衣物
	 * 1.$id 订单ID
	 */
    public function shouquAll( $id = ''){
    	if( $id <= 0 ){
    		return $this->error('该订单不存在！');
    	}

    	$Orders_Model = new OrdersModel;

    	$result = $Orders_Model->shouquAllClothes( $id );

    	if( $result ){
    		return $this->success('收取所有衣物成功！', URL_PATH . 'gx/public/index/orders/details/id/' . $id);
    	}else{
    		return $this->error('该订单不存在！');
    	}

    	
    }


}

