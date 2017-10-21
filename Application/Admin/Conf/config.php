<?php
return array(
    //'配置项'=>'配置值'
    'LAYOUT_ON'       => true,
    'editor_img_attr' => array(
        //编辑器上传文件分类，如果没有为默认，主要用于搜索管理图片和文件，格式'方法名'=>'说明文字'例如：'article'=>'文档图片'
    ),
    'AUTH_CONFIG'     => array(
        'AUTH_ON'           => true, //认证开关
        'AUTH_TYPE'         => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP'        => C('DB_PREFIX') . 'admin_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => C('DB_PREFIX') . 'admin_auth_group_access', //用户组明细表
        'AUTH_RULE'         => C('DB_PREFIX') . 'admin_auth_rule', //权限规则表
        'AUTH_USER'         => C('DB_PREFIX') . 'admin_user', //用户信息表
    ),

);
