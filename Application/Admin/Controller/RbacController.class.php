<?php

namespace Admin\Controller;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class RbacController extends CommenController {

    /*
     *
     * Rbac权限管理
     *
     *
     * */

    /*role角色列表*/
    public  function  role(){
        $role=M('role');
        $this->data=$role->select();
        $this->display();

    }
    /*role_add添加角色*/
    public  function  role_add(){
        $this->display();

    }
    /*role_add添加角色 事件处理*/
    public function  role_add_handle(){
        $data=array(
            'name'=>I('role_name'),
            'remark'=>I('role_remark'),
            'status'=>I('role_status'),
        );
        $role=M('role');
        if($role->add($data)){
            $this->success('添加角色成功！');
        }else{
            $this->error('添加角色失败');
        };

//        dump($_POST);
    }
    /*node节点列表*/
    public  function  node(){
        $node=M('node');
        $field=array('id','name','title','pid');
        $data=$node->field($field)->order('sort')->select();
        $data=node_merge($data);

        $this->assign('data',$data);
        $this->display();

    }
    /*node_add添加节点*/
    public  function  node_add(){
        $this->pid=I('pid',0,'intval');
        $this->level=I('level',1,'intval');
        switch($this->level){
            case 1:
                $this->type='应用';
                break;
            case 2:
                $this->type='控制器';
                break;
            case 3:
                $this->type='方法';
                break;
            default: break;
        }

        $this->display();

    }
    /*node_add添加节点 事件处理*/
    public function  node_add_handle(){
        $data=array(
            'name'=>I('node_name'),
            'title'=>I('node_title'),
            'status'=>I('node_status'),
            'sort'=>I('node_sort'),
            'pid'=>I('node_pid'),
            'level'=>I('node_level'),
        );
        $node=M('node');
        if($node->add($data)){
            $this->success('添加节点成功！',U('node'));
        }else{
            $this->error('添加节点失败');
        };

    }
    /*node_update*/
    public  function  node_update(){
        $nid=I('nid');
        $node=M('node');
        switch($this->level){
            case 1:
                $this->type='应用';
                break;
            case 2:
                $this->type='控制器';
                break;
            case 3:
                $this->type='方法';
                break;
            default: break;
        }

        $this->data=$node->where(array('id'=>$nid))->find();
        $this->display('node_update');
    }

    /*node_update_handle 节点修改事件*/
    public  function  node_update_handle(){
        $data=array();
        foreach( $_POST as $k=> $v){
            $exp=explode('_',$k);
            $data[$exp[1]]=$v;
        }
        $node=M('node');
       if( $node->where(array('id'=>$data['id']))->save($data)){
           $this->success('修改节点成功',U('Rbac/node'));
       }else{
           $this->error('修改节点失败');
       };
    }

    /*节点删除事件处理*/

    public  function  node_delete(){

    }



    /*配置角色权限*/
    public  function  access(){
        $rid=I('rid',0,'intval');

        $node=M('node');
        $data=$node->order('sort')->select();
        $access=M('access')->where(array('role_id'=>$rid))->getField('node_id',true);
//        dump($access);
        $data=node_merge($data,$access);

        $this->assign('data',$data);
        $this->assign('rid',$rid);
        $this->display();
    }


    /*更新角色权限*/
    public  function  setAccess(){

        $rid=I('rid');
        $access=M('access');
        $access->where(array('role_id'=>$rid))->delete();
        $accessarr=array();
        foreach ($_POST['access'] as $v) {
            $exp=explode('_',$v);
            $accessarr[]=array(
                'role_id'=>$rid,
                'node_id'=>$exp[0],
                'level'=>$exp[1]
            );
        }
        if($access->addAll($accessarr)){
            $this->success('更新成功',U('Rbac/role'));
        }else{
            $this->error('更新失败');
        }

    }



    /*用户列表 user*/
    public  function  user(){
        $user=M('user');
        $this->data=$user
            ->join('sun_role_user ON  sun_user.user_id = sun_role_user.user_id')
            ->join('sun_role ON  sun_role_user.role_id = sun_role.id')
            ->select();

//        dump($data);
        $this->display();
    }

    /*添加用户 user_add*/
    public  function  user_add(){
        $this->role=M('role')->select();
//        dump($role);
        $this->display();
    }
    /*添加用户表单处理 user_add_handle*/
    public  function  user_add_handle(){
        $user=M('user');
        $role_user=M('role_user');
        $user->startTrans();
        $userdata=array(
            'user_name'=>I('username'),
            'user_psd'=>md5(I('password')),
            'user_remark'=>I('remark',''),
            'user_email'=>I('email',''),
            'user_registtime'=>date('Y-m-d H:i:s')
        );
        $user_id=$user->add($userdata);
        if($user_id){
            $role_userdata=array(
                'role_id'=>I('role'),
                'user_id'=>$user_id
            );
            if($role_user->add($role_userdata)){
                $user->commit();
                $this->success('添加用户成功');
            }else{
                $user->rollback();
                $this->error('添加用户失败');
            };
        }else{
            $user->rollback();
        }
    }



}