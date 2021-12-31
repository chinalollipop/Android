<?php

/**
 * 根据支付类型，获取支付方式参数pay_type，支付方式代码PayCode，第三方简写sPrifix
 *
 * @param  $aRow          当前第三方支付数据
 * @param  $reqData       当前请求参数
 * @Return $returnData    回传参数 (pay_type,PayCode,sPrifix)
 */
function CompanyPayType($aRow ,$reqData) {
        $returnData = [];
        // 2为银行卡支付，4微信支付，5为支付宝,6为QQ扫码
        // iPayCode 支付方式代码
        switch ($aRow['account_company']){
            case 2: //银行卡支付
                if($aRow['thirdpay_code'] == 'fkt') {
                    $pay_type = 2;
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='fkt';
                } elseif($aRow['thirdpay_code'] == 'sf') {
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='sf';
                } elseif($aRow['thirdpay_code'] == 'rx') {
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='rx';
                } elseif($aRow['thirdpay_code'] == 'sft') { //顺付通
                    $pay_type = 2;
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='sft';
                } elseif($aRow['thirdpay_code'] == 'db') { //得宝
                    //支付类型, 取值如下（必须小写，多选时请用逗号隔开）,b2c(网银支付),weixin（微信扫码）,alipay_scan（支付宝扫码）,tenpay_scan（qq钱包扫码）
                    $pay_type = 'b2c';
                    $iPayCode=$reqData['banklist'];   // 银行简码
                    $sPrifix='db';
                } elseif($aRow['thirdpay_code'] == 'zrb') { //智融宝
                    $pay_type = 'b2c';
                    $iPayCode=$reqData['banklist'];   // 银行简码
                    $sPrifix='zrb';
                } elseif($aRow['thirdpay_code'] == 'xft') { //信付通
                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $pay_type = 'H5';
                    } else {
                        $pay_type = 'web';
                    }

                    $iPayCode=$reqData['banklist'];   // 银行简码
                    $sPrifix='xft';
                } elseif($aRow['thirdpay_code'] == 'flg') { //菲利谷
                    $pay_type = 'b2c';
                    $iPayCode=$reqData['banklist'];   // 银行简码
                    $sPrifix='flg';
                } elseif($aRow['thirdpay_code'] == 'zb') { //众宝
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='zb';
                } elseif($aRow['thirdpay_code'] == 'wdf') { //维多付
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='wdf';
                } elseif($aRow['thirdpay_code'] == 'clzldz') { //村里最靓的崽
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='clzldz';
                } elseif($aRow['thirdpay_code'] == 'csj') { // 创世纪
                    $iPayCode=$reqData['banklist'];
                    $sPrifix='csj';
                }
                break;
            case 4: //微信支付
                if($aRow['thirdpay_code'] == 'sf') {
                    $iPayCode=57;
                    $sPrifix='sfwx';
                } elseif($aRow['thirdpay_code'] == 'rx') { //仁信支付
					if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                    //if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
						$iPayCode = 'WEIXINWAP'; // 移动端
					} else {
						$iPayCode = 'WEIXIN'; // PC端
					}
                    
                    $sPrifix='rxwx';
                } elseif($aRow['thirdpay_code'] == 'fkt') { //福卡通
                    $pay_type = 2;
                    $iPayCode = 2;
                    $sPrifix='fktwx';
                } elseif($aRow['thirdpay_code'] == 'zrb') { //智融宝
                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'wxwap'; // 移动端
                    } else {
                        $iPayCode = 'wxcode'; // PC端
                    }
                    $sPrifix='zrbwx';
                } elseif($aRow['thirdpay_code'] == 'flg') { //菲利谷微信
                    $pay_type = 4;
                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'WX_wap'; // 移动端
                    } else {
                        $iPayCode = 'WX_QRcode'; // PC端
                    }
                    $sPrifix='flgwx';
                } elseif($aRow['thirdpay_code'] == 'zb') { //众宝
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = '1002'; // 微信直连-手机端(H5)
                    } else {
                        $iPayCode = '1000'; // PC端
                    }
                    $sPrifix = 'zbwx';
                } elseif($aRow['thirdpay_code'] == 'wdf') { //维多付
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'w_wechath5'; // 微信直连-手机端(H5)
                    } else {
                        $iPayCode = 'w_wechat'; // PC端
                    }
                    $sPrifix = 'wdfwx';
                } elseif($aRow['thirdpay_code'] == 'csj') { //创世纪
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = '4'; // '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
                    } else {
                        $iPayCode = '1'; // PC端
                    }
                    $sPrifix = 'csjwx';
                }
                break;
            case 5: //支付宝
                if($aRow['thirdpay_code'] == 'sf') {
                    $iPayCode=758;
                    $sPrifix='sfzfb';
                } elseif($aRow['thirdpay_code'] == 'rx') {

                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
						$iPayCode = 'ALIPAYWAP'; // 移动端
					} else {
						$iPayCode = 'ALIPAY'; // PC端
					}

                    $sPrifix='rxzfb';
                } elseif($aRow['thirdpay_code'] == 'fkt') {
                    $pay_type = 3;
                    $iPayCode = 3;
                    $sPrifix='fktzfb';
                } elseif($aRow['thirdpay_code'] == 'zrb') { //智融宝
                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'alipaywap'; // 移动端
                    } else {
                        $iPayCode = 'alipay'; // PC端
                    }
                    $sPrifix='zrbzfb';
                } elseif($aRow['thirdpay_code'] == 'xft') { //信付通
                    $pay_type = 'H5';
                    $iPayCode = 'ALIPAY'; // 移动端
                    $sPrifix='xftzfb';
                } elseif($aRow['thirdpay_code'] == 'jbf') { //聚宝付
                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'ALIPAY_WAP'; // 移动端
                    } else {
                        $iPayCode = 'ALIPAY'; // PC端
                    }
                    $sPrifix='jbfzfb';
                } elseif($aRow['thirdpay_code'] == 'flg') { //菲利谷支付宝
                    $pay_type = 5;
                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'Alipay_wap'; // 移动端
                    } else {
                        $iPayCode = 'Alipay_QRcode'; // PC端
                    }
                    $sPrifix='flgzfb';
                } elseif($aRow['thirdpay_code'] == 'zb') { //众宝
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = '1004'; // 支付宝直连-手机端(H5)
                    } else {
                        $iPayCode = '1003'; // PC端
                    }
                    $sPrifix = 'zbzfb';
                } elseif($aRow['thirdpay_code'] == 'wdf') { //维多付
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = 'w_alipayh5'; // 支付宝直连-手机端(H5)  w_alipayh5
                    } else {
                        $iPayCode = 'w_alibank'; // PC端  阿里网关:w_alibank 云闪付:w_union  支转银:w_alipay 支转支:w_alipayqr 微信扫码:w_wechat 微信转卡:w_wechath5
                    }
                    $sPrifix = 'wdfzfb';
                } elseif($aRow['thirdpay_code'] == 'csj') { //创世纪
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = '8'; //   9 支付宝直连-手机端(H5)  w_alipayh5 未配置
                    } else {
                        $iPayCode = '0'; // PC端   // '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
                    }
                    $sPrifix = 'csjzfb';
                }
                break;
            case 6: //QQ扫码
                if($aRow['thirdpay_code'] == 'sf') {
                    $iPayCode=77;
                    $sPrifix='sfqq';
                } elseif($aRow['thirdpay_code'] == 'rx') {

                    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
						$iPayCode = 'QQWAP'; // 移动端
					} else {
						$iPayCode = 'QQ'; // PC端
					}
                    
                    $sPrifix='rxqq';
                } elseif($aRow['thirdpay_code'] == 'fkt') {
                    $pay_type = 5;
                    $iPayCode = 5;
                    $sPrifix='fktqq';
                } elseif($aRow['thirdpay_code'] == 'zb') { //众宝
                    if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') || strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') || strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
                        $iPayCode = '1006'; // QQ钱包直连-手机端(H5)
                    } else {
                        $iPayCode = '1005'; // PC端
                    }
                    $sPrifix = 'zbqq';
                }
                break;
            default:
                $pay_type = 0;
                $iPayCode = 0; $sPrifix=0;
                break;
        }
        $pay_type = isset($pay_type) ? $pay_type : 0;
        $returnData = ['pay_type' => $pay_type, 'iPayCode' => $iPayCode, 'sPrifix' => $sPrifix];
        return $returnData;
}


