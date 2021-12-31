<?php
session_start();
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");      
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

include ("../../agents/include/address.mem.php");
// echo "<script>if(self == top) parent.location='".BROWSER_IP."'</script>\n";
require_once ("../../agents/include/config.inc.php");

checkAdminLogin(); // 同一账号不能同时登陆

if( !isset($_SESSION['Oid']) || $_SESSION['Oid'] == "" || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != ADMINLOGINFLAG ) {
    echo "<script>alert('您的登录信息已过期,请重新登录!');top.location.href='/';</script>";
    exit;
}

if($_SESSION['UserName'] != 'admin'){ // 仅admin有转移会员的权限-20180807
    echo "<script>alert('抱歉，您没有转移会员的权限！');top.location.href='/';</script>";
    exit;
}

$cpMasterDbLink = @mysqli_connect($database['cpDefault']['host'],$database['cpDefault']['user'],$database['cpDefault']['password'],$database['cpDefault']['dbname'],$database['cpDefault']['port']) or die("mysqli connect error".mysqli_connect_error());

$uid=$_REQUEST["uid"];
$langx=$_SESSION["langx"];
$loginname=$_SESSION['UserName'];
$lv=$_REQUEST["lv"];
$userlv=$_REQUEST["userlv"];
$tid=$_REQUEST["tid"];
$name=$_REQUEST["name"]; // 会员帐号
$agents=trim($_REQUEST["agents"]); // 代理账号
require ("../../agents/include/traditional.$langx.inc.php");

