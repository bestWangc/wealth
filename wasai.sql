/*
 Navicat Premium Data Transfer

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : wasai

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 18/03/2019 17:27:55
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for xx_admin
-- ----------------------------
DROP TABLE IF EXISTS `xx_admin`;
CREATE TABLE `xx_admin`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '管理员名称',
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '管理员密码',
  `sort` int(11) NULL DEFAULT 10,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态，0-禁用,1-正常',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ly_name`(`name`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_admin
-- ----------------------------
INSERT INTO `xx_admin` VALUES (19, 'admin', '7812555d77287d693e711b96e58ac893', 10, 1);

-- ----------------------------
-- Table structure for xx_admin_bihistory
-- ----------------------------
DROP TABLE IF EXISTS `xx_admin_bihistory`;
CREATE TABLE `xx_admin_bihistory`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `epoints` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '金额',
  `admin` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `uid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员充值收益币表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of xx_admin_bihistory
-- ----------------------------
INSERT INTO `xx_admin_bihistory` VALUES (26, 13, 1470278339, 10.00, 19);
INSERT INTO `xx_admin_bihistory` VALUES (27, 13, 1470278486, 1.00, 19);

-- ----------------------------
-- Table structure for xx_admin_moneyhistory
-- ----------------------------
DROP TABLE IF EXISTS `xx_admin_moneyhistory`;
CREATE TABLE `xx_admin_moneyhistory`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `epoints` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '金额',
  `admin` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `uid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 27 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '管理员充值钱包历史记录表' ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of xx_admin_moneyhistory
-- ----------------------------
INSERT INTO `xx_admin_moneyhistory` VALUES (26, 13, 1470278339, 10.00, 19);

-- ----------------------------
-- Table structure for xx_article
-- ----------------------------
DROP TABLE IF EXISTS `xx_article`;
CREATE TABLE `xx_article`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号，自动递增',
  `input_time` int(11) NULL DEFAULT NULL COMMENT '录入时间',
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '名称',
  `seo_keys` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT 'seo页面关键词',
  `seo_description` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT 'SEO页面简介',
  `num` int(11) NULL DEFAULT 0 COMMENT '浏览次数',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NULL COMMENT '文章内容',
  `kind_id` int(11) NULL DEFAULT 0 COMMENT '类别ID，用来进行访问查找，唯一标识',
  `sort` int(11) NULL DEFAULT 10 COMMENT '后台排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `kind_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_article
-- ----------------------------
INSERT INTO `xx_article` VALUES (3, 1482733158, '关于我们', '公司简介,公司介绍', '公司简介', 0, ' <div class=\"col-lg-12\">\r\n    关于我们：金凤平台一个全新的理财模式，目前虚拟卡币投资，每天分红收益不断，结合分销模式，直销模式多项奖励直推奖，团队奖，分红奖；打造一个全新的模式平台，给人焕然一新。在此给大家分享分享金凤模式的流程运作。<br>\r\n    　　\r\n    金凤平台介绍：金凤集团全新力作——静态，动态，每日静态分红可免费可投资！一个币30元！首次投资 送1个币，每天静态1元，日签到0.2！<br>\r\n    　　\r\n    1.静态收益:注册激活需要购买最少一个币，不推广，每天收益最少1元，一个月最少30元，一年最少 360元 20个币每天分红20元，30个币每天分红30元，50个币每天分红50元，1000个币每天分红1000元… …以此类推，永久分红<br>\r\n    　　\r\n    2.市场推广：1，直推：一代奖励6% 领导 ：二代奖励4%. 三代2%2，您的1级会员每天分红赚钱您有20%的提成！3，您的2级会员每天分红赚钱您有10%的提成！4，您的3级会员每天分红赚钱您有5%的提成！<br>\r\n    　　\r\n    3提现管理：提现金额50.100 200 500 1000 3000 。 支付宝打款！每天提现一次！<br>\r\n    　　\r\n    4.团队奖励:1，直推10人，团队300人晋升为主管，奖励1万元。2，直推25人，团队500人晋升为经理，奖励5万元。3，直推35人，团队1000人晋升为总监，奖励10万元。4，直推50人，团队5000人，奖励50万，另加一部20万的轿车.欢迎各界人士加盟，机会创造财富！金凤一日投入终身受益！<br>\r\n    　　\r\n以上是金凤平台模式介绍；中国的金融商业理财模式，金上添金具有独特的理财思想，短期收入，中期团队获利，和后期的分红收益，让理财这不会有疲乏的感觉。。                                        </div>\r\n</div>', 1, 10);
INSERT INTO `xx_article` VALUES (9, NULL, '系统公告', '', NULL, 0, '暂无公告', 3, 10);
INSERT INTO `xx_article` VALUES (4, 1470012453, '帮助中心', '联系方式,联系电话,通信地址', '联系方式', 0, '帮助信息', 2, 10);
INSERT INTO `xx_article` VALUES (10, 1482401113, '11', '11', '11', 0, '11', 0, 10);

-- ----------------------------
-- Table structure for xx_config
-- ----------------------------
DROP TABLE IF EXISTS `xx_config`;
CREATE TABLE `xx_config`  (
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `c_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '中文名称',
  `remart` varchar(300) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '备注说明',
  `val` varchar(2000) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '',
  `status` tinyint(1) NULL DEFAULT 1,
  `sort` int(11) NULL DEFAULT 10,
  `list_type` tinyint(1) NULL DEFAULT 0 COMMENT '0:手动输入 长框 1:单选 2:下拉 3:文本域 4:图像 5:手动输入  短框',
  `val_arr` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '可选的值的集合，序列化存放',
  `group_id` tinyint(2) NULL DEFAULT 1 COMMENT '组ID',
  `is_show` tinyint(1) NULL DEFAULT 1 COMMENT '在后台是否显示配置 0-不显示  1-显示',
  PRIMARY KEY (`name`) USING BTREE,
  INDEX `main`(`name`, `status`, `sort`, `group_id`, `is_show`) USING BTREE
) ENGINE = MyISAM CHARACTER SET = utf8 COLLATE = utf8_bin COMMENT = '全站配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_config
-- ----------------------------
INSERT INTO `xx_config` VALUES ('daily_income', '每日收益系数', '收益币的每日收益，默认每天1个收益币获取1元', '1', 1, 4, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('sign_income', '每日签到收益', '每日签到可以获得的收益', '0.3', 1, 4, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('site_name', '网站名称', '网站的名称', '金上添金', 1, 1, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('worker_price', '旷工的价格', '旷工对应的价格', '10', 1, 6, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('site_switch', '网站开关', '当为1的时候为开，0为关闭', '1', 1, 2, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('first_level', '一级会员收益', '推荐的一级会员每日收益', '0.2', 1, 5, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('second_level', '二级会员收益', '推荐的二级会员每日收益', '0.1', 1, 5, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('three_level', '三级会员收益', '推荐的三级会员每日收益', '0.05', 1, 5, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('tuijian_switch', '推荐开关', '是否必须需要推荐才能注册 1-必须推荐才能注册', '1', 1, 10, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('system_alipay', '网站支付宝账号', '网站支付宝账号', '123@163.com', 1, 10, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('cash_num', '每日提现次数限制', '每日提现次数限制', '1', 1, 10, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('cash_min', '最小提现金额', '最小提现金额', '50', 1, 10, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('cash_fee', '提现手续费', '提现手续费', '0.1', 1, 10, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('site_url', '网站域名', '域名 如http://www.qq.com', 'http://www.weal.com', 1, 10, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('day_count', '每日发放收益币数量', '每日发放收益币数量上限值', '10000', 1, 9, 0, '', 1, 1);
INSERT INTO `xx_config` VALUES ('buy_max', '总积分币最大个数', '总积分币最大个数', '30', 1, 10, 0, '', 1, 1);

-- ----------------------------
-- Table structure for xx_daily_execute
-- ----------------------------
DROP TABLE IF EXISTS `xx_daily_execute`;
CREATE TABLE `xx_daily_execute`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `create_date` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '执行的日期',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '执行时间',
  `epoints` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '本次操作影响的金额',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `uid`, `create_date`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 22 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '每日奖励执行记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_daily_execute
-- ----------------------------
INSERT INTO `xx_daily_execute` VALUES (21, 16, '2019-02-26', 1551185114, 2.00);
INSERT INTO `xx_daily_execute` VALUES (20, 13, '2019-02-26', 1551185114, 15.00);

-- ----------------------------
-- Table structure for xx_day_sign
-- ----------------------------
DROP TABLE IF EXISTS `xx_day_sign`;
CREATE TABLE `xx_day_sign`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '签到时间',
  `sign_date` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '签到日期',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`uid`, `sign_date`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '每日签到表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_day_sign
-- ----------------------------
INSERT INTO `xx_day_sign` VALUES (11, 13, 1551185326, '2019-02-26');
INSERT INTO `xx_day_sign` VALUES (12, 13, 1552482324, '2019-03-13');
INSERT INTO `xx_day_sign` VALUES (13, 13, 1552713600, '2019-03-16');

-- ----------------------------
-- Table structure for xx_extract_apply
-- ----------------------------
DROP TABLE IF EXISTS `xx_extract_apply`;
CREATE TABLE `xx_extract_apply`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `epoints` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '提现金额',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请时间',
  `create_date` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '提现日期',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态 0-拒绝 1-提现完成,2-未审核',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员操作时间',
  `update_admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作的管理员id',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `uid`, `status`, `create_date`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '提现申请表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_extract_apply
-- ----------------------------
INSERT INTO `xx_extract_apply` VALUES (12, 13, 200.00, 1470279729, '2016-08-04', 1, '', 1470291622, 19);
INSERT INTO `xx_extract_apply` VALUES (19, 13, 50.00, 1550982622, '2019-02-24', 0, 'uuuuuu', 1552714974, 19);
INSERT INTO `xx_extract_apply` VALUES (18, 13, 100.00, 1550585439, '2019-02-19', 2, '', 1552486154, 19);

-- ----------------------------
-- Table structure for xx_level
-- ----------------------------
DROP TABLE IF EXISTS `xx_level`;
CREATE TABLE `xx_level`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '级别名称',
  `first_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '要求直推会员数量',
  `team_num` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '要求团队的会员数量',
  `bonus_amount` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '一次性奖励金额',
  `rate` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '每日增长的倍数，基础增长为1',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '显示排序，递减，大数在前',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `sort`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '级别配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_level
-- ----------------------------
INSERT INTO `xx_level` VALUES (2, '主管', 10, 300, 10000, 1, 1);
INSERT INTO `xx_level` VALUES (3, '经理', 25, 500, 50000, 1, 2);
INSERT INTO `xx_level` VALUES (4, '总监', 35, 1000, 100000, 1, 3);
INSERT INTO `xx_level` VALUES (5, '总经理', 50, 5000, 500000, 1, 4);

-- ----------------------------
-- Table structure for xx_level_apply
-- ----------------------------
DROP TABLE IF EXISTS `xx_level_apply`;
CREATE TABLE `xx_level_apply`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '会员id',
  `level_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请的级别ID',
  `text` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '申请说明',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 2 COMMENT '审核状态 2-未审核 1-审核通过 0-审核拒绝',
  `action_status` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否执行了奖励操作 0-未执行 1-执行',
  `remark` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '申请时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `uid`, `status`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '级别申请表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_level_apply
-- ----------------------------
INSERT INTO `xx_level_apply` VALUES (1, 13, 3, '11111', 1, 1, '', 1470208951, 1552717527);

-- ----------------------------
-- Table structure for xx_money_history
-- ----------------------------
DROP TABLE IF EXISTS `xx_money_history`;
CREATE TABLE `xx_money_history`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `action_type` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作类型 默认0-其他 1-利息 2-提现 3-充值 4-奖励 5-签到',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `epoints` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '金额',
  `original` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '操作之前金额',
  `after` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '操作之后的金额',
  `text` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '操作的文字说明',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`id`, `uid`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 99 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '钱包金额历史记录表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_money_history
-- ----------------------------
INSERT INTO `xx_money_history` VALUES (92, 13, 1, 1551185326, 0.20, 1015.40, 1015.60, '每日签到奖励');
INSERT INTO `xx_money_history` VALUES (91, 16, 1, 1551185114, 2.00, 102.20, 104.20, '每日利息');
INSERT INTO `xx_money_history` VALUES (89, 13, 4, 1551185114, 0.40, 1015.00, 1015.40, '一级推荐奖金20%');
INSERT INTO `xx_money_history` VALUES (90, 12, 4, 1551185114, 0.20, 32.90, 33.10, '二级推荐奖金10%');
INSERT INTO `xx_money_history` VALUES (88, 13, 1, 1551185114, 15.00, 1000.00, 1015.00, '每日利息');
INSERT INTO `xx_money_history` VALUES (87, 12, 4, 1551185114, 3.00, 29.90, 32.90, '一级推荐奖金20%');
INSERT INTO `xx_money_history` VALUES (93, 13, 0, 1552482308, 10.00, 1015.20, 1005.20, '购买矿工');
INSERT INTO `xx_money_history` VALUES (94, 13, 5, 1552482324, 0.20, 1005.20, 1005.40, '每日签到奖励');
INSERT INTO `xx_money_history` VALUES (95, 13, 0, 1552713478, 10.00, 905.40, 895.40, '购买矿工');
INSERT INTO `xx_money_history` VALUES (96, 13, 0, 1552713578, 10.00, 895.40, 885.40, '购买矿工');
INSERT INTO `xx_money_history` VALUES (97, 13, 5, 1552713600, 0.20, 885.40, 885.60, '每日签到奖励');
INSERT INTO `xx_money_history` VALUES (98, 13, 4, 1552717527, 50000.00, 885.60, 50885.60, '晋级经理奖励');

-- ----------------------------
-- Table structure for xx_recharge
-- ----------------------------
DROP TABLE IF EXISTS `xx_recharge`;
CREATE TABLE `xx_recharge`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL COMMENT '用户id',
  `order_no` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '流水编号',
  `amount` int(10) NOT NULL COMMENT '充值金额',
  `way` tinyint(1) NOT NULL COMMENT '充值方式,0-微信,1-支付宝',
  `status` tinyint(1) NOT NULL COMMENT '充值状态，2- 未付款，0-失败，1-成功',
  `created_date` int(11) NOT NULL,
  `updated_date` int(11) NULL DEFAULT NULL,
  `response` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '充值返回json',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 77 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '用户充值表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of xx_recharge
-- ----------------------------
INSERT INTO `xx_recharge` VALUES (54, 13, '2019012053495098', 1, 1, 2, 1547993413, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (55, 13, '2019012010099971', 1, 0, 2, 1547993421, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (56, 13, '2019012150979897', 10, 0, 2, 1548060706, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (57, 13, '2019012197525453', 10, 1, 1, 1548060714, 1548060727, '{\"channelOrderNum\":\"762019012122001483061013208306\",\"chcd\":\"ALP\",\"consumerAccount\":\"186****2143\",\"errorDetail\":\"SUCCESS\",\"orderNum\":\"2019012197525453\",\"txamt\":\"000000001000\"}');
INSERT INTO `xx_recharge` VALUES (58, 13, '2019012154529997', 1, 0, 2, 1548061030, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (59, 13, '2019012151101561', 1, 0, 2, 1548061043, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (60, 13, '2019012199515748', 1, 1, 1, 1548061052, 1548061062, '{\"channelOrderNum\":\"552019012122001483061013207389\",\"chcd\":\"ALP\",\"consumerAccount\":\"186****2143\",\"errorDetail\":\"SUCCESS\",\"orderNum\":\"2019012199515748\",\"txamt\":\"000000000100\"}');
INSERT INTO `xx_recharge` VALUES (61, 13, '2019012197559997', 11, 1, 2, 1548075850, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (62, 13, '2019012198102515', 1, 0, 2, 1548076491, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (63, 13, '2019012156984956', 1, 1, 1, 1548076504, 1548076515, '{\"channelOrderNum\":\"202019012122001483061013223483\",\"chcd\":\"ALP\",\"consumerAccount\":\"186****2143\",\"errorDetail\":\"SUCCESS\",\"orderNum\":\"2019012156984956\",\"txamt\":\"000000000100\"}');
INSERT INTO `xx_recharge` VALUES (64, 13, '2019012197495353', 1, 0, 2, 1548076794, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (65, 13, '2019012148551001', 1, 1, 2, 1548076816, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (66, 13, '2019021749575048', 31, 1, 2, 1550366497, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (67, 13, '2019021710010050', 31, 1, 2, 1550366589, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (68, 13, '2019021756995657', 31, 1, 2, 1550366616, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (69, 13, '2019021754555710', 31, 1, 2, 1550366630, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (70, 13, '2019021753505098', 30, 1, 2, 1550366677, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (71, 13, '2019021954100549', 31, 0, 2, 1550580102, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (72, 13, '2019021910197565', 31, 0, 2, 1550580158, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (73, 13, '2019021950101525', 31, 1, 2, 1550580226, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (74, 13, '2019021953101974', 31, 0, 2, 1550580261, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (75, 13, '2019021997101101', 31, 1, 2, 1550580282, NULL, NULL);
INSERT INTO `xx_recharge` VALUES (76, 13, '2019021950555450', 31, 0, 2, 1550580322, NULL, NULL);

-- ----------------------------
-- Table structure for xx_users
-- ----------------------------
DROP TABLE IF EXISTS `xx_users`;
CREATE TABLE `xx_users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `real_name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `user_pwd` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '登录密码',
  `user_pwd1` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '二级密码',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态 1-启用 0-禁用',
  `mobile` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '手机号码',
  `level_id` int(10) UNSIGNED NOT NULL DEFAULT 1 COMMENT '级别id，默认1 普通会员',
  `main` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '直接推荐用户，默认0，为顶级',
  `worker` int(10) NOT NULL DEFAULT 0 COMMENT '矿工数量',
  `path` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '推荐路径，最多记录到5级',
  `money` decimal(12, 2) NOT NULL DEFAULT 0.00 COMMENT '钱包金额',
  `alipay_no` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '支付宝账号',
  `alipay_pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '支付宝收款二维码url',
  `create_ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '注册IP',
  `login_count` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录次数',
  `last_login_ip` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册的时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `main`(`user_name`, `status`) USING BTREE,
  INDEX `main1`(`id`, `create_time`, `status`, `main`, `path`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '会员信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_users
-- ----------------------------
INSERT INTO `xx_users` VALUES (12, 'lanfengye', '', '1', '1', 1, '110', 1, 0, 0, '', 33.10, '', '', '', 0, '', 0, 0, 1482841001);
INSERT INTO `xx_users` VALUES (13, 'test', '张三12', '926e2b4cbba173cc36a4f67c734da0e0', 'c81e728d9d4c2f636f067f89cc14862c', 1, '18868881888', 3, 12, 1, '12', 50885.60, '123456', '/uploads/alipay/5514abe6b1db9c560549b309ae2bd488.jpg', '127.0.0.1', 27, '127.0.0.1', 1552708843, 0, 1482751964);
INSERT INTO `xx_users` VALUES (16, '12345678', '', '8ffde9b2bec703b0037c4d589a968fbf', '8ffde9b2bec703b0037c4d589a968fbf', 1, '14432465678', 1, 13, 0, '13,12', 104.20, '', '', '127.0.0.1', 3, '127.0.0.1', 1550369697, 1550369260, 1550369260);
INSERT INTO `xx_users` VALUES (17, '123456789', '', '8ffde9b2bec703b0037c4d589a968fbf', '8ffde9b2bec703b0037c4d589a968fbf', 1, '13423456789', 1, 16, 0, '16,13,12', 0.00, '', '', '127.0.0.1', 2, '127.0.0.1', 1550369740, 1550369728, 1550369728);
INSERT INTO `xx_users` VALUES (18, '1234567890', '', '8ffde9b2bec703b0037c4d589a968fbf', '8ffde9b2bec703b0037c4d589a968fbf', 1, '13324567898', 1, 17, 0, '17,16,13,12', 0.00, '', '', '127.0.0.1', 1, '127.0.0.1', 1550369771, 1550369771, 1550369771);
INSERT INTO `xx_users` VALUES (19, '145785654', '', '8ffde9b2bec703b0037c4d589a968fbf', '8ffde9b2bec703b0037c4d589a968fbf', 1, '15562523321', 1, 13, 0, '13,12', 0.00, '', '', '127.0.0.1', 1, '127.0.0.1', 1550980557, 1550980557, 1550980557);

-- ----------------------------
-- Table structure for xx_worker
-- ----------------------------
DROP TABLE IF EXISTS `xx_worker`;
CREATE TABLE `xx_worker`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `worker_type_id` int(11) NOT NULL COMMENT '矿工种类id',
  `work_time` int(11) NOT NULL DEFAULT 0 COMMENT '旷工工作时长',
  `status` tinyint(1) NOT NULL COMMENT '0-已停用，1-工作中',
  `create_time` int(11) NOT NULL COMMENT '旷工创建时间',
  `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Records of xx_worker
-- ----------------------------
INSERT INTO `xx_worker` VALUES (3, 13, 0, 0, 1, 1552713578, NULL);

-- ----------------------------
-- Table structure for xx_worker_type
-- ----------------------------
DROP TABLE IF EXISTS `xx_worker_type`;
CREATE TABLE `xx_worker_type`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '矿工名称',
  `price` int(10) NOT NULL COMMENT '矿工价格',
  `daily_income` int(10) NOT NULL COMMENT '每日收益',
  `retire` int(10) NOT NULL COMMENT '矿工退休金额',
  `work_time` tinyint(2) NOT NULL COMMENT '矿工工作时长',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of xx_worker_type
-- ----------------------------
INSERT INTO `xx_worker_type` VALUES (1, '普通矿工', 10, 1, 15, 15);
INSERT INTO `xx_worker_type` VALUES (2, '白银矿工', 328, 12, 0, 0);
INSERT INTO `xx_worker_type` VALUES (3, '黄金矿工', 628, 16, 0, 0);
INSERT INTO `xx_worker_type` VALUES (4, '钻石矿工', 928, 20, 0, 0);

SET FOREIGN_KEY_CHECKS = 1;
