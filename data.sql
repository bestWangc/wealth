-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2018 年 04 月 12 日 12:20
-- 服务器版本: 5.5.53
-- PHP 版本: 5.4.45

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `test`
--

-- --------------------------------------------------------

--
-- 表的结构 `xx_admin`
--

CREATE TABLE IF NOT EXISTS `xx_admin` (
  `ly_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `ly_name` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '管理员名称',
  `ly_pwd` char(32) COLLATE utf8_bin NOT NULL COMMENT '管理员密码',
  `sort` int(11) DEFAULT '10',
  PRIMARY KEY (`ly_id`),
  KEY `ly_name` (`ly_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=20 ;

--
-- 转存表中的数据 `xx_admin`
--

INSERT INTO `xx_admin` (`ly_id`, `ly_name`, `ly_pwd`, `sort`) VALUES
(19, 'admin', '21232f297a57a5a743894a0e4a801fc3', 10);

-- --------------------------------------------------------

--
-- 表的结构 `xx_admin_bihistory`
--

CREATE TABLE IF NOT EXISTS `xx_admin_bihistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `epoints` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `admin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员充值收益币表' AUTO_INCREMENT=28 ;

--
-- 转存表中的数据 `xx_admin_bihistory`
--

INSERT INTO `xx_admin_bihistory` (`id`, `uid`, `create_time`, `epoints`, `admin`) VALUES
(26, 13, 1470278339, '10.00', 19),
(27, 13, 1470278486, '1.00', 19);

-- --------------------------------------------------------

--
-- 表的结构 `xx_admin_moneyhistory`
--

CREATE TABLE IF NOT EXISTS `xx_admin_moneyhistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `epoints` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `admin` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='管理员充值钱包历史记录表' AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `xx_admin_moneyhistory`
--

INSERT INTO `xx_admin_moneyhistory` (`id`, `uid`, `create_time`, `epoints`, `admin`) VALUES
(26, 13, 1470278339, '10.00', 19);

-- --------------------------------------------------------

--
-- 表的结构 `xx_article`
--

CREATE TABLE IF NOT EXISTS `xx_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '编号，自动递增',
  `input_time` int(11) DEFAULT NULL COMMENT '录入时间',
  `title` varchar(50) COLLATE utf8_bin DEFAULT '' COMMENT '名称',
  `seo_keys` varchar(200) COLLATE utf8_bin DEFAULT '' COMMENT 'seo页面关键词',
  `seo_description` varchar(500) COLLATE utf8_bin DEFAULT NULL COMMENT 'SEO页面简介',
  `num` int(11) DEFAULT '0' COMMENT '浏览次数',
  `content` text COLLATE utf8_bin COMMENT '文章内容',
  `kind_id` int(11) DEFAULT '0' COMMENT '类别ID，用来进行访问查找，唯一标识',
  `sort` int(11) DEFAULT '10' COMMENT '后台排序',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`kind_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `xx_article`
--

INSERT INTO `xx_article` (`id`, `input_time`, `title`, `seo_keys`, `seo_description`, `num`, `content`, `kind_id`, `sort`) VALUES
(3, 1482733158, '关于我们', '公司简介,公司介绍', '公司简介', 0, ' <div class="col-lg-12">\r\n                                            关于我们：金凤平台一个全新的理财模式，目前虚拟卡币投资，每天分红收益不断，结合分销模式，直销模式多项奖励直推奖，团队奖，分红奖；打造一个全新的模式平台，给人焕然一新。在此给大家分享分享金凤模式的流程运作。<br>\r\n                                            　　\r\n                                            金凤平台介绍：金凤集团全新力作——静态，动态，每日静态分红可免费可投资！一个币30元！首次投资 送1个币，每天静态1元，日签到0.2！<br>\r\n                                            　　\r\n                                            1.静态收益:注册激活需要购买最少一个币，不推广，每天收益最少1元，一个月最少30元，一年最少 360元 20个币每天分红20元，30个币每天分红30元，50个币每天分红50元，1000个币每天分红1000元… …以此类推，永久分红<br>\r\n                                            　　\r\n                                            2.市场推广：1，直推：一代奖励6% 领导 ：二代奖励4%. 三代2%2，您的1级会员每天分红赚钱您有20%的提成！3，您的2级会员每天分红赚钱您有10%的提成！4，您的3级会员每天分红赚钱您有5%的提成！<br>\r\n                                            　　\r\n                                            3提现管理：提现金额50.100 200 500 1000 3000 。 支付宝打款！每天提现一次！<br>\r\n                                            　　\r\n                                            4.团队奖励:1，直推10人，团队300人晋升为主管，奖励1万元。2，直推25人，团队500人晋升为经理，奖励5万元。3，直推35人，团队1000人晋升为总监，奖励10万元。4，直推50人，团队5000人，奖励50万，另加一部20万的轿车.欢迎各界人士加盟，机会创造财富！金凤一日投入终身受益！<br>\r\n                                            　　\r\n                                        以上是金凤平台模式介绍；中国的金融虚拟币商业理财模式，金凤具有独特的理财思想，短期收入，中期团队获利，和后期的分红收益，让理财这不会有疲乏的感觉。。                                        </div>\r\n                                    </div>', 1, 10),
(9, NULL, '系统公告', '', NULL, 0, '系统公告', 3, 10),
(4, 1470012453, '帮助中心', '联系方式,联系电话,通信地址', '联系方式', 0, '帮助信息', 2, 10),
(10, 1482401113, '11', '11', '11', 0, '11', 0, 10);

-- --------------------------------------------------------

--
-- 表的结构 `xx_config`
--

CREATE TABLE IF NOT EXISTS `xx_config` (
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `c_name` varchar(100) COLLATE utf8_bin DEFAULT '' COMMENT '中文名称',
  `remart` varchar(300) COLLATE utf8_bin DEFAULT '' COMMENT '备注说明',
  `val` varchar(2000) COLLATE utf8_bin DEFAULT '',
  `status` tinyint(1) DEFAULT '1',
  `sort` int(11) DEFAULT '10',
  `list_type` tinyint(1) DEFAULT '0' COMMENT '0:手动输入 长框 1:单选 2:下拉 3:文本域 4:图像 5:手动输入  短框',
  `val_arr` varchar(200) COLLATE utf8_bin DEFAULT '' COMMENT '可选的值的集合，序列化存放',
  `group_id` tinyint(2) DEFAULT '1' COMMENT '组ID',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '在后台是否显示配置 0-不显示  1-显示',
  PRIMARY KEY (`name`),
  KEY `main` (`name`,`status`,`sort`,`group_id`,`is_show`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='全站配置表';

--
-- 转存表中的数据 `xx_config`
--

INSERT INTO `xx_config` (`name`, `c_name`, `remart`, `val`, `status`, `sort`, `list_type`, `val_arr`, `group_id`, `is_show`) VALUES
('daily_income', '每日收益系数', '收益币的每日收益，默认每天1个收益币获取1元', '1', 1, 4, 0, '', 1, 1),
('sign_income', '每日签到收益', '每日签到可以获得的收益', '0.2', 1, 4, 0, '', 1, 1),
('site_name', '网站名称', '网站的名称', '乐金惠', 1, 1, 0, '', 1, 1),
('bi_price', '收益币的价格', '收益币对应的价格', '30', 1, 6, 0, '', 1, 1),
('site_switch', '网站开关', '当为1的时候为开，0为关闭', '1', 1, 2, 0, '', 1, 1),
('qq', '客服QQ', '客户QQ号码', '993424780', 1, 3, 0, '', 1, 1),
('first_level', '一级会员收益', '推荐的一级会员每日收益', '0.2', 1, 5, 0, '', 1, 1),
('second_level', '二级会员收益', '推荐的二级会员每日收益', '0.1', 1, 5, 0, '', 1, 1),
('three_level', '三级会员收益', '推荐的三级会员每日收益', '0.05', 1, 5, 0, '', 1, 1),
('tuijian_switch', '推荐开关', '是否必须需要推荐才能注册 1-必须推荐才能注册', '1', 1, 10, 0, '', 1, 1),
('system_alipay', '网站支付宝账号', '网站支付宝账号', '123@163.com', 1, 10, 0, '', 1, 1),
('cash_num', '每日提现次数限制', '每日提现次数限制', '1', 1, 10, 0, '', 1, 1),
('cash_min', '最小提现金额', '最小提现金额', '50', 1, 10, 0, '', 1, 1),
('cash_fee', '提现手续费', '提现手续费', '0.05', 1, 10, 0, '', 1, 1),
('site_url', '网站域名', '域名 如http://www.qq.com', 'http://localhost/', 1, 10, 0, '', 1, 1),
('day_count', '每日发放收益币数量', '每日发放收益币数量上限值', '10000', 1, 9, 0, '', 1, 1),
('buy_max', '总积分币最大个数', '总积分币最大个数', '30', 1, 10, 0, '', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xx_daily_execute`
--

CREATE TABLE IF NOT EXISTS `xx_daily_execute` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `create_date` varchar(20) NOT NULL DEFAULT '' COMMENT '执行的日期',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '执行时间',
  `epoints` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '本次操作影响的金额',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`,`create_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='每日奖励执行记录' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `xx_daily_execute`
--

INSERT INTO `xx_daily_execute` (`id`, `uid`, `create_date`, `create_time`, `epoints`) VALUES
(11, 13, '2016-08-04', 1470288735, '11.00'),
(12, 14, '2016-08-04', 1470288735, '2.00'),
(13, 15, '2016-08-04', 1470288735, '5.00'),
(14, 13, '2016-12-22', 1482399223, '220.00'),
(15, 14, '2016-12-22', 1482399223, '40.00'),
(16, 13, '2016-12-26', 1482750635, '11.00'),
(17, 13, '2016-12-27', 1482841067, '11.00');

-- --------------------------------------------------------

--
-- 表的结构 `xx_day_sign`
--

CREATE TABLE IF NOT EXISTS `xx_day_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '签到时间',
  `sign_date` varchar(20) NOT NULL DEFAULT '' COMMENT '签到日期',
  PRIMARY KEY (`id`),
  KEY `main` (`uid`,`sign_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='每日签到表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `xx_day_sign`
--

INSERT INTO `xx_day_sign` (`id`, `uid`, `create_time`, `sign_date`) VALUES
(1, 13, 1470206132, '1970-01-01'),
(2, 13, 1470275493, '2016-08-04'),
(3, 13, 1482731759, '2016-12-26'),
(4, 13, 1482843543, '2016-12-27');

-- --------------------------------------------------------

--
-- 表的结构 `xx_jibie`
--

CREATE TABLE IF NOT EXISTS `xx_jibie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '级别名称',
  `zhitui_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '要求直推会员数量',
  `team_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '要求团队的会员数量',
  `bonus_amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '一次性奖励金额',
  `rate` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '每日增长的倍数，基础增长为1',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '显示排序，递减，大数在前',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='级别配置表' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `xx_jibie`
--

INSERT INTO `xx_jibie` (`id`, `title`, `zhitui_num`, `team_num`, `bonus_amount`, `rate`, `sort`) VALUES
(2, '主管', 10, 300, 10000, 1, 1),
(3, '经理', 25, 500, 50000, 1, 2),
(4, '总监', 35, 1000, 100000, 1, 3),
(5, '总经理', 50, 5000, 500000, 1, 4);

-- --------------------------------------------------------

--
-- 表的结构 `xx_jibie_apply`
--

CREATE TABLE IF NOT EXISTS `xx_jibie_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `jibie_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请的级别ID',
  `text` varchar(200) NOT NULL DEFAULT '' COMMENT '申请说明',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态 0-未审核 1-审核通过 2-审核拒绝',
  `action_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否执行了奖励操作 0-未执行 1-执行',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='级别申请表' AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `xx_jibie_apply`
--

INSERT INTO `xx_jibie_apply` (`id`, `uid`, `jibie_id`, `text`, `create_time`, `status`, `action_status`, `remark`) VALUES
(1, 13, 3, '11111', 1470208951, 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `xx_moneyhistory`
--

CREATE TABLE IF NOT EXISTS `xx_moneyhistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `action_type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型 默认0-其他 1-利息 2-提现 3-充值 4-奖励 5-签到',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `epoints` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `original` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '操作之前金额',
  `after` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '操作之后的金额',
  `text` varchar(100) NOT NULL DEFAULT '' COMMENT '操作的文字说明',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='钱包金额历史记录表' AUTO_INCREMENT=68 ;

--
-- 转存表中的数据 `xx_moneyhistory`
--

INSERT INTO `xx_moneyhistory` (`id`, `uid`, `action_type`, `create_time`, `epoints`, `original`, `after`, `text`) VALUES
(1, 13, 2, 1470203099, '-100.00', '1300.00', '1200.00', '提现'),
(2, 13, 6, 1470203117, '100.00', '1200.00', '1300.00', '取消提现'),
(3, 13, 2, 1470203136, '-100.00', '1300.00', '1200.00', '提现'),
(4, 13, 6, 1470203966, '100.00', '1200.00', '1300.00', '取消提现'),
(5, 13, 2, 1470204047, '-50.00', '1300.00', '1250.00', '提现'),
(6, 13, 2, 1470204047, '-2.50', '1250.00', '1247.50', '提现手续费0.05'),
(7, 13, 6, 1470204098, '50.00', '1247.50', '1297.50', '取消提现'),
(8, 13, 6, 1470204098, '2.50', '1297.50', '1300.00', '取消提现手续费返还'),
(9, 13, 2, 1470204982, '-50.00', '1300.00', '1250.00', '提现'),
(10, 13, 2, 1470204982, '-2.50', '1250.00', '1247.50', '提现手续费0.05'),
(11, 13, 6, 1470204984, '50.00', '1247.50', '1297.50', '取消提现'),
(12, 13, 6, 1470204984, '2.50', '1297.50', '1300.00', '取消提现手续费返还'),
(13, 13, 2, 1470204989, '-1000.00', '1300.00', '300.00', '提现'),
(14, 13, 2, 1470204989, '-50.00', '300.00', '250.00', '提现手续费0.05'),
(15, 13, 0, 1470205132, '-30.00', '250.00', '220.00', '购买收益币'),
(16, 13, 0, 1470205185, '-30.00', '220.00', '190.00', '购买收益币'),
(17, 13, 0, 1470205190, '-30.00', '190.00', '160.00', '购买收益币'),
(18, 13, 0, 1470205195, '-30.00', '160.00', '130.00', '购买收益币'),
(19, 13, 0, 1470205495, '-30.00', '130.00', '100.00', '购买收益币'),
(20, 13, 5, 1470206132, '0.20', '100.00', '100.20', '每日签到奖励'),
(21, 13, 5, 1470275493, '0.20', '100.20', '100.40', '每日签到奖励'),
(22, 13, 3, 1470277800, '100.00', '200.40', '300.40', '管理员操作'),
(23, 13, 3, 1470277830, '200.00', '500.40', '700.40', '管理员操作'),
(24, 13, 3, 1470277871, '100.00', '700.40', '800.40', '管理员操作'),
(25, 13, 3, 1470277947, '10.00', '800.40', '810.40', '管理员操作'),
(26, 13, 3, 1470278339, '10.00', '810.40', '820.40', '管理员操作'),
(27, 13, 2, 1470279729, '-200.00', '820.40', '620.40', '提现'),
(28, 13, 2, 1470279729, '-10.00', '620.40', '610.40', '提现手续费0.05'),
(29, 12, 4, 1470288273, '2.20', '0.00', '2.20', '一级推荐奖金'),
(30, 13, 1, 1470288273, '11.00', '610.40', '621.40', '每日利息'),
(31, 12, 4, 1470288273, '0.40', '2.20', '2.60', '一级推荐奖金'),
(32, 14, 1, 1470288273, '2.00', '0.00', '2.00', '每日利息'),
(33, 13, 4, 1470288273, '1.00', '621.40', '622.40', '一级推荐奖金'),
(34, 12, 4, 1470288273, '0.50', '2.60', '3.10', '二级推荐奖金'),
(35, 15, 1, 1470288273, '5.00', '0.00', '5.00', '每日利息'),
(36, 12, 4, 1470288541, '2.20', '3.10', '5.30', '一级推荐奖金'),
(37, 13, 1, 1470288541, '11.00', '622.40', '633.40', '每日利息'),
(38, 12, 4, 1470288541, '0.40', '5.30', '5.70', '一级推荐奖金'),
(39, 13, 4, 1470288541, '0.20', '633.40', '633.60', '二级推荐奖金'),
(40, 14, 1, 1470288541, '2.00', '2.00', '4.00', '每日利息'),
(41, 13, 4, 1470288541, '1.00', '633.60', '634.60', '一级推荐奖金'),
(42, 12, 4, 1470288541, '0.50', '5.70', '6.20', '二级推荐奖金'),
(43, 15, 1, 1470288541, '5.00', '5.00', '10.00', '每日利息'),
(44, 12, 4, 1470288735, '2.20', '6.20', '8.40', '一级推荐奖金20%'),
(45, 13, 1, 1470288735, '11.00', '634.60', '645.60', '每日利息'),
(46, 12, 4, 1470288735, '0.40', '8.40', '8.80', '一级推荐奖金20%'),
(47, 13, 4, 1470288735, '0.20', '645.60', '645.80', '二级推荐奖金10%'),
(48, 14, 1, 1470288735, '2.00', '4.00', '6.00', '每日利息'),
(49, 13, 4, 1470288735, '1.00', '645.80', '646.80', '一级推荐奖金20%'),
(50, 12, 4, 1470288735, '0.50', '8.80', '9.30', '二级推荐奖金10%'),
(51, 15, 1, 1470288735, '5.00', '10.00', '15.00', '每日利息'),
(52, 13, 3, 1470290918, '30.00', '646.80', '676.80', '充值'),
(53, 13, 3, 1470291020, '30.00', '676.80', '706.80', '充值'),
(54, 13, 4, 1470297202, '50000.00', '706.80', '50706.80', '晋级经理奖励'),
(55, 13, 4, 1470297328, '50000.00', '50706.80', '100706.80', '晋级经理奖励'),
(56, 12, 4, 1482399223, '11.00', '9.30', '20.30', '一级推荐奖金5%'),
(57, 13, 1, 1482399223, '220.00', '100706.80', '100926.80', '每日利息'),
(58, 12, 4, 1482399223, '2.00', '20.30', '22.30', '一级推荐奖金5%'),
(59, 13, 4, 1482399223, '1.60', '100926.80', '100928.40', '二级推荐奖金4%'),
(60, 14, 1, 1482399223, '40.00', '6.00', '46.00', '每日利息'),
(61, 13, 5, 1482731759, '0.20', '100928.40', '100928.60', '每日签到奖励'),
(62, 12, 4, 1482750635, '2.20', '22.30', '24.50', '一级推荐奖金20%'),
(63, 13, 1, 1482750635, '11.00', '100928.60', '100939.60', '每日利息'),
(64, 12, 4, 1482841067, '2.20', '24.50', '26.70', '一级推荐奖金20%'),
(65, 13, 1, 1482841067, '11.00', '100939.60', '100950.60', '每日利息'),
(66, 13, 5, 1482843543, '0.20', '100950.60', '100950.80', '每日签到奖励'),
(67, 13, 0, 1482844086, '-30.00', '100950.80', '100920.80', '购买收益币');

-- --------------------------------------------------------

--
-- 表的结构 `xx_tixian_apply`
--

CREATE TABLE IF NOT EXISTS `xx_tixian_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `epoints` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '提现金额',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请时间',
  `create_date` varchar(20) NOT NULL DEFAULT '' COMMENT '提现日期',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0-未审核 1-提现完成',
  `remark` varchar(100) NOT NULL DEFAULT '' COMMENT '备注',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '管理员操作时间',
  `update_admin_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作的管理员id',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`,`status`,`create_date`) USING BTREE
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='提现申请表' AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `xx_tixian_apply`
--

