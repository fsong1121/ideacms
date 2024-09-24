SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `{PREFIX}admin`;
CREATE TABLE `{PREFIX}admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `uuid` varchar(50) DEFAULT NULL COMMENT 'uuid(唯一)',
  `uid` varchar(50) DEFAULT NULL COMMENT '用户名',
  `pwd` varchar(255) DEFAULT NULL COMMENT '密码',
  `role_id` int(10) unsigned DEFAULT '0' COMMENT '角色ID',
  `is_work` tinyint(1) unsigned DEFAULT '0' COMMENT '激活',
  `last_login_time` int(10) unsigned DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登录ID',
  `login_times` int(10) unsigned DEFAULT '0' COMMENT '登录次数',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员';

DROP TABLE IF EXISTS `{PREFIX}admin_menu`;
CREATE TABLE `{PREFIX}admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '上级ID',
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `subtitle` varchar(50) DEFAULT NULL COMMENT '简称',
  `controller` varchar(100) DEFAULT NULL COMMENT '控制器',
  `operation` varchar(50) DEFAULT NULL COMMENT '方法',
  `other_operation` varchar(300) DEFAULT NULL COMMENT '允许的其他方法',
  `ico` varchar(50) DEFAULT NULL COMMENT '图标',
  `deep` tinyint(1) unsigned DEFAULT '1' COMMENT '层级',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '0:系统菜单 1:自定义菜单',
  `is_turn` int(1) unsigned DEFAULT '0' COMMENT '点击顶级菜单是否跳转',
  `is_addon` tinyint(1) unsigned DEFAULT '0' COMMENT '是否插件',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='平台后台菜单';

