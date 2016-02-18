<?php

namespace Admin\Controller;
use Org\Util\Rbac;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class LoginController extends Controller {
    /*登录入口*/
    public function login(){
        $this->display();
    }
    /*登录检测*/
    public function login_check(){
        $u_name=I('username');
        $u_psd=I('userpsd');
        $u_checkcode=I('usercheckcode');
        $admin=M('user');
        $result=$admin->where(array('user_name'=>$u_name))->find();
        if(md5($u_psd)==$result['user_psd'] && check_verify($u_checkcode)){
            $_SESSION['aso_key']=C('ASO_KEY');
            $_SESSION['u_name']=$u_name;

            $_SESSION[C('USER_AUTH_KEY')]=$result['user_id'];
            if($u_name==C('RBAC_SUPERADMIN')){
                $_SESSION[C('ADMIN_AUTH_KEY')]=true;
            }

            import('Org.Util.Rbac');//引入Rbac文件
            Rbac::saveAccessList(); //将权限写入SESSION中

          $this->success('
            <div style="font-size: 30px;"> 登录成功 </div>

            ',U('Index/index'));

        }else{
            $this->error("登录失败，请检查用户名或者验证码");
        }

    }
    /*注销*/
    public  function  login_out(){

        session_unset();//删除内存中的seesion变量
        session_destroy();//删除SESSION文件以及ID
        redirect(U('Login/login'));

    }
    /*新建验证码*/
    public function New_verify(){
        return verify();
    }


}