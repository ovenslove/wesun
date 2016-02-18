<?php
namespace Admin\Controller;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class IndexController extends CommenController {
    /*后台入口*/
    public function index(){
        $type=M('type');
        import('Class.orderList');
        $data=$type->where(array('node_promit'=>1,'node_type'=>1))->order('node_sort')->select();
        $this->node=ordersortlist($data);
//        p(ordersortlist($data));
        $this->display();
    }





}