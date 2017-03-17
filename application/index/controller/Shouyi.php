<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\Shouyi as ShouyiModel;
use app\index\model\Title as TitleModel;

class Shouyi extends Controller
{
    public function index()
    {

    	$this->assign('controller_name', 'shouyi');
        return $this->fetch();
    }

    public function dostep1(){
    	$tel = $_POST['tel'];
    	if( $tel == '' || $tel <= 0 ){
    		return $this->error('请输入正确的手机号');
    	}
    	$Shouyi_Model = new ShouyiModel;
    	$user = $Shouyi_Model->telIsExist($tel);

    	if( $user ){
    		return $this->success('已经查询到顾客信息！', URL_PATH . 'gx/public/index/shouyi/step2/id/'.$user['user_id']);
    	}else{
    		return $this->success('新顾客！请完善信息！', URL_PATH . 'gx/public/index/shouyi/addNewUser/tel/'.$tel);
    	}
    }

    public function step2( $id ){
    	$Shouyi_Model = new ShouyiModel;
    	$Title_Model = new TitleModel;
        $shouzimus = $Title_Model->getAllShouzimu();
    	$user = $Shouyi_Model->idIsExist($id);
    	$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','W','X','Y','Z');
    	if( $user ){
    		$this->assign('letters', $letters);
    		$this->assign('shouzimus', $shouzimus);
    		$this->assign('user', $user);
	    	$this->assign('controller_name', 'shouyi');
	    	return $this->fetch();
    	}else{
    		return $this->error('参数错误！', URL_PATH . 'gx/public/index/shouyi');
    	}
    	
    }

    public function addNewUser( $tel ){
    	$this->assign('tel', $tel);
    	$this->assign('controller_name', 'shouyi');
    	return $this->fetch();
    }

    public function doaddNewUser(){
    	if( !$_POST['tel'] || !$_POST['truename'] ){
    		return $this->error('请传入正确的参数！', URL_PATH . 'gx/public/index/shouyi');
    	}
    	$tel = $_POST['tel'];
    	$truename = $_POST['truename'];
    	$sex = intval($_POST['sex']);
    	$Shouyi_Model = new ShouyiModel;
    	$user = $Shouyi_Model->telIsExist($tel);
    	if( $user ){
    		return $this->success('老顾客！已经查询到顾客信息！', URL_PATH . 'gx/public/index/shouyi/step2/id/'.$user['user_id']);
    	}
    	$user = array();
    	$user['tel'] = $tel;
    	$user['truename'] = $truename;
    	$user['sex'] = $sex;
    	$user['add_time'] = time();
    	$result = $Shouyi_Model->createNewUser($user);
    	$result = $result->toArray();
    	return $this->success('信息已经完善！', URL_PATH . 'gx/public/index/shouyi/step2/id/'.$result['user_id']);
    }

    public function dodingdan(){

    	$Shouyi_Model = new ShouyiModel;
    	//设置订单编号
    	$Shouyi_Model->setOrdersId();
    	$clothes = getOrder($_POST['values']);

    	//v($clothes);
    	//v($_POST);
    	if( count($clothes) <= 0 ){
    		return $this->error('请选择衣物！');
    	}
    	//[生成订单]
    	//=需要添加的数据
    	//	用户id，标题，顾客姓名，手机号，价格，支付方式，状态，添加时间，预计完成时间。
    	$orders = array();
    	$orders['title'] = '';
    	foreach( $clothes as $key => $val ){
    		$orders['title'] .= $clothes[$key]['title'] . "，";
    	}
    	$orders['user_id'] =  $_POST['user_id'];
    	$orders['title'] =  qu($orders['title'],3);
    	$orders['truename'] =  $_POST['truename'];
    	$orders['tel'] =  $_POST['tel'];
    	$orders['jine'] =  $_POST['price'];
    	$orders['payway'] =  0;//支付方式 0-赊账 1-现金 2-微信 3-支付宝 4-金额
    	$orders['status'] =  0;//0未完成 1已完成
    	$orders['add_time'] =  time();
    	$orders_id = $Shouyi_Model->addOrders($orders);

    	//[生成衣物]
    	//=需要添加的数据
    	//	关联订单ID，处理，效果，瑕疵，品牌，颜色，衣物标题，状态，价格，收衣时间，修改时间，水洗条码
    	foreach( $clothes as $key => $val ){
    		$clothe = array();
    		$clothe['orders_id'] = $orders_id;
    		$clothe['chuli'] = $val['chuli'];
    		$clothe['xiaoguo'] = $val['xiaoguo'];
    		$clothe['xiaci'] = $val['xiaci'];
    		$clothe['brand'] = $val['brand'];
    		$clothe['color'] = $val['color'];
    		$clothe['title'] = $val['title'];
    		$clothe['status'] = 0;
    		$clothe['jine'] = $val['ture_jine'];
    		$clothe['add_time'] = time();
    		$clothe['update_time'] = time();
    		$clothes_id = $Shouyi_Model->addClothes($clothe);
    	}
    	
    	return $this->success('订单生成成功！', URL_PATH . 'gx/public/index/orders/details/id/'.$orders_id);
    }




//-------------------------------------------------[A--J--A--X--操作]-----------------------------------------------------------------------------------------------



