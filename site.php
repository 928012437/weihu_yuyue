<?php

defined('IN_IA') or exit('Access Denied');

class Weihu_yuyueModuleSite extends WeModuleSite {

    public function doWebset()
    {
        global $_W, $_GPC;

        $uniacid=$_W['uniacid'];

        $set=pdo_get('weihu_yuyue_set',array('uniacid'=>$uniacid));

        if($_W['ispost']){
            $data=array(
                'uniacid'=>$uniacid,
                'tmpid'=>$_GPC['tmpid'],
                'yytype'=>$_GPC['yytype']
            );
            if(empty($set)){
                pdo_insert('weihu_yuyue_set',$data);
            }else{
                pdo_update('weihu_yuyue_set',$data,array('id'=>$set['id']));
            }
            message('修改成功',$this->createWebUrl('set'),'success');
        }

        include $this->template('set');
    }


    public function doWebmember() {
        global $_W,$_GPC;

        if($_W['ispost']&&!empty($_GPC['mid'])){

            $data=array(
                'name'=>$_GPC['name'],
                'mobile'=>$_GPC['mobile'],
                'content'=>$_GPC['content'],
                'status'=>$_GPC['status'],
                'ismanage'=>$_GPC['ismanage'],
                'birthday'=>strtotime($_GPC['birthday']),
            );
            pdo_update('weihu_yuyue_member',$data,array('id'=>$_GPC['mid']));
            message('修改成功',$this->createWebUrl('member'),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);

        if(!empty($_GPC['keyword'])){
            $condition.=" and (nickname like '%{$_GPC['keyword']}%' or name like '%{$_GPC['keyword']}%' or mobile like '%{$_GPC['keyword']}%' )";
        }
        if(!empty($_GPC['time'])){
            $condition.=" and createtime >= ".strtotime($_GPC['time']['start'])." and createtime < ".strtotime('+1 day',strtotime($_GPC['time']['end']));
        }

        if($_GPC['export']==1){
            $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member') . (' WHERE 1 ' . $condition . ' order by createtime desc  ') , $params);

            foreach ($list as &$v){
                $v['total1'] = pdo_fetchcolumn('SELECT sum(price) FROM ' . tablename('weihu_yuyue_membercard') . " where mid=:mid and `type`=1 ", array(':mid'=>$v['id']));
                $v['total2'] = pdo_fetchcolumn('SELECT sum(price) FROM ' . tablename('weihu_yuyue_membercard') . " where mid=:mid and `type`=2 ", array(':mid'=>$v['id']));
                $v['total3'] = pdo_fetchcolumn('SELECT sum(price) FROM ' . tablename('weihu_yuyue_membercard') . " where mid=:mid ", array(':mid'=>$v['id']));
            }
            unset($v);

            require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getProperties()->setCreator("ctos")
                ->setLastModifiedBy("ctos")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);

            //设置行高度
            $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);

            //set font size bold
            $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

            //设置水平居中
            $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

            // set table header content
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', '姓名')
                ->setCellValue('B1', '手机号')
                ->setCellValue('C1', '生日')
                ->setCellValue('D1', '购卡总金额')
                ->setCellValue('E1', '线上购卡金额')
                ->setCellValue('F1', '实体卡金额')
                ->setCellValue('G1', '注册时间');

            // Miscellaneous glyphs, UTF-8

            for($i=0;$i<count($list);$i++){
                $objPHPExcel->getActiveSheet(0)
                    ->setCellValue('A'.($i+2), $list[$i]['name'])
                    ->setCellValue('B'.($i+2), $list[$i]['mobile'])
                    ->setCellValue('C'.($i+2), date('Y-m-d',$list[$i]['birthday']))
                    ->setCellValue('D'.($i+2), $list[$i]['total3'])
                    ->setCellValue('E'.($i+2), $list[$i]['total1'])
                    ->setCellValue('F'.($i+2), $list[$i]['total2'])
                    ->setCellValue('G'.($i+2),  date('Y-m-d H:i',$list[$i]['createtime']))
                    ->getRowDimension($i+2)->setRowHeight(16);
            }

            //  sheet命名
            $objPHPExcel->getActiveSheet()->setTitle('用户记录');

            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $objPHPExcel->setActiveSheetIndex(0);

            // excel头参数
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="用户记录('.date('Y年m月d日H时i分s秒').').xls"');  //日期为文件名后缀
            header('Cache-Control: max-age=0');

            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel5为xls格式，excel2007为xlsx格式

            $objWriter->save('php://output');
        }

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member') . (' WHERE 1 ' . $condition . ' order by createtime desc limit ')  . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_member') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        include $this->template('member');
    }

    public function doWebshiming() {
        global $_W,$_GPC;

        if($_W['ispost']&&!empty($_GPC['mid'])){

            $data=array('content'=>$_GPC['content']);
            pdo_update('weihu_yuyue_shiming',$data,array('mid'=>$_GPC['mid']));

            message('操作成功',$this->createWebUrl('shiming'),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);

        if(!empty($_GPC['keyword'])){
            $condition.=" and (idcardnum like '%{$_GPC['keyword']}%' )";
        }
        if(!empty($_GPC['time'])){
            $condition.=" and createtime >= ".strtotime($_GPC['time']['start'])." and createtime < ".strtotime('+1 day',strtotime($_GPC['time']['end']));
        }

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_shiming') . (' WHERE 1 ' . $condition . ' order by createtime desc limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_shiming') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        include $this->template('shiming');
    }

    public function doWebchangeshiming(){
        global $_W,$_GPC;

        $memberlog=pdo_get('weihu_yuyue_shiming',array('id'=>$_GPC['id']));

        if(empty($memberlog)){
            message('信息错误');
        }elseif (!empty($memberlog['status'])){
            message('该实名信息已处理');
        }

        $data=array();

        if($_GPC['type']==1){
            $data['status']=1;
        }elseif($_GPC['type']==2){
            $data['status']=2;
        }else{
            message('参数错误');
        }

        pdo_update('weihu_yuyue_shiming',$data,array('id'=>$memberlog['id']));

        $mid=$_GPC['mid'];

        message('操作成功',$this->createWebUrl('shiming',array('mid'=>$mid)),'success');
    }

    public function doWebchangememberlog(){
        global $_W,$_GPC;

        $memberlog=pdo_get('weihu_yuyue_member_log',array('id'=>$_GPC['id']));

        if(empty($memberlog)){
            message('信息错误');
        }elseif (!empty($memberlog['status'])){
            message('该订单已处理');
        }

        $data=array(
            'manageid'=>-1
        );

        if($_GPC['type']==1){
            $data['status']=1;
        }elseif($_GPC['type']==2){
            $data['status']=2;
            $mcard=pdo_get('weihu_yuyue_membercard',array('id'=>$memberlog['mcardid']));
            pdo_update('weihu_yuyue_membercard',array('snum'=>($mcard['snum']+1)),array('id'=>$mcard['id']));
        }else{
            message('参数错误');
        }

        pdo_update('weihu_yuyue_member_log',$data,array('id'=>$memberlog['id']));

        $mid=$_GPC['mid'];

        message('操作成功',$this->createWebUrl('order',array('mid'=>$mid)),'success');
    }

    public function doWebmembercard(){
        global $_W,$_GPC;

        if(!empty($_GPC['mid'])){
            $member = pdo_get('weihu_yuyue_member',array('id'=>$_GPC['mid']));
        }

        if($_W['ispost']&&!empty($_GPC['change'])){

                pdo_update('weihu_yuyue_membercard',array('content'=>$_GPC['content']),array('id'=>$_GPC['id']));

            message('操作成功',$this->createWebUrl('membercard',array('mid'=>$_GPC['mid'])),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);

        if(!empty($_GPC['mid'])){
            $condition.=' and mid=:mid ';
            $params[':mid']=$_GPC['mid'];
        }

        if(!empty($_GPC['keyword'])){
            $condition.=" and cardnum like '%{$_GPC['keyword']}%' ";
        }
        if(!empty($_GPC['time'])){
            $condition.=" and starttime >= ".strtotime($_GPC['time']['start'])." and starttime < ".strtotime('+1 day',strtotime($_GPC['time']['end']));
        }

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_membercard') . (' WHERE 1 ' . $condition . ' order by starttime desc limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_membercard') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);
        if(empty($_GPC['mid'])){
            foreach ($list as &$v){
                $v['member']=pdo_get('weihu_yuyue_member',array('id'=>$v['mid']));
            }
            unset($v);
        }

        include $this->template('membercard');
    }

    public function doWebmembercardexport(){
        global $_W,$_GPC;

        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);
        if(!empty($_GPC['mid'])){
            $condition.=' and mid=:mid ';
            $params[':mid']=$_GPC['mid'];
        }

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_membercard') . (' WHERE 1 ' . $condition . ' order by starttime desc ') , $params);

        foreach ($list as &$v){
            $v['member']=pdo_get('weihu_yuyue_member',array('id'=>$v['mid']));
            $v['starttime']=date('Y-m-d H:i',$v['starttime']);
            $v['endtime']=date('Y-m-d H:i',$v['endtime']);
        }
        unset($v);

        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);

        //设置行高度
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);


        //set font size bold
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        // set table header content
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '姓名')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '卡名')
            ->setCellValue('D1', '天数')
            ->setCellValue('E1', '次数')
            ->setCellValue('F1', '价格')
            ->setCellValue('G1', '备注')
            ->setCellValue('H1', '开始时间')
            ->setCellValue('I1', '结束时间');


        // Miscellaneous glyphs, UTF-8

        for($i=0;$i<count($list);$i++){
            $objPHPExcel->getActiveSheet(0)
                ->setCellValue('A'.($i+2), $list[$i]['member']['name'])
                ->setCellValue('B'.($i+2), $list[$i]['member']['mobile'])
                ->setCellValue('C'.($i+2), $list[$i]['name'])
                ->setCellValue('D'.($i+2), $list[$i]['daynum'])
                ->setCellValue('E'.($i+2), $list[$i]['num'])
                ->setCellValue('F'.($i+2), $list[$i]['price'])
                ->setCellValue('G'.($i+2), $list[$i]['content'])
                ->setCellValue('H'.($i+2), $list[$i]['starttime'])
                ->setCellValue('I'.($i+2), $list[$i]['endtime'])
                ->getRowDimension($i+2)->setRowHeight(16);
        }

        $member=pdo_get('weihu_yuyue_member',array('id'=>$_GPC['mid']));
        //  sheet命名
        $objPHPExcel->getActiveSheet()->setTitle((empty($member['name'])?'':$member['name']).'办卡记录');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // excel头参数
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.(empty($member['name'])?'':$member['name']).'办卡记录('.date('Y年m月d日H时i分s秒').').xls"');  //日期为文件名后缀
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel5为xls格式，excel2007为xlsx格式

        $objWriter->save('php://output');

    }

    public function doWeborder(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];

        if($_W['ispost']&&!empty($_GPC['change'])){
            $log=pdo_get('weihu_yuyue_member_log',array('id'=>$_GPC['id']));
            $mid=$log['mid'];
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'content'=>$_GPC['content'],
            );
            pdo_update('weihu_yuyue_member_log',$data,array('id'=>$_GPC['id']));

            message('操作成功',$this->createWebUrl('order',array('mid'=>$mid)),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $uniacid);

        if(!empty($_GPC['mid'])){
            $condition.=' and mid=:mid ';
            $params[':mid']=$_GPC['mid'];
        }

        if(!empty($_GPC['keyword'])){
            $condition.=" and (ordernum like '%{$_GPC['keyword']}%' )";
        }
        if(!empty($_GPC['time'])){
            $condition.=" and createtime >= ".strtotime($_GPC['time']['start'])." and createtime < ".strtotime('+1 day',strtotime($_GPC['time']['end']));
        }

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition . ' order by createtime desc limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);


            foreach ($list as &$v){
                if(empty($_GPC['mid'])){
                $v['member']=pdo_get('weihu_yuyue_member',array('id'=>$v['mid']));
                }
                $v['member2']=pdo_get('weihu_yuyue_member',array('id'=>$v['manageid']));
                $v['membercard']=pdo_get('weihu_yuyue_membercard',array('id'=>$v['mcardid']));
            }
            unset($v);

        $hospital=pdo_getall('weihu_yuyue_hospital',array('uniacid'=>$uniacid),'','id');
        $kscate=pdo_getall('weihu_yuyue_kscate',array('uniacid'=>$uniacid),'','id');

        include $this->template('order');
    }

    public function doWebmemberlogexport(){
        global $_W,$_GPC;

        $uniacid=$_W['uniacid'];

        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $uniacid);
        if(!empty($_GPC['mid'])){
            $condition.=' and mid=:mid ';
            $params[':mid']=$_GPC['mid'];
        }

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition . ' order by createtime desc ') , $params);

        foreach ($list as &$v){
            $v['member']=pdo_get('weihu_yuyue_member',array('id'=>$v['mid']));
            $v['member2']=pdo_get('weihu_yuyue_member',array('id'=>$v['manageid']));
            $v['seektime']=date('Y-m-d H:i',$v['seektime']);
            $v['createtime']=date('Y-m-d H:i',$v['createtime']);
            $v['membercard']=pdo_get('weihu_yuyue_membercard',array('id'=>$v['mcardid']));
        }
        unset($v);

        $hospital=pdo_getall('weihu_yuyue_hospital',array('uniacid'=>$uniacid),'','id');
        $kscate=pdo_getall('weihu_yuyue_kscate',array('uniacid'=>$uniacid),'','id');

        require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel.php';

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("ctos")
            ->setLastModifiedBy("ctos")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");

        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(18);
        $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(18);

        //设置行高度
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);


        //set font size bold
        $objPHPExcel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        //设置水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('I')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('J')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('K')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle('L')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        // set table header content
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '姓名')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '订单号')
            ->setCellValue('D1', '医院')
            ->setCellValue('E1', '科室')
            ->setCellValue('F1', '卡号')
            ->setCellValue('G1', '预约时间')
            ->setCellValue('H1', '状态')
            ->setCellValue('I1', '备注')
            ->setCellValue('J1', '提交时间')
            ->setCellValue('K1', '核销员姓名')
            ->setCellValue('L1', '核销员手机号');


        // Miscellaneous glyphs, UTF-8

        for($i=0;$i<count($list);$i++){
            $objPHPExcel->getActiveSheet(0)
                ->setCellValue('A'.($i+2), $list[$i]['member']['name'])
                ->setCellValue('B'.($i+2), $list[$i]['member']['mobile'])
                ->setCellValue('C'.($i+2), $list[$i]['ordernum'])
                ->setCellValue('D'.($i+2), $hospital[$list[$i]['hid']]['name'])
                ->setCellValue('E'.($i+2), $kscate[$list[$i]['ksid']]['name'].'-'.$kscate[$list[$i]['ksid2']]['name'])
                ->setCellValue('F'.($i+2), $list[$i]['membercard']['cardnum'])
                ->setCellValue('G'.($i+2), $list[$i]['seektime'])
                ->setCellValue('H'.($i+2), $list[$i]['status']==0?'已预约':($list[$i]['status']==1?'已完成':'已取消'))
                ->setCellValue('I'.($i+2), $list[$i]['content'])
                ->setCellValue('J'.($i+2), $list[$i]['createtime'])
                ->setCellValue('K'.($i+2), $list[$i]['member2']['name'])
                ->setCellValue('L'.($i+2), $list[$i]['member2']['mobile'])
                ->getRowDimension($i+2)->setRowHeight(16);
        }

        $member=pdo_get('weihu_yuyue_member',array('id'=>$_GPC['mid']));
        //  sheet命名
        $objPHPExcel->getActiveSheet()->setTitle((empty($member['name'])?'':$member['name']).'扫码记录');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // excel头参数
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.(empty($member['name'])?'':$member['name']).'扫码记录('.date('Y年m月d日H时i分s秒').').xls"');  //日期为文件名后缀
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //excel5为xls格式，excel2007为xlsx格式

        $objWriter->save('php://output');

    }

    public function doWebcard() {
        global $_W,$_GPC;

        if(!empty($_GPC['del'])){
            pdo_delete('weihu_yuyue_card',array('id'=>$_GPC['id']));
            message('删除成功',$this->createWebUrl('card'),'success');
        }
        if($_W['ispost']){
            if(empty($_GPC['name'])||empty($_GPC['daynum'])){
                message('信息不完整');
            }
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'name'=>$_GPC['name'],
                'daynum'=>$_GPC['daynum'],
                'price'=>$_GPC['price'],
                'num'=>$_GPC['num'],
                'discount'=>$_GPC['discount'],
                'discountcolor'=>$_GPC['discountcolor'],
                'content'=>$_GPC['content'],
                'status'=>$_GPC['status'],
                'thumb'=>$_GPC['thumb'],
            );
            if(empty($_GPC['id'])){
                pdo_insert('weihu_yuyue_card',$data);
            }else{
                pdo_update('weihu_yuyue_card',$data,array('id'=>$_GPC['id']));
            }

            message('操作成功',$this->createWebUrl('card'),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_card') . (' WHERE 1 ' . $condition . ' limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_card') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        include $this->template('card');
    }

    public function doWebentitycard() {
        global $_W,$_GPC;

        if(empty($_GPC['cid'])){
            message('未选择卡！');
        }

        if(!empty($_GPC['del'])){
            pdo_delete('weihu_yuyue_entitycard',array('id'=>$_GPC['id']));
            message('删除成功',$this->createWebUrl('entitycard',array('cid'=>$_GPC['cid'])),'success');
        }

        if($_W['ispost']){
            if(empty($_GPC['cardnum'])||empty($_GPC['actcode'])){
                message('信息不完整');
            }
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'cid'=>$_GPC['cid'],
                'cardnum'=>$_GPC['cardnum'],
                'actcode'=>$_GPC['actcode'],
                'content'=>$_GPC['content'],
                'status'=>$_GPC['status'],
            );
            if(empty($_GPC['id'])){
                pdo_insert('weihu_yuyue_entitycard',$data);
            }else{
                pdo_update('weihu_yuyue_entitycard',$data,array('id'=>$_GPC['id']));
            }

            message('操作成功',$this->createWebUrl('entitycard',array('cid'=>$_GPC['cid'])),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid and cid='.$_GPC['cid'];
        $params = array(':uniacid' => $_W['uniacid']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_entitycard') . (' WHERE 1 ' . $condition . ' limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_entitycard') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);
        foreach ($list as &$item) {
            $item['member']=pdo_get('weihu_yuyue_member',array('id'=>$item['jhmid']));
        }
        unset($item);

        include $this->template('entitycard');
    }

    public function doWebhexiao() {
        global $_W,$_GPC;

        if($_W['ispost']){
            $data=array(
                'name'=>$_GPC['name'],
                'mobile'=>$_GPC['mobile'],
                'content'=>$_GPC['content'],
                'status'=>$_GPC['status'],
                'ismanage'=>$_GPC['ismanage'],
                'birthday'=>strtotime($_GPC['birthday']),
            );
            pdo_update('weihu_yuyue_member',$data,array('id'=>$_GPC['mid']));
            message('修改成功',$this->createWebUrl('hexiao'),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid and ismanage=1 ';
        $params = array(':uniacid' => $_W['uniacid']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member') . (' WHERE 1 ' . $condition . '  ORDER BY createtime DESC limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_member') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        include $this->template('hexiao');
    }

    public function doWebhospital() {
        global $_W,$_GPC;

        if(!empty($_GPC['del'])){
            pdo_delete('weihu_yuyue_hospital',array('id'=>$_GPC['id']));
            message('删除成功',$this->createWebUrl('hospital'),'success');
        }
        if($_W['ispost']){
            if(empty($_GPC['name'])||empty($_GPC['province'])||empty($_GPC['map'])){
                message('信息不完整');
            }
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'name'=>$_GPC['name'],
                'province'=>$_GPC['province'],
                'city'=>$_GPC['city'],
                'area'=>$_GPC['area'],
                'lng'=>$_GPC['map']['lng'],
                'lat'=>$_GPC['map']['lat'],
                'content'=>$_GPC['content'],
                'status'=>$_GPC['status'],
            );
            if(empty($_GPC['id'])){
                pdo_insert('weihu_yuyue_hospital',$data);
            }else{
                pdo_update('weihu_yuyue_hospital',$data,array('id'=>$_GPC['id']));
            }

            message('操作成功',$this->createWebUrl('hospital'),'success');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $_W['uniacid']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_hospital') . (' WHERE 1 ' . $condition . ' limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_hospital') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        include $this->template('hospital');
    }

    public function doWebkscate() {
        global $_W,$_GPC;

        if(empty($_GPC['hid'])){
            message('未选择医院！');
        }

        if(!empty($_GPC['del'])){
            pdo_delete('weihu_yuyue_kscate',array('id'=>$_GPC['id']));
            message('删除成功',$this->createWebUrl('kscate',array('hid'=>$_GPC['hid'])),'success');
        }

        if($_W['ispost']){
            if(empty($_GPC['name'])){
                message('信息不完整');
            }
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'name'=>$_GPC['name'],
                'hid'=>$_GPC['hid'],
                'sjid'=>$_GPC['sjid'],
                'content'=>$_GPC['content'],
                'status'=>$_GPC['status'],
                'ysopenid'=>$_GPC['ysopenid']
            );
            if(empty($_GPC['id'])){
                pdo_insert('weihu_yuyue_kscate',$data);
            }else{
                pdo_update('weihu_yuyue_kscate',$data,array('id'=>$_GPC['id']));
            }

            message('操作成功',$this->createWebUrl('kscate',array('hid'=>$_GPC['hid'])),'success');
        }

        $condition = ' and uniacid=:uniacid and sjid=0 and hid='.$_GPC['hid'];
        $params = array(':uniacid' => $_W['uniacid']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_kscate') . (' WHERE 1 ' . $condition ) , $params);

        foreach ($list as &$v){
            $condition2 = ' and uniacid=:uniacid and sjid='.$v['id'].' and hid='.$_GPC['hid'];
            $v['xiaji']=pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_kscate') . (' WHERE 1 ' . $condition2 ) , $params);
            $v['ysmember']=pdo_get('weihu_yuyue_member',array('openid'=>$v['ysopenid']));
            foreach ($v['xiaji'] as &$v2){
                $v2['ysmember']=pdo_get('weihu_yuyue_member',array('openid'=>$v2['ysopenid']));
            }
            unset($v2);
        }
        unset($v);

        include $this->template('kscate');
    }

    public function doWebgetmemberajax(){
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];

        $condition = ' and uniacid=:uniacid ';
        $params = array(':uniacid' => $uniacid);

        if(!empty($_GPC['keyword'])){
            $condition.=" and (name like '%{$_GPC['keyword']}%' or mobile like '%{$_GPC['keyword']}%')";
            $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member') . (' WHERE 1 ' . $condition . ' order by createtime desc ') , $params);

            echo json_encode($list);
        }else{
            echo 0;
        }

    }

    public function doMobileindex2() {
        global $_W,$_GPC;
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }
        $uniacid=$_W['uniacid'];
        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        if(empty($member)){
            header('location:'.$this->createMobileUrl('index'));
        }else if($member['status']==0){
            message('账号已禁用');
        }else if(empty($member['ismanage'])){
            message('账号无权限');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 200;
        $condition = ' and uniacid=:uniacid and manageid=:manageid ';
        $params = array(':uniacid' => $uniacid,':manageid'=>$member['id']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition . ' order by createtime desc limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        foreach ($list as &$v){
            $v['member']=pdo_get('weihu_yuyue_member',array('id'=>$v['mid']));
            $v['membercard']=pdo_get('weihu_yuyue_membercard',array('id'=>$v['mcardid']));
        }
        unset($v);

        $hospital=pdo_getall('weihu_yuyue_hospital',array('uniacid'=>$uniacid),'','id');
        $kscate=pdo_getall('weihu_yuyue_kscate',array('uniacid'=>$uniacid),'','id');

        include $this->template('index2');
    }

    public function doMobilemenu() {
        global $_W,$_GPC;
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }
        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        load()->model('mc');
        $fans=mc_oauth_userinfo();

        if(empty($member)){
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid'=>$_W['openid'],
                'nickname'=>$fans['nickname'],
                'avatar'=>$fans['avatar'],
            );
            $data['createtime']=time();
            $data['status']=1;
            pdo_insert('weihu_yuyue_member',$data);
        }else if($member['status']==0){
            message('账号已禁用');
        }

        include $this->template('menu');
    }

    public function doMobileindex() {
        global $_W,$_GPC;
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }
        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        load()->model('mc');
        $fans=mc_oauth_userinfo();

        if(empty($member)){
            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid'=>$_W['openid'],
                'nickname'=>$fans['nickname'],
                'avatar'=>$fans['avatar'],
            );
            $data['createtime']=time();
            $data['status']=1;
            pdo_insert('weihu_yuyue_member',$data);
        }else if($member['status']==0){
            message('账号已禁用');
        }

        if($_W['ispost']){

            if(empty($_GPC['name'])||empty($_GPC['mobile'])||empty($_GPC['birthday'])){
//                message('信息不全');
                echo json_encode(array('status'=>0,'msg'=>'信息不全'));die;
            }

            if(!preg_match("/^1[3456789]{1}\d{9}$/",$_GPC['mobile'])){
                echo json_encode(array('status'=>0,'msg'=>'手机号格式错误'));die;
            }

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'openid'=>$_W['openid'],
                'nickname'=>$_GPC['nickname'],
                'avatar'=>$_GPC['avatar'],
                'name'=>$_GPC['name'],
                'mobile'=>$_GPC['mobile'],
                'birthday'=>strtotime($_GPC['birthday']),
            );

            if (empty($member)){
                $data['createtime']=time();
                $data['status']=1;
                pdo_insert('weihu_yuyue_member',$data);
            }else{
                pdo_update('weihu_yuyue_member',$data,array('id'=>$member['id']));
            }
//            message('修改成功','','success');
            echo json_encode(array('status'=>1));die;
        }

        include $this->template('index');
    }

    public function doMobileshiming() {
        global $_W,$_GPC;
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }
        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        load()->model('mc');
        $fans=mc_oauth_userinfo();

        if(empty($member)){
            header('location:'.$this->createMobileUrl('index'));
        }else if($member['status']==0){
            message('账号已禁用');
        }else if(empty($member['name'])||empty($member['mobile'])||empty($member['birthday'])){
            message('请先填写个人信息');
        }

        $shiming=pdo_get('weihu_yuyue_shiming',array('mid'=>$member['id']));
        if($_W['ispost']){

            if(empty($_GPC['idcardnum'])){
                echo json_encode(array('status'=>0,'msg'=>'信息不全'));die;
            }

            if(!empty($shiming)){
                if($shiming['status']==0){
                    echo json_encode(array('status'=>0,'msg'=>'审核中请耐心等待'));die;
                }
                if($shiming['status']==1){
                    echo json_encode(array('status'=>0,'msg'=>'审核已通过'));die;
                }
            }

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'mid'=>$member['id'],
                'idcardnum'=>$_GPC['idcardnum'],
                'status'=>0,
                'createtime'=>time()
            );

            if(!empty($_FILES["imgfile1"]["tmp_name"])){
                $time=time();
                $sjtext1=rand(10,99);
                move_uploaded_file($_FILES["imgfile1"]["tmp_name"],dirname(__FILE__)."/imgfile/if1" . $sjtext1.$time .'.jpg');
                $data['photo']=$_W['siteroot'].'addons/weihu_yuyue/imgfile/if1'. $sjtext1.$time .'.jpg';

                if(intval($_FILES['imgfile1']['size']/1024)>300){
                    $url=$_W['siteroot'].'addons/weihu_yuyue/imgprocess.php';
                    $data2=array('file'=>'imgfile/if1'. $sjtext1.$time .'.jpg');
                    $this->imgprocesspost($url,$data2);
                }
            }

            if (empty($shiming)){
                pdo_insert('weihu_yuyue_shiming',$data);
            }else{
                pdo_update('weihu_yuyue_shiming',$data,array('id'=>$shiming['id']));
            }

            echo json_encode(array('status'=>1));die;
        }

        include $this->template('shiming');
    }

    public function doMobilemembercard() {
        global $_W,$_GPC;
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }

        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        if(empty($member)){
            header('location:'.$this->createMobileUrl('index'));
        }else if($member['status']==0){
            message('账号已禁用');
        }else if(empty($member['name'])||empty($member['mobile'])||empty($member['birthday'])){
            message('请先填写个人信息');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 100;
        $condition = ' and uniacid=:uniacid and mid=:mid ';
        $params = array(':uniacid' => $_W['uniacid'],':mid'=>$member['id']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_membercard') . (' WHERE 1 ' . $condition . ' order by starttime desc limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_membercard') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        $cards=pdo_getall('weihu_yuyue_card',array('uniacid'=>$_W['uniacid'],'status'=>0));

        include $this->template('membercard');
    }

    public function doMobileentitycard() {
        global $_W,$_GPC;
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }

        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        if(empty($member)){
            header('location:'.$this->createMobileUrl('index'));
        }else if($member['status']==0){
            message('账号已禁用');
        }else if(empty($member['name'])||empty($member['mobile'])||empty($member['birthday'])){
            message('请先填写个人信息');
        }

        if($_W['ispost']){

            if(empty($_GPC['cardnum'])||empty($_GPC['actcode'])){
                echo json_encode(array('status'=>0,'msg'=>'信息不全'));die;
            }

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'cardnum'=>$_GPC['cardnum'],
                'actcode'=>$_GPC['actcode'],
            );

            $entity=pdo_get('weihu_yuyue_entitycard',$data);

            if(empty($entity)){
                echo json_encode(array('status'=>0,'msg'=>'实体卡不存在'));die;
            }
            if($entity['status']==1){
                echo json_encode(array('status'=>0,'msg'=>'失败，该卡已激活'));die;
            }

            $card=pdo_get('weihu_yuyue_card',array('id'=>$entity['cid']));
            $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

            if(empty($card)){
                echo json_encode(array('status'=>0,'msg'=>'实体卡错误'));die;
            }

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'mid'=>$member['id'],
                'cid'=>$entity['cid'],
                'name'=>$card['name'],
                'num'=>$card['num'],
                'snum'=>$card['num'],
                'daynum'=>$card['daynum'],
                'price'=>$card['price'],
                'starttime'=>time(),
                'endtime'=>time()+$card['daynum']*24*60*60,
                'content'=>'',
                'cardnum'=>$_GPC['cardnum'],
                'type'=>2
            );

            pdo_update('weihu_yuyue_entitycard',array('status'=>1,'jhmid'=>$member['id']),array('id'=>$entity['id']));
            pdo_insert('weihu_yuyue_membercard',$data);

            $mcid=pdo_insertid();
            $this->tpmessage($mcid);

            echo json_encode(array('status'=>1));die;
        }

        include $this->template('entitycard');
    }

    public function doMobileguahao() {
        global $_W,$_GPC;
        $uniacid=$_W['uniacid'];
        if(empty($_W['openid'])){
            message('请在微信端打开');
        }

        $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        if(empty($member)){
            header('location:'.$this->createMobileUrl('index'));
        }else if($member['status']==0){
            message('账号已禁用');
        }else if(empty($member['name'])||empty($member['mobile'])||empty($member['birthday'])){
            message('请先填写个人信息');
        }

        $shiming=pdo_get('weihu_yuyue_shiming',array('mid'=>$member['id']));

        if(empty($shiming)){
            message('未实名认证',$this->createMobileUrl('shiming'),'error');
        }elseif(empty($shiming['status'])){
            message('实名信息审核中请耐心等待',$this->createMobileUrl('shiming'),'error');
        }
        if($shiming['status']==2){
            message('实名认证未通过请重新提交',$this->createMobileUrl('shiming'),'error');
        }

        if($_W['ispost']){
            if(empty($_GPC['hid'])||empty($_GPC['ksid'])||empty($_GPC['ksid2'])||empty($_GPC['mcardid'])||empty($_GPC['seektime'])||empty($_GPC['yytype'])){
                echo json_encode(array('status'=>0,'msg'=>'信息不全'));die;
            }

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'ordernum'=>'MO'.time(),
                'mid'=>$member['id'],
                'hid'=>$_GPC['hid'],
                'ksid'=>$_GPC['ksid'],
                'ksid2'=>$_GPC['ksid2'],
                'mcardid'=>$_GPC['mcardid'],
                'seektime'=>strtotime($_GPC['seektime']),
                'yytype'=>$_GPC['yytype'],
                'status'=>0,
                'createtime'=>time(),
            );

            $mcard=pdo_get('weihu_yuyue_membercard',array('id'=>$_GPC['mcardid']));
            pdo_update('weihu_yuyue_membercard',array('snum'=>($mcard['snum']-1)),array('id'=>$mcard['id']));
            pdo_insert('weihu_yuyue_member_log',$data);

            $logid=pdo_insertid();
            $this->tpmessage2($logid);

            echo json_encode(array('status'=>1));die;

        }

        $mcards=pdo_fetchall("select * from ".tablename('weihu_yuyue_membercard')." where uniacid=:uniacid and snum>0 and endtime>=:nowtime ",array(':uniacid'=>$_W['uniacid'],':nowtime'=>strtotime(date('Y-m-d').' 0:0:0')));

        $cards=pdo_getall('weihu_yuyue_card',array('uniacid'=>$_W['uniacid'],'status'=>0),'','id');
        $set=pdo_get('weihu_yuyue_set',array('uniacid'=>$uniacid));
        $yytype=str_replace('，',',',$set['yytype']);
        $yytype=explode(',',$yytype);



        include $this->template('guahao');
    }

    public function doMobileorder()
    {
        global $_W, $_GPC;
        $uniacid = $_W['uniacid'];

//        $member = pdo_get('weihu_yuyue_member', array('id' => 5));

        if (empty($_W['openid'])) {
            message('请在微信端打开');
        }

        $member = pdo_get('weihu_yuyue_member', array('openid' => $_W['openid']));

        if (empty($member)) {
            header('location:' . $this->createMobileUrl('index'));
        } else if ($member['status'] == 0) {
            message('账号已禁用');
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 100;
        $condition = ' and uniacid=:uniacid and mid=:mid ';
        $params = array(':uniacid' => $uniacid,':mid'=>$member['id']);

        $list = pdo_fetchall('SELECT * FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition . ' order by createtime desc limit ') . ($pindex - 1) * $psize . ',' . $psize, $params);
        $total = pdo_fetchcolumn('SELECT count(*) FROM ' . tablename('weihu_yuyue_member_log') . (' WHERE 1 ' . $condition), $params);
        $pager = pagination($total, $pindex, $psize);

        foreach ($list as &$v){
            $v['membercard']=pdo_get('weihu_yuyue_membercard',array('id'=>$v['mcardid']));
            $v['member2']=pdo_get('weihu_yuyue_member',array('id'=>$v['manageid']));
        }
        unset($v);

        $hospital=pdo_getall('weihu_yuyue_hospital',array('uniacid'=>$uniacid),'','id');
        $kscate=pdo_getall('weihu_yuyue_kscate',array('uniacid'=>$uniacid),'','id');

        include $this->template('order');
    }

    public function doMobilegethospital()
    {
        global $_W, $_GPC;
        $unicaid=$_W['uniacid'];
        $lat = floatval($_GPC['lat']);
        $lng = floatval($_GPC['lng']);

        if (empty($_W['openid'])) {
            echo '';die;
        }

        $hospital=pdo_getall('weihu_yuyue_hospital',array('uniacid'=>$unicaid,'status'=>0));

        foreach ($hospital as $k => $v) {
            if ($lat != 0 && $lng != 0 && !empty($v['lat']) && !empty($v['lng'])) {
                $distance = $this->GetDistance($lat, $lng, $v['lat'], $v['lng'], 2);

                $hospital[$k]['distance'] = $distance;
            } else {
                $hospital[$k]['distance'] = 100000;
            }

        }

        $hospitals = $this->multi_array_sort($hospital, 'distance');

        echo json_encode($hospitals);die;

    }

    public function doMobilegetkscate()
    {
        global $_W, $_GPC;
        $unicaid=$_W['uniacid'];
        $hid=$_GPC['hid'];
        $kscate=pdo_getall('weihu_yuyue_kscate',array('uniacid'=>$unicaid,'hid'=>$hid,'sjid'=>0,'status'=>0));

        echo json_encode($kscate);die;

    }

    public function doMobilegetkscate2()
    {
        global $_W, $_GPC;
        $unicaid=$_W['uniacid'];
        $hid=$_GPC['hid'];
        $kscate=pdo_getall('weihu_yuyue_kscate',array('uniacid'=>$unicaid,'hid'=>$hid,'sjid'=>$_GPC['sjid'],'status'=>0));

        echo json_encode($kscate);die;

    }

    public function doMobileWxpay(){
        global $_W,$_GPC;
        if(empty($_GPC['cardid'])){
            message('未选择卡');
        }

        $card=pdo_get('weihu_yuyue_card',array('id'=>$_GPC['cardid']));

        $params = array(
            'tid' => $card['id'].'|MC'.time(),      //充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' => 'MC'.time(),  //收银台中显示的订单号
            'title' => '购卡：'.$card['name'],          //收银台中显示的标题
            'fee' => $card['price'],      //收银台中显示需要支付的金额,只能大于 0
            'user' => $_W['member']['uid'],     //付款用户, 付款的用户名(选填项)
        );

        include $this->template('pay');
    }

    public function payResult($params) {
        global $_W,$_GPC;

        if ($params['result'] == 'success' && $params['from'] == 'return') {
            $tidarr=explode('|',$params['tid']);
            $cardid=$tidarr[0];
            $card=pdo_get('weihu_yuyue_card',array('id'=>$cardid));
            $member=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

            $data=array(
                'uniacid'=>$_W['uniacid'],
                'mid'=>$member['id'],
                'cid'=>$cardid,
                'name'=>$card['name'],
                'num'=>$card['num'],
                'snum'=>$card['num'],
                'daynum'=>$card['daynum'],
                'price'=>$params['fee'],
                'starttime'=>time(),
                'endtime'=>time()+$card['daynum']*24*60*60,
                'content'=>'',
                'cardnum'=>'MC'.time(),
                'type'=>1
            );

            pdo_insert('weihu_yuyue_membercard',$data);

            $mcid=pdo_insertid();
            $this->tpmessage($mcid);

            message('支付成功',$this->createMobileUrl('index',array('paysuccess'=>1)),'success');
        }else{
            message('支付出错，购卡失败',$this->createMobileUrl('index'));
        }
    }

    public function doMobilegetqrcode(){
        global $_W,$_GPC;
        require_once IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
        $value=$this->createMobileUrl('qrcode',array('id'=>$_GPC['id']),true,true);
        $errorCorrectionLevel = 'L';  //容错级别
        $matrixPointSize = 5;      //生成图片大小
        //生成二维码图片
        $QR = QRcode::png($value,false,$errorCorrectionLevel, $matrixPointSize, 2);
    }

    public function doMobileqrcode(){
        global $_W,$_GPC;

        $memberlog=pdo_get('weihu_yuyue_member_log',array('id'=>$_GPC['id']));

        $member2=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        if(empty($member2['ismanage'])){
            message('账号权限不足');
        }

        if(empty($memberlog)){
            message('二维码错误');
        }

        include $this->template('qrcode');
    }

    public function doMobilechangememberlog(){
        global $_W,$_GPC;

        $memberlog=pdo_get('weihu_yuyue_member_log',array('id'=>$_GPC['id']));

        $member2=pdo_get('weihu_yuyue_member',array('openid'=>$_W['openid']));

        if(empty($member2['ismanage'])){
            echo json_encode(array('status'=>0,'msg'=>'账号权限不足'));die;
        }

        if(empty($memberlog)){
            echo json_encode(array('status'=>0,'msg'=>'信息错误'));die;
        }elseif (!empty($memberlog['status'])){
            echo json_encode(array('status'=>0,'msg'=>'失败，该订单已处理'));die;
        }

            $data=array(
                'manageid'=>$member2['id']
            );

        if($_GPC['type']==1){
            $data['status']=1;
        }elseif($_GPC['type']==2){
            $data['status']=2;
            $mcard=pdo_get('weihu_yuyue_membercard',array('id'=>$memberlog['mcardid']));
            pdo_update('weihu_yuyue_membercard',array('snum'=>($mcard['snum']+1)),array('id'=>$mcard['id']));
        }else{
            echo json_encode(array('status'=>0,'msg'=>'参数错误'));die;
        }

        pdo_update('weihu_yuyue_member_log',$data,array('id'=>$memberlog['id']));

        echo json_encode(array('status'=>1));die;
    }

    function tpmessage($mcid)
    {
        global $_W;

        $uniacid=$_W['uniacid'];

        $set=pdo_get('weihu_yuyue_set',array('uniacid'=>$uniacid));
        $template_id=$set['tmpid'];
        $topcolor = '#FF0000';

        $mc=pdo_get('weihu_yuyue_membercard',array('id'=>$mcid));
        $member=pdo_get('weihu_yuyue_member',array('id'=>$mc['mid']));
        $url=$this->createMobileUrl('index',array(),true,true);

        if (!empty($template_id))
        {
            $datas = array(
                'first' => array(
                    'value' => '会员卡开通成功', 'color' => '#173177'
                ),
                'keyword1' => array('value' => time(), 'color' => '#173177'),
                'keyword2' => array('value' => '卡名：'.$mc['name'].'，价格：'.$mc['price'].'，天数：'.$mc['daynum'].'，次数：'.$mc['num'], 'color' => '#173177'),
                'remark' => array(
                    'value' => '有效期：'.date('Y-m-d',$mc['starttime']).'~'.date('Y-m-d',$mc['endtime']), 'color' => '#173177'
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

    function tpmessage2($logid)
    {
        global $_W;

        $uniacid=$_W['uniacid'];

        $set=pdo_get('weihu_yuyue_set',array('uniacid'=>$uniacid));
        $template_id=$set['tmpid'];
        $topcolor = '#FF0000';

        $log=pdo_get('weihu_yuyue_member_log',array('id'=>$logid));
        $member=pdo_get('weihu_yuyue_member',array('id'=>$log['mid']));
        $hospital=pdo_get('weihu_yuyue_hospital',array('id'=>$log['hid']));
        $kscate=pdo_get('weihu_yuyue_kscate',array('id'=>$log['ksid']));
        $kscate2=pdo_get('weihu_yuyue_kscate',array('id'=>$log['ksid2']));
        $mcard=pdo_get('weihu_yuyue_membercard',array('id'=>$log['mcardid']));
        $url=$this->createMobileUrl('menu',array(),true,true);

        if (!empty($template_id))
        {
            $datas = array(
                'first' => array(
                    'value' => '有用户提交预约', 'color' => '#173177'
                ),
                'keyword1' => array('value' => time(), 'color' => '#173177'),
                'keyword2' => array('value' => '订单号：'.$log['ordernum'].',卡号：'.$mcard['cardnum'].'，预约类型：'.$log['yytype'], 'color' => '#173177'),
                'remark' => array(
                    'value' => '用户姓名：'.$member['name'].'，电话：'.$member['mobile'], 'color' => '#173177'
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
            $postarr = '{"touser":"' . $kscate2['ysopenid'] . '","template_id":"' . $template_id . '","url":"' . $url . '","topcolor":"' . $topcolor . '","data":' . $data . '}';
            $res = ihttp_post('https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $tokens, $postarr);

        }
    }

    public function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $pi = 3.1415926;
        $er = 6378.137;
        $radLat1 = $lat1 * $pi / 180;
        $radLat2 = $lat2 * $pi / 180;
        $a = $radLat1 - $radLat2;
        $b = $lng1 * $pi / 180 - $lng2 * $pi / 180;
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * $er;
        $s = round($s * 1000);
        if (1 < $len_type) {
            $s /= 1000;
        }
        return round($s, $decimal);
    }

    public function multi_array_sort($multi_array, $sort_key, $sort = SORT_ASC)
    {
        if (is_array($multi_array)) {
            foreach ($multi_array as $row_array) {
                if (is_array($row_array)) {
                    $key_array[] = $row_array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_array, $sort, $multi_array);
        return $multi_array;
    }

    function imgprocesspost($url, $data) {

        //初使化init方法
        $ch = curl_init();

        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);

        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);

        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //发送请求
        $output = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        //返回数据
        return $output;
    }

}