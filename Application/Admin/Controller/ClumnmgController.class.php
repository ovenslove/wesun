<?php

namespace Admin\Controller;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class ClumnmgController extends CommenController {
/*后台界面栏目*/
    public  function  admin_clumn(){
        $admin=M('type');
        $clumndata=$admin->where(array('node_type'=>1))->order('node_sort')->select();
        $this->clumndata=ordersortlist($clumndata);
        $this->display();
    }
    /*前台界面栏目*/
    public  function  home_clumn(){
        $admin=M('type');
        $clumndata=$admin->where(array('node_type'=>2))->select();
        $this->clumndata=ordersortlist($clumndata);
        $this->display();
    }
/*添加栏目*/
    public  function  clumn_add(){
        $this->pid=I('pid',0);
        $this->level=I('level',0);
        $this->type=I('type',1);
        $this->display();
    }

/*添加栏目表单事件*/
    public  function  clumn_add_handle(){
        $admin=M('type');
        if($admin->add($_POST)){
            if($_POST['node_type']==1){
                $this->success("添加成功！",U('Clumnmg/admin_clumn'));
            }else if($_POST['node_type']==2){
                $this->success("添加成功！",U('Clumnmg/home_clumn'));
            }

        }else{
            $this->error("添加失败！");
        };
//       p($_POST);
    }
/*栏目删除处理事件*/
    public  function  clumn_delete_handle(){
        $admin=M('type');
        $condition['node_id'] = I('id');
        $condition['node_pid'] = I('id');
        $condition['_logic'] = 'OR';

        if($admin->where($condition)->delete()){
            if(I('node_type')==1){
                $this->success("删除成功！",U('Clumnmg/admin_clumn'));
            }else if(I('node_type')==2){
                $this->success("删除成功！",U('Clumnmg/home_clumn'));
            }
        }else{
            $this->error("删除失败！");
        };

    }

    /*修改栏目信息*/
    public  function  clumn_update(){
        $this->pid=I('id');
        $admin=M('type');
        $this->data=$admin->where(array('node_id'=>I('id')))->find();
//        p($result);
        $this->display();
    }
    /*修改栏目信息处理事件*/
    public  function  clumn_update_handle(){
        $admin=M('type');
//        $admin->save($_POST);

        if($admin->save($_POST)){
            if(I('node_type')==1){
                $this->success("更新成功！",U('Clumnmg/admin_clumn'));
            }else if(I('node_type')==2){
                $this->success("更新成功！",U('Clumnmg/home_clumn'));
            }
        }else{
            $this->error("更新失败！");
        };

    }

}