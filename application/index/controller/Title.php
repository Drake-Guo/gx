<?php
namespace app\index\controller;

use app\index\model\Title as TitleModel;
use think\Controller;
class Title extends Controller
{
    /**
     * 衣物标题管理
     */
    
    //首页
    public function index( ){
        $Title_Model = new TitleModel;
        $zimus = $Title_Model->getAllShouzimu();

        $this->assign('zimus', $zimus);
        $this->assign('controller_name', 'title');
        return $this->fetch();
        
    }
    public function index2( $szm ){
        $Title_Model = new TitleModel;
        $titles = $Title_Model->getTitleList($szm);

        $this->assign('titles', $titles);
        $this->assign('controller_name', 'title');
        return $this->fetch();
        
    }

    public function get2(){
        $Title_Model = new TitleModel;
        $a = $Title_Model->getAllShouzimu();
        v($a);
    }

    public function add(){
        $this->assign('controller_name', 'title');
        return $this->fetch();
    }

    public function doadd(){
        $title = $_POST['title'];
        if( !$title ){
            return $this->error('衣物标题为空！');
        }
        if( !getfirstchar($title) ){
            return $this->error('衣物标题必须为汉字开头！');
        }
        $Title_Model = new TitleModel;
        
        $result = $Title_Model->addTitle($title);
        v($result);
        if( $result ){
            return $this->success('添加成功！');
        }else{
            return $this->error('添加失败！');
        }
    }

}