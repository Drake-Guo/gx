<?php
namespace app\index\controller;
use think\Controller;
class Clothes extends Controller
{
    public function index()
    {
    	$this->assign('controller_name', 'clothes');
        return $this->fetch();
    }
}
