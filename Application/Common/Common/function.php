<?php


/*生成验证码*/
function   verify(){
    $config =    array(
        'fontSize'    =>    32,    // 验证码字体大小
        'length'      =>    4,     // 验证码位数
        'useNoise'    =>    true, // 关闭验证码杂点
    );
    $Verify = new \Think\Verify($config);
    $Verify->entry();

}
/*检查验证码*/
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}


function  node_merge($data,$access=null,$pid='0'){
    $arr=array();
//        dump($data);
    foreach( $data as $val){
        if(is_array($access)){
            $val['access']=in_array($val['id'],$access)?1:0;
        }

        if($val['pid']==$pid){
            $val['child']=node_merge($data,$access,$val['id']);
            $arr[]=$val;
        }
    }
    return $arr;
}

function ordersortlist($cate,$name='childs',$pid=0){
    $arr=array();
    foreach($cate as $v){
        if($v['node_pid']==$pid){
            $v[$name]=ordersortlist($cate,$name,$v['node_id']);
            $arr[]=$v;
        }
    }
    return $arr;
}

/*格式打印*/
function p($data){
    dump($data,1,'<pre>',0);
}




















