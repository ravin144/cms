<?php
//系统公共配置
require_once 'database.php';			//数据库配置
require_once 'system_config.php';		//系统配置
require_once 'map_rules.php';			//静态路由配置
require_once 'route_rules.php';			//动态路由配置
require_once 'site_config.php';			//站点设置

//合并所有公共配置和前台配置
return array_merge($conf_databases, $conf_system, $conf_map_rules, $conf_route_rules, $conf_site);
