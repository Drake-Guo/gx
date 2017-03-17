<?php
namespace app\index\model;
use think\Model;
use app\index\model\User as UserModel;

class User extends Model{
	//设置数据表（不含前缀）
	protected $name = 'user';

	//性别读取器
		protected function getSexAttr($sex)
		{
			if( $sex == 1 ){
				return "男";
			}elseif( $sex == 2 ){
				return "女";
			}elseif( $sex == 0 ){
				return "保密";
			}
		}

	//生日读取器
		protected function getBrithdayAttr($brithday)
		{
			return date('Y-m-d', $brithday);
		}

	//注册时间读取器
		protected function getAddTimeAttr($add_time)
		{
			return date('Y-m-d', $add_time);
		}

	/**
	 * 单个添加用户
	 * 1.数组 array([字段名]=>'值',...)
	 */
	public function addUser( $user ){
		if( !$user || !is_array($user) ){
			return false;
		}
		$User_Model = new UserModel;
		foreach( $user as $key => $val ){
			$User_Model->$key = $val;
		}
		$status = $User_Model->save();
		if( $status ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 根据手机号添加用户
	 * 1.$tel 手机号
	 */
	public function addUserByTel( $tel ){
		$User_Model = new UserModel;
		$checkUser = $this->checkUserByTel($tel);
		if( $checkUser === true ){
			return false;
		}
		$User_Model->tel = $tel;
		$status = $User_Model->insertGetId(array('tel'=>$tel));
		if( $status ){
			return $status;
		}else{
			return false;
		}	
	}

	/**
	 * 根据手机号检查用户是否存在
	 * 1.$tel 手机号
	 */
	public function checkUserByTel( $tel ){
		$user = $this->getUserByTel($tel);
		if( $user != false ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * 根据手机号查询一个用户
	 * 1.$tel 手机号
	 */
	public function getUserByTel( $tel ){
		$user = UserModel::getByTel($tel);
		if( is_object($user) && $user != null ){
			return $user->toArray();
		}else{
			return false;
		}
	}

	/**
	 * 查询用户列表(分页)
	 * 1.where 条件
	 * 2.$order 排序方式
	 * 3.$now_page 当前页数
	 * 4.$page_size 分页大小
	 * 5.$limit 限制
	 */
	public function getUserListPage( $where = '', $order = 'user_id desc', $now_page = 1, $page_size = 10 , $limit = '' ){
		$User_Model = new UserModel;
		$user_list = $User_Model->where($where)->order($order)->limit($limit)->paginate($page_size,false,['page' => $now_page,]);
		$user_list = $user_list->toArray();
		
		if( count( $user_list['data'] ) === 0 ||  empty( $user_list['data'] ) === true ){
			return false;
		}
		return $user_list;
	}

	/**
	 * 搜索结果高亮
	 * 1.$lists 搜索的结果集
	 * 2.$str 高亮的字符串
	 * 3.$filed 高亮的字段
	 */
	
	public function Gaoliang( $lists, $str, $filed ){
		if( is_array($lists) ){
			foreach( $lists as $key_list => $list){
				foreach( $list as $key => $val ){
					foreach( $filed as $name ){
						$lists[$key_list][$name] = str_replace($str,'<font style="color:red;font-weight:bold">'.$str.'</font>',$lists[$key_list][$name]);
					}
				}
			}
			return $lists;
		}else{
			return false;
		}
	}
	
}