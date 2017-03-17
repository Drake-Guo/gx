<?php
namespace app\index\model;
use think\Model;
use app\index\model\User as UserModel;
use createName\rndChinaName as rndChinaName;

class testUser extends Model{
	//设置数据表（不含前缀）
	protected $name = 'user';

	/**
	 * 添加1000条测试用户
	 */
    public function add1000TestUsers(){
    	for( $i = 1; $i <= 1000; $i++){
    		$user = array('tel'=>$i,'yue'=>$this->rand_jine(),'zonge1'=>$this->rand_jine(),'zonge2'=>$this->rand_jine());
        	$User_Model = new UserModel;
    		$status = $User_Model->addUser($user);
    	}
    	return true;
    }

    /**
	 * 生成随机金额（用于生成测试数据）
	 */
	public function rand_jine(){
		//生成随机小数
		$xiaosuh = mt_rand()/mt_getrandmax();
		//0的个数
		$zeros = mt_rand(1,6);
		//未简化的随机小数
		$number = pow(10,$zeros)*$xiaosuh;
		//简化后的随机小数
		return round($number,2);
	}
	/**
	 * 生成随机昵称（用于生成测试数据）
	 */
	public function getTruename(){
		$name_obj = new rndChinaName;
 		return $name = $name_obj->getName();
	}

	/**
	 * 生成随机真实姓名（用于生成测试数据）
	 */
	public function getNickname(){
		$name_obj = new rndChinaName;
 		return $name = $name_obj->getNickname();

	}

	/**
	 * 更新数据
	 */
	public function testUpdate( $user_id ){
		$user = UserModel::get($user_id);
		$user->nickname = $this->getNickname();
		$user->truename = $this->getTruename();
		$status = $user->save();
		if($status){
			return true;
		}else{
			return false;
		}
	}
	
}