INSERT INTO `xx_tixian_apply` (`id`, `uid`, `epoints`, `create_time`, `create_date`, `status`, `remark`, `update_time`, `update_admin_id`) VALUES
(12, 13, '200.00', 1470279729, '2016-08-04', 1, '', 1470291622, 19),
(11, 13, '1000.00', 1470204989, '1970-01-01', 0, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xx_users`
--

CREATE TABLE IF NOT EXISTS `xx_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pwd` varchar(40) NOT NULL DEFAULT '' COMMENT '登录密码',
  `user_pwd1` varchar(40) NOT NULL DEFAULT '' COMMENT '二级密码',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册的时间',
  `last_login_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(100) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `create_ip` varchar(100) NOT NULL DEFAULT '' COMMENT '注册IP',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后修改时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1-启用 0-禁用',
  `real_name` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `zhifubao_no` varchar(100) NOT NULL DEFAULT '' COMMENT '支付宝账号',
  `nick_name` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号码',
  `jibie_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '级别id，默认1 普通会员',
  `main` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '直接推荐用户，默认0，为顶级',
  `path` varchar(200) NOT NULL DEFAULT '' COMMENT '推荐路径，最多记录到5级',
  `money` decimal(12,2) NOT NULL DEFAULT '0.00' COMMENT '钱包金额',
  `bi` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收益币数量，默认为0 ',
  PRIMARY KEY (`id`),
  KEY `main` (`user_name`,`status`),
  KEY `main1` (`id`,`create_time`,`status`,`main`,`path`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='会员信息表' AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `xx_users`
--

INSERT INTO `xx_users` (`id`, `user_name`, `user_pwd`, `user_pwd1`, `create_time`, `last_login_time`, `last_login_ip`, `create_ip`, `login_count`, `update_time`, `status`, `real_name`, `zhifubao_no`, `nick_name`, `mobile`, `jibie_id`, `main`, `path`, `money`, `bi`) VALUES
(12, 'lanfengye', '1', '1', 0, 0, '', '', 0, 1482841001, 1, '', '', '', '110', 1, 0, '', '26.70', 0),
(13, '1', 'c4ca4238a0b923820dcc509a6f75849b', 'c81e728d9d4c2f636f067f89cc14862c', 0, 1523533743, '127.0.0.1', '127.0.0.1', 16, 1482751964, 1, '张三12', '123456', '1234', '1', 3, 12, '12', '100920.80', 12);

-- --------------------------------------------------------

--
-- 表的结构 `xx_zhifubao_chongzhi`
--

CREATE TABLE IF NOT EXISTS `xx_zhifubao_chongzhi` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员id',
  `epoints` decimal(12,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `liushui_no` varchar(100) NOT NULL DEFAULT '' COMMENT '支付宝流水号码',
  `text` varchar(100) NOT NULL DEFAULT '' COMMENT '其他说明',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0-未入账 1-已入账 2-错误拒绝',
  `action_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `main` (`id`,`uid`,`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='支付宝充值申请' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `xx_zhifubao_chongzhi`
--

INSERT INTO `xx_zhifubao_chongzhi` (`id`, `uid`, `epoints`, `create_time`, `liushui_no`, `text`, `status`, `action_time`) VALUES
(3, 13, '30.00', 1470201025, 'gfgfd', '', 1, 1470291020);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
