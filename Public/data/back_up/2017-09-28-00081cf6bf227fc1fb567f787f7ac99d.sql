-- ----------------------------
-- 日期：2017-09-28 23:06:27
-- 不适合处理超大量数据
-- ----------------------------

-- ----------------------------
-- Table structure for `dc_admin_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `dc_admin_auth_group`;
CREATE TABLE `dc_admin_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `description` varchar(250) DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  `listorder` int(11) DEFAULT '99',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_admin_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `dc_admin_auth_group_access`;
CREATE TABLE `dc_admin_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_admin_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `dc_admin_auth_rule`;
CREATE TABLE `dc_admin_auth_rule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `attr_id` int(11) DEFAULT '1' COMMENT '级数，1顶部，2左侧顶部，3左侧菜单，4右侧操作',
  `pid` int(8) DEFAULT '0' COMMENT '上级菜单id',
  `name` char(80) DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `condition` char(100) NOT NULL DEFAULT '',
  `listorder` int(11) DEFAULT '999' COMMENT '排序',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `dc_admin_user`;
CREATE TABLE `dc_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` tinyint(3) DEFAULT '0' COMMENT '分组id',
  `user_name` varchar(20) DEFAULT NULL COMMENT '用户名',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `encrypt` varchar(100) DEFAULT NULL COMMENT '加密标示',
  `login_count` int(11) DEFAULT '0' COMMENT '登录次数',
  `last_login_time` char(20) DEFAULT '0' COMMENT '上次登录时间',
  `last_login_ip` char(20) DEFAULT '1.1.1.1' COMMENT '上次登录ip',
  `reg_time` char(20) DEFAULT '1431960099' COMMENT '加入时间',
  PRIMARY KEY (`id`),
  KEY `user_name` (`user_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_category`
-- ----------------------------
DROP TABLE IF EXISTS `dc_category`;
CREATE TABLE `dc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '栏目id',
  `name` varchar(100) DEFAULT NULL COMMENT '栏目名称',
  `enname` varchar(200) DEFAULT NULL COMMENT '栏目英文名称',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级栏目id',
  `parents_id` varchar(150) DEFAULT NULL COMMENT '上级栏目id（以英文逗号隔开，按顺序从高到低）',
  `childs_id` varchar(150) DEFAULT NULL COMMENT '下级栏目id（以英文逗号隔开，按顺序从高到低）',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '栏目类型',
  `model_id` int(11) NOT NULL DEFAULT '1' COMMENT '模型id',
  `cate_tpl` varchar(100) DEFAULT NULL COMMENT '栏目模板',
  `page_tpl` varchar(100) DEFAULT NULL COMMENT '单页模板',
  `show_tpl` varchar(100) DEFAULT NULL COMMENT '内容模板',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示（1：是，0：否）',
  `cate_order` int(11) NOT NULL DEFAULT '999' COMMENT '排序（默认：999）',
  `page_num` tinyint(2) NOT NULL DEFAULT '9' COMMENT '分页（默认：9）',
  `url` varchar(150) DEFAULT NULL COMMENT '栏目链接',
  `route` varchar(150) DEFAULT NULL COMMENT '栏目路由',
  `description` text COMMENT '栏目描述',
  `image` varchar(100) DEFAULT NULL COMMENT '栏目图片',
  `seo_title` varchar(120) DEFAULT NULL COMMENT 'seo标题',
  `seo_keyword` varchar(120) DEFAULT NULL COMMENT 'seo关键字',
  `seo_description` varchar(300) DEFAULT NULL COMMENT 'seo描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_content_article`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_article`;
CREATE TABLE `dc_content_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clone_id` int(11) DEFAULT NULL COMMENT '来源文章id',
  `model_id` int(11) DEFAULT NULL COMMENT '模型id',
  `title` varchar(250) NOT NULL DEFAULT '' COMMENT '标题',
  `seo_title` varchar(120) DEFAULT NULL COMMENT 'SEO标题',
  `seo_keyword` varchar(120) DEFAULT NULL COMMENT 'SEO关键字',
  `seo_description` varchar(300) DEFAULT NULL COMMENT 'SEO描述',
  `url` varchar(120) DEFAULT NULL COMMENT '链接',
  `add_time` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_content_article_banner`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_article_banner`;
CREATE TABLE `dc_content_article_banner` (
  `arc_id` int(11) NOT NULL COMMENT '所属文章id,关联content_article',
  `art_order` int(11) NOT NULL DEFAULT '999' COMMENT '文章排序,默认999',
  `cid` int(11) NOT NULL COMMENT '所属栏目id',
  `img` char(80) DEFAULT NULL,
  `target` varchar(80) DEFAULT NULL,
  `link` varchar(250) DEFAULT NULL,
  UNIQUE KEY `arc_id` (`arc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='banner模型';

-- ----------------------------
-- Table structure for `dc_content_article_class`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_article_class`;
CREATE TABLE `dc_content_article_class` (
  `arc_id` int(11) NOT NULL COMMENT '所属文章id,关联content_article',
  `art_order` int(11) NOT NULL DEFAULT '999' COMMENT '文章排序,默认999',
  `cid` int(11) NOT NULL COMMENT '所属栏目id',
  `desc` varchar(250) DEFAULT NULL,
  `img` char(80) DEFAULT NULL,
  UNIQUE KEY `arc_id` (`arc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='垃圾类别';

-- ----------------------------
-- Table structure for `dc_content_article_default`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_article_default`;
CREATE TABLE `dc_content_article_default` (
  `arc_id` int(11) NOT NULL COMMENT '所属文章id,关联content_article',
  `art_order` int(11) DEFAULT '999' COMMENT '文章排序，默认999',
  `cid` int(11) NOT NULL COMMENT '所属栏目id',
  `description` varchar(300) DEFAULT NULL COMMENT '描述',
  `thumb` varchar(100) DEFAULT NULL COMMENT '缩略图',
  `content` longtext COMMENT '内容',
  `desc` varchar(250) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `longdesc` text NOT NULL,
  `img` char(80) DEFAULT NULL,
  UNIQUE KEY `arc_id` (`arc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻模型';

-- ----------------------------
-- Table structure for `dc_content_article_news`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_article_news`;
CREATE TABLE `dc_content_article_news` (
  `arc_id` int(11) NOT NULL COMMENT '所属文章id,关联content_article',
  `art_order` int(11) NOT NULL DEFAULT '999' COMMENT '文章排序,默认999',
  `cid` int(11) NOT NULL COMMENT '所属栏目id',
  `desc` varchar(250) DEFAULT NULL,
  `img` char(80) DEFAULT NULL,
  UNIQUE KEY `arc_id` (`arc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='新闻模型';

-- ----------------------------
-- Table structure for `dc_content_model`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_model`;
CREATE TABLE `dc_content_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(50) DEFAULT NULL COMMENT '模型名称',
  `table_name` varchar(50) DEFAULT NULL COMMENT '模型表名',
  `model_description` varchar(250) DEFAULT '' COMMENT '模型描述',
  `list_count` int(11) DEFAULT '0' COMMENT '数据量',
  `add_time` int(11) DEFAULT NULL COMMENT '添加日期',
  `update_time` int(11) DEFAULT NULL COMMENT '更新日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_name` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_content_model_field`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_model_field`;
CREATE TABLE `dc_content_model_field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` int(11) DEFAULT NULL COMMENT '所属模型id',
  `field_name` varchar(50) DEFAULT NULL COMMENT '字段名称',
  `field_title` varchar(50) DEFAULT NULL COMMENT '字段名',
  `field_tip` varchar(300) DEFAULT NULL COMMENT '字段提示',
  `input_type` varchar(30) DEFAULT NULL COMMENT '字段类型，主要用于后台显示，比如是单选，多选，单行，多行等',
  `field_type` varchar(50) DEFAULT NULL COMMENT '表字段类型，存储在数据库的字段类型',
  `field_length` int(11) DEFAULT '10' COMMENT '表字段类型长度',
  `field_options` text COMMENT '字段值，主要用于选项类',
  `default_value` varchar(300) DEFAULT NULL COMMENT '默认值',
  `is_unique` tinyint(1) DEFAULT '0' COMMENT '是否唯一',
  `is_must` tinyint(1) DEFAULT '0' COMMENT '是否必填',
  `preg_params` varchar(100) DEFAULT NULL COMMENT '匹配正则',
  `error_msg` varchar(250) DEFAULT NULL COMMENT '错误提示',
  `is_disabled` tinyint(1) DEFAULT '0' COMMENT '是否禁用',
  `is_system` tinyint(1) DEFAULT '0' COMMENT '是否是系统默认',
  `listorder` tinyint(3) DEFAULT '99' COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `model_id` (`model_id`),
  KEY `field_name` (`field_name`),
  KEY `listorder` (`listorder`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_database`
-- ----------------------------
DROP TABLE IF EXISTS `dc_database`;
CREATE TABLE `dc_database` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `addtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='数据库';

-- ----------------------------
-- Table structure for `dc_system_setting`
-- ----------------------------
DROP TABLE IF EXISTS `dc_system_setting`;
CREATE TABLE `dc_system_setting` (
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `value` text COMMENT '值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_upload_file`
-- ----------------------------
DROP TABLE IF EXISTS `dc_upload_file`;
CREATE TABLE `dc_upload_file` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attr` varchar(15) DEFAULT '' COMMENT '分类',
  `url` varchar(100) DEFAULT NULL COMMENT '地址',
  `title` varchar(250) DEFAULT NULL COMMENT '名称',
  `type` varchar(10) DEFAULT NULL COMMENT '类型',
  `size` int(11) DEFAULT NULL COMMENT '大小',
  `mtime` int(11) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `attr` (`attr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_upload_image`
-- ----------------------------
DROP TABLE IF EXISTS `dc_upload_image`;
CREATE TABLE `dc_upload_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `attr` varchar(15) DEFAULT '' COMMENT '分类',
  `url` varchar(100) DEFAULT NULL COMMENT '地址',
  `title` varchar(250) DEFAULT NULL COMMENT '图片名称',
  `type` varchar(10) DEFAULT NULL COMMENT '类型',
  `size` int(11) DEFAULT NULL COMMENT '大小',
  `mtime` int(11) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`),
  KEY `attr` (`attr`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records for `dc_admin_auth_group`
-- ----------------------------
INSERT INTO `dc_admin_auth_group` VALUES ('1', '超级管理员', '超级管理员不能修改或删除，可以执行后台所有操作', '1', '0', '1');
INSERT INTO `dc_admin_auth_group` VALUES ('2', '内容管理组', '', '1', '1,5,38,40,39,2,20,21,24,25,35,36,22,26,27,37,23,28,29,30,31,32,33,34', '2');

-- ----------------------------
-- Records for `dc_admin_auth_rule`
-- ----------------------------
INSERT INTO `dc_admin_auth_rule` VALUES ('1', '1', '0', 'nav1466237235', '系统设置', '1', '1', '', '1');
INSERT INTO `dc_admin_auth_rule` VALUES ('2', '1', '0', 'nav1466237236', '内容管理', '1', '1', '', '2');
INSERT INTO `dc_admin_auth_rule` VALUES ('3', '1', '0', 'nav1466237267', '用户管理', '1', '0', '', '3');
INSERT INTO `dc_admin_auth_rule` VALUES ('4', '1', '0', 'nav1466237277', '商城管理', '1', '0', '', '4');
INSERT INTO `dc_admin_auth_rule` VALUES ('5', '2', '1', 'nav1466242075', '系统设置', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('6', '3', '5', 'Admin/AdminAuthRule/nav', '后台菜单管理', '1', '1', '', '1');
INSERT INTO `dc_admin_auth_rule` VALUES ('7', '4', '6', 'Admin/AdminAuthRule/navAdd', '添加菜单', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('8', '4', '6', 'Admin/AdminAuthRule/navUpdate', '编辑菜单', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('9', '4', '6', 'Admin/AdminAuthRule/navDelete', '删除菜单', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('10', '4', '6', 'Admin/AdminAuthRule/updateNavCache', '更新菜单缓存', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('11', '2', '1', 'nav1466415484', '管理员管理', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('12', '3', '11', 'Admin/AdminUser/group', '管理组及权限', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('13', '4', '12', 'Admin/AdminUser/groupAdd', '添加管理组', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('14', '4', '12', 'Admin/AdminUser/groupUpdate', '编辑管理组', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('15', '4', '12', 'Admin/AdminUser/groupDelete', '删除管理组', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('16', '3', '11', 'Admin/AdminUser/index', '管理员管理', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('17', '4', '16', 'Admin/AdminUser/add', '添加管理员', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('18', '4', '16', 'Admin/AdminUser/update', '编辑管理员', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('19', '4', '16', 'Admin/AdminUser/delete', '删除管理员', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('20', '2', '2', '', '管理内容', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('21', '3', '20', 'Admin/Article/index', '文章管理', '1', '1', '', '3');
INSERT INTO `dc_admin_auth_rule` VALUES ('22', '3', '20', 'Admin/Content/category', '文章分类', '1', '1', '', '2');
INSERT INTO `dc_admin_auth_rule` VALUES ('23', '3', '20', 'Admin/ContentModel/index', '模型管理', '1', '1', '', '1');
INSERT INTO `dc_admin_auth_rule` VALUES ('24', '4', '21', 'Admin/Article/cateList', '栏目分类树', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('25', '4', '21', 'Admin/Article/add', '添加文章', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('26', '4', '22', 'Admin/Content/categoryAdd', '添加分类', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('27', '4', '22', 'Admin/Content/categoryUpdate', '编辑分类', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('28', '4', '23', 'Admin/ContentModel/add', '新增模型', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('29', '4', '23', 'Admin/ContentModel/update', '编辑模型', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('30', '4', '23', 'Admin/ContentModel/delete', '删除模型', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('31', '4', '23', 'Admin/ContentModel/field', '字段管理', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('32', '4', '23', 'Admin/ContentModel/fieldAdd', '新增字段', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('33', '4', '23', 'Admin/ContentModel/fieldUpdate', '编辑字段', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('34', '4', '23', 'Admin/ContentModel/fieldDelete', '删除字段', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('35', '4', '21', 'Admin/Article/update', '编辑文章', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('36', '4', '21', 'Admin/Article/arcList', '文章列表', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('37', '4', '22', 'Admin/Content/categoryDelete', '删除分类', '1', '0', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('38', '3', '5', 'Admin/Database/index', '数据库备份', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('39', '3', '5', 'Admin/Site/index', '站点设置', '1', '1', '', '999');
INSERT INTO `dc_admin_auth_rule` VALUES ('40', '4', '38', 'Admin/Database/db', '数据库备份', '1', '1', '', '999');

-- ----------------------------
-- Records for `dc_admin_user`
-- ----------------------------
INSERT INTO `dc_admin_user` VALUES ('1', '1', 'admin', 'e3ab392241582a9c5023733036886f12', '65a0c', '82', '1506607745', '127.0.0.1', '1431960099');

-- ----------------------------
-- Records for `dc_category`
-- ----------------------------
INSERT INTO `dc_category` VALUES ('1', '首页', '', '0', '0', '1,3,4,5,6', '0', '1', 'cate.html', '', 'show.html', '0', '999', '9', '/Category/1.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('2', '新闻中心', '', '0', '0', '2', '0', '2', 'cate_news.html', '', 'show.html', '1', '999', '9', '/news.html', 'news', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('3', 'banner', '', '1', '0,1', '3', '0', '3', 'cate.html', '', 'show.html', '0', '999', '9', '/Category/3.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('4', '合作伙伴', '', '1', '0,1', '4', '0', '3', 'cate.html', '', 'show.html', '0', '999', '9', '/Category/4.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('5', '模块1', '', '1', '0,1', '5', '0', '1', 'cate.html', '', 'show.html', '1', '999', '9', '/Category/5.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('6', '模块2', '', '1', '0,1', '6', '0', '1', 'cate.html', '', 'show.html', '1', '999', '9', '/Category/6.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('7', '分类攻略', '', '0', '0', '7,11,12,13,14', '0', '4', 'cate_jump_first_category.html', '', 'show.html', '1', '999', '9', '/Category/7.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('8', '覆盖小区', '', '0', '0', '8', '0', '1', 'cate_cover.html', '', 'show.html', '1', '999', '9', '/map.html', 'map', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('9', '成长历程', '', '0', '0', '9', '0', '1', 'cate_course.html', '', 'show.html', '1', '999', '9', '/Category/9.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('10', '我要回收', '', '0', '0', '10', '0', '1', 'cate_recovery.html', '', 'show.html', '1', '999', '9', '/Category/10.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('11', '可回收垃圾', '', '7', '0,7', '11', '0', '4', 'cate_class.html', '', 'show.html', '1', '999', '12', '/Category/11.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('12', '厨余垃圾', '', '7', '0,7', '12', '0', '4', 'cate_class.html', '', 'show.html', '1', '999', '12', '/Category/12.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('13', '有害垃圾', '', '7', '0,7', '13', '0', '4', 'cate_class.html', '', 'show.html', '1', '999', '12', '/Category/13.html', '', '', '', '', '', '');
INSERT INTO `dc_category` VALUES ('14', '其他垃圾', '', '7', '0,7', '14', '0', '4', 'cate_class.html', '', 'show.html', '1', '999', '12', '/Category/14.html', '', '', '', '', '', '');

-- ----------------------------
-- Records for `dc_content_article`
-- ----------------------------
INSERT INTO `dc_content_article` VALUES ('1', '1', '3', '1', '', '', '', '/show/3/1.html', '1506529085');
INSERT INTO `dc_content_article` VALUES ('2', '2', '3', '2', '', '', '', '/show/3/2.html', '1506529205');
INSERT INTO `dc_content_article` VALUES ('3', '3', '3', '3', '', '', '', '/show/3/3.html', '1506529230');
INSERT INTO `dc_content_article` VALUES ('4', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('5', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('6', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('7', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('8', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('9', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('10', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('11', '4', '3', '1', '', '', '', '/show/4/4.html', '1506529452');
INSERT INTO `dc_content_article` VALUES ('12', '12', '1', '自建适合垃圾分类的物流和分拣体系', '', '', '', '/show/5/12.html', '1506529862');
INSERT INTO `dc_content_article` VALUES ('13', '13', '1', '二维码-垃圾的“身份证”', '', '', '', '/show/5/13.html', '1506529877');
INSERT INTO `dc_content_article` VALUES ('14', '14', '1', '覆盖99.9万家庭， 888个小区', '', '', '', '/show/5/14.html', '1506529890');
INSERT INTO `dc_content_article` VALUES ('15', '15', '1', '国内首家垃圾分类服务企业', '', '', '', '/show/5/15.html', '1506529902');
INSERT INTO `dc_content_article` VALUES ('16', '16', '1', '节约石油', '', '', '', '/show/6/16.html', '1506529969');
INSERT INTO `dc_content_article` VALUES ('17', '17', '1', '减少二氧化碳', '', '', '', '/show/6/17.html', '1506529981');
INSERT INTO `dc_content_article` VALUES ('18', '18', '1', '减少砍伐', '', '', '', '/show/6/18.html', '1506529993');
INSERT INTO `dc_content_article` VALUES ('19', '19', '1', '地址&amp;电话', '', '', '', '/show/1/19.html', '1506530427');
INSERT INTO `dc_content_article` VALUES ('20', '20', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/20.html', '1506531286');
INSERT INTO `dc_content_article` VALUES ('21', '21', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/21.html', '1506531293');
INSERT INTO `dc_content_article` VALUES ('22', '22', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/22.html', '1506531299');
INSERT INTO `dc_content_article` VALUES ('23', '20', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/20.html', '1506531286');
INSERT INTO `dc_content_article` VALUES ('24', '21', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/21.html', '1506531293');
INSERT INTO `dc_content_article` VALUES ('25', '22', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/22.html', '1506531299');
INSERT INTO `dc_content_article` VALUES ('26', '20', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/20.html', '1506531286');
INSERT INTO `dc_content_article` VALUES ('27', '21', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/21.html', '1506531293');
INSERT INTO `dc_content_article` VALUES ('28', '22', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/22.html', '1506531299');
INSERT INTO `dc_content_article` VALUES ('29', '20', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/20.html', '1506531286');
INSERT INTO `dc_content_article` VALUES ('30', '21', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/21.html', '1506531293');
INSERT INTO `dc_content_article` VALUES ('31', '22', '2', '明天起，提现到微信零钱', '', '', '', '/show/2/22.html', '1506531299');
INSERT INTO `dc_content_article` VALUES ('32', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('33', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('34', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('35', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('36', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('37', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('38', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('39', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('40', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('41', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('42', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('43', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('44', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('45', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('46', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('47', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('48', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('49', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('50', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('51', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('52', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('53', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('54', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('55', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('56', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('57', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('58', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('59', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('60', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('61', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('62', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('63', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('64', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('65', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('66', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('67', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('68', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('69', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('70', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('71', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('72', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('73', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('74', '32', '4', 'T恤', '', '', '', '/show/11/32.html', '1506609881');
INSERT INTO `dc_content_article` VALUES ('75', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('76', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('77', '33', '4', 'U盘', '', '', '', '/show/11/33.html', '1506609899');
INSERT INTO `dc_content_article` VALUES ('78', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('79', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('80', '34', '4', '安全帽', '', '', '', '/show/11/34.html', '1506609920');
INSERT INTO `dc_content_article` VALUES ('81', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('82', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('83', '35', '4', '扳手', '', '', '', '/show/11/35.html', '1506609938');
INSERT INTO `dc_content_article` VALUES ('84', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('85', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('86', '36', '4', '报纸', '', '', '', '/show/11/36.html', '1506609955');
INSERT INTO `dc_content_article` VALUES ('87', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('88', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('89', '37', '4', '笔记本电脑', '', '', '', '/show/11/37.html', '1506609975');
INSERT INTO `dc_content_article` VALUES ('90', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('91', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('92', '38', '4', '不锈钢拖把', '', '', '', '/show/11/38.html', '1506610003');
INSERT INTO `dc_content_article` VALUES ('93', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('94', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('95', '39', '4', '玻璃茶杯', '', '', '', '/show/11/39.html', '1506610020');
INSERT INTO `dc_content_article` VALUES ('96', '96', '1', '美丽家园推出手机APP', '', '', '', '/show/9/96.html', '1506610797');
INSERT INTO `dc_content_article` VALUES ('97', '97', '1', '美丽家园走进社区', '', '', '', '/show/9/97.html', '1506610876');
INSERT INTO `dc_content_article` VALUES ('98', '98', '1', '我国目前垃圾分类回收的情况', '', '', '', '/show/9/98.html', '1506610908');
INSERT INTO `dc_content_article` VALUES ('99', '99', '1', '甘肃美丽家园环保科技服务有限公司', '', '', '', '/show/9/99.html', '1506610929');
INSERT INTO `dc_content_article` VALUES ('100', '100', '1', '我国目前垃圾分类回收的情况', '', '', '', '/show/9/100.html', '1506610946');

-- ----------------------------
-- Records for `dc_content_article_banner`
-- ----------------------------
INSERT INTO `dc_content_article_banner` VALUES ('1', '999', '3', '/Public/uploads/editor/image/2017-09-28/59cbcf131b0d1.jpg', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('2', '999', '3', '/Public/uploads/editor/image/2017-09-28/59cbcf132dd99.jpg', '1', 'http://www.bilibili.com/');
INSERT INTO `dc_content_article_banner` VALUES ('3', '999', '3', '/Public/uploads/editor/image/2017-09-28/59cbcf1341231.jpg', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('4', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('5', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('6', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('7', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('8', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('9', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('10', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');
INSERT INTO `dc_content_article_banner` VALUES ('11', '999', '4', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', '1', '');

-- ----------------------------
-- Records for `dc_content_article_class`
-- ----------------------------
INSERT INTO `dc_content_article_class` VALUES ('32', '999', '11', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('33', '999', '11', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('34', '999', '11', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('35', '999', '11', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('36', '999', '11', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('37', '999', '11', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('38', '999', '11', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('39', '999', '11', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('40', '999', '11', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('41', '999', '12', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('42', '999', '13', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('43', '999', '14', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('44', '999', '11', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('45', '999', '12', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('46', '999', '13', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('47', '999', '14', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('48', '999', '11', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('49', '999', '12', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('50', '999', '13', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('51', '999', '14', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('52', '999', '11', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('53', '999', '12', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('54', '999', '13', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('55', '999', '14', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('56', '999', '11', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('57', '999', '12', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('58', '999', '13', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('59', '999', '14', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('60', '999', '11', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('61', '999', '12', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('62', '999', '13', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('63', '999', '14', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('64', '999', '11', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('65', '999', '12', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('66', '999', '13', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('67', '999', '14', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('68', '999', '11', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('69', '999', '12', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('70', '999', '13', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('71', '999', '14', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('72', '999', '12', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('73', '999', '13', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('74', '999', '14', '符合二次穿用的衣物回收消毒用于国内销售或出口非洲，不符合二次穿用的衣物回收再生成工业布料。', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png');
INSERT INTO `dc_content_article_class` VALUES ('75', '999', '12', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('76', '999', '13', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('77', '999', '14', 'U盘里含有多种电子元件，可以拆分后进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png');
INSERT INTO `dc_content_article_class` VALUES ('78', '999', '12', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('79', '999', '13', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('80', '999', '14', '安全帽一般外壳是塑料、内里填充海绵、泡沫、棉布等，经过拆解后可以回收塑料外壳。', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png');
INSERT INTO `dc_content_article_class` VALUES ('81', '999', '12', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('82', '999', '13', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('83', '999', '14', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png');
INSERT INTO `dc_content_article_class` VALUES ('84', '999', '12', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('85', '999', '13', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('86', '999', '14', '报纸作为可回收废纸的一种，经过分选、制浆和脱墨等工序，能再生为新的纸品。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png');
INSERT INTO `dc_content_article_class` VALUES ('87', '999', '12', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('88', '999', '13', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('89', '999', '14', '笔记本电脑等废旧电子产品经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png');
INSERT INTO `dc_content_article_class` VALUES ('90', '999', '12', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('91', '999', '13', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('92', '999', '14', '冰箱等废旧电器经过拆解可以获得电子元件，可以回收。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png');
INSERT INTO `dc_content_article_class` VALUES ('93', '999', '12', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('94', '999', '13', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');
INSERT INTO `dc_content_article_class` VALUES ('95', '999', '14', '扳手由金属制成，金属制品会送到制铁厂进行回收利用。', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png');

-- ----------------------------
-- Records for `dc_content_article_default`
-- ----------------------------
INSERT INTO `dc_content_article_default` VALUES ('12', '999', '5', '', '', '', '通过自建垃圾分类物流和分拣体系，保证高效的垃圾分类投放、分类运输以及分类处置', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('13', '999', '5', '', '', '', '为每位用户提供二维码作为垃圾的“身份证”，让每袋垃圾可以溯源，每次分类投递有回馈', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('14', '999', '5', '', '', '', '截至 2017年 9月，绿色地球已覆盖成都市99.9 万家庭， 888 个小区，总共回收9.99 万吨可回收物', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('15', '999', '5', '', '', '', '美好家园成立于2008年，是国内首家提供垃圾分类全生态服务的企业', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('16', '999', '6', '', '', '', '100000 棵', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('17', '999', '6', '', '', '', '60000 棵', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('18', '999', '6', '', '', '', '80000 棵', '', '', '', '');
INSERT INTO `dc_content_article_default` VALUES ('19', '999', '1', '', '', '', '', '地址：甘肃省拉兰州市城关区临夏路57号沛丰大厦西区8楼066室-099', '电话：13919270017', '', '');
INSERT INTO `dc_content_article_default` VALUES ('96', '999', '9', '', '', '', '', '', '', '&lt;p&gt;如今中国生活垃圾一般可分为四大类：可回收垃圾、厨余垃圾、有害垃圾和其他垃圾。目前常用的垃圾处理方法主要有综合利用、卫生填埋、焚烧和堆肥。&lt;/p&gt;&lt;p&gt;可回收垃圾主要包括废纸、塑料、玻璃、金属和布料五大类。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '/Public/uploads/editor/image/2017-09-28/59cd0ea1a4fd8.jpg');
INSERT INTO `dc_content_article_default` VALUES ('98', '999', '9', '', '', '', '', '', '', '&lt;p&gt;如今中国生活垃圾一般可分为四大类：可回收垃圾、厨余垃圾、有害垃圾和其他垃圾。目前常用的垃圾处理方法主要有综合利用、卫生填埋、焚烧和堆肥。&lt;/p&gt;&lt;p&gt;可回收垃圾主要包括废纸、塑料、玻璃、金属和布料五大类。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '/Public/uploads/editor/image/2017-09-28/59cd0ea184080.jpg');
INSERT INTO `dc_content_article_default` VALUES ('97', '999', '9', '', '', '', '', '', '', '&lt;p&gt;甘肃美丽家园环保科技服务有限公司于2004年10-30日成立&lt;/p&gt;', '/Public/uploads/editor/image/2017-09-28/59cd0ea191758.jpg');
INSERT INTO `dc_content_article_default` VALUES ('99', '999', '9', '', '', '', '', '', '', '&lt;p&gt;甘肃美丽家园环保科技服务有限公司于2004年10-30日成立&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '/Public/uploads/editor/image/2017-09-28/59cd0ea15f2a8.jpg');
INSERT INTO `dc_content_article_default` VALUES ('100', '999', '9', '', '', '', '', '', '', '&lt;p&gt;如今中国生活垃圾一般可分为四大类：可回收垃圾、厨余垃圾、有害垃圾和其他垃圾。目前常用的垃圾处理方法主要有综合利用、卫生填埋、焚烧和堆肥。&lt;/p&gt;&lt;p&gt;可回收垃圾主要包括废纸、塑料、玻璃、金属和布料五大类。&lt;/p&gt;&lt;p&gt;&lt;br/&gt;&lt;/p&gt;', '/Public/uploads/editor/image/2017-09-28/59cd0ea14c5e0.jpg');

-- ----------------------------
-- Records for `dc_content_article_news`
-- ----------------------------
INSERT INTO `dc_content_article_news` VALUES ('20', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4e08f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('21', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4ee7a1.jpg');
INSERT INTO `dc_content_article_news` VALUES ('22', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d50d9f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('23', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4e08f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('24', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4ee7a1.jpg');
INSERT INTO `dc_content_article_news` VALUES ('25', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d50d9f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('26', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4e08f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('27', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4ee7a1.jpg');
INSERT INTO `dc_content_article_news` VALUES ('28', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d50d9f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('29', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4e08f9.jpg');
INSERT INTO `dc_content_article_news` VALUES ('30', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d4ee7a1.jpg');
INSERT INTO `dc_content_article_news` VALUES ('31', '999', '2', '每一颗星星都是可回收垃圾，我们一起打捞它。', '/Public/uploads/editor/image/2017-09-28/59cbd7d50d9f9.jpg');

-- ----------------------------
-- Records for `dc_content_model`
-- ----------------------------
INSERT INTO `dc_content_model` VALUES ('1', '默认模型', 'content_article_default', '系统默认模型，不可删除', '0', '1469167856', '1496285330');
INSERT INTO `dc_content_model` VALUES ('2', '新闻模型', 'content_article_news', '新闻模型', '0', '1496286233', '1496286233');
INSERT INTO `dc_content_model` VALUES ('3', 'banner模型', 'content_article_banner', 'banner模型', '0', '1496287584', '1496287584');
INSERT INTO `dc_content_model` VALUES ('4', '垃圾类别', 'content_article_class', '', '0', '1506609749', '1506609749');

-- ----------------------------
-- Records for `dc_content_model_field`
-- ----------------------------
INSERT INTO `dc_content_model_field` VALUES ('1', '1', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');
INSERT INTO `dc_content_model_field` VALUES ('2', '2', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');
INSERT INTO `dc_content_model_field` VALUES ('3', '3', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');
INSERT INTO `dc_content_model_field` VALUES ('4', '3', 'img', '图片', '', 'image', 'CHAR', '80', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('5', '3', 'target', '是否新窗口', '', 'input_radio', 'VARCHAR', '80', '1|是
0|否', '1', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('6', '3', 'link', '链接', '', 'input_text', 'VARCHAR', '250', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('7', '1', 'desc', '描述', '', 'textarea', 'VARCHAR', '250', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('8', '1', 'address', '地址', '', 'input_text', 'VARCHAR', '250', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('9', '1', 'phone', '电话', '', 'input_text', 'VARCHAR', '250', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('10', '2', 'desc', '描述', '', 'textarea', 'VARCHAR', '250', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('11', '2', 'img', '缩略图', '', 'image', 'CHAR', '80', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('12', '4', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');
INSERT INTO `dc_content_model_field` VALUES ('13', '4', 'desc', '描述', '', 'textarea', 'VARCHAR', '250', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('14', '4', 'img', '缩略图', '', 'image', 'CHAR', '80', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('15', '1', 'longdesc', '长描述', '', 'editor_short', 'TEXT', '', '', '', '0', '0', '', '', '0', '0', '99');
INSERT INTO `dc_content_model_field` VALUES ('16', '1', 'img', '缩略图', '', 'image', 'CHAR', '80', '', '', '0', '0', '', '', '0', '0', '99');

-- ----------------------------
-- Records for `dc_database`
-- ----------------------------
INSERT INTO `dc_database` VALUES ('1', '2017-09-26-0a91d975625da8ea3c9fdd91fcf61542.sql', '1506429465');
INSERT INTO `dc_database` VALUES ('2', '2017-09-28-00081cf6bf227fc1fb567f787f7ac99d.sql', '1506611187');

-- ----------------------------
-- Records for `dc_system_setting`
-- ----------------------------
INSERT INTO `dc_system_setting` VALUES ('WEB_NAME', '美好家园');
INSERT INTO `dc_system_setting` VALUES ('WEB_ENNAME', '');
INSERT INTO `dc_system_setting` VALUES ('SEARCH_WORD', '');
INSERT INTO `dc_system_setting` VALUES ('WEB_COPYRIGHT', 'Copyright © 017 甘肃美好家园环保科技服务有限公司 378897932@QQ.COM');
INSERT INTO `dc_system_setting` VALUES ('META_KEYWORD', '美好家园');
INSERT INTO `dc_system_setting` VALUES ('META_DESCRIPTION', '');
INSERT INTO `dc_system_setting` VALUES ('QQ_IMAGE', '');
INSERT INTO `dc_system_setting` VALUES ('QQ_IMAGE_ALT', '');
INSERT INTO `dc_system_setting` VALUES ('WEIXIN_IMAGE', '/Public/uploads/editor/image/2017-09-28/59cbd4ebdb309.jpg');
INSERT INTO `dc_system_setting` VALUES ('WEIXIN_IMAGE_ALT', '2wh.jpg');
INSERT INTO `dc_system_setting` VALUES ('SMTP_HOST', 'smtp.163.com');
INSERT INTO `dc_system_setting` VALUES ('SMTP_PORT', '465');
INSERT INTO `dc_system_setting` VALUES ('SMTP_USER', 'docan1234@163.com');
INSERT INTO `dc_system_setting` VALUES ('SMTP_PASS', 'docan1477');
INSERT INTO `dc_system_setting` VALUES ('FROM_NAME', 'ravin');
INSERT INTO `dc_system_setting` VALUES ('WEB_MAIL', '390673982@qq.com');
INSERT INTO `dc_system_setting` VALUES ('FROM_EMAIL', 'docan1234@163.com');

-- ----------------------------
-- Records for `dc_upload_image`
-- ----------------------------
INSERT INTO `dc_upload_image` VALUES ('1', 'default', '/Public/uploads/editor/image/2017-09-28/59cbcf131b0d1.jpg', 'banner.jpg', 'jpg', '95203', '1506529043');
INSERT INTO `dc_upload_image` VALUES ('2', 'default', '/Public/uploads/editor/image/2017-09-28/59cbcf132dd99.jpg', '59cbcf132dd99.jpg', 'jpg', '205700', '1506529043');
INSERT INTO `dc_upload_image` VALUES ('3', 'default', '/Public/uploads/editor/image/2017-09-28/59cbcf1341231.jpg', '59cbcf1341231.jpg', 'jpg', '257473', '1506529043');
INSERT INTO `dc_upload_image` VALUES ('4', 'default', '/Public/uploads/editor/image/2017-09-28/59cbd0a73df69.png', 'l2s.png', 'png', '4544', '1506529447');
INSERT INTO `dc_upload_image` VALUES ('5', 'default', '/Public/uploads/editor/image/2017-09-28/59cbd4ebdb309.jpg', '2wh.jpg', 'jpg', '12586', '1506530539');
INSERT INTO `dc_upload_image` VALUES ('6', 'default', '/Public/uploads/editor/image/2017-09-28/59cbd7d4e08f9.jpg', 'h2.jpg', 'jpg', '5848', '1506531284');
INSERT INTO `dc_upload_image` VALUES ('7', 'default', '/Public/uploads/editor/image/2017-09-28/59cbd7d4ee7a1.jpg', '59cbd7d4ee7a1.jpg', 'jpg', '61634', '1506531284');
INSERT INTO `dc_upload_image` VALUES ('8', 'default', '/Public/uploads/editor/image/2017-09-28/59cbd7d50d9f9.jpg', '59cbd7d50d9f9.jpg', 'jpg', '68708', '1506531285');
INSERT INTO `dc_upload_image` VALUES ('9', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad058930.png', 'cl1.png', 'png', '7398', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('10', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad06e4d8.png', '59cd0ad06e4d8.png', 'png', '17464', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('11', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad07cb50.png', '59cd0ad07cb50.png', 'png', '12787', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('12', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad0907b8.png', '59cd0ad0907b8.png', 'png', '4028', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('13', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad0a28c8.png', '59cd0ad0a28c8.png', 'png', '3315', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('14', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad0b0b58.png', '59cd0ad0b0b58.png', 'png', '18038', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('15', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad0be230.png', '59cd0ad0be230.png', 'png', '12334', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('16', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ad0cefb8.png', '59cd0ad0cefb8.png', 'png', '2809', '1506609872');
INSERT INTO `dc_upload_image` VALUES ('17', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ea14c5e0.jpg', '59cd0ea14c5e0.jpg', 'jpg', '15142', '1506610849');
INSERT INTO `dc_upload_image` VALUES ('18', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ea15f2a8.jpg', '59cd0ea15f2a8.jpg', 'jpg', '47353', '1506610849');
INSERT INTO `dc_upload_image` VALUES ('19', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ea184080.jpg', '59cd0ea184080.jpg', 'jpg', '31710', '1506610849');
INSERT INTO `dc_upload_image` VALUES ('20', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ea191758.jpg', '59cd0ea191758.jpg', 'jpg', '27626', '1506610849');
INSERT INTO `dc_upload_image` VALUES ('21', 'default', '/Public/uploads/editor/image/2017-09-28/59cd0ea1a4fd8.jpg', '59cd0ea1a4fd8.jpg', 'jpg', '17610', '1506610849');