    //ajax
    public function getTitleHtml(){
    	$Shouyi_Model = new ShouyiModel;
    	$Title_Model = new TitleModel;
        $shouzimus = $Title_Model->getAllShouzimu();
    	$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','W','X','Y','Z');

		$str = '<div class="tpl-block">
			                    <div class="am-g">
			                        <div class="tpl-form-body tpl-form-line">
			                            <form class="am-form tpl-form-line-form">
			                                <div class="am-form-group">
			                                	
			                                    <label for="user-name" class="am-u-sm-3 am-form-label">衣物标题 <span class="tpl-form-line-small-title">Title</span></label>
			                                    <div class="am-u-sm-9">
			                                        <input name="title" type="text" class="tpl-form-input"  placeholder="请输入衣物标题">
			                                        <small id="tishi1" style="color:red"></small>
			                                    </div>
			                                </div>
			                                <div class="am-form-group">
			                                    <div class="am-u-sm-9 am-u-sm-push-3">
			                                        <button id="ajax_add_title" type="button" class="am-btn am-btn-primary tpl-btn-bg-color-success ">添加标题</button>
			                                    </div>
			                                </div>
			                            </form>
			                        </div>
			                    </div>
			                </div><div class="am-g">';
		foreach( $letters as $key => $val){
			if( isset($shouzimus[$val]) ){
				$str .= '<div class="am-u-sm-1 am-u-end" style="font-weight: 700;"><a href="#'.$val.'">'.$val.'</a></div>';
			}else{
				$str .= '<div class="am-u-sm-1 am-u-end" style="color:#888">'.$val.'</div>';
			}
		}
		$str .= '</div>
                            	<hr>
                            	
                            	<div class="am-g" id="xinzeng" style="display:none">
									<div class="am-u-sm-12 am-u-end " style="font-weight: 700;" >新增</div>
                            		

                            		
								</div>
                            	
                            	<div class="am-g">';
        foreach( $shouzimus as $key => $val ){
        	$str .= '<div class="am-u-sm-12 am-u-end " style="font-weight: 700;" id="'.$key.'">'.$key.'</div>';
        	foreach( $shouzimus[$key] as $key2 => $val2 ){
        		$str .= '<div class="am-u-sm-2 am-u-end ofh" title="'.$val2['title'].'" id="title_checked">'.$val2['title'].'</div>';
        	}
        }
        $str .= '</div>
                            	<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
                            	
                            	
				                
				                

                            	
                            	
                            	
                                <div class="am-cf">

                                </div>
                                <hr>
                            </div>';
    	echo $str;exit;
    }

    public function getColorHtml(){
    	$Shouyi_Model = new ShouyiModel;
    	$colors = $Shouyi_Model->getColorList();
    	if( $colors === false ){
    		echo  '<span style="color:red">没有颜色！请添加！</span>';exit;
    	}else{
    		$str = '<div class="am-u-sm-12 am-u-md-12" style="font-size: 30px;">
							请选择颜色：<br /><br />
							</div>';
    		foreach( $colors as $color ){

    			$str .= '<div id="color_checked" val="'.$color['color_id'].'" class="am-u-sm-12 am-u-md-6 am-u-lg-2" style="margin-bottom: 20px;"><div class="tpl-i-title ofh" style="font-size: 30px;" title="'.$color['color_name'].'">'.$color['color_name'].'</div></div>';
    			
    		}
    		echo $str;exit;
    	}
    }

