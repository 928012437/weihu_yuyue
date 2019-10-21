<?php
/**
 * PHP定时任务
 * @author wuquanyao <git@yeahphp.com>
 * @link http://www.yeahphp.com
 */

/**
 * 任务列表
 * 格式:[执行间隔秒数, 要执行的命令]
 */
return
[
    //每隔1秒输出当前系统日期
    [86400, "/www/wdlinux/phps/56/bin/php /www/web/20190709_jnweishangjia_com/public_html/addons/weihu_yuyue/brithday.php"],
    //每隔5秒输出PHP-FPM运行情况
    // [5, "ps aux | grep 'php-fpm'"],
];
