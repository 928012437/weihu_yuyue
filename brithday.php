<?php
error_reporting(0);
require("/www/web/20190709_jnweishangjia_com/public_html/framework/bootstrap.inc.php");
global $_W;
global $_GPC;

ignore_user_abort();
set_time_limit(0);

$brithdaymember=pdo_fetchall("select * from ".tablename('weihu_yuyue_member')." where uniacid=:uniacid MONTH(birthday) = MONTH(NOW()) and DAY(birthday) = DAY(NOW()) ",array(':uniacid'=>$_W['uniacid']));

foreach ($brithdaymember as $v){
    tpmessage($v['openid']);
}

function tpmessage($openid)
{
    global $_W;

    $uniacid=$_W['uniacid'];

    $set=pdo_get('weihu_yuyue_set',array('uniacid'=>$uniacid));
    $template_id=$set['tmpid'];
    $topcolor = '#FF0000';

    $member=pdo_get('weihu_yuyue_member',array('openid'=>$openid));
    $url=$this->createMobileUrl('index',array(),true,true);

    if (!empty($template_id))
    {
        $datas = array(
            'first' => array(
                'value' => '生日快乐', 'color' => '#173177'
            ),
            'keyword1' => array('value' => time(), 'color' => '#173177'),
            'keyword2' => array('value' => '祝您生日快乐！', 'color' => '#173177'),
            'remark' => array(
                'value' => '', 'color' => '#173177'
            ),
        );

        $data = json_encode($datas);

        load()->func('communication');

        $account_api = WeAccount::create();
        $tokens = $account_api->getAccessToken();

        if (empty($tokens))
        {
            $account_api->clearAccessToken();

            $tokens = $account_api->getAccessToken();
        }
        $postarr = '{"touser":"' . $member['openid'] . '","template_id":"' . $template_id . '","url":"' . $url . '","topcolor":"' . $topcolor . '","data":' . $data . '}';
        $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $tokens, $postarr);

    }
}