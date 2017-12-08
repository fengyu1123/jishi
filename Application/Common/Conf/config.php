<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '127.0.0.1', // 服务器地址
	'DB_NAME'   => 'prize', // 数据库名
	'DB_USER'   => 'root', // 用户名
	'DB_PWD'    => '', // 密码
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	//url访问模式为rewrite模式
	'URL_MODEL'=>'2',
    /*允许访问模块*/
    'MODULE_ALLOW_LIST'    =>    array('Home','Smi'),
    //开启伪静态
    // 'URL_HTML_SUFFIX'=>'html|shmtl|xml', // 多个用 | 分割
    'URL_HTML_SUFFIX' =>'.html',
    //Auth配置
    'AUTH_CONFIG' => array(
		'AUTH_ON' => true, //认证开关
		'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
		'AUTH_GROUP' => 'qing_auth_group', //用户组数据表名
		'AUTH_GROUP_ACCESS' => 'qing_auth_group_access', //用户组明细表
		'AUTH_RULE' => 'qing_auth_rule', //权限规则表
		'AUTH_USER' => 'qing_user'//用户信息表
	)
);