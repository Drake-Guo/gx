<?php
namespace app\index\model;
use think\Model;
use app\index\model\Clothes as ClothesModel;
use app\index\model\Color as ColorModel;
use app\index\model\Brand as BrandModel;
use app\index\model\Chuli as ChuliModel;
use app\index\model\Xiaoguo as XiaoguoModel;

class Clothes extends Model{
	//设置数据表（不含前缀）
	protected $name = 'clothes';

	
	protected function getColorAttr($color,$data =[])
	{
		$attrid = '';
	    if($attrid = $data['color']){
	        $arr_name = ColorModel::where('color_id','in',$data['color'])->column('color_name');
	        return $arr_name ? implode(',',$arr_name) : false;
	    }
    	return false;
	}

	protected function getBrandAttr($brand,$data =[])
	{
		$attrid = '';
	    if($attrid = $data['brand']){
	        $arr_name = BrandModel::where('brand_id','in',$data['brand'])->column('brand_name');
	        return $arr_name ? implode(',',$arr_name) : false;
	    }
    	return false;
	}

	protected function getChuliAttr($chuli,$data =[])
	{
		$attrid = '';
	    if($attrid = $data['chuli']){
	        $arr_name = ChuliModel::where('chuli_id','in',$data['chuli'])->column('chuli_name');
	        return $arr_name ? implode(',',$arr_name) : false;
	    }
    	return false;
	}

	protected function getXiaoguoAttr($xiaoguo,$data =[])
	{
		$attrid = '';
	    if($attrid = $data['xiaoguo']){
	        $arr_name = XiaoguoModel::where('xiaoguo_id','in',$data['xiaoguo'])->column('xiaoguo_name');
	        return $arr_name ? implode(',',$arr_name) : false;
	    }
    	return false;
	}
}