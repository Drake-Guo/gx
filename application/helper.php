<?php


/**
 * 打印数组专用函数！
 */
function v($var)
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";exit;
}

/**
 * 去掉字符串最后一位字符！
 */

function qu($str,$len = 1)
{
    return $str = substr( $str, 0, strlen($str) - $len );
}

/**
 * 将select查询的对象数组集合转换成数组数组集合。
 * 1.数组
 * 2.保留的下标
 */
function oToa( $arr = [], $args = [])
{
	if( !is_array($arr) || !is_array($args) ){
		return false;
	}else{
		if( count($arr) == 0 ){
			return false;
		}
	}
	if( count($args) == 0 ){
		$arrs = [];
	    foreach( $arr as $val ){
	    	$arrs[] = $val->toArray();
	    }
	    return $arrs;
	}else{
		$arrs = [];
	    foreach( $arr as $val ){
	    	$arrs[] = $val->toArray();
	    }

	    foreach( $arrs as $key => $val){
	    	$a = [];
	    	foreach( $args as $arg ){
	    		$a[$arg] = $val[$arg];
	    		$arrs[$key] = $a;
	    	}
	    }

	    return $arrs;
	}
	

}

/**
 * 将查询的数组结果集，按照某个字段去重。
 * eg:将大类select出很多数据，然后根据大类名去重  array_array_unique( $p_categorys, ['p_category_name'])
 */
function array_array_unique( $arr = [], $arg = '')
{
    if( !is_array($arr) || !$arg ){
		return false;
	}else{
		if( count($arr) <= 0 ){
			return false;
		}
	}
	$test_arr = [];
	foreach( $arr as $key => $val){
		$test_arr[$key] = $val[$arg];
	}
	return $test_arr = array_unique($test_arr);
}

/**
 * 多数组去重
 */
function arr_del_arr( $arr1 = [], $arr2 = [])
{
    if( !is_array($arr1) || !is_array($arr2) ){
		return false;
	}else{
		if( count($arr1) <= 0 || count($arr2) <= 0 ){
			return false;
		}
	}
	
	foreach( $arr2 as $val ){
		unset($arr1[array_search($val,$arr1)]);
	}
	

	return $arr1;
}
/**
 * 生成分页的html代码!
 * 功能:
 * 1.生成分页的html代码。
 * 2.适合分页数量少的。
 * 参数：
 * 1.$now_page 当前页
 * 2.$total_page 总页数
 * 3.$url_val 其他url参数串
 * 4.$page_key 分页参数的参数名
 */
function create_page_html( $now_page, $total_page, $start_url, $url_val, $page_key ){
	$total_page = intval($total_page);$now_page = intval($now_page);
	if( ($total_page <= 0) || ( $now_page <= 0 ) ){
		return false;
	}
	if( $total_page === 1 ){
		return '<li class="am-active"><a href="javascript:void(0)">1</a></li><li>当前页：1/总页数：1</li><div class="am-u-sm-12 am-u-md-2"></div>';
	}else{
		$page_html_str = '';
		if( $now_page === 1 ){
			$page_html_str .= '';
		}else{
			$page_html_str .= '<li><a href="'.$start_url.$url_val.$page_key.'/'.( $now_page - 1).'">«上一页</a></li>';
		}
		for( $i = 1; $i <= $total_page; $i++ ){
			if( $i === $now_page ){
				$page_html_str .= '<li class="am-active"><a href="javascript:void(0)">'.$i.'</a></li>';
			}else{
				$page_html_str .= '<li><a href="'.$start_url.$url_val.$page_key.'/'.$i.'">'.$i.'</a></li>';
			}
		}
		if( $now_page === $total_page ){
			$page_html_str .= '';
		}else{
			$page_html_str .= '<li><a href="'.$start_url.$url_val.$page_key.'/'.( $now_page + 1).'">»下一页</a></li>';
		}
		$page_html_str .= '<li>当前页：'.$now_page.'/总页数：'.$total_page.'</li>';
		return $page_html_str;
	}
	return false;
}



function getfirstchar($s0){   //获取单个汉字拼音首字母。注意:此处不要纠结。汉字拼音是没有以U和V开头的
    $fchar = ord($s0{0});

    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;

    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "H";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return NULL;
    //return $s0;
}
//将提交过来的订单进行解析
function getOrder( $order = '' ){
	if( !$order ){
		return false;
	}
	$order = explode("|",$order);
	array_pop($order);
	if( count($order) <= 0 ){
		return false;
	}
	$new_order = array();
	foreach( $order as $key => $val ){
		$val = explode(",",$val);
		$new_order[$key]['title'] = $val[0];
		$new_order[$key]['color'] = $val[1];
		$new_order[$key]['brand'] = $val[2];
		$new_order[$key]['xiaci'] = $val[3];
		$new_order[$key]['xiaoguo'] = $val[4];
		$new_order[$key]['chuli'] = $val[5];
		$new_order[$key]['ture_jine'] = $val[6];
	}
	return $new_order;
}