$sql = "select ID,World,Corprator,Super,Admin,agent_url from ".DBPREFIX."web_agents_data where UserName='$agents'";
$result = mysqli_query($dbLink,$sql);
$row = mysqli_fetch_assoc($result);
$agentId=$row['ID'];
$world=$row['World'];
$corprator=$row['Corprator'];
$super=$row['Super'];
$admin=$row['Admin'];
$agent_url = $row['agent_url'] ;
$cou=mysqli_num_rows($result);
if($cou==0){ // 代理帐号不存在
	echo "<script language='javascript'>alert('请输入正确的代理商账号!!');self.location='user_browse.php?uid=$uid&lv=$lv&userlv=$userlv&langx=$langx';</script>";
}else{
	
	$beginFrom = mysqli_query($dbMasterLink,"start transaction");	//开启事务$from
	if($beginFrom) {
    	$mysql="update ".DBPREFIX.MEMBERTABLE." set Agents='$agents',agent_url='$agent_url',World='$world',Corprator='$corprator',Super='$super',Admin='$admin' where ID='$tid'";
    	if(mysqli_query($dbMasterLink,$mysql)) {
    	    $rRePsql="update ".DBPREFIX."web_report_history_report_data set Agents='$agents' where userid='$tid'";
            if(mysqli_query($dbMasterLink,$rRePsql)) {
			    $rsql="update ".DBPREFIX."web_report_data set Agents='$agents',agent_url='$agent_url',World='$world',Corprator='$corprator',Super='$super',Admin='$admin',updateTime='".date('Y-m-d H:i:s',time())."' where M_Name='$name'";
			    if(mysqli_query($dbMasterLink,$rsql)) {
			    	// -----------------------------------------------------------------------转移代理，同步AG用户表、AG真人历史报表、AG捕鱼历史报表 Start
			    	// ag_users
			    	$agUsersql="update ".DBPREFIX."ag_users set Agents='$agents' where userid='$tid'";
                    if(mysqli_query($dbMasterLink,$agUsersql)) {
						// ag_projects_history_report
                        $agRsql="update ".DBPREFIX."ag_projects_history_report set Agents='$agents' where userid='$tid'";
                        if(mysqli_query($dbMasterLink,$agRsql)) {
                            // ag_buyu_scene
                            $agBuyuRsql="update ".DBPREFIX."ag_buyu_scene set Agents='$agents' where userid='$tid'";
                            if(mysqli_query($dbMasterLink,$agBuyuRsql)) {
								// -------------------------------------------------------------------------------------转移代理，同步OG真人用户表、OG真人历史报表 Start
                                // og_member_data
                                $ogUsersql="update ".DBPREFIX."og_member_data set Agents='$agents' where userid='$tid'";
                                if(mysqli_query($dbMasterLink,$ogUsersql)) {
                                    // og_history_report
                                    $ogRsql="update ".DBPREFIX."og_history_report set Agents='$agents' where userid='$tid'";
                                    if(mysqli_query($dbMasterLink,$ogRsql)) {
                                        // -------------------------------------------------------------------------------------转移代理，同步VG棋牌用户表、VG棋牌历史报表 Start
                                        // vg_member_data
                                        $vgUsersql="update ".DBPREFIX."vg_member_data set agents='$agents' where userid='$tid'";
                                        if(mysqli_query($dbMasterLink,$vgUsersql)) {
                                            // vg_history_report
                                            $vgRsql="update ".DBPREFIX."vg_history_report set agents='$agents' where userid='$tid'";
                                            if(mysqli_query($dbMasterLink,$vgRsql)) {
                                                // -------------------------------------------------------------------------------------转移代理，同步乐游棋牌用户表、乐游棋牌历史报表 Start
                                                // ly_member_data
                                                $lyUsersql="update ".DBPREFIX."ly_member_data set agents='$agents' where userid='$tid'";
                                                if(mysqli_query($dbMasterLink,$lyUsersql)) {
                                                    // ly_history_report
                                                    $lyRsql="update ".DBPREFIX."ly_history_report set agents='$agents' where userid='$tid'";
                                                    if(mysqli_query($dbMasterLink,$lyRsql)) {
                                                        // -------------------------------------------------------------------------------------转移代理，同步皇冠棋牌用户表、皇冠棋牌历史报表 Start
                                                        // ff_member_data
                                                        $ffUsersql="update ".DBPREFIX."ff_member_data set agents='$agents' where userid='$tid'";
                                                        if(mysqli_query($dbMasterLink,$ffUsersql)) {
                                                            // ff_history_report
                                                            $ffRsql="update ".DBPREFIX."ff_history_report set agents='$agents' where userid='$tid'";
                                                            if(mysqli_query($dbMasterLink,$ffRsql)) {
                                                                // -------------------------------------------------------------------------------------转移代理，同步开元棋牌用户表、开元棋牌历史报表 Start
                                                                // ky_member_data
                                                                $kyUsersql="update ".DBPREFIX."ky_member_data set agents='$agents' where userid='$tid'";
                                                                if(mysqli_query($dbMasterLink,$kyUsersql)) {
                                                                    // ky_history_report
                                                                    $kyRsql="update ".DBPREFIX."ky_history_report set agents='$agents' where userid='$tid'";
                                                                    if(mysqli_query($dbMasterLink,$kyRsql)) {
                                                                // -------------------------------------------------------------------------------------转移代理，同步快乐棋牌用户表、快乐棋牌历史报表 Start
                                                                // kl_member_data
                                                                $kyUsersql="update ".DBPREFIX."kl_member_data set agents='$agents' where userid='$tid'";
                                                                if(mysqli_query($dbMasterLink,$kyUsersql)) {
                                                                    // kl_history_report
                                                                    $kyRsql="update ".DBPREFIX."kl_history_report set agents='$agents' where userid='$tid'";
                                                                    if(mysqli_query($dbMasterLink,$kyRsql)) {
                                                                        // -------------------------------------------------------------------------------------转移代理，同步MG电子用户表、MG电子历史报表 Start
                                                                        // mg_member_data
                                                                        $mgUsersql="update ".DBPREFIX."mg_member_data set agents='$agents' where userid='$tid'";
                                                                        if(mysqli_query($dbMasterLink,$mgUsersql)) {
                                                                            // mg_history_report
                                                                            $mgRsql="update ".DBPREFIX."mg_history_report set agents='$agents' where userid='$tid'";
                                                                            if(mysqli_query($dbMasterLink,$mgRsql)) {
                                                                        // -------------------------------------------------------------------------------------转移代理，同步MW电子用户表、MW电子历史报表 Start
                                                                        // mw_member_data
                                                                        $mgUsersql="update ".DBPREFIX."mw_member_data set agents='$agents' where userid='$tid'";
                                                                        if(mysqli_query($dbMasterLink,$mgUsersql)) {
                                                                            // mw_history_report
                                                                            $mgRsql="update ".DBPREFIX."mw_history_report set agents='$agents' where userid='$tid'";
                                                                            if(mysqli_query($dbMasterLink,$mgRsql)) {
                                                                        // -------------------------------------------------------------------------------------转移代理，同步CQ9电子用户表、CQ9电子历史报表 Start
                                                                        // cq9_member_data
                                                                        $mgUsersql="update ".DBPREFIX."cq9_member_data set agents='$agents' where userid='$tid'";
                                                                        if(mysqli_query($dbMasterLink,$mgUsersql)) {
                                                                            // cq9_history_report
                                                                            $mgRsql="update ".DBPREFIX."cq9_history_report set agents='$agents' where userid='$tid'";
                                                                            if(mysqli_query($dbMasterLink,$mgRsql)) {
                                                                        // -------------------------------------------------------------------------------------转移代理，同步FG电子用户表、FG电子历史报表 Start
                                                                        // fg_member_data
                                                                        $mgUsersql="update ".DBPREFIX."fg_member_data set agents='$agents' where userid='$tid'";
                                                                        if(mysqli_query($dbMasterLink,$mgUsersql)) {
                                                                            // fg_history_report
                                                                            $mgRsql="update ".DBPREFIX."fg_history_report set agents='$agents' where userid='$tid'";
                                                                            if(mysqli_query($dbMasterLink,$mgRsql)) {
                                                                                // -------------------------------------------------------------------------------------转移代理，同步avia电竞用户表、avia电竞历史报表 Start
                                                                                // avia_member_data
                                                                                $aviaUsersql="update ".DBPREFIX."avia_member_data set agents='$agents' where userid='$tid'";
                                                                                if(mysqli_query($dbMasterLink,$aviaUsersql)) {
                                                                                    // avia_history_report
                                                                                    $aviaRsql="update ".DBPREFIX."avia_history_report set agents='$agents' where userid='$tid'";
                                                                                    if(mysqli_query($dbMasterLink,$aviaRsql)) {
                                                                                // -------------------------------------------------------------------------------------转移代理，同步fire电竞用户表、fire电竞历史报表 Start
                                                                                // fire_member_data
                                                                                $aviaUsersql="update ".DBPREFIX."fire_member_data set agents='$agents' where userid='$tid'";
                                                                                if(mysqli_query($dbMasterLink,$aviaUsersql)) {
                                                                                    // fire_history_report
                                                                                    $aviaRsql="update ".DBPREFIX."fire_history_report set agents='$agents' where userid='$tid'";
                                                                                    if(mysqli_query($dbMasterLink,$aviaRsql)) {
                                                                                        // -------------------------------------------------------------------------------------转移代理，同步国民彩票用户表、国民彩票（历史报表、信用盘历史报表、追号历史报表） Start
                                                                                        // cp_member_data
                                                                                        $cpUsersql="update ".DBPREFIX."cp_member_data set agents='$agents' where userid='$tid'";
                                                                                        if(mysqli_query($dbMasterLink,$cpUsersql)) {
                                                                                            // web_third_projects_history_report
                                                                                            $cpProjectRsql="update ".DBPREFIX."web_third_projects_history_report set agents='$agents' where userid='$tid'";
                                                                                            if(mysqli_query($dbMasterLink,$cpProjectRsql)) {
                                                                                                // web_third_ssc_history_report
                                                                                                $cpSscRsql="update ".DBPREFIX."web_third_ssc_history_report set agents='$agents' where userid='$tid'";
                                                                                                if(mysqli_query($dbMasterLink,$cpSscRsql)) {
                                                                                                    // web_third_traces_history_report
                                                                                                    $cpTraceRsql="update ".DBPREFIX."web_third_traces_history_report set agents='$agents' where userid='$tid'";
                                                                                                    if(mysqli_query($dbMasterLink,$cpTraceRsql)) {
                                                                                                        // -------------------------------------------------------------------------------------转移代理，同步体育彩票用户表、体育彩票历史报表 Start
																										$beginHgcp = mysqli_query($cpMasterDbLink,"start transaction");//开启事务$to
                                                                                                        if($beginHgcp) {
                                                                                                            // gxfcy_user
                                                                                                            $hgcpUsersql="update gxfcy_user set hg_agent_uid='$agentId' where hguid=".$tid;
                                                                                                            if(mysqli_query($cpMasterDbLink,$hgcpUsersql)){
                                                                                                                // gxfcy_history_bill_report_less_12hours
                                                                                                                $hgcpLess12Rsql="update gxfcy_history_bill_report_less_12hours set hg_agent_uid='$agentId' where username='$name'";
                                                                                                                if(mysqli_query($cpMasterDbLink,$hgcpLess12Rsql)){
                                                                                                                    // gxfcy_history_bill_report
                                                                                                                    $hgcpRsql="update gxfcy_history_bill_report set hg_agent_uid='$agentId' where username='$name'";
                                                                                                                    if(mysqli_query($cpMasterDbLink,$hgcpRsql)) {
                                                                                                                        $hgcpCommit = mysqli_query($cpMasterDbLink,"COMMIT");
                                                                                                                        if($hgcpCommit) {

                                                                                                                            $hgCommit = mysqli_query($dbMasterLink, "COMMIT");
                                                                                                                            if($hgCommit){
                                                                                                                                /* 插入系统日志 */
                                                                                                                                $loginfo = $loginname.' 对会员帐号 <font class="green">'.$name.'</font>转移到了代理账号 <font class="red">'.$agents.'</font> 下面 ' ;
                                                                                                                                innsertSystemLog($loginname,$userlv,$loginfo);

                                                                                                                                echo "<script Language=javascript>alert('会员转移到代理线成功');self.location='user_browse.php?uid=$uid&lv=$lv&userlv=$userlv&langx=$langx';</script>";
                                                                                                                            }else{
                                                                                                                                mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                                                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                                                                die('会员转移到代理线失败!') ;
                                                                                                                            }
                                                                                                                        }else{
                                                                                                                            mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                                                                                            die('体育彩票转移到代理线失败!') ;
                                                                                                                        }
                                                                                                                    }else{
                                                                                                                        mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                                                                                        die('gxfcy_history_bill_report操作失败!') ;
                                                                                                                    }
                                                                                                                }else{
                                                                                                                    mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                                                                                    die('gxfcy_history_bill_report_less_12hours操作失败!') ;
                                                                                                                }
																											}else{
                                                                                                                mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                                                                                die('gxfcy_user操作失败!') ;
                                                                                                            }
                                                                                                        }else{
                                                                                                            mysqli_query($cpMasterDbLink,"ROLLBACK");
                                                                                                            die('CP事务开启失败！') ;
                                                                                                        }
                                                                                                    }else {
                                                                                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                                        die('web_third_traces_history_report操作失败!') ;
                                                                                                    }
                                                                                                }else {
                                                                                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                                    die('web_third_ssc_history_report操作失败!') ;
                                                                                                }
                                                                                            }else {
                                                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                                die('web_third_projects_history_report操作失败!') ;
                                                                                            }
                                                                                        }else {
                                                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                            die('cp_member_data操作失败!') ;
                                                                                        }
                                                                                    }else {
                                                                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                        die('fire_history_report操作失败!') ;
                                                                                    }
                                                                                }else {
                                                                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                    die('fire_member_data操作失败!') ;
                                                                                }
                                                                                    }else {
                                                                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                        die('avia_history_report操作失败!') ;
                                                                                    }
                                                                                }else {
                                                                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                    die('avia_member_data操作失败!') ;
                                                                                }
                                                                            }else {
                                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                die('fg_history_report操作失败!') ;
                                                                            }
                                                                        }else {
                                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                                            die('fg_member_data操作失败!') ;
                                                                        }
                                                                            }else {
                                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                die('cq9_history_report操作失败!') ;
                                                                            }
                                                                        }else {
                                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                                            die('cq9_member_data操作失败!') ;
                                                                        }
                                                                            }else {
                                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                die('mw_history_report操作失败!') ;
                                                                            }
                                                                        }else {
                                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                                            die('mw_member_data操作失败!') ;
                                                                        }
                                                                            }else {
                                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                                die('mg_history_report操作失败!') ;
                                                                            }
                                                                        }else {
                                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                                            die('mg_member_data操作失败!') ;
                                                                        }
                                                                    }else {
                                                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                                                        die('kl_history_report操作失败!') ;
                                                                    }
                                                                }else {
                                                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                                                    die('kl_member_data操作失败!') ;
                                                                }
                                                                    }else {
                                                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                                                        die('ky_history_report操作失败!') ;
                                                                    }
                                                                }else {
                                                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                                                    die('ky_member_data操作失败!') ;
                                                                }
                                                            }else {
                                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                                die('ff_history_report操作失败!') ;
                                                            }
                                                        }else {
                                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                                            die('ff_member_data操作失败!') ;
                                                        }
                                                    }else {
                                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                                        die('ly_history_report操作失败!') ;
                                                    }
                                                }else {
                                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                                    die('ly_member_data操作失败!') ;
                                                }
                                            }else {
                                                mysqli_query($dbMasterLink,"ROLLBACK");
                                                die('vg_history_report操作失败!') ;
                                            }
                                        }else {
                                            mysqli_query($dbMasterLink,"ROLLBACK");
                                            die('vg_member_data操作失败!') ;
                                        }
                                    }else {
                                        mysqli_query($dbMasterLink,"ROLLBACK");
                                        die('og_history_report操作失败!') ;
                                    }
                                }else {
                                    mysqli_query($dbMasterLink,"ROLLBACK");
                                    die('og_member_data操作失败!') ;
                                }
                            }else {
                                mysqli_query($dbMasterLink,"ROLLBACK");
                                die('ag_buyu_scene操作失败!') ;
                            }
                        }else {
                            mysqli_query($dbMasterLink,"ROLLBACK");
                            die('ag_projects_history_report操作失败!') ;
                        }
                    }else {
                        mysqli_query($dbMasterLink,"ROLLBACK");
                        die('ag_users操作失败!') ;
                    }
			    }else {
					mysqli_query($dbMasterLink,"ROLLBACK");
			    	die('操作失败!') ;
			    }
            }else {
                mysqli_query($dbMasterLink,"ROLLBACK");
                die('体育报表操作失败!') ;
            }

    	}else {
			mysqli_query($dbMasterLink,"ROLLBACK");
			die('操作失败');
    	}
	}else {
		die('操作失败');
	}
    

}

?>
