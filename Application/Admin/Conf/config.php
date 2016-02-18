<?php
return array(
	//'配置项'=>'配置值'
    'ASO_KEY'=>'a66abb5684c45962d887564f08346e8d',//admin123456

    'RBAC_SUPERADMIN'=>'wenwen',/*超级管理员名称*/
    'ADMIN_AUTH_KEY'=> 'superadmin',  //超级管理员识别号
    'USER_AUTH_ON'=> true,  //是否开启认证
    'USER_AUTH_TYPE'=> 1,   //认证类型（1：登录认证 2：及时认证）
    'USER_AUTH_KEY' =>'uid',//用户识别号
    'NOT_AUTH_MODULE'=>'Login,Home,Index', //无需验证的控制器
    'NOT_AUTH_ACTION'=>'user_add_handle,node_update_handle,node_add_handle,role_add_handle', //无需验证的方法
    'RBAC_ROLE_TABLE'=>'sun_role', //角色表
    'RBAC_USER_TABLE'=>'sun_role_user',//用户角色关系表
    'RBAC_ACCESS_TABLE'=>'sun_access',//权限表
    'RBAC_NODE_TABLE'=>'sun_node',//节点表
);