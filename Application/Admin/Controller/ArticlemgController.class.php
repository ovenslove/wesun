<?php

namespace Admin\Controller;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class ArticlemgController extends CommenController {

    public  function index(){
        $this->display();
    }

/*显示添加文章界面，提供类别选项和标签推荐*/
    public  function  article_add(){
        $admin=M('type');
        $mark=M('mark');
        /*获取分类列表*/
        $clumndata=$admin->where(array('node_type'=>2))->select();
        $clumndata=ordersortlist($clumndata);
        foreach( $clumndata as $val){
            if($val['node_name']=='article'){
                $articledata=$val['childs'];
            }
        }
        /*获取标签列表*/
        $markdata=$mark->select();
        $this->markdata=$markdata;
//        p($markdata);

        $this->articledata=$articledata;
        $this->display();
    }

/*新建文章数据处理*/
    public  function  article_add_handle(){
//        p($_POST);
//        die;
        $article = M('article');
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
                'art_title' => I('art_title'),
                'art_introduce' => I('art_introduce'),
                'art_type' => I('art_type'),
                'art_preimg' => $preimgpath,
                'art_content' => I('editorValue'),
                'art_mark' => I('art_mark'),
                'art_author' => session('u_name'),
                'art_addtime' => date('Y-m-d H:i:s'),
                'art_ip' => $_SERVER["REMOTE_ADDR"]


            );

        if ($article->add($data)) {

            $this->success('发布成功');
        } else {
            $this->error('发布失败');
        };


    }/*article_add_handle*/

/*文章列表*/
    public  function  article_all(){

        $article=M('article');
        $mark=M('mark');

        $count      = $article->where('art_status=1')->count();// 查询满足要求的总记录数
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
        $data = $article->where('art_status=1')->order('art_sort asc,art_addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }


/*文章更新*/
public  function  article_update(){
    $article = M('article');
    $mark=M('mark');
    $this->data=$article->where(array('id'=>I('id')))->find();

    $admin=M('type');
    $clumndata=$admin->where(array('node_type'=>2))->select();
    $clumndata=ordersortlist($clumndata);

    foreach( $clumndata as $val){
        if($val['node_name']=='article'){
            $articledata=$val['childs'];
        }
    }
    $markdata=$mark->select();
    $this->markdata=$markdata;
    $this->articledata=$articledata;

    $this->display();
//    p($data);
}

    /*新建文章数据处理*/
    public  function  article_update_handle(){
      /*  p($_POST);
        p($_FILES);
die;*/
        $article = M('article');
        $upload = new \Think\Upload();// 实例化上传类

        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->saveName = time().'_'.mt_rand();
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './upload/images/preimg/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录

        if($_FILES['preimg']['name']==''){
            $data = array(
                'art_title' => I('art_title'),
                'art_introduce' => I('art_introduce'),
                'art_type' => I('art_type'),
                'art_content' => I('editorValue'),
                'art_mark' => I('art_mark'),
                'art_author' => session('u_name'),
                'art_addtime' => date('Y-m-d H:i:s'),
                'art_ip' => $_SERVER["REMOTE_ADDR"]

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
                'art_title' => I('art_title'),
                'art_introduce' => I('art_introduce'),
                'art_type' => I('art_type'),
                'art_preimg' => $preimgpath,
                'art_content' => I('editorValue'),
                'art_mark' => I('art_mark'),
                'art_author' => session('u_name'),
                'art_addtime' => date('Y-m-d H:i:s'),
                'art_ip' => $_SERVER["REMOTE_ADDR"]


            );
        }



        if ($article->where(array('id'=>I('id')))->save($data)) {

            $this->success('更新成功',U('Articlemg/article_all'));
        } else {
            $this->error('更新失败');
        };


    }/*article_update_handle*/

/*删除文章事件处理*/
    public  function  article_delete(){
        $article=M('article');
        if($article->where(array('id'=>I('id')))->delete()){
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }

    }

    /*启用/禁用事件处理*/
    public  function  article_status_handle(){
        $article=M('article');
        if($article->where(array('id'=>I('id')))->save(array('art_status'=>I('status')))){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }

    public  function  article_resort(){
        $article=M('article');
        if($article->where(array('id'=>I('id')))->save(array('art_sort'=>I('sort')))){
            $this->success('修改成功',U('Articlemg/article_all'));
        }else{
            $this->error('修改失败',U('Articlemg/article_all'));
        }
    }


    public  function  article_trush(){
        $article=M('article');

        $count      = $article->where('art_status=0')->count();// 查询满足要求的总记录数
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
        $data = $article->where('art_status=0')->order('art_sort asc,art_addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();

        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }







/*classend*/
}