<?php
require_once dirname(__FILE__).'/conjunction.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>�ޱ����ĵ�</title>
</head>

<body>
<?php
$mynav=array();

$d=4;
echo "".$_SESSION['flag']."";
$arr=explode(",",$_SESSION['flag']);

foreach($arr as $it){
  echo "".$it."<br>";
  switch ($it)
{
case "01":
echo "�̿ڹ���";
  break;
  case "02":
  echo "��������";
  break;
  case "03":
  echo "��ʱע��";
  break;
  case "04":
  echo "�߷�";
  break;
  case "05":
  echo "�ɶ�";
  break;
  case "06":
  echo "�ܴ���";
  break;
  case "07":
  echo "����";
  break;
  case "08":
  echo "��Ա";
  break;
  case "09":
  echo "����";
  break;
  case "10":
  echo "ϵͳά��";
  break;
  case "11":
  echo "ע����ѯ";
  break;
  case "12":
  echo "�޸�����";
  break;
  case "13":
  echo "����ͳ��";
  break;
  default:
  echo "��";
}
  //$mynav[]=$it;
}
echo "".count($mynav)."";
		?>
</body>
</html>
