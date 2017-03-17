<?php
namespace app\index\controller;

use app\index\model\User as UserModel;
use think\Controller;
use \think\Request;
use app\index\model\testUser as testUserModel;
class User extends Controller
{

    public $controller_name ;

    /**
     * 功能:
     * 1.默认，显示出第一页的用户。根据id降序排列。
     *     传入$page，显示其他页
     * 2.根据总消费金额排序
     * 3.根据余额排序
     * 参数：
     * 1.$page 当前页
     * 2.$order 排序方式 1.用户排序 2.消费排序 3.余额排序
     */
    //$where $order $page
    public function index( $where1 = '',$where2 = '',$where3 = '', $order1 = '1', $page1 = 1){
        

        //order
        if( $order1 == '2' ){
            $order = '`user_id` asc';
        }elseif( $order1 == '3' ){
            $order = '`yue` desc';
        }elseif( $order1 == '4' ){
            $order = '`yue` asc';
        }elseif( $order1 == '5' ){
            $order = '`zonge2` desc';
        }elseif( $order1 == '6' ){
            $order = '`zonge2` asc';
        }else{
            $order = '`user_id` desc';$order1 = 1;
        }
        //where 手机号/姓名, 开始时间, 结束时间
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

        if( !$where1 && !$where2 && !$where3 ){
            $where = '';
        }elseif( !$where1 && ( $where2 || $where3 ) ){
            if( !$where2 ){
                $where .= ' `add_time` <= ' . $where3;
            }elseif( !$where3 ){
                $where .= ' `add_time` >= ' . $where2;
            }else{
                $where .= ' `add_time` <= ' . $where3.' and `add_time` >= ' . $where2;
            }
        }elseif( $where1 && ( $where2 || $where3 ) ){
            if( !$where2 ){
                $where .= ' `tel` like "%' . $where1 . '%" || `truename` like "%' . $where1 . '%" and `add_time` <= ' . $where3;
            }elseif( !$where3 ){
                $where .= ' `tel` like "%' . $where1 . '%" || `truename` like "%' . $where1 . '%" and `add_time` >= ' . $where2;
            }else{
                $where .= ' `tel` like "%' . $where1 . '%" || `truename` like "%' . $where1 . '%" and `add_time` <= ' . $where3 . ' and `add_time` >= ' . $where2;
            }
        }elseif( $where1 && ( !$where2 && !$where3 ) ){
            $where .= ' `tel` like "%' . $where1 . '%" || `truename` like "%' . $where1 . '%" ';
        }elseif( $where1 && $where2 && $where3 ){
            $where .= ' `tel` like "%' . $where1 . '%" || `truename` like "%' . $where1 . '%" and `add_time` <= ' . $where3 . ' and `add_time` >= ' . $where2;
        }
        //$page
        if( intval($page1) > 0 ){
            $page = intval($page1);
        }else{
            $page = 1;
        }
        //获取用户数据
        $User_Model = new UserModel;
        $users = $User_Model->getUserListPage($where, $order, $page);
        
        //判断是否搜索到了用户
        if( $users === false ){
            //给视图传参
            $js_val_arr = "var val_arr = ['','','','',''];";
            $this->assign('js_val_arr', $js_val_arr);
            $this->assign('order_model', $order1);
            $this->assign('sousuo_none_html', '<h1><font style="color:red;">搜索结果为空！</font></h1>');
            $this->assign('count', 1);//总页数
            //条件查询后，显示查询的值
            $this->assign('tel_text', '');
            $this->assign('starttime_text', '');
            $this->assign('endtime_text', '');
            $this->assign('controller_name', 'user');
            return $this->fetch();
        }else{
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

            //url参数链(除去page)
            $get_val_str = '/';
            $get_val_str .= $where1 ? "where1/" . $where1 . "/" : "";
            $get_val_str .= $where2 ? "where2/" . $where2 . "/" : "";
            $get_val_str .= $where3 ? "where3/" . $where3 . "/" : "";
            $get_val_str .= $order1 ? "order1/" . $order1 . "/" : "";

            //搜索高亮
            if( $where1 ){
                $users['data'] = $User_Model->Gaoliang( $users['data'], $where1, array('tel','truename'));
            }

            //条件查询后，显示查询的值
            $tel_text = $where1 ? $where1 : '';
            $starttime_text = $where2 ? date('Y-m-d',$where2) : '';
            $endtime_text = $where3 ? date('Y-m-d',$where3) : '';

            //总页码
            $total_page = ceil( $users['total'] / $users['per_page'] );

            //分页逻辑
            //上一页
            if( $users['current_page'] == 1 ){
                $prev_page = '';
            }else{
                $prev_page = '<li><a href="' . URL_PATH . 'gx/public/index/user/index/page1/' . ($users['current_page']-1) . $get_val_str . '">«上一页</a></li>';
            }
            //下一页
            if( $users['current_page'] == $total_page ){
                $next_page = '';
            }else{
                $next_page = '<li><a href="' . URL_PATH . 'gx/public/index/user/index/page1/' . ($users['current_page']+1) . $get_val_str . '">»下一页</a></li>';
            }
            //第一个...
            if( $users['current_page'] > 1 ){
                $frist_dian = '<li class="am-disabled"><a href="javascript:void(0)">...</a></li>';
            }else{
                $frist_dian = '';
            }
            //第二个...

            if( $users['current_page'] == $total_page ){
                $last_dian = '';
            }else{
                $last_dian = '<li class="am-disabled"><a href="javascript:void(0)">...</a></li>';
            }
            //当前页
            if( $total_page === 1 ){
                $now_page_html = '';
            }else{
                $now_page_html = '<li class="am-active"><a href="javascript:void(0)">'.$users['current_page'].'</a></li>';
            }

            //用户数据
            $this->assign('users', $users['data']);//用户数组
            //分页数据
            $this->assign('current_page', $users['current_page']);//当前页
            $this->assign('count', $total_page);//总页数
            //html
            $this->assign('prev_page', $prev_page);//上一页
            $this->assign('next_page', $next_page);//下一页
            $this->assign('frist_dian', $frist_dian);//第一个...
            $this->assign('last_dian', $last_dian);//第二个...
            $this->assign('now_page_html', $now_page_html);//当前页数的html
            $this->assign('page_text_html', "<li>当前页：$users[current_page]/总页数：{$total_page}</li>");//当前页/总页面的html
            $this->assign('sousuo_html', '<div class="am-u-sm-12 am-u-md-2"><div class="am-input-group am-input-group-sm"><input type="text" class="am-form-field" id=\'page_tz\' placeholder="输入页码"><span class="am-input-group-btn"><button id="page_tz_f" class="am-btn  am-btn-default am-btn-success tpl-am-btn-success am-icon-search" type="button"></button></span></div></div>');//搜索框
            //传递url参数
            $this->assign('js_val_arr', $js_val_arr);
            $this->assign('order_model', $order1);
            //条件查询后，显示查询的值
            $this->assign('tel_text', $tel_text);
            $this->assign('starttime_text', $starttime_text);
            $this->assign('endtime_text', $endtime_text);
            $this->assign('controller_name', 'user');
            return $this->fetch();
        }

        

    }



    //根据tel查询
    public function getUserByTel( $tel )
    {
    	$User_Model = new UserModel;
    	$user = $User_Model->getUserByTel($tel);
    	if( is_array($user) ){
    		echo $user['yue'];
    	}else{
    		echo "没有查到该用户";
    	}
    	
    }
    //根据tel添加用户
    public function addUserByTel( $tel ){
    	$User_Model = new UserModel;
    	$status = $User_Model->addUserByTel($tel);
        if( $status ){
            return $status;
        }else{
            return false;
        }
    }

    

	public function select_100_user(){
		$User_Model = new UserModel;
		$where = 'yue=123.23';//'yue<=50 and zonge1<1000';
		$order = 'yue desc,zonge1 desc';
		$users = $User_Model->getUserList( $where, $order, 2, 5, 100);
	}

}