    public function getBrandHtml(){
    	$Shouyi_Model = new ShouyiModel;
    	$brands = $Shouyi_Model->getBrandList();
    	if( $brands === false ){
    		echo  '<span style="color:red">没有品牌！请添加！</span>';exit;
    	}else{
    		$str = '<div class="am-u-sm-12 am-u-md-12" style="font-size: 30px;">
							请选择品牌：<br /><br />
							</div>';
    		foreach( $brands as $brand ){

    			$str .= '<div id="brand_checked" val="'.$brand['brand_id'].'" class="am-u-sm-12 am-u-md-6 am-u-lg-2" style="margin-bottom: 20px;"><div class="tpl-i-title ofh" style="font-size: 30px;" title="'.$brand['brand_name'].'">'.$brand['brand_name'].'</div></div>';
    			
    		}
    		echo $str;exit;
    	}
    }

    public function getXiaciHtml(){
    	$Shouyi_Model = new ShouyiModel;
    	$xiacis = $Shouyi_Model->getXiaciList();
    	if( $xiacis === false ){
    		echo  '<span style="color:red">没有瑕疵！请添加！</span>';exit;
    	}else{
    		$str = '<div class="am-u-sm-12 am-u-md-12" style="font-size: 30px;">
							请选择瑕疵：<br /><br />
							</div>';
    		foreach( $xiacis as $xiaci ){

    			$str .= '<div id="xiaci_checked" val="'.$xiaci['xiaci_id'].'" class="am-u-sm-12 am-u-md-6 am-u-lg-2" style="margin-bottom: 20px;"><div class="tpl-i-title ofh" style="font-size: 30px;" title="'.$xiaci['xiaci_name'].'">'.$xiaci['xiaci_name'].'</div></div>';
    			
    		}
    		echo $str;exit;
    	}
    }

    public function getXiaoguoHtml(){
    	$Shouyi_Model = new ShouyiModel;
    	$xiaoguos = $Shouyi_Model->getXiaoguoList();
    	if( $xiaoguos === false ){
    		echo  '<span style="color:red">没有洗后效果！请添加！</span>';exit;
    	}else{
    		$str = '<div class="am-u-sm-12 am-u-md-12" style="font-size: 30px;">
							请选择洗后效果：<br /><br />
							</div>';
    		foreach( $xiaoguos as $xiaoguo ){

    			$str .= '<div id="xiaoguo_checked" val="'.$xiaoguo['xiaoguo_id'].'" class="am-u-sm-12 am-u-md-6 am-u-lg-2" style="margin-bottom: 20px;"><div class="tpl-i-title ofh" style="font-size: 30px;" title="'.$xiaoguo['xiaoguo_name'].'">'.$xiaoguo['xiaoguo_name'].'</div></div>';
    			
    		}
    		echo $str;exit;
    	}
    }

    public function getChuliHtml(){
    	$Shouyi_Model = new ShouyiModel;
    	$chulis = $Shouyi_Model->getChuliList();
    	if( $chulis === false ){
    		echo  '<span style="color:red">没有特殊处理！请添加！</span>';exit;
    	}else{
    		$str = '<div class="am-u-sm-12 am-u-md-12" style="font-size: 30px;">
							请选择特殊处理：<br /><br />
							</div>';
    		foreach( $chulis as $chuli ){

    			$str .= '<div id="chuli_checked" val="'.$chuli['chuli_id'].'" price="'.$chuli['chuli_price'].'" class="am-u-sm-12 am-u-md-6 am-u-lg-2" style="margin-bottom: 20px;"><div class="tpl-i-title ofh" style="font-size: 30px;" title="'.$chuli['chuli_name'].'">'.$chuli['chuli_name'].'</div></div>';
    			
    		}
    		echo $str;exit;
    	}
    }

    public function ajaxAddTitle(){
    	$Title_Model = new TitleModel;
    	$result = $Title_Model->addTitle($_GET['title']);
    	if( $result ){
    		return array('status'=>1,'title'=>$_GET['title']);
    	}else{
    		return array('status'=>2);
    	}
    	
    }

    
    
}