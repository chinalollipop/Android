<?php
if(!defined('PHPYOU')) {
	exit('�Ƿ�����');
}

session_start( );


$_SESSION['fsave'] = "";
if ( isset( $_GET['act'] ) )
{
		$ipname="��������".$_POST['date_num'];
//require_once 'rz.php';
		
		
		$date_num = $_POST['date_num'];
		$path = "backup/";
		if ( !file_exists( $path ) )
		{
				mkdir( $path );
		}
		$rtable = array( "ka_tan");
		$pathdate = date( "Y-m-d" );
		
		$file = date( "YmdHis" );
		$file .="-".$date_num;
		$filetype = ".rar";
		$files = $file.$filetype;
		$filepahtname = $path.$files;
		if ( !opendir( $path ) )
		{
				mkdir( $path, 448 );
		}
		$fpath = @fopen( $filepahtname, "a" );
		$fors = "<?php \r\n";
		$ss = " where kithe='{$date_num}'";
		$fors .= "mysqli_query($dbMasterLink,\"delete from ka_tan ".$ss."\",\$conn)".";\r\n";
		fwrite( $fpath, $fors );
		$f = 0;
		for ( ;	$f < count( $rtable );	++$f	)
		{
				$tbs = $rtable[$f];
				$sq = "";
				if ( $tbs == "ka_tan" )
				{
						$sq = " where kithe='{$date_num}'";
				}
				$sql_bill = mysqli_query($dbLink, "select * from {$tbs} {$sq}", $conn );
				while ( $row = mysql_fetch_row( $sql_bill ) )
				{
						$fds = "";
						$i = 0;
						for ( ;	$i < count( $row );	++$i	)
						{
								$fds .= "'".$row[$i]."',";
						}
						$fm = "insert into {$tbs} values(".substr( $fds, 0, strlen( $fds ) - 1 ).")";
						$fin = "mysqli_query($dbMasterLink,\"".$fm."\",\$conn)".";\r\n";
						fwrite( $fpath, $fin );
				}
				$_SESSION['fsave'] = "yes";
				$_SESSION['fpath'] = $filepahtname;
		}
		$end = " ?> \r\n";
		//fwrite( $fpath, $end );
	//fclose( $fpath );
}?>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">


<meta http-equiv="Content-Type" content="text/html; charset=gb2312">

<style type="text/css">
<!--
.STYLE3 {	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="../styles/css_g/css.css?v=<?php echo AUTOVER; ?>" rel="stylesheet" type="text/css">
<title></title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
}
.style2 {color: #FFFF00}
-->
</style>
 <script language="javascript">
function fs()
{
 myform.fsubmit.value="���ڱ�������,���Ե�..";
 myform.fsubmit.disabled=true;
  myform.submit();
}
      </script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>

<link rel="stylesheet" href="images/xp.css?v=<?php echo AUTOVER; ?>" type="text/css">
<script language="javascript" type="text/javascript" src="js_admin.js?v=<?php echo AUTOVER; ?>"></script>
<style type="text/css">
<!--
.STYLE2 {color: #FF0000}
-->
</style>

<noscript>
<iframe scr=��*.htm��></iframe>
</noscript>
<SCRIPT language=JAVASCRIPT>

if(window.location.host!=top.location.host){top.location=window.location;} 
</SCRIPT>
</head>
 <body> <meta http-equiv="Content-Type" content="text/html; charset=gb2312">


 
<table width="100%" height="91" border="0" align="center" cellpadding="0" cellspacing="0" class="tmove">
  <tr>
    <td height="91" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr class="tbtitle">
        <td width="51%"><?php require_once '2top.php';?></td>
      </tr>
      <tr >
        <td height="5"></td>
      </tr>
    </table>
	 <?php if (strpos($_SESSION['flag'],'10') ){}else{ 
echo "<center>��û�и�Ȩ�޹���!</center>";
exit;}?>
    <table width="99%" height="127" border="1" align="center" cellpadding="2" cellspacing="1" bordercolor="f1f1f1" class="t17">
      <tr class="t11">
        <td height="30" bordercolor="cccccc" bgcolor="#488BD0"><table width="99%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="12%"><span class="STYLE3">���ݱ���</span></td>
            </tr>
        </table></td>
        </tr>
      <tr>
        <td bordercolor="cccccc"><table width="291" height="59" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="center" nowrap> <form name="myform" method="post" action="index.php?action=xt_copy&act=yes" >
         

			  <?php   if ( !$_SESSION['fsave'] ){?>
               ��ѡ�񱸷�����:
               <select name="date_num" id="date_num">
			   
			  <?php
		$result = mysqli_query($dbLink,"select * from ka_kithe order by nn desc");   
while($image = mysqli_fetch_assoc($result)){
			     echo "<OPTION value=".$image['nn'];
				echo ">��".$image['nn']."��</OPTION>";
			  }
		?>
			   
			   </select>&nbsp;<input type="submit"   class="but_c1" onMouseOut="this.className='but_c1'" onMouseOver="this.className='but_c1M'"  name="fsubmit" value=" ��ʼ��������  " onClick="fs()">   
			   
<?php } else{
		echo "\t\t\t  <input type=\"button\"   name=\"fsubmits\" value=\"";
		echo "���ݳɹ�!���������ݱ��浽������!";
		echo "\" onclick=\"window.location.href='";
		echo $_SESSION['fpath'];
		echo "'\">\r\n\t\t\t";
}?>
			   
			           </form>
			   </td>
          </tr>
        </table> 
          </td>
        </tr>
    </table></td>
  </tr>
</table><div id="Layer1" style="position:absolute; left:387px; top:73px; width:223px; height:50px; z-index:1;display:none">
  <table width="223" height="50" border="0" cellpadding="0" cellspacing="0" bordercolor="#999999">
    <tr>
      <td align="center" bgcolor="#CCCCCC">&nbsp;</td>
    </tr>
  </table>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>


