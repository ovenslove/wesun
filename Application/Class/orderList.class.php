<?php
class orderList{
     public   function ordersort($cate,$html='--',$pid=0,$level=0){
         $arr=array();
         foreach($cate as $v){
             if($v['pid']==$pid){
                 $v['level']=$level+1;
                $v['html']=$level==0?"": str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;',$level)."|".$html;
                 $arr[]=$v;
                 $arr=array_merge($arr,self::ordersort($cate,$html,$v['id'],$level+1));
             }
         }
         return $arr;
    }


    public  function ordersortlist($cate,$name='childs',$pid=0){
        $arr=array();
        foreach($cate as $v){
            if($v['node_pid']==$pid){
                $v[$name]=self::ordersortlist($cate,$name,$v['node_id']);
                $arr[]=$v;
            }
        }
        return $arr;
    }

}
?>