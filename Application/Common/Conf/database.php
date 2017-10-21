<?php
$conf_databases = array(
    /* 数据库设置 */
    'db_type'         => 'mysql', // 数据库类型
    'db_host'         => 'localhost', // 服务器地址
    'db_name'         => 'new', // 数据库名
    'db_user'         => 'root', // 用户名
    'db_pwd'          => '', // 密码
    'db_port'         => '3306', // 端口
    'db_prefix'       => 'dc_', // 数据库表前缀
    'db_params'       => array(), // 数据库连接参数
    'db_debug'        => true, // 数据库调试模式 开启后可以记录SQL日志
    'db_fields_cache' => true, // 启用字段缓存
    'db_charset'      => 'utf8', // 数据库编码默认采用utf8
    'db_deploy_type'  => 0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'db_rw_separate'  => false, // 数据库读写是否分离 主从式有效
    'db_master_num'   => 1, // 读写分离后 主服务器数量
    'db_slave_on'     => '', // 指定从服务器序号
);
