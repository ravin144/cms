<?php
//规则或正则路由
$conf_route_rules = array(
    // 开启路由
    'URL_ROUTER_ON'   => true,

    // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
    // 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  默认为PATHINFO 模式
    'URL_MODEL'       => 2,

    'URL_ROUTE_RULES' => array(
        // 'news/read/:id'          => '/news/:1',
        'category/:cid' => 'Index/category', // 栏目页
        'page/:cid'     => 'Index/page', // 单页
        'show/:cid/:id' => 'Index/show', // 内容页
        'setmap'        => 'Index/setmap', // 站点地图
        'link'          => 'Index/link', // 友情链接
        'search'        => 'Index/search', // 搜索页
    ),
);
