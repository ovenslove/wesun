<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {

    public function index(){
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


        foreach( $data as $key=> $val){
            $data[$key]['art_mark']=explode(',',$val['art_mark']);
        }
        /*查询mark标签*/
        $this->mark=$mark->select();
        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }



    public function article(){
        if(!IS_GET){

        }else{
            $article=M('article');
            $id=I('art_id');
            $article->where('id='.$id)->setInc('art_readcount');

            $preid=-1;
            $pretitle='';
            $nextid=-1;
            $nexttitle='';
            $art=$article->where(array('id'=>I('art_id')))->find();
            $allart=$article->where(array('art_status'=>1))->order('art_sort asc,art_addtime desc')->select();
            foreach( $allart as $key=>$value ){
                if($value['id']==$id){
                    if($key-1>=0){
                        $preid=$allart[$key-1]['id'];
                        $pretitle=$allart[$key-1]['art_title'];
                    }
                    if($key+1<sizeof($allart)){
                        $nextid=$allart[$key+1]['id'];
                        $nexttitle=$allart[$key+1]['art_title'];
                    }
//                    echo $preid.'=>'.$allart[$key]['id'].'=>'.$nextid."<br/>";
                }

            }

            $this->preid=$preid;
            $this->pretitle=$pretitle;
            $this->nextid=$nextid;
            $this->nexttitle=$nexttitle;


            $this->art=$art;
            $this->display();
        }

    }

    /*markshowarticle 通过mark标签来分类文章*/
    public  function markshowarticle(){
        $art_mark=I('mark');


        $article=M('article');
        $mark=M('mark');

        $count      = $article->where(array('art_status'=>1,'art_mark'=>array('like','%'.$art_mark.'%')))->count();// 查询满足要求的总记录数
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
        $data = $article->where(array('art_status'=>1,'art_mark'=>array('like','%'.$art_mark.'%')))->order('art_sort asc,art_addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();


        foreach( $data as $key=> $val){
            $markarr=explode(',',$val['art_mark']);

            if($art_mark!='' &&!in_array($art_mark,$markarr,true)){

                unset($data[$key]);
            }else{
                $data[$key]['art_mark']=$markarr;
            }
        }
        /*查询mark标签*/
        $this->mark=$mark->select();
        $this->assign('markactive',$art_mark);// 赋值数据集

        $this->assign('data',$data);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('index');
    }
}