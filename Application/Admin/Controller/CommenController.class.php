<?php

namespace Admin\Controller;
use Org\Util\Rbac;
use PhpParser\Node\Expr\Cast\Array_;
use Think\Controller;
class CommenController extends Controller {
    public function __construct()
    {
        parent::__construct();
        if(!isset($_SESSION[C('USER_AUTH_KEY')])){
            $this->redirect('Admin/Login/login');
        }
       $notAuth= in_array( MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))||
           in_array( ACTION_NAME,explode(',',C('NOT_AUTH_ACTION')));

//        echo ACTION_NAME;
//        echo MODULE_NAME;
//        echo C('NOT_AUTH_MODULE');

        if(C('USER_AUTH_ON') && !$notAuth){

            import('Org.Util.Rbac');
           /* var_dump(Rbac::AccessDecision());*/
            if(!Rbac::AccessDecision()){
               echo '没有权限';
                die;
            };
        }
    }

}