<?php

namespace Admin\Controller;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class SystemController extends CommenController {


    /*获取MYSQL版本*/
    private function _mysql_version()
    {
        $Model =new \Think\Model();
        $version = $Model->query("select version() as ver");
        return $version[0]['ver'];
    }
    /*系统信息*/
    public function system_info(){
        $age=array("Peter"=>"35","Ben"=>"37","Joe"=>"43");
        $data=array(
            array('name'=>'系统名称','value'=>C('WEB_NAME')),
            array('name'=>'系统版本','value'=>C('WEB_VERSION')),
            array('name'=>'Web服务器','value'=> $_SERVER["SERVER_SOFTWARE"]),
            array('name'=>'系统类型及版本号','value'=>php_uname()),
            array('name'=>'PHP运行方式','value'=>php_sapi_name() ),
            array('name'=>'PHP版本','value'=>PHP_VERSION),
            array('name'=>'MYSQL版本','value'=> $this->_mysql_version()),
            array('name'=>'服务器IP','value'=>GetHostByName($_SERVER['SERVER_NAME'])),
            array('name'=>'客户端IP','value'=>$_SERVER['REMOTE_ADDR']),
            array('name'=>'服务器语言','value'=>$_SERVER['HTTP_ACCEPT_LANGUAGE']),
            array('name'=>'服务器Web端口','value'=>$_SERVER['SERVER_PORT']),
            array('name'=>'最大执行时间','value'=> ini_get("max_execution_time").'秒'),
            array('name'=>'最大上传限制','value'=> ini_get("file_uploads") ? ini_get("upload_max_filesize") : "Disabled"),
            array('name'=>'服务器时间','value'=> date("Y-m-d H:i:s",time())),

        );
        $this->data=$data;
//        p($_SESSION);
        $this->display('system_info');
    }


    /*个人信息*/
    public  function  per_info(){
        $admin=M('user');

        $data1['adm_name']=$_SESSION['u_name'];
        $data=$admin
            ->join('sun_role_user ON  sun_user.user_id  = sun_role_user.user_id')
            ->where(array('sun_user.user_id'=>$_SESSION['uid'],'user_name'=>$_SESSION['u_name']))
            ->join('sun_role ON  sun_role_user.role_id = sun_role.id')
            ->find();
//      p($data);
        $this->data=$data;
        $this->display();
    }
    /*个人信息更新*/
    public  function  per_infoupdate(){
        $admin=M('user');

        if($admin->where(array('user_id'=>$_POST['user_id']))->save($_POST)){
            $this->success('更新成功');
        }else{
            $this->error('更新失败');
        }
    }

    public  function  per_psdup_handle(){
        $admin=M('user');
        $result=$admin->field(array('user_psd'))->where(array('user_id'=>I('user_id')))->find();

        if(md5(I('user_psdold'))==$result['user_psd']){
            if(md5(I('user_psdnew1'))==md5(I('user_psdnew2'))){

                if(md5(I('user_psdold'))==md5(I('user_psdnew1'))){
                    $this->error('两次密码不能相同！');
                }else{
                    $data=array(
                        'user_id'=>I('user_id'),
                        'user_psd'=>md5(I('user_psdnew1'))
                    );
                    if($admin->save($data)){
                        $this->success('密码修改成功');
                    }else{
                        $this->error("密码修改失败！请再试一次！");
                    };
                }
            }else{
                $this->error("两次密码不匹配");
            }
        }else{
            $this->error("原密码错误");
        }
    }



public  function  up_cache(){
    //清文件缓存
    $dirs = array('./Application/Runtime/Cache/');
    @mkdir('Runtime',0777,true);
    //清理缓存
    foreach($dirs as $value) {
        $this->rmdirr($value);
    }
    echo '<h2>缓存更新成功！</h2>';


}


    /*清除缓存函数*/
   public  function rmdirr($dirname) {
        if (!file_exists($dirname)) {
            return false;
        }
        if (is_file($dirname) || is_link($dirname)) {
            return unlink($dirname);
        }
        $dir = dir($dirname);
        if($dir){
            while (false !== $entry = $dir->read()) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                //递归
                $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
            }
        }
        $dir->close();
        return rmdir($dirname);
    }



}