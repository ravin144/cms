-- ----------------------------
-- 日期：2017-09-26 20:37:45
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_content_article_banner`
-- ----------------------------
DROP TABLE IF EXISTS `dc_content_article_banner`;
CREATE TABLE `dc_content_article_banner` (
  `arc_id` int(11) NOT NULL COMMENT '所属文章id,关联content_article',
  `art_order` int(11) NOT NULL DEFAULT '999' COMMENT '文章排序,默认999',
  `cid` int(11) NOT NULL COMMENT '所属栏目id',
  UNIQUE KEY `arc_id` (`arc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='banner模型';

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `dc_database`
-- ----------------------------
DROP TABLE IF EXISTS `dc_database`;
CREATE TABLE `dc_database` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(100) NOT NULL COMMENT '名称',
  `addtime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='数据库';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
INSERT INTO `dc_admin_user` VALUES ('1', '1', 'admin', 'e3ab392241582a9c5023733036886f12', '65a0c', '80', '1506429021', '127.0.0.1', '1431960099');

-- ----------------------------
-- Records for `dc_content_model`
-- ----------------------------
INSERT INTO `dc_content_model` VALUES ('1', '默认模型', 'content_article_default', '系统默认模型，不可删除', '0', '1469167856', '1496285330');
INSERT INTO `dc_content_model` VALUES ('2', '新闻模型', 'content_article_news', '新闻模型', '0', '1496286233', '1496286233');
INSERT INTO `dc_content_model` VALUES ('3', 'banner模型', 'content_article_banner', 'banner模型', '0', '1496287584', '1496287584');

-- ----------------------------
-- Records for `dc_content_model_field`
-- ----------------------------
INSERT INTO `dc_content_model_field` VALUES ('1', '1', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');
INSERT INTO `dc_content_model_field` VALUES ('2', '2', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');
INSERT INTO `dc_content_model_field` VALUES ('3', '3', 'title', '标题', '', 'input_text', 'VARCHAR', '250', '', '', '0', '1', '*1-250', '', '0', '1', '1');

-- ----------------------------
-- Records for `dc_database`
-- ----------------------------
INSERT INTO `dc_database` VALUES ('1', '2017-09-26-0a91d975625da8ea3c9fdd91fcf61542.sql', '1506429465');

-- ----------------------------
-- Records for `dc_system_setting`
-- ----------------------------
INSERT INTO `dc_system_setting` VALUES ('WEB_NAME', '1');
INSERT INTO `dc_system_setting` VALUES ('WEB_ENNAME', '');
INSERT INTO `dc_system_setting` VALUES ('SEARCH_WORD', '');
INSERT INTO `dc_system_setting` VALUES ('WEB_COPYRIGHT', '');
INSERT INTO `dc_system_setting` VALUES ('META_KEYWORD', '');
INSERT INTO `dc_system_setting` VALUES ('META_DESCRIPTION', '');
INSERT INTO `dc_system_setting` VALUES ('QQ_IMAGE', '/Public/uploads/editor/image/2017-03-15/58c89d3e4ec57.png');
INSERT INTO `dc_system_setting` VALUES ('QQ_IMAGE_ALT', 'qq');
INSERT INTO `dc_system_setting` VALUES ('WEIXIN_IMAGE', '/Public/uploads/editor/image/2017-03-03/58b8d8bec322d.jpg');
INSERT INTO `dc_system_setting` VALUES ('WEIXIN_IMAGE_ALT', 'wx');
INSERT INTO `dc_system_setting` VALUES ('SMTP_HOST', 'smtp.163.com');
INSERT INTO `dc_system_setting` VALUES ('SMTP_PORT', '465');
INSERT INTO `dc_system_setting` VALUES ('SMTP_USER', 'docan1234@163.com');
INSERT INTO `dc_system_setting` VALUES ('SMTP_PASS', 'docan1477');
INSERT INTO `dc_system_setting` VALUES ('FROM_NAME', 'ravin');
INSERT INTO `dc_system_setting` VALUES ('WEB_MAIL', '390673982@qq.com');

