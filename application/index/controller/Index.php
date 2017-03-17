<?php
namespace app\index\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
    	$this->assign('controller_name', 'index');
        return $this->fetch();
    }
}