INSERT INTO `{PREFIX}admin_menu` VALUES (1, 0, '商品管理', '商品', NULL, NULL, NULL, 'component', 1, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (2, 1, '商品列表', NULL, 'goods', NULL, 'create,edit,save,delete,get_list,set_top,set_sale,get_goods_spec,get_goods_spec_item,get_edit_goods_spec_item,get_sku_data,spec_create,spec_delete,spec_value_create,spec_value_delete,select_goods,get_card_list', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (3, 1, '商品分类', NULL, 'goods_category', NULL, 'create,edit,save,delete,get_list', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (4, 1, '商品规格', NULL, 'goods_spec', NULL, 'create,edit,save,delete,get_list', NULL, 2, 3, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (5, 1, '商品参数', NULL, 'goods_param', NULL, 'create,edit,save,delete,get_list', NULL, 2, 4, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (6, 1, '商品单位', NULL, 'unit', NULL, 'create,edit,save,delete,get_list', NULL, 2, 5, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (7, 1, '商品品牌', NULL, 'brand', NULL, 'create,edit,save,delete,get_list', NULL, 2, 6, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (8, 1, '商品评价', NULL, 'comment', NULL, 'create,edit,save,delete,get_list,reply,set_show', NULL, 2, 7, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (9, 1, '服务承诺', NULL, 'service', NULL, 'create,edit,save,delete,get_list', NULL, 2, 8, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (10, 1, '商品回收站', NULL, 'recycle', NULL, 'delete,get_list,recovery', NULL, 2, 9, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (11, 0, '订单管理', '订单', NULL, NULL, NULL, 'cart', 1, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (12, 11, '订单列表', NULL, 'order', NULL, 'edit,save,delete,get_list,print_order,edit_price,save_price,edit_info,save_info,save_pay,send_goods,save_send,receipt,cancel,export_data', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (13, 11, '退款维权', NULL, 'refund', NULL, 'edit,get_list,refuse,save_refuse,agree', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (14, 0, '营销管理', '营销', NULL, NULL, NULL, 'gift', 1, 3, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (15, 14, '优惠券', NULL, 'coupon', NULL, 'create,edit,save,delete,get_list,show,get_info_list,give,save_give', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (16, 14, '满减送', NULL, 'discount', NULL, 'create,edit,save,delete,get_list', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (17, 0, '会员管理', '会员', NULL, NULL, NULL, 'username', 1, 4, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (18, 17, '会员列表', NULL, 'user', NULL, 'create,edit,save,delete,get_list,edit_pwd,save_pwd,edit_integral,save_integral,edit_balance,save_balance,edit_growth,save_growth,export_data', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (19, 17, '会员等级', NULL, 'user_level', NULL, 'create,edit,save,delete,get_list', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (20, 17, '会员标签', NULL, 'user_label', NULL, 'create,edit,save,delete,get_list', NULL, 2, 3, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (21, 17, '余额明细', NULL, 'user_balance', NULL, 'get_list', NULL, 2, 4, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (22, 17, '积分明细', NULL, 'user_integral', NULL, 'get_list', NULL, 2, 5, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (23, 17, '成长值明细', NULL, 'user_growth', NULL, 'get_list', NULL, 2, 6, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (24, 0, '财务管理', '财务', NULL, NULL, NULL, 'rmb', 1, 5, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (25, 24, '资金流水', NULL, 'finance', NULL, 'get_list', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (26, 24, '提现管理', NULL, 'cash', NULL, 'get_list,agree,edit_cash,save_cash', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (27, 24, '发票管理', NULL, 'bill', NULL, 'edit,save,get_list', NULL, 2, 3, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (28, 0, '数据分析', '数据', '', '', '', 'chart', 1, 6, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (29, 28, '交易统计', '', 'report_order', '', '', '', 2, 1, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (30, 28, '商品统计', '', 'report_goods', '', '', '', 2, 2, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (31, 28, '会员统计', '', 'report_user', '', '', '', 2, 3, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (32, 0, '文章管理', '文章', NULL, NULL, NULL, 'read', 1, 7, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (33, 32, '文章分类', NULL, 'article_category', NULL, 'create,edit,save,delete,get_list', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (34, 32, '文章列表', NULL, 'article', NULL, 'create,edit,save,delete,get_list', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (35, 0, '权限设置', '权限', NULL, NULL, NULL, 'password', 1, 8, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (36, 35, '管理员列表', NULL, 'admin', NULL, 'create,edit,save,delete,get_list', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (37, 35, '管理员角色', NULL, 'admin_role', NULL, 'create,edit,save,delete,get_list', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (38, 0, '系统设置', '设置', NULL, NULL, NULL, 'set', 1, 9, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (39, 38, '基本设置', NULL, 'config', 'site', 'save_site', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (40, 38, '商城设置', NULL, 'config', 'shop', 'save_shop', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (41, 38, '上传设置', NULL, 'config', 'upload', 'save_upload', NULL, 2, 3, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (42, 38, '支付设置', NULL, 'config', 'pay', 'save_pay', NULL, 2, 4, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (43, 38, '快递设置', NULL, 'config', 'express', 'save_express', NULL, 2, 5, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (44, 38, '运费模板', NULL, 'express_template', NULL, 'create,edit,save,delete,get_list', NULL, 2, 6, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (45, 38, '短信设置', NULL, 'config', 'sms', 'save_sms', NULL, 2, 7, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (46, 38, '消息设置', NULL, 'config', 'message', 'save_message', NULL, 2, 8, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (47, 38, '协议设置', NULL, 'config', 'agreement', 'save_agreement', NULL, 2, 9, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (48, 38, '微信设置', NULL, 'config', 'wechat', 'save_wechat', NULL, 2, 10, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (49, 38, '广告管理', NULL, 'ads', NULL, 'create,edit,save,delete,get_list', NULL, 2, 12, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (50, 38, '登录日志', NULL, 'login_log', NULL, 'get_list', NULL, 2, 13, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (51, 38, '后台菜单', NULL, 'admin_menu', NULL, 'create,edit,save,delete,get_list', NULL, 2, 14, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (52, 0, '装修风格', '装修', NULL, NULL, NULL, 'theme', 1, 10, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (53, 52, '主题风格', NULL, 'setting', 'theme', 'save_theme', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (54, 52, '手机端装修', NULL, 'setting', 'mobile', 'save_mobile', NULL, 2, 2, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (55, 52, '手机金刚区', NULL, 'mobile_menu', NULL, 'create,edit,save,delete,get_list', NULL, 2, 3, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (56, 52, 'PC端装修', NULL, 'setting', 'pc', 'save_pc', NULL, 2, 4, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (57, 0, '插件扩展', '插件', NULL, NULL, NULL, 'senior', 1, 11, 0, 0, 0, 0);
INSERT INTO `{PREFIX}admin_menu` VALUES (58, 0, '应用中心', '应用', NULL, NULL, NULL, 'app', 1, 12, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (59, 58, '本地应用', NULL, 'local_app', NULL, 'get_list,install,up,down,uninstall', NULL, 2, 1, 0, 0, 0, 1);
INSERT INTO `{PREFIX}admin_menu` VALUES (60, 1, '卡密网盘', NULL, 'card', NULL, 'create,edit,save,delete,get_list', NULL, 2, 4, 0, 0, 0, 1);

DROP TABLE IF EXISTS `{PREFIX}admin_role`;
CREATE TABLE `{PREFIX}admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '角色名称',
  `info` text COMMENT '角色描述',
  `power` varchar(200) DEFAULT NULL COMMENT '角色权限',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='管理员角色';

DROP TABLE IF EXISTS `{PREFIX}ads`;
CREATE TABLE `{PREFIX}ads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `url` varchar(100) DEFAULT NULL COMMENT '链接地址',
  `pic` varchar(200) DEFAULT NULL COMMENT '图片',
  `info` varchar(200) DEFAULT NULL COMMENT '描述',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '广告类型 0:普通广告 1:pc端 2:移动端',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='广告';

INSERT INTO `{PREFIX}ads` VALUES (1, 0, '幻灯片一', '#', 'ads/5d118dfac4c1c.jpg', '', 2, 3, 1);
INSERT INTO `{PREFIX}ads` VALUES (2, 0, '幻灯片二', '#', 'ads/5d118e1a8c50d.jpg', '', 2, 2, 1);
INSERT INTO `{PREFIX}ads` VALUES (3, 0, '幻灯片三', '#', 'ads/5d118e2d7bfa8.jpg', '', 2, 1, 1);

DROP TABLE IF EXISTS `{PREFIX}area`;
CREATE TABLE `{PREFIX}area` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0' COMMENT '父级',
  `name` varchar(100) DEFAULT NULL COMMENT '名称',
  `short_name` varchar(30) DEFAULT NULL COMMENT '简称',
  `longitude` varchar(30) DEFAULT NULL COMMENT '经度',
  `latitude` varchar(30) DEFAULT NULL COMMENT '纬度',
  `level` smallint(6) DEFAULT '0' COMMENT '级别',
  `sequence` mediumint(9) DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态1有效',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `level` (`level`,`sequence`,`status`) USING BTREE,
  KEY `longitude` (`longitude`,`latitude`) USING BTREE,
  KEY `pid` (`parent_id`) USING BTREE,
  KEY `name` (`name`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=820205 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=84 ROW_FORMAT=DYNAMIC COMMENT='地址库';

DROP TABLE IF EXISTS `{PREFIX}article`;
CREATE TABLE `{PREFIX}article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `cat_id` int(10) unsigned DEFAULT '0' COMMENT '分类ID',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  `source` varchar(50) DEFAULT NULL COMMENT '来源',
  `pic` varchar(200) DEFAULT NULL COMMENT '图片',
  `summary` text COMMENT '摘要',
  `info` text COMMENT '内容',
  `tags` varchar(200) DEFAULT NULL COMMENT 'Tags',
  `url` varchar(200) DEFAULT NULL COMMENT '外链地址',
  `keywords` varchar(200) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT 'seo描述',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '推荐',
  `is_show` tinyint(1) DEFAULT '0' COMMENT '是否发布',
  `hits` int(10) unsigned DEFAULT '0' COMMENT '阅读量',
  `zan` int(10) unsigned DEFAULT '0' COMMENT '点赞数',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章';

INSERT INTO `{PREFIX}article` VALUES (1, 0, 3, '本站仅为演示，请勿真实下单哦！', '', '', '', '本站仅为演示，下单不发货，请勿真实下单哦！', '', '', '', '', 1, 1, 17, 0, 1, 1700535849);

DROP TABLE IF EXISTS `{PREFIX}article_category`;
CREATE TABLE `{PREFIX}article_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '上级ID',
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `wap_title` varchar(50) DEFAULT NULL COMMENT '手机名称',
  `info` text COMMENT '描述',
  `keywords` varchar(200) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT 'seo描述',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '推荐',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '显示',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型 0:普通 1:系统(不可删)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='文章分类';

INSERT INTO `{PREFIX}article_category` VALUES (1, 0, 0, '新闻动态', '动态', '', '', '', 9, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (2, 0, 1, '公司新闻', '公司新闻', '', '', '', 2, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (3, 0, 1, '最新公告', '最新公告', '', '', '', 1, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (4, 0, 0, '购物指南', '帮助', '', '', '', 8, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (5, 0, 4, '购物流程', '购物流程', '', '', '', 3, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (6, 0, 4, '会员注册', '会员注册', '', '', '', 2, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (7, 0, 4, '常见问题', '常见问题', '', '', '', 1, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (8, 0, 0, '支付方式', '支付', '', '', '', 7, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (9, 0, 8, '在线支付', '在线支付', '', '', '', 3, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (10, 0, 8, '银行转账', '银行转账', '', '', '', 2, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (11, 0, 8, '货到付款', '货到付款', '', '', '', 1, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (12, 0, 0, '售后服务', '售后', '', '', '', 6, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (13, 0, 12, '售后政策', '售后政策', '', '', '', 3, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (14, 0, 12, '退款说明', '退款说明', '', '', '', 2, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (15, 0, 12, '取消订单', '取消订单', '', '', '', 1, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (16, 0, 0, '关于我们', '关于', '', '', '', 5, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (17, 0, 16, '关于我们', '关于我们', '', '', '', 3, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (18, 0, 16, '加入我们', '加入我们', '', '', '', 2, 1, 1, 0);
INSERT INTO `{PREFIX}article_category` VALUES (19, 0, 16, '联系方式', '联系方式', '', '', '', 1, 1, 1, 0);

DROP TABLE IF EXISTS `{PREFIX}balance_detail`;
CREATE TABLE `{PREFIX}balance_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `fee` decimal(10,2) DEFAULT '0.00' COMMENT '数量',
  `info` text COMMENT '说明',
  `account_fee` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '账号余额',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='余额明细';

DROP TABLE IF EXISTS `{PREFIX}bill`;
CREATE TABLE `{PREFIX}bill` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `order_id` int(10) unsigned DEFAULT '0' COMMENT '订单ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型 1:个人 2:单位',
  `tax_title` varchar(255) DEFAULT NULL COMMENT '发票抬头',
  `tax_sn` varchar(50) DEFAULT NULL COMMENT '税号',
  `fee` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `info` text COMMENT '备注',
  `tax_no` varchar(100) DEFAULT NULL COMMENT '发票号码',
  `tax_pic` varchar(200) DEFAULT NULL COMMENT '发票图片',
  `tax_url` varchar(255) DEFAULT NULL COMMENT '发票地址',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '状态 1:待开 2:已开 3:拒绝',
  `reason` varchar(255) DEFAULT NULL COMMENT '拒绝原因',
  `opt_date` int(10) unsigned DEFAULT NULL COMMENT '操作时间',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='发票';

DROP TABLE IF EXISTS `{PREFIX}brand`;
CREATE TABLE `{PREFIX}brand` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(200) DEFAULT NULL COMMENT '品牌名称',
  `pic` varchar(200) DEFAULT NULL COMMENT '品牌图片',
  `info` text COMMENT '品牌描述',
  `letter` varchar(10) DEFAULT NULL COMMENT '首字母',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '是否推荐',
  `is_show` tinyint(1) unsigned DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='品牌';

INSERT INTO `{PREFIX}brand` VALUES (1, 0, '小米', 'brand/5cff772df2bf1.jpg', '一家全球领先的智能硬件和电子产品公司，成立于2010年。我们致力于创造高品质、高性价比的科技产品，让每个人都能享受科技的乐趣。我们的产品涵盖智能手机、智能家居、智能穿戴等多个领域，拥有庞大的生态链和丰富的产品线。小米始终坚持创新、品质、实惠的理念，致力于为用户提供更好的服务和体验。', 'X', 1, 1, 1);
INSERT INTO `{PREFIX}brand` VALUES (2, 0, '格力', 'brand/5cff772207f5d.jpg', '中国家电巨头，专注于空调、冰箱等家电产品的研发、生产和销售。以卓越的品质和领先的技术，赢得了全球消费者的信赖。致力于提供舒适、节能、环保的家电产品，推动中国制造走向世界。', 'G', 2, 1, 1);
INSERT INTO `{PREFIX}brand` VALUES (3, 0, '美的', 'brand/5cff772207f5f.jpg', '中国知名家电品牌，创立于1981年，经过多年发展已成为一家多元化、全球化的大型综合性企业。美的以家电产业为主，涉足智能物流、智能供应链、房地产等多个领域。美的产品包括厨房电器、生活电器、空调、冰箱、洗衣机等，覆盖全球多个国家和地区，深受消费者喜爱。美的注重科技创新和品质管理，不断推出高品质、智能化的产品，满足消费者日益增长的需求。', 'M', 1, 0, 1);
INSERT INTO `{PREFIX}brand` VALUES (4, 0, '阿迪达斯', 'brand/5cff772207f5a.jpg', '德国运动品牌，创办于1949年。其品牌标志性的“三条纹”徽标广为人知。阿迪达斯以其卓越的设计和技术，成为全球知名的运动品牌，产品线覆盖鞋类、服装、配件等。阿迪达斯在运动领域有着深厚的历史，长期与世界各地的运动员和运动团队合作，推动着体育事业的发展。', 'A', 1, 1, 1);
INSERT INTO `{PREFIX}brand` VALUES (5, 0, '李宁', 'brand/5cff772207f5b.jpg', '中国领先的专业体育品牌，创立于1990年。以经营李宁品牌专业及休闲运动鞋、服装、器材和配件产品为主，拥有完善的品牌营销、研发、设计、制造、经销及销售能力。李宁品牌以“一切皆有可能”为口号，持续创新，致力于为消费者创造更多元化、更高品质的运动装备。', 'L', 1, 1, 1);
INSERT INTO `{PREFIX}brand` VALUES (6, 0, '华为', 'brand/5cff772207f5c.jpg', '全球领先的ICT解决方案供应商，致力于为全球运营商、企业和消费者提供创新、绿色、智能的ICT解决方案。我们的产品与解决方案已应用于全球170多个国家，服务全球三分之一的人口。', 'H', 1, 1, 1);
INSERT INTO `{PREFIX}brand` VALUES (7, 0, '美津浓', 'brand/5cff772207f5e.jpg', '美津浓（MIZUNO）是世界领先的运动品牌之一，服务于各类运动项目。创立于1906年日本，产品种类齐全，覆盖几乎全部主要运动项目，达到30万多个品类。其产品开发坚持以广泛的科学研究为基础，确保运动是更加舒适，同时坚信科技与人类感性结合起来，才能创造好品质。', 'M', 1, 0, 1);

DROP TABLE IF EXISTS `{PREFIX}cart`;
CREATE TABLE `{PREFIX}cart` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `spec_key` varchar(50) DEFAULT NULL COMMENT '规格项key',
  `spec_key_name` varchar(255) DEFAULT NULL COMMENT '规格项名称',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '单价',
  `amount` int(10) unsigned DEFAULT '0' COMMENT '数量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='购物车';

DROP TABLE IF EXISTS `{PREFIX}cash`;
CREATE TABLE `{PREFIX}cash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `fee` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现金额(实际到账)',
  `service_fee` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '服务费',
  `info` text COMMENT '说明',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '方式 1:余额 2:银行卡 3:微信 4:支付宝',
  `cash_name` varchar(50) DEFAULT NULL COMMENT '收款人',
  `cash_bank` varchar(50) DEFAULT NULL COMMENT '收款银行',
  `bank_account` varchar(50) DEFAULT NULL COMMENT '银行账户',
  `cash_pic` varchar(200) DEFAULT NULL COMMENT '收款码',
  `cash_state` tinyint(1) DEFAULT '0' COMMENT '提现状态(0:待审 1:通过 -1:拒绝)',
  `cash_date` int(10) unsigned DEFAULT '0' COMMENT '提现处理时间',
  `cash_info` varchar(100) DEFAULT NULL COMMENT '处理意见',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='提现';

DROP TABLE IF EXISTS `{PREFIX}collect`;
CREATE TABLE `{PREFIX}collect` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `info_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `type_id` tinyint(1) unsigned DEFAULT '1' COMMENT '1:商品 2:店铺',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='收藏';

DROP TABLE IF EXISTS `{PREFIX}commission_detail`;
CREATE TABLE `{PREFIX}commission_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `fee` decimal(10,2) DEFAULT '0.00' COMMENT '数量',
  `info` text COMMENT '说明',
  `account_fee` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '账号余额',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0:订单佣金',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='佣金明细';

DROP TABLE IF EXISTS `{PREFIX}coupon`;
CREATE TABLE `{PREFIX}coupon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `uuid` varchar(50) DEFAULT NULL COMMENT 'uuid(唯一)',
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型(1:内部券(指定发放或下单赠送) 2:公开券 3:会员券 4:线下券)',
  `cut_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '优惠金额',
  `min_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '满这么多才优惠',
  `send_amount` int(10) unsigned DEFAULT '0' COMMENT '发放数量',
  `per_amount` int(10) unsigned DEFAULT '0' COMMENT '每人最多领取数量(0表示无限)',
  `get_amount` int(10) unsigned DEFAULT '0' COMMENT '已领取数量',
  `use_type` tinyint(1) unsigned DEFAULT '0' COMMENT '使用范围 0:所有商品 1:指定商品',
  `goods_ids` varchar(255) DEFAULT NULL COMMENT '商品ID',
  `b_date` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `e_date` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  `use_time` int(10) unsigned DEFAULT '0' COMMENT '领取后多久有效(0为领取截止时间)',
  `white_label_id` int(10) unsigned DEFAULT '0' COMMENT '白名单会员标签ID',
  `black_label_id` int(10) unsigned DEFAULT '0' COMMENT '黑名单会员标签ID',
  `version` int(10) unsigned DEFAULT '0' COMMENT '版本号(乐观锁使用)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券';

DROP TABLE IF EXISTS `{PREFIX}coupon_user`;
CREATE TABLE `{PREFIX}coupon_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `coupon_id` int(10) unsigned DEFAULT '0' COMMENT '优惠券ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `coupon_sn` varchar(50) DEFAULT NULL COMMENT '优惠券序列号',
  `is_use` tinyint(1) unsigned DEFAULT '0' COMMENT '是否使用',
  `use_date` int(10) unsigned DEFAULT NULL COMMENT '使用时间',
  `order_sn` varchar(200) DEFAULT NULL COMMENT '订单编号',
  `add_date` int(10) unsigned DEFAULT '0' COMMENT '领取时间',
  `end_date` int(10) unsigned DEFAULT '0' COMMENT '失效时间',
  `type` int(10) unsigned DEFAULT '0' COMMENT '来源 1:正常的领取',
  `activity_id` int(10) unsigned DEFAULT '0' COMMENT '来源活动ID',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='优惠券领取明细';

DROP TABLE IF EXISTS `{PREFIX}discount`;
CREATE TABLE `{PREFIX}discount` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '满减类型(0:按金额 1:按件数)',
  `min_price` decimal(10,2) unsigned DEFAULT NULL COMMENT '优惠门槛',
  `send_type` tinyint(1) unsigned DEFAULT '0' COMMENT '优惠类型(0:直减 1:折扣 2:送积分 3:送优惠券)',
  `send_price` decimal(10,2) unsigned DEFAULT NULL COMMENT '直接金额',
  `send_rebate` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '折扣(如8表示8折)',
  `send_integral` int(10) unsigned DEFAULT '0' COMMENT '赠送积分',
  `send_coupon_id` int(10) unsigned DEFAULT '0' COMMENT '赠送的优惠券ID',
  `use_type` tinyint(1) unsigned DEFAULT '0' COMMENT '使用范围(0:所有商品 1:指定商品)',
  `goods_ids` varchar(255) DEFAULT '0' COMMENT '商品ID(当为指定商品必选)',
  `b_date` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `e_date` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='满减送';

DROP TABLE IF EXISTS `{PREFIX}express_template`;
CREATE TABLE `{PREFIX}express_template` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(100) DEFAULT NULL COMMENT '模板名称',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '计费方式 0:按重量 1:按体积 2:按件数',
  `first_num` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '默认起步量',
  `first_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '默认起步价',
  `second_num` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '默认续步量',
  `second_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '默认续步价',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='运费模板';

INSERT INTO `{PREFIX}express_template` VALUES (1, 0, '默认模板', 0, 2.00, 6.00, 1.00, 2.00, 9);

DROP TABLE IF EXISTS `{PREFIX}express_template_price`;
CREATE TABLE `{PREFIX}express_template_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `express_template_id` int(10) unsigned DEFAULT '0' COMMENT '模板ID',
  `area_names` varchar(200) DEFAULT NULL COMMENT '地区名称',
  `first_num` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '起步量',
  `first_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '起步价',
  `second_num` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '续步量',
  `second_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '续步价',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='运费模板费用明细';

INSERT INTO `{PREFIX}express_template_price` VALUES (1, 1, '河南省,湖北省,湖南省,广东省,广西壮族自治区,重庆市,四川省,贵州省,云南省,陕西省', 2.00, 8.00, 1.00, 2.00);
INSERT INTO `{PREFIX}express_template_price` VALUES (3, 1, '北京市,天津市,河北省,山西省', 2.00, 5.00, 1.00, 2.00);
INSERT INTO `{PREFIX}express_template_price` VALUES (4, 1, '上海市,江苏省,浙江省,安徽省,福建省,江西省,山东省', 2.00, 3.00, 1.00, 2.00);
INSERT INTO `{PREFIX}express_template_price` VALUES (5, 1, '内蒙古自治区,辽宁省,吉林省,黑龙江省', 2.00, 10.00, 1.00, 2.00);
INSERT INTO `{PREFIX}express_template_price` VALUES (6, 1, '海南省,西藏自治区,甘肃省,青海省,宁夏回族自治区,新疆维吾尔自治区', 1.00, 15.00, 1.00, 5.00);
INSERT INTO `{PREFIX}express_template_price` VALUES (7, 1, '台湾省,香港特别行政区,澳门特别行政区', 1.00, 50.00, 1.00, 10.00);

DROP TABLE IF EXISTS `{PREFIX}finance_detail`;
CREATE TABLE `{PREFIX}finance_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `related_sn` varchar(50) DEFAULT NULL COMMENT '关联订单',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `fee` decimal(10,2) DEFAULT '0.00' COMMENT '数量',
  `info` text COMMENT '说明',
  `pay_type` tinyint(1) unsigned DEFAULT '0' COMMENT '支付类型 1:微信支付 2:支付宝 3:网银支付 5:货到付款线下支付',
  `pay_sn` varchar(50) DEFAULT NULL COMMENT '交易号',
  `pay_gateway` varchar(100) DEFAULT NULL COMMENT '支付网关',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型 1:订单支付 2:订单退款 3:会员充值 4:充值退款 5:佣金提现 6:购买vip',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='资金流水';

DROP TABLE IF EXISTS `{PREFIX}goods`;
CREATE TABLE `{PREFIX}goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(200) DEFAULT NULL COMMENT '名称',
  `subtitle` varchar(200) DEFAULT NULL COMMENT '副标题',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型 0:普通商品 1:卡密/网盘 2:虚拟商品',
  `cat_id` int(10) unsigned DEFAULT '0' COMMENT '分类ID',
  `brand_id` int(10) unsigned DEFAULT '0' COMMENT '品牌ID',
  `supplier_id` int(11) unsigned DEFAULT '0' COMMENT '供应商ID',
  `express_type` tinyint(1) unsigned DEFAULT '0' COMMENT '运费类型 0:包邮 1:固定 2:模板',
  `express_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '固定运费',
  `express_template_id` int(10) unsigned DEFAULT '0' COMMENT '运费模板ID(非免邮商品使用)',
  `spu` varchar(200) DEFAULT NULL COMMENT '商品编码',
  `pic` varchar(200) DEFAULT NULL COMMENT '图片',
  `slide` varchar(500) DEFAULT NULL COMMENT '幻灯片',
  `video` varchar(200) DEFAULT NULL COMMENT '视频地址',
  `video_pic` varchar(200) DEFAULT NULL COMMENT '视频图片',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '价格',
  `market_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '市场价',
  `unit` varchar(200) DEFAULT NULL COMMENT '单位',
  `stock` int(10) unsigned DEFAULT '0' COMMENT '库存',
  `initial_sales` int(10) unsigned DEFAULT '0' COMMENT '原始销量',
  `sales` int(10) unsigned DEFAULT '0' COMMENT '总销量',
  `multi_spec` tinyint(1) unsigned DEFAULT '0' COMMENT '是否多规格',
  `spec_str` varchar(500) DEFAULT NULL COMMENT '规格JSON字符串',
  `service_ids` varchar(200) DEFAULT NULL COMMENT '服务承诺',
  `info` text COMMENT '商品介绍',
  `other_info` text COMMENT '包装等说明',
  `keywords` varchar(200) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT 'seo描述',
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '商品佣金',
  `integral` int(10) unsigned DEFAULT '0' COMMENT '赠送积分',
  `growth` int(10) unsigned DEFAULT '0' COMMENT '赠送成长值',
  `hits` int(10) unsigned DEFAULT '0' COMMENT '点击量',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '推荐',
  `is_full_free` tinyint(1) unsigned DEFAULT '1' COMMENT '参与满额包邮',
  `is_new` tinyint(1) unsigned DEFAULT '0' COMMENT '新品',
  `is_hot` tinyint(1) unsigned DEFAULT '0' COMMENT '热销',
  `is_sale` tinyint(1) DEFAULT '0' COMMENT '上架(-1:审核 0:下架 1:上架)',
  `is_delete` tinyint(1) unsigned DEFAULT '0' COMMENT '回收站',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `version` int(10) unsigned DEFAULT '0' COMMENT '版本号(乐观锁使用)',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品';

INSERT INTO `{PREFIX}goods` VALUES (2, 0, 'Aveeno艾惟诺/艾维诺儿童洗发水沐浴露 宝宝洗头膏沐浴液洗护二合一354ml 原装进口', '修护滋养，儿童沐浴露洗发水二合一 原装进口', 0, 52, 0, 0, 0, 8.00, 0, '', 'goods/630ae8de67536.jpeg', 'goods/630ae8de67536.jpeg,goods/630ae8e2643e6.jpeg,goods/630ae8e6b68c4.jpeg', '', '', 69.00, 99.00, '', 92, 0, 7, 0, '[]', '5,4,3', '', '', '', '', 0.00, 0, 0, 33, 1, 0, 1, 1, 1, 0, 1, 5, 1655363847);
INSERT INTO `{PREFIX}goods` VALUES (3, 0, '维达卷纸 蓝色经典4层130g30卷 整箱装卫生纸家用纸巾厕纸手纸有芯卷筒纸厕所纸批发加厚厚实单提', '维达官方正品', 0, 50, 0, 0, 0, 0.00, 0, '', 'goods/630ad8629bfe8.png', 'goods/630ad8629bfe8.png,goods/630add711fab9.jpeg,goods/630add760760f.jpeg', '', '', 21.90, 29.90, '', 294, 0, 3, 1, '[{\"spec\":\"规格\",\"items\":[{\"title\":\"蓝色经典140g 10卷\",\"select\":1},{\"title\":\"蓝色经典140g 20卷\",\"select\":1},{\"title\":\"蓝色经典130g 30卷\",\"select\":1}]}]', '5,4,3', '<img src=\"/upload/pic/goods/630ae680ef233.jpeg\" alt=\"undefined\"><img src=\"https://www.haolinju.site/upload/pic/goods/630ae68f9e3b1.jpeg\" alt=\"undefined\"><img src=\"https://www.haolinju.site/upload/pic/goods/630ae6970c725.jpeg\" alt=\"undefined\">', '', '', '', 0.00, 0, 0, 181, 1, 0, 1, 1, 1, 0, 1, 3, 1655365246);
INSERT INTO `{PREFIX}goods` VALUES (4, 0, '蓝宝(BLAUPUNKT)破壁机家用全自动小型智能预约多功能隔音罩柔音榨汁辅食豆浆搅拌机无渣果汁机', '53dB柔音 冷饮热饮磨粉 隔音罩嵌入式杯体下沉底座全包裹隔音 100℃高温烘干预防细菌', 0, 5, 0, 0, 0, 0.00, 0, '', 'goods/630aea0b3fc85.jpeg', 'goods/630aea0b3fc85.jpeg,goods/630aea0fb7a7d.jpeg', '', '', 599.00, 699.00, '', 99, 0, 0, 0, '[]', '', '', '', '', '', 0.00, 0, 0, 5, 1, 0, 1, 1, 1, 0, 1, 0, 1661659711);
INSERT INTO `{PREFIX}goods` VALUES (5, 0, '美的(Midea) 电饭煲4L/4升智能预约多功能家用电饭锅大容量微电脑式底盘加热不粘锅不溢锅WFD4016', '一键柴火饭 黄晶蜂窝内胆 智能预约 4L大容量 健康不粘 大火力5段焖香曲线', 0, 5, 3, 0, 2, 0.00, 1, '', 'goods/630aeac3be1c2.jpeg', 'goods/630aeac3be1c2.jpeg,goods/630aeac8861de.jpeg', '', '', 249.00, 299.00, '', 99, 0, 4, 0, '[]', '5,4,3', '', '', '', '', 0.00, 0, 0, 23, 1, 0, 1, 1, 1, 0, 1, 4, 1661659884);
INSERT INTO `{PREFIX}goods` VALUES (6, 0, 'Apple iPhone 13 移动联通电信5G全网通手机', '全新A15仿生超高速芯片；相机系统升级拍摄效果更佳；续航能力提升满足日常需要；', 0, 1, 0, 0, 0, 0.00, 0, '', 'goods/6312fe4ab7c39.jpeg', 'goods/6312fe4ab7c39.jpeg,goods/6312fe5c2c3aa.jpeg,goods/6312fe6211f85.jpeg', '', '', 5999.00, 6299.00, '', 561, 0, 33, 1, '[{\"spec\":\"颜色\",\"items\":[{\"title\":\"黑色\",\"select\":0},{\"title\":\"蓝色\",\"select\":1},{\"title\":\"紫色\",\"select\":0},{\"title\":\"午夜色\",\"select\":1},{\"title\":\"星光色\",\"select\":1}]},{\"spec\":\"内存\",\"items\":[{\"title\":\"64G\",\"select\":0},{\"title\":\"128G\",\"select\":1},{\"title\":\"256G\",\"select\":1}]}]', '5,4,3,2', '<img src=\"/upload/pic/goods/6312ff768cc36.jpeg\" alt=\"undefined\">', '', '', '', 0.00, 0, 0, 815, 1, 0, 1, 0, 1, 0, 1, 32, 1662189455);
INSERT INTO `{PREFIX}goods` VALUES (7, 0, '华为/HUAWEI P50 Pro 移动联通电信全网通手机', '120Hz高刷新率；后置6400万像素潜望式长焦摄像头；前置100°超广角影像系统', 0, 1, 6, 0, 1, 10.00, 0, '', 'goods/631301304008a.jpeg', 'goods/631301304008a.jpeg,goods/6313013424da6.jpeg,goods/63130137ca0be.jpeg', '', '', 5399.00, 5499.00, '', 388, 0, 8, 1, '[{\"spec\":\"颜色\",\"items\":[{\"title\":\"曜金黑\",\"select\":1},{\"title\":\"雪域白\",\"select\":1}]},{\"spec\":\"内存\",\"items\":[{\"title\":\"8GB+128GB\",\"select\":1},{\"title\":\"8GB+256GB\",\"select\":1}]}]', '5,4,3,2', '<img src=\"/upload/pic/goods/63130204db5bc.jpeg\" alt=\"undefined\">', '', '', '', 0.00, 0, 0, 53, 0, 0, 1, 0, 1, 0, 1, 8, 1662190094);

DROP TABLE IF EXISTS `{PREFIX}goods_category`;
CREATE TABLE `{PREFIX}goods_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `parent_id` int(10) unsigned DEFAULT '0' COMMENT '上级ID',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `wap_title` varchar(50) DEFAULT NULL COMMENT '手机标题',
  `pic` varchar(200) DEFAULT NULL COMMENT '图片',
  `slide_pic` varchar(200) DEFAULT NULL COMMENT '幻灯片',
  `info` text COMMENT '描述',
  `keywords` varchar(200) DEFAULT NULL COMMENT '关键词',
  `description` varchar(255) DEFAULT NULL COMMENT 'seo描述',
  `goods_screen_id` int(10) unsigned DEFAULT '0' COMMENT '商品筛选ID',
  `per_pages` int(10) unsigned DEFAULT '0' COMMENT '分页数量',
  `deep` tinyint(1) unsigned DEFAULT '0' COMMENT '分类级别',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '推荐',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品分类';

INSERT INTO `{PREFIX}goods_category` VALUES (1, 0, 56, '数码办公', '数码办公', '', '', '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (2, 0, 1, '电脑整机', '电脑整机', 'category/11.jpg', '', '', '', '', 0, 0, 3, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (5, 0, 56, '厨房电器', '厨房电器', '', '', '', '', '', 0, 0, 2, 3, 0, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (6, 0, 5, '油烟机', '油烟机', 'category/21.jpg', '', '', '', '', 0, 0, 3, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (13, 0, 5, '热水器', '热水器', 'category/22.jpg', '', '', '', '', 0, 0, 3, 2, 0, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (14, 0, 56, '生活电器', '生活电器', '', '', '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (15, 0, 14, '清洁电器', '清洁电器', 'category/31.jpg', '', '', '', '', 0, 0, 3, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (16, 0, 14, '衣物护理', '衣物护理', 'category/32.jpg', '', '', '', '', 0, 0, 3, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (17, 0, 0, '居家生活', '居家生活', 'category/cat3.png', '', '', '', '', 0, 0, 1, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (18, 0, 1, '网络设备', '网络设备', 'category/13.jpg', '', '', '', '', 0, 0, 3, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (20, 0, 1, '办公设备', '办公设备', 'category/15.jpg', '', '', '', '', 0, 0, 3, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (21, 0, 1, '文具用品', '文具用品', 'category/16.jpg', '', '', '', '', 0, 0, 3, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (23, 0, 17, '床上用品', '床上用品', 'category/41.jpg', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (25, 0, 5, '净水器', '净水器', 'category/24.jpg', NULL, '', '', '', 0, 0, 3, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (26, 0, 14, '空气调节', '空气调节', 'category/33.jpg', NULL, '', '', '', 0, 0, 3, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (28, 0, 17, '收纳日用', '收纳日用', 'category/42.jpg', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (29, 0, 17, '家居饰品', '家居饰品', 'category/43.jpg', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (30, 0, 17, '厨房用品', '厨房用品', 'category/44.jpg', NULL, '', '', '', 0, 0, 2, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (31, 0, 0, '运动户外', '运动户外', 'category/cat6.png', NULL, '', '', '', 0, 0, 1, 6, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (32, 0, 31, '旅行用品', '旅行用品', 'category/51.jpg', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (33, 0, 31, '运动健身', '运动健身', 'category/52.jpg', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (34, 0, 31, '运动服装', '运动服装', 'category/53.jpg', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (35, 0, 31, '户外露营', '户外露营', 'category/54.jpg', NULL, '', '', '', 0, 0, 2, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (36, 0, 0, '图书文娱', '图书文娱', 'category/cat3.png', NULL, '', '', '', 0, 0, 1, 9, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (37, 0, 36, '图书音像', '图书音像', 'category/61.jpg', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (38, 0, 36, '文创周边', '文创周边', 'category/62.jpg', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (39, 0, 36, '游戏周边', '游戏周边', 'category/63.jpg', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (41, 0, 0, '服饰鞋包', '服饰鞋包', 'category/cat2.png', NULL, '', '', '', 0, 0, 1, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (42, 0, 41, '男装', '男装', '', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (43, 0, 41, '女装', '女装', '', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (44, 0, 41, '鞋靴', '鞋靴', '', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (45, 0, 41, '箱包', '箱包', '', NULL, '', '', '', 0, 0, 2, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (46, 0, 0, '个护清洁', '个护清洁', 'category/cat4.png', NULL, '', '', '', 0, 0, 1, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (47, 0, 46, '面部护理', '面部护理', '', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (48, 0, 46, '个人护理', '个人护理', '', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (49, 0, 46, '彩妆香水', '彩妆香水', '', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (50, 0, 46, '纸品清洁', '纸品清洁', '', NULL, '', '', '', 0, 0, 2, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (51, 0, 0, '母婴亲子', '母婴亲子', 'category/cat5.png', NULL, '', '', '', 0, 0, 1, 5, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (52, 0, 51, '洗护喂养', '洗护喂养', '', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (53, 0, 51, '婴童寝居', '婴童寝居', '', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (54, 0, 51, '玩具出行', '玩具出行', '', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (55, 0, 51, '童装童鞋', '童装童鞋', '', NULL, '', '', '', 0, 0, 2, 4, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (56, 0, 0, '数码家电', '数码家电', 'category/cat1.png', NULL, '', '', '', 0, 0, 1, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (58, 0, 0, '家具家装', '家具家装', 'category/cat7.png', NULL, '', '', '', 0, 0, 1, 7, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (59, 0, 58, '厨房卫浴', '厨房卫浴', '', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (60, 0, 58, '灯饰照明', '灯饰照明', '', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (61, 0, 58, '五金工具', '五金工具', '', NULL, '', '', '', 0, 0, 2, 3, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (62, 0, 0, '钟表珠宝', '钟表珠宝', 'category/cat8.png', NULL, '', '', '', 0, 0, 1, 8, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (63, 0, 62, '腕表', '腕表', '', NULL, '', '', '', 0, 0, 2, 1, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (64, 0, 62, '黄金', '黄金', '', NULL, '', '', '', 0, 0, 2, 2, 1, 1);
INSERT INTO `{PREFIX}goods_category` VALUES (65, 0, 62, '钻石', '钻石', '', NULL, '', '', '', 0, 0, 2, 3, 1, 1);

DROP TABLE IF EXISTS `{PREFIX}goods_comment`;
CREATE TABLE `{PREFIX}goods_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `order_id` int(10) unsigned DEFAULT '0' COMMENT '订单ID',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `spec_key_name` varchar(100) DEFAULT NULL COMMENT '规格',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `user_name` varchar(200) DEFAULT NULL COMMENT '会员昵称',
  `goods_rate` tinyint(1) unsigned DEFAULT '0' COMMENT '商品评分',
  `express_rate` tinyint(1) unsigned DEFAULT '0' COMMENT '物流评分',
  `service_rate` tinyint(1) unsigned DEFAULT '0' COMMENT '服务评分',
  `pic` varchar(255) DEFAULT NULL COMMENT '图片',
  `info` varchar(255) DEFAULT NULL COMMENT '评论内容',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '是否显示',
  `is_top` tinyint(1) unsigned DEFAULT '0' COMMENT '是否推荐',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '评论日期',
  `reply_info` varchar(255) DEFAULT NULL COMMENT '回复内容',
  `reply_date` int(10) unsigned DEFAULT NULL COMMENT '回复日期',
  `zan` int(10) unsigned DEFAULT '0' COMMENT '点赞数',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品评价';

DROP TABLE IF EXISTS `{PREFIX}goods_price`;
CREATE TABLE `{PREFIX}goods_price` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `spec_key` varchar(50) DEFAULT NULL COMMENT '如：黑-L',
  `spec_key_name` varchar(100) DEFAULT NULL COMMENT '如：颜色：黑 尺码：L',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '价格',
  `market_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '市场价',
  `cost_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '成本价',
  `weight` smallint(6) unsigned DEFAULT '0' COMMENT '重量:克',
  `volume` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '体积:平方米',
  `stock` int(10) unsigned DEFAULT '0' COMMENT '库存',
  `card_id` int(10) unsigned DEFAULT '0' COMMENT '卡密ID',
  `pic` varchar(200) DEFAULT NULL COMMENT '规格图片',
  `sku` varchar(50) DEFAULT NULL COMMENT '类型号(条形码)',
  `sales` int(10) unsigned DEFAULT '0' COMMENT '销量',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品价格';

INSERT INTO `{PREFIX}goods_price` VALUES (31, 4, '', '', 599.00, 699.00, 499.00, 10000, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (35, 2, '', '', 69.00, 99.00, 59.00, 360, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (50, 3, '蓝色经典140g 10卷', '规格:蓝色经典140g 10卷 ', 21.90, 29.90, 18.90, 1400, 0.00, 96, 0, '', '0', 3);
INSERT INTO `{PREFIX}goods_price` VALUES (51, 3, '蓝色经典140g 20卷', '规格:蓝色经典140g 20卷 ', 49.80, 59.90, 46.80, 2800, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (52, 3, '蓝色经典130g 30卷', '规格:蓝色经典130g 30卷 ', 59.90, 69.90, 56.90, 3900, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (59, 6, '蓝色-128G', '颜色:蓝色 内存:128G ', 5999.00, 6299.00, 5799.00, 300, 0.00, 69, 0, '', '0', 12);
INSERT INTO `{PREFIX}goods_price` VALUES (60, 6, '蓝色-256G', '颜色:蓝色 内存:256G ', 6799.00, 6999.00, 6599.00, 300, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (61, 6, '午夜色-128G', '颜色:午夜色 内存:128G ', 5999.00, 6299.00, 5799.00, 300, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (62, 6, '午夜色-256G', '颜色:午夜色 内存:256G ', 6799.00, 6999.00, 6599.00, 300, 0.00, 98, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (63, 6, '星光色-128G', '颜色:星光色 内存:128G ', 5999.00, 6299.00, 5799.00, 300, 0.00, 97, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (64, 6, '星光色-256G', '颜色:星光色 内存:256G ', 6799.00, 6999.00, 6599.00, 300, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (81, 7, '曜金黑-8GB+128GB', '颜色:曜金黑 内存:8GB+128GB ', 5399.00, 5499.00, 5199.00, 200, 0.00, 91, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (82, 7, '曜金黑-8GB+256GB', '颜色:曜金黑 内存:8GB+256GB ', 5899.00, 5999.00, 5699.00, 200, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (83, 7, '雪域白-8GB+128GB', '颜色:雪域白 内存:8GB+128GB ', 5399.00, 5499.00, 5199.00, 200, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (84, 7, '雪域白-8GB+256GB', '颜色:雪域白 内存:8GB+256GB ', 5899.00, 5999.00, 5699.00, 200, 0.00, 99, 0, '', '0', 0);
INSERT INTO `{PREFIX}goods_price` VALUES (85, 5, '', '', 249.00, 299.00, 199.00, 4500, 0.00, 99, 0, '', '0', 0);

DROP TABLE IF EXISTS `{PREFIX}goods_spec`;
CREATE TABLE `{PREFIX}goods_spec` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '模板名称',
  `info` varchar(200) DEFAULT NULL COMMENT '描述',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品规格模型';

INSERT INTO `{PREFIX}goods_spec` VALUES (1, 0, '服装', '适用于各种服装', 9);
INSERT INTO `{PREFIX}goods_spec` VALUES (2, 0, '手机', '', 1);

DROP TABLE IF EXISTS `{PREFIX}goods_spec_item`;
CREATE TABLE `{PREFIX}goods_spec_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `spec_id` int(10) unsigned DEFAULT '0' COMMENT '规格模板ID',
  `title` varchar(50) DEFAULT NULL COMMENT '规格名',
  `items` varchar(200) DEFAULT NULL COMMENT '规格项',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='规格明细';

INSERT INTO `{PREFIX}goods_spec_item` VALUES (1, 1, '颜色', '白色,黑色,红色,黄色,蓝色', 2);
INSERT INTO `{PREFIX}goods_spec_item` VALUES (2, 1, '尺码', 'S,M,L,XL', 1);
INSERT INTO `{PREFIX}goods_spec_item` VALUES (3, 2, '颜色', '黑色,蓝色,紫色', 2);
INSERT INTO `{PREFIX}goods_spec_item` VALUES (4, 2, '内存', '64G,128G,256G', 1);

DROP TABLE IF EXISTS `{PREFIX}growth_detail`;
CREATE TABLE `{PREFIX}growth_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `fee` int(10) DEFAULT '0' COMMENT '数量',
  `info` text COMMENT '说明',
  `account_fee` int(10) unsigned DEFAULT '0' COMMENT '账号余额',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='成长值明细';

DROP TABLE IF EXISTS `{PREFIX}integral_detail`;
CREATE TABLE `{PREFIX}integral_detail` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `fee` int(10) DEFAULT '0' COMMENT '数量',
  `info` text COMMENT '说明',
  `account_fee` int(10) unsigned DEFAULT '0' COMMENT '账号余额',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='积分明细';

DROP TABLE IF EXISTS `{PREFIX}login_log`;
CREATE TABLE `{PREFIX}login_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `type` varchar(100) DEFAULT NULL COMMENT '类型 admin:管理员 user:会员',
  `uid` varchar(50) DEFAULT NULL COMMENT '用户名',
  `data` text COMMENT '传输数据',
  `ip` varchar(50) DEFAULT NULL COMMENT 'ip地址',
  `info` varchar(200) DEFAULT NULL COMMENT '操作行为',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '0:失败 1:成功',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='登录日志';

DROP TABLE IF EXISTS `{PREFIX}mark`;
CREATE TABLE `{PREFIX}mark` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '浏览日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='足迹';

DROP TABLE IF EXISTS `{PREFIX}mobile_menu`;
CREATE TABLE `{PREFIX}mobile_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `url` varchar(100) DEFAULT NULL COMMENT '链接地址',
  `url_type` tinyint(1) unsigned DEFAULT '1' COMMENT '链接类型 1:navigateTo 2:switchTab',
  `pic` varchar(200) DEFAULT NULL COMMENT '图片',
  `info` varchar(200) DEFAULT NULL COMMENT '描述',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型 0:普通 1:客服',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='金刚区菜单';

INSERT INTO `{PREFIX}mobile_menu` VALUES (1, 0, '全部商品', '/pages/goods/category', 2, 'menu/65aa1973575ff.png', '', 0, 10, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (2, 0, '新闻资讯', '/pages/article/list', 1, 'menu/65aa1a11dbe8b.png', '', 0, 9, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (3, 0, '签到有礼', '#', 1, 'menu/65aa1a7243d3c.png', '', 0, 8, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (4, 0, '精品推荐', '/pages/goods/list?type=top', 1, 'menu/65aa1aa083591.png', '', 0, 7, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (5, 0, '品牌专区', '/pages/brand/index', 1, 'menu/65aa1ac0b6b2c.png', '', 0, 6, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (6, 0, '我的收藏', '/pages/user/collect', 1, 'menu/65aa1af3cdd79.png', '', 0, 5, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (7, 0, '我的订单', '/pages/order/list', 1, 'menu/65aa1b5472404.png', '', 0, 4, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (8, 0, '领券中心', '/pages/coupon/index', 1, 'menu/65aa1b751ad8e.png', '', 0, 3, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (9, 0, '会员中心', '/pages/user/index', 2, 'menu/65aa1baa634a1.png', '', 0, 2, 1);
INSERT INTO `{PREFIX}mobile_menu` VALUES (10, 0, '联系客服', '#', 1, 'menu/65aa1bc4dfbd1.png', '', 1, 1, 1);

DROP TABLE IF EXISTS `{PREFIX}order`;
CREATE TABLE `{PREFIX}order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '店铺ID',
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '订单金额',
  `pay_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '实付金额',
  `order_state` tinyint(1) DEFAULT '1' COMMENT '订单状态 1:待付款 2:待发货 3:待收货 4:已完成 -1:已取消',
  `pay_type` tinyint(1) unsigned DEFAULT '0' COMMENT '支付方式 1:微信支付 2:支付宝 3:网银支付 4:余额支付 5:货到付款',
  `pay_sn` varchar(50) DEFAULT NULL COMMENT '交易号',
  `pay_date` varchar(20) DEFAULT NULL COMMENT '支付时间',
  `pay_order_sn` varchar(200) DEFAULT NULL COMMENT '支付订单号',
  `pay_gateway` varchar(100) DEFAULT NULL COMMENT '支付网关',
  `terminal` tinyint(1) unsigned DEFAULT '1' COMMENT '订单来源 1:h5端 2:pc端 3:微信端 4:小程序 5:app',
  `send_type` int(10) unsigned DEFAULT '1' COMMENT '1:快递 2:自提',
  `store_id` int(10) unsigned DEFAULT '0' COMMENT '自提门店ID',
  `info` text COMMENT '订单备注',
  `name` varchar(50) DEFAULT NULL COMMENT '收货人',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话',
  `address` varchar(200) DEFAULT NULL COMMENT '收货地址',
  `express_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '运费',
  `express_type` tinyint(1) unsigned DEFAULT '1' COMMENT '快递类型 1:快递 2:无需快递',
  `express_title` varchar(50) DEFAULT NULL COMMENT '快递公司',
  `express_sn` varchar(50) DEFAULT NULL COMMENT '运单号',
  `express_info` varchar(200) DEFAULT NULL COMMENT '发货信息',
  `express_date` int(10) unsigned DEFAULT NULL COMMENT '发货时间',
  `receive_date` int(10) unsigned DEFAULT NULL COMMENT '收货时间',
  `pickup_sn` varchar(50) DEFAULT NULL COMMENT '提货号(自提订单)',
  `coupon_id` int(10) unsigned DEFAULT '0' COMMENT '优惠券ID',
  `coupon_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '优惠券金额',
  `rebate_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '会员折扣金额',
  `discount_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '满减优惠金额',
  `discount_integral` int(10) unsigned DEFAULT '0' COMMENT '满减送积分',
  `discount_coupon_ids` varchar(200) DEFAULT NULL COMMENT '满减送优惠券ID',
  `exchange_integral` int(10) unsigned DEFAULT '0' COMMENT '使用积分',
  `exchange_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '积分抵扣费用',
  `trim_price` decimal(10,2) DEFAULT '0.00' COMMENT '调整订单价格(正数为加，负数为减)',
  `refund_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '退款金额',
  `refund_state` tinyint(1) unsigned DEFAULT '0' COMMENT '退款状态 1:部分退款 2:全部退款',
  `user_commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '可分佣金(分销使用)',
  `commission_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '分佣金额',
  `order_integral` int(10) unsigned DEFAULT '0' COMMENT '商品赠送积分',
  `order_growth` int(10) unsigned DEFAULT '0' COMMENT '订单成长值',
  `order_type` varchar(50) DEFAULT '' COMMENT '订单类型 空:普通订单',
  `activity_id` int(10) unsigned DEFAULT '0' COMMENT '活动ID',
  `activity_state` tinyint(1) unsigned DEFAULT '1' COMMENT '活动状态 0:进行中 1:成功 2:失败',
  `add_date` int(10) DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `id` (`id`) USING BTREE,
  KEY `order_sn` (`order_sn`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单';

DROP TABLE IF EXISTS `{PREFIX}order_goods`;
CREATE TABLE `{PREFIX}order_goods` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `order_id` int(10) unsigned DEFAULT '0' COMMENT '订单ID',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `spec_key` varchar(100) DEFAULT NULL COMMENT '规格key(暂时没用)',
  `spec_key_name` varchar(255) DEFAULT '0' COMMENT '规格',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '商品单价',
  `amount` int(10) unsigned DEFAULT '0' COMMENT '购买数量',
  `rebate_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '会员折扣',
  `discount_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '满减优惠',
  `coupon_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '优惠券优惠',
  `exchange_integral` int(10) unsigned DEFAULT '0' COMMENT '商品积分',
  `exchange_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '积分抵扣',
  `trim_price` decimal(10,2) DEFAULT '0.00' COMMENT '调整订单价格(正数为加，负数为减)',
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '商品佣金',
  `integral` int(10) unsigned DEFAULT '0' COMMENT '赠送积分',
  `growth` int(10) unsigned DEFAULT '0' COMMENT '成长值',
  `is_comment` tinyint(1) unsigned DEFAULT '0' COMMENT '评论',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '退款状态 0:正常单 1:退款中 2:退款成功',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `is_comment` (`is_comment`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='订单明细';

DROP TABLE IF EXISTS `{PREFIX}order_log`;
CREATE TABLE `{PREFIX}order_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(1) unsigned DEFAULT '1' COMMENT '类型 1:用户 2:商家',
  `order_id` int(11) unsigned DEFAULT '0' COMMENT '订单id',
  `user_id` int(11) unsigned DEFAULT '0' COMMENT '操作会员ID',
  `info` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT '操作描述',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=COMPACT COMMENT='订单操作纪录';

DROP TABLE IF EXISTS `{PREFIX}order_refund`;
CREATE TABLE `{PREFIX}order_refund` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `sn` varchar(50) DEFAULT NULL COMMENT '单号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '会员ID',
  `order_goods_id` int(10) unsigned DEFAULT '0' COMMENT '订单商品ID',
  `order_id` int(10) unsigned DEFAULT '0' COMMENT '订单ID',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '退款类型 1:仅退款 2:退货退款 3:仅换货',
  `reason` varchar(200) DEFAULT NULL COMMENT '退款原因',
  `pay_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '订单实付款',
  `express_price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '发货运费(已发货订单)',
  `price` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '退款金额',
  `integral` int(10) unsigned DEFAULT '0' COMMENT '退积分',
  `info` varchar(200) DEFAULT NULL COMMENT '说明',
  `pic` varchar(200) DEFAULT NULL COMMENT '图片',
  `express_title` varchar(100) DEFAULT NULL COMMENT '退货快递公司',
  `express_sn` varchar(100) DEFAULT NULL COMMENT '退货物流单号',
  `state` tinyint(1) DEFAULT '0' COMMENT '退款状态 -2:已取消 -1:已拒绝 0:退款中 1:退款成功',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加时间',
  `reply_info` varchar(200) DEFAULT NULL COMMENT '回复内容',
  `reply_date` int(10) unsigned DEFAULT NULL COMMENT '审核时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='退换货';

DROP TABLE IF EXISTS `{PREFIX}service`;
CREATE TABLE `{PREFIX}service` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '标题',
  `info` varchar(200) DEFAULT NULL COMMENT '描述',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='服务';

INSERT INTO `{PREFIX}service` VALUES (2, 0, '全场包邮', '所有商品包邮（偏远地区除外）', 1, 1);
INSERT INTO `{PREFIX}service` VALUES (3, 0, '24小时发货', '全现货商品，24小时极速发货', 2, 1);
INSERT INTO `{PREFIX}service` VALUES (4, 0, '7天无理由退货', '满足相应条件时，消费者可申请7天无理由退货', 3, 1);
INSERT INTO `{PREFIX}service` VALUES (5, 0, '假一赔十', '若收到商品是假冒品牌，可获得十倍现金券赔偿', 4, 1);

DROP TABLE IF EXISTS `{PREFIX}token`;
CREATE TABLE `{PREFIX}token` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '序号',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户id',
  `type` varchar(50) DEFAULT NULL COMMENT '用户类型(user,admin等)',
  `access_token` varchar(500) NOT NULL COMMENT 'token',
  `refresh_token` varchar(500) NOT NULL COMMENT 'refresh_token',
  `create_time` int(10) unsigned NOT NULL COMMENT '创建时间',
  `expires_time` int(10) unsigned NOT NULL COMMENT '到期时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `uid` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Jwt-token';

DROP TABLE IF EXISTS `{PREFIX}unit`;
CREATE TABLE `{PREFIX}unit` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(200) DEFAULT NULL COMMENT '单位名称',
  `info` text COMMENT '描述',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  `is_show` tinyint(1) unsigned DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='商品单位';

INSERT INTO `{PREFIX}unit` VALUES (1, 0, '件', '', 2, 1);
INSERT INTO `{PREFIX}unit` VALUES (2, 0, '盒', '', 1, 1);

DROP TABLE IF EXISTS `{PREFIX}user`;
CREATE TABLE `{PREFIX}user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `uuid` varchar(50) DEFAULT NULL COMMENT 'uuid(唯一)',
  `uid` varchar(50) DEFAULT NULL COMMENT '用户名',
  `pwd` varchar(255) DEFAULT NULL COMMENT '密码',
  `sex` tinyint(1) DEFAULT '0' COMMENT '0保密 1男 2女',
  `integral` int(10) unsigned DEFAULT '0' COMMENT '积分',
  `balance` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '余额',
  `growth` int(10) unsigned DEFAULT '0' COMMENT '成长值',
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '佣金',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(200) DEFAULT NULL COMMENT '头像',
  `email` varchar(100) DEFAULT NULL COMMENT '邮箱',
  `mobile` varchar(20) DEFAULT NULL COMMENT '手机号',
  `pay_pwd` varchar(255) DEFAULT NULL COMMENT '支付密码',
  `pid` int(10) unsigned DEFAULT '0' COMMENT '上级ID',
  `level_id` int(10) unsigned DEFAULT '0' COMMENT '等级ID',
  `label_id` varchar(250) DEFAULT NULL COMMENT '标签ID',
  `wechat_user_id` int(10) unsigned DEFAULT '0' COMMENT '微信会员ID(作废)',
  `miniapp_user_id` int(10) unsigned DEFAULT '0' COMMENT '小程序会员ID(作废)',
  `qq_user_id` int(10) unsigned DEFAULT '0' COMMENT 'QQ会员ID',
  `wechat_openid` varchar(50) DEFAULT NULL COMMENT '公众号openid',
  `miniapp_openid` varchar(50) DEFAULT NULL COMMENT '小程序openid',
  `wechat_unionid` varchar(50) DEFAULT NULL COMMENT '微信unionid',
  `is_fx` tinyint(1) unsigned DEFAULT '0' COMMENT '是否分销商',
  `invite_code` varchar(50) DEFAULT NULL COMMENT '分销商邀请码',
  `is_vip` tinyint(1) unsigned DEFAULT '0' COMMENT '是否vip会员',
  `vip_effective_date` int(10) unsigned DEFAULT NULL COMMENT 'vip有效期至',
  `is_work` tinyint(1) unsigned DEFAULT '0' COMMENT '激活',
  `last_login_time` int(10) unsigned DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(20) DEFAULT NULL COMMENT '最后登录IP',
  `login_times` int(10) unsigned DEFAULT '0' COMMENT '登录次数',
  `version` int(10) unsigned DEFAULT '0' COMMENT '版本号(乐观锁使用)',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员';

DROP TABLE IF EXISTS `{PREFIX}user_address`;
CREATE TABLE `{PREFIX}user_address` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `name` varchar(50) DEFAULT NULL COMMENT '收货人',
  `province` varchar(50) DEFAULT NULL COMMENT '省',
  `city` varchar(50) DEFAULT NULL COMMENT '市',
  `county` varchar(50) DEFAULT NULL COMMENT '县',
  `address` varchar(200) DEFAULT NULL COMMENT '地址',
  `tel` varchar(20) DEFAULT NULL COMMENT '电话',
  `is_default` tinyint(1) unsigned DEFAULT '0' COMMENT '默认',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员地址';

DROP TABLE IF EXISTS `{PREFIX}user_label`;
CREATE TABLE `{PREFIX}user_label` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '名称',
  `info` varchar(200) DEFAULT '0' COMMENT '描述',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员标签';

DROP TABLE IF EXISTS `{PREFIX}user_level`;
CREATE TABLE `{PREFIX}user_level` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '等级名称',
  `growth` int(10) unsigned DEFAULT '0' COMMENT '成长值(用户注册、签到、下单等获取)',
  `rebate` tinyint(3) unsigned DEFAULT '0' COMMENT '折扣率',
  `info` varchar(200) DEFAULT '0' COMMENT '等级描述',
  `sequence` int(10) unsigned DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='会员等级';

INSERT INTO `{PREFIX}user_level` VALUES (1, 0, '钻石会员', 10000, 80, '', 1);
INSERT INTO `{PREFIX}user_level` VALUES (2, 0, '黄金会员', 8000, 85, '', 2);
INSERT INTO `{PREFIX}user_level` VALUES (3, 0, '白银会员', 6000, 90, '', 3);

DROP TABLE IF EXISTS `{PREFIX}visit`;
CREATE TABLE `{PREFIX}visit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
  `user_agent` varchar(255) DEFAULT NULL COMMENT '访问头(判断来源)',
  `user_id` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `goods_id` int(10) unsigned DEFAULT '0' COMMENT '商品ID',
  `url` varchar(200) DEFAULT NULL COMMENT '访问页面',
  `platform` varchar(100) DEFAULT NULL COMMENT '平台(app,web,h5)',
  `model` varchar(100) DEFAULT NULL COMMENT '型号',
  `ip` varchar(50) DEFAULT NULL COMMENT 'ip地址',
  `add_date` int(10) unsigned DEFAULT NULL COMMENT '添加日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='访问日志';

DROP TABLE IF EXISTS `idea_card`;
CREATE TABLE `idea_card` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
    `tenant_id` int(10) unsigned DEFAULT '0' COMMENT '租户ID',
    `title` varchar(50) DEFAULT NULL COMMENT '名称',
    `type` tinyint(1) unsigned DEFAULT '0' COMMENT '类型(0:卡密 1:网盘)',
    `url` varchar(255) DEFAULT NULL COMMENT '地址(固定卡密或网盘)',
    `pwd` varchar(100) DEFAULT NULL COMMENT '密码(固定卡密或网盘)',
    `add_date` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='卡密/网盘';

DROP TABLE IF EXISTS `idea_card_detail`;
CREATE TABLE `idea_card_detail` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '序号',
    `card_id` int(10) unsigned DEFAULT '0' COMMENT '卡密ID',
    `account` varchar(200) DEFAULT NULL COMMENT '账号',
    `pwd` varchar(200) DEFAULT NULL COMMENT '密码',
    `order_sn` varchar(100) DEFAULT NULL COMMENT '所属订单',
    `get_date` int(10) unsigned DEFAULT '0' COMMENT '发放时间',
    `add_date` int(10) unsigned DEFAULT '0' COMMENT '添加时间',
    `version` int(10) unsigned DEFAULT '0' COMMENT '版本号(乐观锁使用)',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='卡密账号密码';

SET FOREIGN_KEY_CHECKS = 1;