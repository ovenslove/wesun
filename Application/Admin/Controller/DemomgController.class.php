<?php

namespace Admin\Controller;
use Think\Controller;
class DemomgController extends Controller {
    public  function  demo_all(){
        $demo = M('demo');
        $demodata=$demo->select();
        $mark=M('mark');

        $count      = $demo->where('dem_status=1')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $Page->lastSuffix=true;
        $Page->setConfig('header','第<b>%NOW_PAGE%</b>/<b>%TOTAL_PAGE%</b>页&nbsp;&nbsp;共<b>'.$count.'</b>条');
        $Page->setConfig('prev',' &laquo;');
        $Page->setConfig('next','&raquo;');
        $Page->setConfig('last','末');
        $Page->setConfig('first','...%TOTAL_PAGE%');
        $Page->setConfig('theme','  %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');



        $show       = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $demo->where('dem_status=1')->order('dem_sort asc,dem_addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();


    }
    public  function  demo_add(){
        $admin=M('type');
        $mark=M('mark');
        /*获取分类列表*/
        $clumndata=$admin->where(array('node_type'=>2))->select();
        $clumndata=ordersortlist($clumndata);
        foreach( $clumndata as $val){
            if($val['node_name']=='demo'){
                $demodata=$val['childs'];
            }
        }
        /*获取标签列表*/
        $markdata=$mark->select();
        $this->markdata=$markdata;
        $this->demodata=$demodata;

        $this->display();
    }

    /*新建文章数据处理*/
    public  function  demo_add_handle(){

        $demo = M('demo');
        $upload = new \Think\Upload();// 实例化上传类

        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->saveName = time().'_'.mt_rand();
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './upload/images/preimg/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录


        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功 获取上传文件信息
            foreach ($info as $file) {
                $preimgpath= __ROOT__ .'/upload/images/preimg/'. $file['savepath'] . $file['savename'];
            }
        }

        $data = array(
            'dem_title' => I('dem_title'),
            'dem_introduce' => I('dem_introduce'),
            'dem_mark' => I('dem_mark'),
            'dem_type' => I('dem_type'),
            'dem_preurl' => I('dem_preurl'),
            'dem_downurl' => I('dem_downurl'),
            'dem_preimg' => $preimgpath,
            'dem_author' => session('u_name'),
            'dem_addtime' => date('Y-m-d H:i:s'),
            'dem_ip' => $_SERVER["REMOTE_ADDR"]


        );

        if ($demo->add($data)) {

            $this->success('发布成功');
        } else {
            $this->error('发布失败');
        };


    }/*article_add_handle*/

    /*文章更新*/
    public  function  demo_update(){
        $demo = M('demo');
        $mark=M('mark');
        $this->data=$demo->where(array('dem_id'=>I('id')))->find();

        $admin=M('type');
        $clumndata=$admin->where(array('node_type'=>2))->select();
        $clumndata=ordersortlist($clumndata);

        foreach( $clumndata as $val){
            if($val['node_name']=='demo'){
                $demodata=$val['childs'];
            }
        }
        $markdata=$mark->select();
        $this->markdata=$markdata;
        $this->demodata=$demodata;

        $this->display();
//    p($data);
    }
    /*更新文章处理*/
    public  function  demo_update_handle(){

        $demo = M('demo');
        $upload = new \Think\Upload();// 实例化上传类

        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->saveName = time().'_'.mt_rand();
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './upload/images/preimg/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录


        if($_FILES['preimg']['name']==''){
            $data = array(
                'dem_title' => I('dem_title'),
                'dem_introduce' => I('dem_introduce'),
                'dem_mark' => I('dem_mark'),
                'dem_type' => I('dem_type'),
                'dem_preurl' => I('dem_preurl'),
                'dem_downurl' => I('dem_downurl'),
                'dem_author' => session('u_name'),
                'dem_addtime' => date('Y-m-d H:i:s'),
                'dem_ip' => $_SERVER["REMOTE_ADDR"]


            );
        }else{
        $info = $upload->upload();
        if (!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        } else {// 上传成功 获取上传文件信息
            foreach ($info as $file) {
                $preimgpath= __ROOT__ .'/upload/images/preimg/'. $file['savepath'] . $file['savename'];
            }
        }

        $data = array(
            'dem_title' => I('dem_title'),
            'dem_introduce' => I('dem_introduce'),
            'dem_mark' => I('dem_mark'),
            'dem_type' => I('dem_type'),
            'dem_preurl' => I('dem_preurl'),
            'dem_downurl' => I('dem_downurl'),
            'dem_preimg' => $preimgpath,
            'dem_author' => session('u_name'),
            'dem_addtime' => date('Y-m-d H:i:s'),
            'dem_ip' => $_SERVER["REMOTE_ADDR"]


        );
        }

        if ($demo->where(array('dem_id'=>I('id')))->save($data)) {

            $this->success('更新成功',U('Demomg/demo_all'));
        } else {
            $this->error('更新失败');
        };


    }


    /*删除demo事件处理 从回收站彻底删除*/
    public  function  demo_delete(){
        $demo=M('demo');
        if($demo->where(array('dem_id'=>I('id')))->delete()){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }
    /*丢如回收站*/
    /*启用/禁用事件处理*/
    public  function  demo_status_handle(){
        $demo=M('demo');
        if($demo->where(array('dem_id'=>I('id')))->save(array('dem_status'=>I('status')))){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }
    /*重新排序（处理推荐，置顶，精华等操作）*/
    public  function  demo_resort(){
        $demo=M('demo');
        if($demo->where(array('dem_id'=>I('id')))->save(array('dem_sort'=>I('sort')))){
            $this->success('修改成功',U('Demomg/demo_all'));
        }else{
            $this->error('修改失败',U('Demomg/demo_all'));
        }
    }
/*垃圾桶*/
    public  function  demo_trush(){
        $demo=M('demo');

        $count      = $demo->where('dem_status=0')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $Page->lastSuffix=true;
        $Page->setConfig('header','第<b>%NOW_PAGE%</b>/<b>%TOTAL_PAGE%</b>页&nbsp;&nbsp;共<b>'.$count.'</b>条');
        $Page->setConfig('prev',' &laquo;');
        $Page->setConfig('next','&raquo;');
        $Page->setConfig('last','末');
        $Page->setConfig('first','...%TOTAL_PAGE%');
        $Page->setConfig('theme','  %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');



        $show       = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $demo->where('dem_status=0')->order('dem_sort asc,dem_addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }




}