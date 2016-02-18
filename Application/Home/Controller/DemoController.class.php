<?php
namespace Home\Controller;
use Think\Controller;
class DemoController extends Controller {

    public function index(){

        $article=M('demo');
        $mark=M('mark');

        $count      = $article->where('dem_status=1')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,12);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $Page->lastSuffix=true;
        $Page->setConfig('header','第<b>%NOW_PAGE%</b>/<b>%TOTAL_PAGE%</b>页&nbsp;&nbsp;共<b>'.$count.'</b>条');
        $Page->setConfig('prev',' &laquo;');
        $Page->setConfig('next','&raquo;');
        $Page->setConfig('last','末');
        $Page->setConfig('first','...%TOTAL_PAGE%');
        $Page->setConfig('theme','  %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');



        $show       = $Page->show();// 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $data = $article->where('dem_status=1')->order('dem_sort asc,dem_addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();


        foreach( $data as $key=> $val){
            $data[$key]['dem_mark']=explode(',',$val['dem_mark']);
        }
        /*查询mark标签*/
        $this->mark=$mark->select();

        $this->assign('data',$data);// 赋值数据集*/
        $this->assign('page',$show);// 赋值分页输出*/
        $this->display();
    }

    public  function  demo_show(){

        $demo=M('demo');
        $demodata=$demo->where(array('dem_id'=>I('id')))->find();
        $this->demo=$demodata;
        $this->display();
    }

    public  function  demo_preview(){
        $this->display();
    }


}