/**
 * 根据传入参数获取银行信息
 *
 * @param $host
 * @return bool|string
 */
function getBankInfo($aThirdPayRow , $aData) {
    $aBanklist = [];
    if(!empty($aThirdPayRow) && !empty($aData)) {
        $reqData['banklist'] = $aData[3];
        if($aThirdPayRow['account_company'] ==2) {
            if ($aThirdPayRow['thirdpay_code'] == 'sf') { //闪付
                $aBanklist = array(
                    '3001' => '招商银行（借）', '3002' => '中国工商银行（借）', '3003' => '中国建设银行（借）', '3004' => '上海浦东发展银行（借）', '3005' => '中国农业银行（借）', '3006' => '中国民生银行（借）', '3009' => '兴业银行（借）', '3020' => '中国交通银行（借）', '3022' => '中国光大银行（借）', '3026' => '中国银行（借）', '3032' => '北京银行（借）', '3035' => '平安银行（借）', '3036' => '广发银行|CGB（借）', '3037' => '上海农商银行（借）', '3038' => '中国邮政储蓄银行（借）', '3039' => '中信银行（借）', '3050' => '华夏银行（借）', '3059' => '上海银行（借）', '3060' => '北京农商银行（借）',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'yb') { // 易宝
                $aBanklist = array(
                    'ICBC-NET-B2C' => '工商银行', 'CMBCHINA-NET-B2C' => '招商银行', 'CCB-NET-B2C' => '建设银行', 'BOCO-NET-B2C' => '交通银行[借]', 'CIB-NET-B2C' => '兴业银行', 'CMBC-NET-B2C' => '中国民生银行', 'CEB-NET-B2C' => '光大银行', 'BOC-NET-B2C' => '中国银行', 'PINGANBANK-NET-B2C' => '平安银行', 'ECITIC-NET-B2C' => '中信银行', 'SDB-NET-B2C' => '深圳发展银行', 'GDB-NET-B2C' => '广发银行', 'SHB-NET-B2C' => '上海银行', 'SPDB-NET-B2C' => '上海浦东发展银行', 'HXB-NET-B2C' => '华夏银行「借」', 'BCCB-NET-B2C' => '北京银行', 'ABC-NET-B2C' => '中国农业银行', 'POST-NET-B2C' => '中国邮政储蓄银行「借」', 'BJRCB-NET-B2C' => '北京农村商业银行「借」-暂不可用',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'rx') { // 仁信
                $aBanklist = array(
                    'ICBC' => '工商银行', 'ABC' => '农业银行', 'CCB' => '建设银行', 'BOC' => '中国银行', 'CMB' => '招商银行', 'BCCB' => '北京银行', 'BOCO' => '交通银行', 'CIB' => '兴业银行', 'NJCB' => '南京银行', 'CMBC' => '民生银行', 'CEB' => '光大银行', 'PINGANBANK' => '平安银行', 'CBHB' => '渤海银行', 'HKBEA' => '东亚银行', 'NBCB' => '宁波银行', 'CTTIC' => '中信银行', 'GDB' => '广发银行', 'SHB' => '上海银行', 'SPDB' => '上海浦东发展银行', 'PSBS' => '中国邮政', 'HXB' => '华夏银行', 'BJRCB' => '北京农村商业银行', 'SRCB' => '上海农商银行', 'SDB' => '深圳发展银行', 'CZB' => '浙江稠州商业银行',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'fkt') { //福卡通
                $aBanklist = array(
                    'ABC' => '中国农业银行', 'BOC' => '中国银行', 'BOCOM' => '交通银行', 'CCB' => '中国建设银行', 'ICBC' => '中国工商银行', 'PSBC' => '中国邮政储蓄银行', 'CMBC' => '招商银行', 'SPDB' => '浦发银行', 'CEBBANK' => '中国光大银行', 'ECITIC' => '中信银行', 'PINGAN' => '平安银行', 'CMBCS' => '中国民生银行', 'HXB' => '华夏银行', 'CGB' => '广发银行', 'BCCB' => '北京银行', 'BOS' => '上海银行', 'CIB' => '兴业银行',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'sft') { //顺付通
                $aBanklist = array(
                    'ABC' => '中国农业银行', 'BCCB' => '北京银行', 'CCB' => '中国建设银行', 'CEB' => '中国光大银行', 'CMB' => '招商银行', 'ICBC' => '中国工商银行', 'PSBC' => '中国邮政储蓄银行', 'BOC' => '中国银行', 'COMM' => '交通银行', 'SPDB' => '浦发银行', 'CNCB' => '中信银行', 'PAB' => '平安银行', 'CMBC' => '中国民生银行', 'HXB' => '华夏银行', 'BOS' => '上海银行', 'CIB' => '兴业银行', 'CBHB' => '渤海银行', 'GDB' => '广发银行',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'db') { // 得宝
                $aBanklist = array(
                    'ABC' => '农业银行', 'ICBC' => '工商银行', 'CCB' => '建设银行', 'BCOM' => '交通银行', 'BOC' => '中国银行', 'CMB' => '招商银行', 'CMBC' => '民生银行', 'CEBB' => '光大银行', 'BOB' => '北京银行', 'SHB' => '上海银行', 'NBB' => '宁波银行', 'HXB' => '华夏银行', 'CIB' => '兴业银行', 'PSBC' => '中国邮政银行', 'SPABANK' => '平安银行', 'SPDB' => '浦发银行', 'ECITIC' => '中信银行', 'HZB' => '杭州银行', 'GDB' => '广发银行',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'zrb') { // 智融宝
                $aBanklist = array(
                    'BOC'  => '中国银行','ICBC' => '工商银行','CCB'  => '建设银行','CMBCHINA'  => '招商银行','GDB'  => '广发银行','POST'  => '中国邮政','ABC'  => '农业银行','CMBC' => '中国民生银行','CEB' => '光大银行','BOCO' => '交通银行',
                );
            }

            if ($aThirdPayRow['thirdpay_code'] == 'xft') { // 信付通
                $aBanklist = array(
                    'CMB' => '招商银行','ICBC' => '工商银行','CCB' => '建设银行','BOC' => '中国银行','ABC' => '农业银行','BOCM' => '交通银行','SPDB' => '浦发银行','CGB' => '广发银行','CITIC' => '中信银行','CEB' => '光大银行','CIB' => '兴业银行','PAYH' => '平安银行','CMBC' => '民生银行','HXB' => '华夏银行','PSBC' => '邮储银行','BCCB' => '北京银行','SHBANK' => '上海银行','WXPAY' => '微信支付','ALIPAY' => '支付宝支付', 'QQPAY' => 'QQ扫码','JDPAY' => '京东扫码','QUICKPAY' => '快捷支付','UNIONPAY' => '中国银联','BDPAY' => '百度钱包','UNIONQRPAY' => '银联扫码',
                );
            }
            if($aThirdPayRow['thirdpay_code'] == 'zb') { // 众宝
                $aBanklist = array(
                    '962'=>'中信银行','963'=>'中国银行','964'=>'农业银行','965'=>'建设银行','967'=>'工商银行','970'=>'招商银行','971'=>'邮储银行','972'=>'兴业银行','977'=>'浦发银行','979'=>'南京银行','980'=>'民生银行','981'=>'交通银行','983'=>'杭州银行','985'=>'广发银行','986'=>'光大银行','987'=>'东亚银行','989'=>'北京银行','990'=>'平安银行','991'=>'华夏银行','992'=>'上海银行','1000'=>'微信扫码','1002'=>'微信直连','1003'=>'支付宝扫码','1004'=>'支付宝直连','1005'=>'QQ钱包扫码','1006'=>'QQ钱包直连','1007'=>'京东钱包扫码','1008'=>'京东钱包直连','1009'=>'银联扫码',
                );
            }
            if($aThirdPayRow['thirdpay_code'] == 'wdf') { // 维多付
                $aBanklist = array(
                    'w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipay'=>'支付宝转卡','w_alipayqr'=>'支付宝转支','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',
                );
            }
            if($aThirdPayRow['thirdpay_code'] == 'clzldz') { // 村里最靓的仔
                $aBanklist = array(
                    '920'=>'银联闪付',
                );
            }
            if($aThirdPayRow['thirdpay_code'] == 'csj') { // 创世纪
                $aBanklist = array(
                    '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
                );
            }
            if($aThirdPayRow['thirdpay_code'] == 'xingchen') { // 星辰
                $aBanklist = array(
                    'bank_transfer'=>'银行卡转账',
                );
            }
            $BankInfos['Bank_Address'] = $aBanklist[$reqData['banklist']];
            $BankInfos['Bank'] = $aBanklist[$reqData['banklist']];
        } else if($aThirdPayRow['account_company'] ==4) { //微信支付
            if($aThirdPayRow['thirdpay_code'] == 'zb') { // 众宝
                $aBanklist = array(
                    '1000'=>'微信扫码','1002'=>'微信直连','1003'=>'支付宝扫码','1004'=>'支付宝直连','1005'=>'QQ钱包扫码','1006'=>'QQ钱包直连','1007'=>'京东钱包扫码','1008'=>'京东钱包直连','1009'=>'银联扫码',
                );
                $BankInfos['Bank_Address'] = $aBanklist[$reqData['banklist']];
                $BankInfos['Bank'] = $aBanklist[$reqData['banklist']];
            } else if($aThirdPayRow['thirdpay_code'] == 'wdf') { // 维多付
                $aBanklist = array(
                    'w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipay'=>'支付宝转卡','w_alipayqr'=>'支付宝转支','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',
                );
                $BankInfos['Bank_Address'] = $aBanklist[$reqData['banklist']];
                $BankInfos['Bank'] = $aBanklist[$reqData['banklist']];
            } else{
                $BankInfos['Bank_Address'] = '';
                $BankInfos['Bank'] = '微信支付';
            }
        } else if($aThirdPayRow['account_company'] ==5) { //支付宝
            if($aThirdPayRow['thirdpay_code'] == 'zb') { // 众宝
                $aBanklist = array(
                    '1000'=>'微信扫码','1002'=>'微信直连','1003'=>'支付宝扫码','1004'=>'支付宝直连','1005'=>'QQ钱包扫码','1006'=>'QQ钱包直连','1007'=>'京东钱包扫码','1008'=>'京东钱包直连','1009'=>'银联扫码',
                );
                $BankInfos['Bank_Address'] = '';
                $BankInfos['Bank'] = $aBanklist[$reqData['banklist']];
            }  else if($aThirdPayRow['thirdpay_code'] == 'wdf') { // 维多付
                $aBanklist = array(
                    'w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipay'=>'支付宝转卡','w_alipayqr'=>'支付宝转支','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',
                );
                $BankInfos['Bank_Address'] = $aBanklist[$reqData['banklist']];
                $BankInfos['Bank'] = $aBanklist[$reqData['banklist']];
            } else if($aThirdPayRow['thirdpay_code'] == 'csj') { // 创世纪
                $aBanklist = array(
                    '0'=>'支付宝转卡','1'=>'微信扫码','2'=>'银联扫码','3'=>'网银支付','4'=>'微信转账','5'=>'支付宝转账','6'=>'手机银行转账','7'=>'银联快捷','8'=>'支付宝个码','9'=>'支付宝wap2/支付宝H5',
                );
                $BankInfos['Bank_Address'] = $aBanklist[$reqData['banklist']];
                $BankInfos['Bank'] = $aBanklist[$reqData['banklist']];
            } else{
                $BankInfos['Bank_Address'] = '';
                $BankInfos['Bank'] = '支付宝扫码';
            }
        } else if($aThirdPayRow['account_company'] ==6) { //QQ扫码
            $BankInfos['Bank_Address'] = '';
            $BankInfos['Bank'] = 'QQ扫码';
        }
    } else {
        $BankInfos['Bank_Address'] = '';
        $BankInfos['Bank'] ='';
    }

    return $BankInfos;
}



function getMainUrl($host) {
    $hostArray = explode('.', $host);
    /*$hostArray = array( {[0]=>"http://www", [1]=>"hg3088_online_pay",[2]=>"lcn")*/
    $count = count($hostArray); //3
    switch ($count)
    {
        case 4 :
        case 3 :
            unset($hostArray[0]);
            /*if(strpos($hostArray['1'], '_', 0)){
                $hostArray['1'] = str_replace("_", "", $hostArray['1']);
            }*/
            $mainhost = implode(".", $hostArray);
            break;
        case 2 :
            $mainhost = implode(".", $hostArray);
            break;
        case 1 :
            $mainhost = false;
            break;
    }
    return $mainhost;
}


// 维多付银联充值转渠道码
$fxpaytype = ['1' => 'w_union', '2' => 'w_alibank', '3' => 'w_alipayqr', '4' => 'w_alipay', '5' => 'w_alipayh5', '6' => 'w_wechat', '7' => 'w_wechath5',];
$wdfpaytype = ['w_union'=>'银联闪付','w_alibank'=>'阿里网关','w_alipayqr'=>'支付宝转支','w_alipay'=>'支付宝转卡','w_alipayh5'=>'支付宝H5','w_wechat'=>'微信扫码','w_wechath5'=>'微信转卡',];

?>