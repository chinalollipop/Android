package com.cfcp.a01.data;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;
import java.util.List;

public class BetGameSettingsForRefreshResult implements Serializable {

    /**
     * errno : 0
     * error :
     * data : {"gameId":15,"gameSeriesId":4,"gameNameEn":"JSK3","gameNameCn":"江苏快三","wayGroups":[{"id":65,"pid":0,"name_cn":"和值","name_en":"hz","children":[{"id":75,"pid":65,"name_cn":"和值","name_en":"hz","children":[{"id":157,"pid":75,"series_way_id":157,"name_cn":"和值","name_en":"fs","price":2,"bet_note":"至少选择1个和值（3个号码之和）进行投注","bonus_note":"所选和值与开奖的3个号码的和值相同即中奖。和值10,11为八等奖，和值9,12为七等奖，和值8,13为六等奖，和值7,14为五等奖，和值6,15为四等奖，和值5,16为三等奖，和值4,17为二等奖，和值3,18为一等级。每个奖级(2块钱投注)对应的奖金请到 个人中心-我的奖金组-查看全部-点击对应的彩种 即可弹窗看到。","basic_methods":"61","prize":"408.31","max_multiple":734}]}]},{"id":66,"pid":0,"name_cn":"三同号","name_en":"sth","children":[{"id":76,"pid":66,"name_cn":"三同号","name_en":"sth","children":[{"id":158,"pid":76,"series_way_id":158,"name_cn":"三同号单选","name_en":"dx","price":2,"bet_note":"对相同的三个号码（111、222、333、444、555、666）中的任意一个进行投注","bonus_note":"所选号码开出即中奖","basic_methods":"57","prize":"408.31","max_multiple":734},{"id":159,"pid":76,"series_way_id":159,"name_cn":"三同号通选","name_en":"tx","price":2,"bet_note":"对所有相同的三个号码（111、222、333、444、555、666）进行投注","bonus_note":"三位号码全相同即中奖","basic_methods":"58","prize":"68.04","max_multiple":4409}]}]},{"id":68,"pid":0,"name_cn":"二同号","name_en":"eth","children":[{"id":78,"pid":68,"name_cn":"二同号","name_en":"eth","children":[{"id":160,"pid":78,"series_way_id":160,"name_cn":"二同号单选","name_en":"dx","price":2,"bet_note":"选择1对相同号码（11,22,33,44,55,66）和1个不同号码(1,2,3,4,5,6)投注","bonus_note":"选号与奖号相同（顺序不限）","basic_methods":"59","prize":"136.09","max_multiple":2204},{"id":161,"pid":78,"series_way_id":161,"name_cn":"二同号复选","name_en":"fx","price":2,"bet_note":"从11～66中任选1个或多个号码","bonus_note":"中奖号码中包括所选的号码即为中奖。如买的是11,则中奖号码为112,则中奖","basic_methods":"60","prize":"25.52","max_multiple":11755}]}]},{"id":70,"pid":0,"name_cn":"三不同号","name_en":"sbth","children":[{"id":80,"pid":70,"name_cn":"三不同号","name_en":"sbth","children":[{"id":162,"pid":80,"series_way_id":162,"name_cn":"三不同号","name_en":"fs","price":2,"bet_note":"从1～6中任选3个或多个号码","bonus_note":"中奖号码由三个不同的号码，且全部在所购买的号码中，即为中奖","basic_methods":"62","prize":"68.04","max_multiple":4409}]}]},{"id":71,"pid":0,"name_cn":"二不同号","name_en":"ebth","children":[{"id":81,"pid":71,"name_cn":"二不同号","name_en":"ebth","children":[{"id":163,"pid":81,"series_way_id":163,"name_cn":"二不同号","name_en":"fs","price":2,"bet_note":"从1～6中任选2个或多个号码","bonus_note":"所选号码与开奖号码任意2个号码相同，即中奖","basic_methods":"63","prize":"13.61","max_multiple":22042}]}]},{"id":72,"pid":0,"name_cn":"三连号","name_en":"slh","children":[{"id":82,"pid":72,"name_cn":"三连号","name_en":"slh","children":[{"id":164,"pid":82,"series_way_id":164,"name_cn":"三连号通选","name_en":"fs","price":2,"bet_note":"对所有3个相连的号码（123、234、345、456）进行投注","bonus_note":"中奖号码为123、234、345、456之一即中奖","basic_methods":"64","prize":"17.00","max_multiple":17647}]}]},{"id":73,"pid":0,"name_cn":"大小","name_en":"dx","children":[{"id":83,"pid":73,"name_cn":"大小","name_en":"dx","children":[{"id":165,"pid":83,"series_way_id":165,"name_cn":"大小","name_en":"fs","price":2,"bet_note":"对号码的号码的三位数和值的大小形态进行投注。和值>10，则为大，反之则为小。","bonus_note":"中奖号码和值的大小形态与所选形态形同，即中奖","basic_methods":"65","prize":"3.78","max_multiple":79365}]}]},{"id":74,"pid":0,"name_cn":"单双","name_en":"ds","children":[{"id":84,"pid":74,"name_cn":"单双","name_en":"ds","children":[{"id":166,"pid":84,"series_way_id":166,"name_cn":"单双","name_en":"fs","price":2,"bet_note":"对号码的号码的三位数和值的单双形态进行投注","bonus_note":"中奖号码和值的单双形态与所选形态形同，即中奖","basic_methods":"66","prize":"3.78","max_multiple":79365}]}]},{"id":85,"pid":0,"name_cn":"猜必出","name_en":"bdw","children":[{"id":86,"pid":85,"name_cn":"猜必出","name_en":"bdw","children":[{"id":167,"pid":86,"series_way_id":167,"name_cn":"猜必出","name_en":"fs","price":2,"bet_note":"从1至6选择1个或更多号码","bonus_note":"所选号码在开奖号码中出现即中奖","basic_methods":"67","prize":"4.48","max_multiple":66964}]}]},{"id":110,"pid":0,"name_cn":"颜色","name_en":"color","children":[{"id":111,"pid":110,"name_cn":"颜色","name_en":"color","children":[{"id":378,"pid":111,"series_way_id":378,"name_cn":"全红","name_en":"red","price":2,"bet_note":"当开出奖号都为红色，仅出现号码1或4，即中奖。","bonus_note":"当开出奖号都为红色，仅出现号码1或4，即中奖。","basic_methods":"110","prize":"51.04","max_multiple":5877},{"id":379,"pid":111,"series_way_id":379,"name_cn":"全黑","name_en":"black","price":2,"bet_note":"当开出奖号都为黑色，仅出现号码2,3,5,6，即中奖。","bonus_note":"当开出奖号都为黑色，仅出现号码2,3,5,6，即中奖。","basic_methods":"111","prize":"6.38","max_multiple":47021}]}]}],"defaultMethodId":157,"optionalPrizes":[{"prize_group":"1502","rate":"0.2240"},{"prize_group":"1503","rate":"0.2235"},{"prize_group":"1504","rate":"0.2230"},{"prize_group":"1505","rate":"0.2225"},{"prize_group":"1506","rate":"0.2220"},{"prize_group":"1507","rate":"0.2215"},{"prize_group":"1508","rate":"0.2210"},{"prize_group":"1509","rate":"0.2205"},{"prize_group":"1510","rate":"0.2200"},{"prize_group":"1511","rate":"0.2195"},{"prize_group":"1512","rate":"0.2190"},{"prize_group":"1513","rate":"0.2185"},{"prize_group":"1514","rate":"0.2180"},{"prize_group":"1515","rate":"0.2175"},{"prize_group":"1516","rate":"0.2170"},{"prize_group":"1517","rate":"0.2165"},{"prize_group":"1518","rate":"0.2160"},{"prize_group":"1519","rate":"0.2155"},{"prize_group":"1520","rate":"0.2150"},{"prize_group":"1521","rate":"0.2145"},{"prize_group":"1522","rate":"0.2140"},{"prize_group":"1523","rate":"0.2135"},{"prize_group":"1524","rate":"0.2130"},{"prize_group":"1525","rate":"0.2125"},{"prize_group":"1526","rate":"0.2120"},{"prize_group":"1527","rate":"0.2115"},{"prize_group":"1528","rate":"0.2110"},{"prize_group":"1529","rate":"0.2105"},{"prize_group":"1530","rate":"0.2100"},{"prize_group":"1531","rate":"0.2095"},{"prize_group":"1532","rate":"0.2090"},{"prize_group":"1533","rate":"0.2085"},{"prize_group":"1534","rate":"0.2080"},{"prize_group":"1535","rate":"0.2075"},{"prize_group":"1536","rate":"0.2070"},{"prize_group":"1537","rate":"0.2065"},{"prize_group":"1538","rate":"0.2060"},{"prize_group":"1539","rate":"0.2055"},{"prize_group":"1540","rate":"0.2050"},{"prize_group":"1541","rate":"0.2045"},{"prize_group":"1542","rate":"0.2040"},{"prize_group":"1543","rate":"0.2035"},{"prize_group":"1544","rate":"0.2030"},{"prize_group":"1545","rate":"0.2025"},{"prize_group":"1546","rate":"0.2020"},{"prize_group":"1547","rate":"0.2015"},{"prize_group":"1548","rate":"0.2010"},{"prize_group":"1549","rate":"0.2005"},{"prize_group":"1550","rate":"0.2000"},{"prize_group":"1551","rate":"0.1995"},{"prize_group":"1552","rate":"0.1990"},{"prize_group":"1553","rate":"0.1985"},{"prize_group":"1554","rate":"0.1980"},{"prize_group":"1555","rate":"0.1975"},{"prize_group":"1556","rate":"0.1970"},{"prize_group":"1557","rate":"0.1965"},{"prize_group":"1558","rate":"0.1960"},{"prize_group":"1559","rate":"0.1955"},{"prize_group":"1560","rate":"0.1950"},{"prize_group":"1561","rate":"0.1945"},{"prize_group":"1562","rate":"0.1940"},{"prize_group":"1563","rate":"0.1935"},{"prize_group":"1564","rate":"0.1930"},{"prize_group":"1565","rate":"0.1925"},{"prize_group":"1566","rate":"0.1920"},{"prize_group":"1567","rate":"0.1915"},{"prize_group":"1568","rate":"0.1910"},{"prize_group":"1569","rate":"0.1905"},{"prize_group":"1570","rate":"0.1900"},{"prize_group":"1571","rate":"0.1895"},{"prize_group":"1572","rate":"0.1890"},{"prize_group":"1573","rate":"0.1885"},{"prize_group":"1574","rate":"0.1880"},{"prize_group":"1575","rate":"0.1875"},{"prize_group":"1576","rate":"0.1870"},{"prize_group":"1577","rate":"0.1865"},{"prize_group":"1578","rate":"0.1860"},{"prize_group":"1579","rate":"0.1855"},{"prize_group":"1580","rate":"0.1850"},{"prize_group":"1581","rate":"0.1845"},{"prize_group":"1582","rate":"0.1840"},{"prize_group":"1583","rate":"0.1835"},{"prize_group":"1584","rate":"0.1830"},{"prize_group":"1585","rate":"0.1825"},{"prize_group":"1586","rate":"0.1820"},{"prize_group":"1587","rate":"0.1815"},{"prize_group":"1588","rate":"0.1810"},{"prize_group":"1589","rate":"0.1805"},{"prize_group":"1590","rate":"0.1800"},{"prize_group":"1591","rate":"0.1795"},{"prize_group":"1592","rate":"0.1790"},{"prize_group":"1593","rate":"0.1785"},{"prize_group":"1594","rate":"0.1780"},{"prize_group":"1595","rate":"0.1775"},{"prize_group":"1596","rate":"0.1770"},{"prize_group":"1597","rate":"0.1765"},{"prize_group":"1598","rate":"0.1760"},{"prize_group":"1599","rate":"0.1755"},{"prize_group":"1600","rate":"0.1750"},{"prize_group":"1601","rate":"0.1745"},{"prize_group":"1602","rate":"0.1740"},{"prize_group":"1603","rate":"0.1735"},{"prize_group":"1604","rate":"0.1730"},{"prize_group":"1605","rate":"0.1725"},{"prize_group":"1606","rate":"0.1720"},{"prize_group":"1607","rate":"0.1715"},{"prize_group":"1608","rate":"0.1710"},{"prize_group":"1609","rate":"0.1705"},{"prize_group":"1610","rate":"0.1700"},{"prize_group":"1611","rate":"0.1695"},{"prize_group":"1612","rate":"0.1690"},{"prize_group":"1613","rate":"0.1685"},{"prize_group":"1614","rate":"0.1680"},{"prize_group":"1615","rate":"0.1675"},{"prize_group":"1616","rate":"0.1670"},{"prize_group":"1617","rate":"0.1665"},{"prize_group":"1618","rate":"0.1660"},{"prize_group":"1619","rate":"0.1655"},{"prize_group":"1620","rate":"0.1650"},{"prize_group":"1621","rate":"0.1645"},{"prize_group":"1622","rate":"0.1640"},{"prize_group":"1623","rate":"0.1635"},{"prize_group":"1624","rate":"0.1630"},{"prize_group":"1625","rate":"0.1625"},{"prize_group":"1626","rate":"0.1620"},{"prize_group":"1627","rate":"0.1615"},{"prize_group":"1628","rate":"0.1610"},{"prize_group":"1629","rate":"0.1605"},{"prize_group":"1630","rate":"0.1600"},{"prize_group":"1631","rate":"0.1595"},{"prize_group":"1632","rate":"0.1590"},{"prize_group":"1633","rate":"0.1585"},{"prize_group":"1634","rate":"0.1580"},{"prize_group":"1635","rate":"0.1575"},{"prize_group":"1636","rate":"0.1570"},{"prize_group":"1637","rate":"0.1565"},{"prize_group":"1638","rate":"0.1560"},{"prize_group":"1639","rate":"0.1555"},{"prize_group":"1640","rate":"0.1550"},{"prize_group":"1641","rate":"0.1545"},{"prize_group":"1642","rate":"0.1540"},{"prize_group":"1643","rate":"0.1535"},{"prize_group":"1644","rate":"0.1530"},{"prize_group":"1645","rate":"0.1525"},{"prize_group":"1646","rate":"0.1520"},{"prize_group":"1647","rate":"0.1515"},{"prize_group":"1648","rate":"0.1510"},{"prize_group":"1649","rate":"0.1505"},{"prize_group":"1650","rate":"0.1500"},{"prize_group":"1651","rate":"0.1495"},{"prize_group":"1652","rate":"0.1490"},{"prize_group":"1653","rate":"0.1485"},{"prize_group":"1654","rate":"0.1480"},{"prize_group":"1655","rate":"0.1475"},{"prize_group":"1656","rate":"0.1470"},{"prize_group":"1657","rate":"0.1465"},{"prize_group":"1658","rate":"0.1460"},{"prize_group":"1659","rate":"0.1455"},{"prize_group":"1660","rate":"0.1450"},{"prize_group":"1661","rate":"0.1445"},{"prize_group":"1662","rate":"0.1440"},{"prize_group":"1663","rate":"0.1435"},{"prize_group":"1664","rate":"0.1430"},{"prize_group":"1665","rate":"0.1425"},{"prize_group":"1666","rate":"0.1420"},{"prize_group":"1667","rate":"0.1415"},{"prize_group":"1668","rate":"0.1410"},{"prize_group":"1669","rate":"0.1405"},{"prize_group":"1670","rate":"0.1400"},{"prize_group":"1671","rate":"0.1395"},{"prize_group":"1672","rate":"0.1390"},{"prize_group":"1673","rate":"0.1385"},{"prize_group":"1674","rate":"0.1380"},{"prize_group":"1675","rate":"0.1375"},{"prize_group":"1676","rate":"0.1370"},{"prize_group":"1677","rate":"0.1365"},{"prize_group":"1678","rate":"0.1360"},{"prize_group":"1679","rate":"0.1355"},{"prize_group":"1680","rate":"0.1350"},{"prize_group":"1681","rate":"0.1345"},{"prize_group":"1682","rate":"0.1340"},{"prize_group":"1683","rate":"0.1335"},{"prize_group":"1684","rate":"0.1330"},{"prize_group":"1685","rate":"0.1325"},{"prize_group":"1686","rate":"0.1320"},{"prize_group":"1687","rate":"0.1315"},{"prize_group":"1688","rate":"0.1310"},{"prize_group":"1689","rate":"0.1305"},{"prize_group":"1690","rate":"0.1300"},{"prize_group":"1691","rate":"0.1295"},{"prize_group":"1692","rate":"0.1290"},{"prize_group":"1693","rate":"0.1285"},{"prize_group":"1694","rate":"0.1280"},{"prize_group":"1695","rate":"0.1275"},{"prize_group":"1696","rate":"0.1270"},{"prize_group":"1697","rate":"0.1265"},{"prize_group":"1698","rate":"0.1260"},{"prize_group":"1699","rate":"0.1255"},{"prize_group":"1700","rate":"0.1250"},{"prize_group":"1701","rate":"0.1245"},{"prize_group":"1702","rate":"0.1240"},{"prize_group":"1703","rate":"0.1235"},{"prize_group":"1704","rate":"0.1230"},{"prize_group":"1705","rate":"0.1225"},{"prize_group":"1706","rate":"0.1220"},{"prize_group":"1707","rate":"0.1215"},{"prize_group":"1708","rate":"0.1210"},{"prize_group":"1709","rate":"0.1205"},{"prize_group":"1710","rate":"0.1200"},{"prize_group":"1711","rate":"0.1195"},{"prize_group":"1712","rate":"0.1190"},{"prize_group":"1713","rate":"0.1185"},{"prize_group":"1714","rate":"0.1180"},{"prize_group":"1715","rate":"0.1175"},{"prize_group":"1716","rate":"0.1170"},{"prize_group":"1717","rate":"0.1165"},{"prize_group":"1718","rate":"0.1160"},{"prize_group":"1719","rate":"0.1155"},{"prize_group":"1720","rate":"0.1150"},{"prize_group":"1721","rate":"0.1145"},{"prize_group":"1722","rate":"0.1140"},{"prize_group":"1723","rate":"0.1135"},{"prize_group":"1724","rate":"0.1130"},{"prize_group":"1725","rate":"0.1125"},{"prize_group":"1726","rate":"0.1120"},{"prize_group":"1727","rate":"0.1115"},{"prize_group":"1728","rate":"0.1110"},{"prize_group":"1729","rate":"0.1105"},{"prize_group":"1730","rate":"0.1100"},{"prize_group":"1731","rate":"0.1095"},{"prize_group":"1732","rate":"0.1090"},{"prize_group":"1733","rate":"0.1085"},{"prize_group":"1734","rate":"0.1080"},{"prize_group":"1735","rate":"0.1075"},{"prize_group":"1736","rate":"0.1070"},{"prize_group":"1737","rate":"0.1065"},{"prize_group":"1738","rate":"0.1060"},{"prize_group":"1739","rate":"0.1055"},{"prize_group":"1740","rate":"0.1050"},{"prize_group":"1741","rate":"0.1045"},{"prize_group":"1742","rate":"0.1040"},{"prize_group":"1743","rate":"0.1035"},{"prize_group":"1744","rate":"0.1030"},{"prize_group":"1745","rate":"0.1025"},{"prize_group":"1746","rate":"0.1020"},{"prize_group":"1747","rate":"0.1015"},{"prize_group":"1748","rate":"0.1010"},{"prize_group":"1749","rate":"0.1005"},{"prize_group":"1750","rate":"0.1000"},{"prize_group":"1751","rate":"0.0995"},{"prize_group":"1752","rate":"0.0990"},{"prize_group":"1753","rate":"0.0985"},{"prize_group":"1754","rate":"0.0980"},{"prize_group":"1755","rate":"0.0975"},{"prize_group":"1756","rate":"0.0970"},{"prize_group":"1757","rate":"0.0965"},{"prize_group":"1758","rate":"0.0960"},{"prize_group":"1759","rate":"0.0955"},{"prize_group":"1760","rate":"0.0950"},{"prize_group":"1761","rate":"0.0945"},{"prize_group":"1762","rate":"0.0940"},{"prize_group":"1763","rate":"0.0935"},{"prize_group":"1764","rate":"0.0930"},{"prize_group":"1765","rate":"0.0925"},{"prize_group":"1766","rate":"0.0920"},{"prize_group":"1767","rate":"0.0915"},{"prize_group":"1768","rate":"0.0910"},{"prize_group":"1769","rate":"0.0905"},{"prize_group":"1770","rate":"0.0900"},{"prize_group":"1771","rate":"0.0895"},{"prize_group":"1772","rate":"0.0890"},{"prize_group":"1773","rate":"0.0885"},{"prize_group":"1774","rate":"0.0880"},{"prize_group":"1775","rate":"0.0875"},{"prize_group":"1776","rate":"0.0870"},{"prize_group":"1777","rate":"0.0865"},{"prize_group":"1778","rate":"0.0860"},{"prize_group":"1779","rate":"0.0855"},{"prize_group":"1780","rate":"0.0850"},{"prize_group":"1781","rate":"0.0845"},{"prize_group":"1782","rate":"0.0840"},{"prize_group":"1783","rate":"0.0835"},{"prize_group":"1784","rate":"0.0830"},{"prize_group":"1785","rate":"0.0825"},{"prize_group":"1786","rate":"0.0820"},{"prize_group":"1787","rate":"0.0815"},{"prize_group":"1788","rate":"0.0810"},{"prize_group":"1789","rate":"0.0805"},{"prize_group":"1790","rate":"0.0800"},{"prize_group":"1791","rate":"0.0795"},{"prize_group":"1792","rate":"0.0790"},{"prize_group":"1793","rate":"0.0785"},{"prize_group":"1794","rate":"0.0780"},{"prize_group":"1795","rate":"0.0775"},{"prize_group":"1796","rate":"0.0770"},{"prize_group":"1797","rate":"0.0765"},{"prize_group":"1798","rate":"0.0760"},{"prize_group":"1799","rate":"0.0755"},{"prize_group":"1800","rate":"0.0750"},{"prize_group":"1801","rate":"0.0745"},{"prize_group":"1802","rate":"0.0740"},{"prize_group":"1803","rate":"0.0735"},{"prize_group":"1804","rate":"0.0730"},{"prize_group":"1805","rate":"0.0725"},{"prize_group":"1806","rate":"0.0720"},{"prize_group":"1807","rate":"0.0715"},{"prize_group":"1808","rate":"0.0710"},{"prize_group":"1809","rate":"0.0705"},{"prize_group":"1810","rate":"0.0700"},{"prize_group":"1811","rate":"0.0695"},{"prize_group":"1812","rate":"0.0690"},{"prize_group":"1813","rate":"0.0685"},{"prize_group":"1814","rate":"0.0680"},{"prize_group":"1815","rate":"0.0675"},{"prize_group":"1816","rate":"0.0670"},{"prize_group":"1817","rate":"0.0665"},{"prize_group":"1818","rate":"0.0660"},{"prize_group":"1819","rate":"0.0655"},{"prize_group":"1820","rate":"0.0650"},{"prize_group":"1821","rate":"0.0645"},{"prize_group":"1822","rate":"0.0640"},{"prize_group":"1823","rate":"0.0635"},{"prize_group":"1824","rate":"0.0630"},{"prize_group":"1825","rate":"0.0625"},{"prize_group":"1826","rate":"0.0620"},{"prize_group":"1827","rate":"0.0615"},{"prize_group":"1828","rate":"0.0610"},{"prize_group":"1829","rate":"0.0605"},{"prize_group":"1830","rate":"0.0600"},{"prize_group":"1831","rate":"0.0595"},{"prize_group":"1832","rate":"0.0590"},{"prize_group":"1833","rate":"0.0585"},{"prize_group":"1834","rate":"0.0580"},{"prize_group":"1835","rate":"0.0575"},{"prize_group":"1836","rate":"0.0570"},{"prize_group":"1837","rate":"0.0565"},{"prize_group":"1838","rate":"0.0560"},{"prize_group":"1839","rate":"0.0555"},{"prize_group":"1840","rate":"0.0550"},{"prize_group":"1841","rate":"0.0545"},{"prize_group":"1842","rate":"0.0540"},{"prize_group":"1843","rate":"0.0535"},{"prize_group":"1844","rate":"0.0530"},{"prize_group":"1845","rate":"0.0525"},{"prize_group":"1846","rate":"0.0520"},{"prize_group":"1847","rate":"0.0515"},{"prize_group":"1848","rate":"0.0510"},{"prize_group":"1849","rate":"0.0505"},{"prize_group":"1850","rate":"0.0500"},{"prize_group":"1851","rate":"0.0495"},{"prize_group":"1852","rate":"0.0490"},{"prize_group":"1853","rate":"0.0485"},{"prize_group":"1854","rate":"0.0480"},{"prize_group":"1855","rate":"0.0475"},{"prize_group":"1856","rate":"0.0470"},{"prize_group":"1857","rate":"0.0465"},{"prize_group":"1858","rate":"0.0460"},{"prize_group":"1859","rate":"0.0455"},{"prize_group":"1860","rate":"0.0450"},{"prize_group":"1861","rate":"0.0445"},{"prize_group":"1862","rate":"0.0440"},{"prize_group":"1863","rate":"0.0435"},{"prize_group":"1864","rate":"0.0430"},{"prize_group":"1865","rate":"0.0425"},{"prize_group":"1866","rate":"0.0420"},{"prize_group":"1867","rate":"0.0415"},{"prize_group":"1868","rate":"0.0410"},{"prize_group":"1869","rate":"0.0405"},{"prize_group":"1870","rate":"0.0400"},{"prize_group":"1871","rate":"0.0395"},{"prize_group":"1872","rate":"0.0390"},{"prize_group":"1873","rate":"0.0385"},{"prize_group":"1874","rate":"0.0380"},{"prize_group":"1875","rate":"0.0375"},{"prize_group":"1876","rate":"0.0370"},{"prize_group":"1877","rate":"0.0365"},{"prize_group":"1878","rate":"0.0360"},{"prize_group":"1879","rate":"0.0355"},{"prize_group":"1880","rate":"0.0350"},{"prize_group":"1881","rate":"0.0345"},{"prize_group":"1882","rate":"0.0340"},{"prize_group":"1883","rate":"0.0335"},{"prize_group":"1884","rate":"0.0330"},{"prize_group":"1885","rate":"0.0325"},{"prize_group":"1886","rate":"0.0320"},{"prize_group":"1887","rate":"0.0315"},{"prize_group":"1888","rate":"0.0310"},{"prize_group":"1889","rate":"0.0305"},{"prize_group":"1890","rate":"0.0300"},{"prize_group":"1891","rate":"0.0295"},{"prize_group":"1892","rate":"0.0290"},{"prize_group":"1893","rate":"0.0285"},{"prize_group":"1894","rate":"0.0280"},{"prize_group":"1895","rate":"0.0275"},{"prize_group":"1896","rate":"0.0270"},{"prize_group":"1897","rate":"0.0265"},{"prize_group":"1898","rate":"0.0260"},{"prize_group":"1899","rate":"0.0255"},{"prize_group":"1900","rate":"0.0250"},{"prize_group":"1901","rate":"0.0245"},{"prize_group":"1902","rate":"0.0240"},{"prize_group":"1903","rate":"0.0235"},{"prize_group":"1904","rate":"0.0230"},{"prize_group":"1905","rate":"0.0225"},{"prize_group":"1906","rate":"0.0220"},{"prize_group":"1907","rate":"0.0215"},{"prize_group":"1908","rate":"0.0210"},{"prize_group":"1909","rate":"0.0205"},{"prize_group":"1910","rate":"0.0200"},{"prize_group":"1911","rate":"0.0195"},{"prize_group":"1912","rate":"0.0190"},{"prize_group":"1913","rate":"0.0185"},{"prize_group":"1914","rate":"0.0180"},{"prize_group":"1915","rate":"0.0175"},{"prize_group":"1916","rate":"0.0170"},{"prize_group":"1917","rate":"0.0165"},{"prize_group":"1918","rate":"0.0160"},{"prize_group":"1919","rate":"0.0155"},{"prize_group":"1920","rate":"0.0150"},{"prize_group":"1921","rate":"0.0145"},{"prize_group":"1922","rate":"0.0140"},{"prize_group":"1923","rate":"0.0135"},{"prize_group":"1924","rate":"0.0130"},{"prize_group":"1925","rate":"0.0125"},{"prize_group":"1926","rate":"0.0120"},{"prize_group":"1927","rate":"0.0115"},{"prize_group":"1928","rate":"0.0110"},{"prize_group":"1929","rate":"0.0105"},{"prize_group":"1930","rate":"0.0100"},{"prize_group":"1931","rate":"0.0095"},{"prize_group":"1932","rate":"0.0090"},{"prize_group":"1933","rate":"0.0085"},{"prize_group":"1934","rate":"0.0080"},{"prize_group":"1935","rate":"0.0075"},{"prize_group":"1936","rate":"0.0070"},{"prize_group":"1937","rate":"0.0065"},{"prize_group":"1938","rate":"0.0060"},{"prize_group":"1939","rate":"0.0055"},{"prize_group":"1940","rate":"0.0050"},{"prize_group":"1941","rate":"0.0045"},{"prize_group":"1942","rate":"0.0040"},{"prize_group":"1943","rate":"0.0035"},{"prize_group":"1944","rate":"0.0030"},{"prize_group":"1945","rate":"0.0025"},{"prize_group":"1946","rate":"0.0020"},{"prize_group":"1947","rate":"0.0015"},{"prize_group":"1948","rate":"0.0010"},{"prize_group":"1949","rate":"0.0005"},{"prize_group":"1950","rate":"0.0000"}],"currentTime":1553066964,"availableCoefficients":{"1.000":"2元","0.500":"1元","0.100":"2角","0.050":"1角","0.010":"2分","0.001":"2厘"},"defaultMultiple":1,"defaultCoefficient":"0.500","prizeLimit":"300000","maxPrizeGroup":"1950","betSubmitCompress":"0","traceMaxTimes":5,"gameNumbers":[{"number":"20190320022","time":"2019-03-20 15:48:00"},{"number":"20190320023","time":"2019-03-20 16:08:00"},{"number":"20190320024","time":"2019-03-20 16:28:00"},{"number":"20190320025","time":"2019-03-20 16:48:00"},{"number":"20190320026","time":"2019-03-20 17:08:00"}],"currentNumber":"20190320022","currentNumberTime":1553068080,"issueHistory":{"issues":[{"issue":"20190320021","wn_number":"","offical_time":1553067000},{"issue":"20190320020","wn_number":"5,6,6","offical_time":1553065800},{"issue":"20190320019","wn_number":"1,2,4","offical_time":1553064600},{"issue":"20190320018","wn_number":"1,3,4","offical_time":1553063400},{"issue":"20190320017","wn_number":"2,3,3","offical_time":1553062200}],"last_number":{"issue":"20190320020","wn_number":"5,6,6","offical_time":1553065800},"current_issue":"20190320022"}}
     * sign : 01a6e676ff6805e13aa127948c5479c8
     */

    private int errno;
    private String error;
    private DataBean data;
    private String sign;

    public int getErrno() {
        return errno;
    }

    public void setErrno(int errno) {
        this.errno = errno;
    }

    public String getError() {
        return error;
    }

    public void setError(String error) {
        this.error = error;
    }

    public DataBean getData() {
        return data;
    }

    public void setData(DataBean data) {
        this.data = data;
    }

    public String getSign() {
        return sign;
    }

    public void setSign(String sign) {
        this.sign = sign;
    }

    public static class DataBean {
        /**
         * gameId : 15
         * gameSeriesId : 4
         * gameNameEn : JSK3
         * gameNameCn : 江苏快三
         * wayGroups : [{"id":65,"pid":0,"name_cn":"和值","name_en":"hz","children":[{"id":75,"pid":65,"name_cn":"和值","name_en":"hz","children":[{"id":157,"pid":75,"series_way_id":157,"name_cn":"和值","name_en":"fs","price":2,"bet_note":"至少选择1个和值（3个号码之和）进行投注","bonus_note":"所选和值与开奖的3个号码的和值相同即中奖。和值10,11为八等奖，和值9,12为七等奖，和值8,13为六等奖，和值7,14为五等奖，和值6,15为四等奖，和值5,16为三等奖，和值4,17为二等奖，和值3,18为一等级。每个奖级(2块钱投注)对应的奖金请到 个人中心-我的奖金组-查看全部-点击对应的彩种 即可弹窗看到。","basic_methods":"61","prize":"408.31","max_multiple":734}]}]},{"id":66,"pid":0,"name_cn":"三同号","name_en":"sth","children":[{"id":76,"pid":66,"name_cn":"三同号","name_en":"sth","children":[{"id":158,"pid":76,"series_way_id":158,"name_cn":"三同号单选","name_en":"dx","price":2,"bet_note":"对相同的三个号码（111、222、333、444、555、666）中的任意一个进行投注","bonus_note":"所选号码开出即中奖","basic_methods":"57","prize":"408.31","max_multiple":734},{"id":159,"pid":76,"series_way_id":159,"name_cn":"三同号通选","name_en":"tx","price":2,"bet_note":"对所有相同的三个号码（111、222、333、444、555、666）进行投注","bonus_note":"三位号码全相同即中奖","basic_methods":"58","prize":"68.04","max_multiple":4409}]}]},{"id":68,"pid":0,"name_cn":"二同号","name_en":"eth","children":[{"id":78,"pid":68,"name_cn":"二同号","name_en":"eth","children":[{"id":160,"pid":78,"series_way_id":160,"name_cn":"二同号单选","name_en":"dx","price":2,"bet_note":"选择1对相同号码（11,22,33,44,55,66）和1个不同号码(1,2,3,4,5,6)投注","bonus_note":"选号与奖号相同（顺序不限）","basic_methods":"59","prize":"136.09","max_multiple":2204},{"id":161,"pid":78,"series_way_id":161,"name_cn":"二同号复选","name_en":"fx","price":2,"bet_note":"从11～66中任选1个或多个号码","bonus_note":"中奖号码中包括所选的号码即为中奖。如买的是11,则中奖号码为112,则中奖","basic_methods":"60","prize":"25.52","max_multiple":11755}]}]},{"id":70,"pid":0,"name_cn":"三不同号","name_en":"sbth","children":[{"id":80,"pid":70,"name_cn":"三不同号","name_en":"sbth","children":[{"id":162,"pid":80,"series_way_id":162,"name_cn":"三不同号","name_en":"fs","price":2,"bet_note":"从1～6中任选3个或多个号码","bonus_note":"中奖号码由三个不同的号码，且全部在所购买的号码中，即为中奖","basic_methods":"62","prize":"68.04","max_multiple":4409}]}]},{"id":71,"pid":0,"name_cn":"二不同号","name_en":"ebth","children":[{"id":81,"pid":71,"name_cn":"二不同号","name_en":"ebth","children":[{"id":163,"pid":81,"series_way_id":163,"name_cn":"二不同号","name_en":"fs","price":2,"bet_note":"从1～6中任选2个或多个号码","bonus_note":"所选号码与开奖号码任意2个号码相同，即中奖","basic_methods":"63","prize":"13.61","max_multiple":22042}]}]},{"id":72,"pid":0,"name_cn":"三连号","name_en":"slh","children":[{"id":82,"pid":72,"name_cn":"三连号","name_en":"slh","children":[{"id":164,"pid":82,"series_way_id":164,"name_cn":"三连号通选","name_en":"fs","price":2,"bet_note":"对所有3个相连的号码（123、234、345、456）进行投注","bonus_note":"中奖号码为123、234、345、456之一即中奖","basic_methods":"64","prize":"17.00","max_multiple":17647}]}]},{"id":73,"pid":0,"name_cn":"大小","name_en":"dx","children":[{"id":83,"pid":73,"name_cn":"大小","name_en":"dx","children":[{"id":165,"pid":83,"series_way_id":165,"name_cn":"大小","name_en":"fs","price":2,"bet_note":"对号码的号码的三位数和值的大小形态进行投注。和值>10，则为大，反之则为小。","bonus_note":"中奖号码和值的大小形态与所选形态形同，即中奖","basic_methods":"65","prize":"3.78","max_multiple":79365}]}]},{"id":74,"pid":0,"name_cn":"单双","name_en":"ds","children":[{"id":84,"pid":74,"name_cn":"单双","name_en":"ds","children":[{"id":166,"pid":84,"series_way_id":166,"name_cn":"单双","name_en":"fs","price":2,"bet_note":"对号码的号码的三位数和值的单双形态进行投注","bonus_note":"中奖号码和值的单双形态与所选形态形同，即中奖","basic_methods":"66","prize":"3.78","max_multiple":79365}]}]},{"id":85,"pid":0,"name_cn":"猜必出","name_en":"bdw","children":[{"id":86,"pid":85,"name_cn":"猜必出","name_en":"bdw","children":[{"id":167,"pid":86,"series_way_id":167,"name_cn":"猜必出","name_en":"fs","price":2,"bet_note":"从1至6选择1个或更多号码","bonus_note":"所选号码在开奖号码中出现即中奖","basic_methods":"67","prize":"4.48","max_multiple":66964}]}]},{"id":110,"pid":0,"name_cn":"颜色","name_en":"color","children":[{"id":111,"pid":110,"name_cn":"颜色","name_en":"color","children":[{"id":378,"pid":111,"series_way_id":378,"name_cn":"全红","name_en":"red","price":2,"bet_note":"当开出奖号都为红色，仅出现号码1或4，即中奖。","bonus_note":"当开出奖号都为红色，仅出现号码1或4，即中奖。","basic_methods":"110","prize":"51.04","max_multiple":5877},{"id":379,"pid":111,"series_way_id":379,"name_cn":"全黑","name_en":"black","price":2,"bet_note":"当开出奖号都为黑色，仅出现号码2,3,5,6，即中奖。","bonus_note":"当开出奖号都为黑色，仅出现号码2,3,5,6，即中奖。","basic_methods":"111","prize":"6.38","max_multiple":47021}]}]}]
         * defaultMethodId : 157
         * optionalPrizes : [{"prize_group":"1502","rate":"0.2240"},{"prize_group":"1503","rate":"0.2235"},{"prize_group":"1504","rate":"0.2230"},{"prize_group":"1505","rate":"0.2225"},{"prize_group":"1506","rate":"0.2220"},{"prize_group":"1507","rate":"0.2215"},{"prize_group":"1508","rate":"0.2210"},{"prize_group":"1509","rate":"0.2205"},{"prize_group":"1510","rate":"0.2200"},{"prize_group":"1511","rate":"0.2195"},{"prize_group":"1512","rate":"0.2190"},{"prize_group":"1513","rate":"0.2185"},{"prize_group":"1514","rate":"0.2180"},{"prize_group":"1515","rate":"0.2175"},{"prize_group":"1516","rate":"0.2170"},{"prize_group":"1517","rate":"0.2165"},{"prize_group":"1518","rate":"0.2160"},{"prize_group":"1519","rate":"0.2155"},{"prize_group":"1520","rate":"0.2150"},{"prize_group":"1521","rate":"0.2145"},{"prize_group":"1522","rate":"0.2140"},{"prize_group":"1523","rate":"0.2135"},{"prize_group":"1524","rate":"0.2130"},{"prize_group":"1525","rate":"0.2125"},{"prize_group":"1526","rate":"0.2120"},{"prize_group":"1527","rate":"0.2115"},{"prize_group":"1528","rate":"0.2110"},{"prize_group":"1529","rate":"0.2105"},{"prize_group":"1530","rate":"0.2100"},{"prize_group":"1531","rate":"0.2095"},{"prize_group":"1532","rate":"0.2090"},{"prize_group":"1533","rate":"0.2085"},{"prize_group":"1534","rate":"0.2080"},{"prize_group":"1535","rate":"0.2075"},{"prize_group":"1536","rate":"0.2070"},{"prize_group":"1537","rate":"0.2065"},{"prize_group":"1538","rate":"0.2060"},{"prize_group":"1539","rate":"0.2055"},{"prize_group":"1540","rate":"0.2050"},{"prize_group":"1541","rate":"0.2045"},{"prize_group":"1542","rate":"0.2040"},{"prize_group":"1543","rate":"0.2035"},{"prize_group":"1544","rate":"0.2030"},{"prize_group":"1545","rate":"0.2025"},{"prize_group":"1546","rate":"0.2020"},{"prize_group":"1547","rate":"0.2015"},{"prize_group":"1548","rate":"0.2010"},{"prize_group":"1549","rate":"0.2005"},{"prize_group":"1550","rate":"0.2000"},{"prize_group":"1551","rate":"0.1995"},{"prize_group":"1552","rate":"0.1990"},{"prize_group":"1553","rate":"0.1985"},{"prize_group":"1554","rate":"0.1980"},{"prize_group":"1555","rate":"0.1975"},{"prize_group":"1556","rate":"0.1970"},{"prize_group":"1557","rate":"0.1965"},{"prize_group":"1558","rate":"0.1960"},{"prize_group":"1559","rate":"0.1955"},{"prize_group":"1560","rate":"0.1950"},{"prize_group":"1561","rate":"0.1945"},{"prize_group":"1562","rate":"0.1940"},{"prize_group":"1563","rate":"0.1935"},{"prize_group":"1564","rate":"0.1930"},{"prize_group":"1565","rate":"0.1925"},{"prize_group":"1566","rate":"0.1920"},{"prize_group":"1567","rate":"0.1915"},{"prize_group":"1568","rate":"0.1910"},{"prize_group":"1569","rate":"0.1905"},{"prize_group":"1570","rate":"0.1900"},{"prize_group":"1571","rate":"0.1895"},{"prize_group":"1572","rate":"0.1890"},{"prize_group":"1573","rate":"0.1885"},{"prize_group":"1574","rate":"0.1880"},{"prize_group":"1575","rate":"0.1875"},{"prize_group":"1576","rate":"0.1870"},{"prize_group":"1577","rate":"0.1865"},{"prize_group":"1578","rate":"0.1860"},{"prize_group":"1579","rate":"0.1855"},{"prize_group":"1580","rate":"0.1850"},{"prize_group":"1581","rate":"0.1845"},{"prize_group":"1582","rate":"0.1840"},{"prize_group":"1583","rate":"0.1835"},{"prize_group":"1584","rate":"0.1830"},{"prize_group":"1585","rate":"0.1825"},{"prize_group":"1586","rate":"0.1820"},{"prize_group":"1587","rate":"0.1815"},{"prize_group":"1588","rate":"0.1810"},{"prize_group":"1589","rate":"0.1805"},{"prize_group":"1590","rate":"0.1800"},{"prize_group":"1591","rate":"0.1795"},{"prize_group":"1592","rate":"0.1790"},{"prize_group":"1593","rate":"0.1785"},{"prize_group":"1594","rate":"0.1780"},{"prize_group":"1595","rate":"0.1775"},{"prize_group":"1596","rate":"0.1770"},{"prize_group":"1597","rate":"0.1765"},{"prize_group":"1598","rate":"0.1760"},{"prize_group":"1599","rate":"0.1755"},{"prize_group":"1600","rate":"0.1750"},{"prize_group":"1601","rate":"0.1745"},{"prize_group":"1602","rate":"0.1740"},{"prize_group":"1603","rate":"0.1735"},{"prize_group":"1604","rate":"0.1730"},{"prize_group":"1605","rate":"0.1725"},{"prize_group":"1606","rate":"0.1720"},{"prize_group":"1607","rate":"0.1715"},{"prize_group":"1608","rate":"0.1710"},{"prize_group":"1609","rate":"0.1705"},{"prize_group":"1610","rate":"0.1700"},{"prize_group":"1611","rate":"0.1695"},{"prize_group":"1612","rate":"0.1690"},{"prize_group":"1613","rate":"0.1685"},{"prize_group":"1614","rate":"0.1680"},{"prize_group":"1615","rate":"0.1675"},{"prize_group":"1616","rate":"0.1670"},{"prize_group":"1617","rate":"0.1665"},{"prize_group":"1618","rate":"0.1660"},{"prize_group":"1619","rate":"0.1655"},{"prize_group":"1620","rate":"0.1650"},{"prize_group":"1621","rate":"0.1645"},{"prize_group":"1622","rate":"0.1640"},{"prize_group":"1623","rate":"0.1635"},{"prize_group":"1624","rate":"0.1630"},{"prize_group":"1625","rate":"0.1625"},{"prize_group":"1626","rate":"0.1620"},{"prize_group":"1627","rate":"0.1615"},{"prize_group":"1628","rate":"0.1610"},{"prize_group":"1629","rate":"0.1605"},{"prize_group":"1630","rate":"0.1600"},{"prize_group":"1631","rate":"0.1595"},{"prize_group":"1632","rate":"0.1590"},{"prize_group":"1633","rate":"0.1585"},{"prize_group":"1634","rate":"0.1580"},{"prize_group":"1635","rate":"0.1575"},{"prize_group":"1636","rate":"0.1570"},{"prize_group":"1637","rate":"0.1565"},{"prize_group":"1638","rate":"0.1560"},{"prize_group":"1639","rate":"0.1555"},{"prize_group":"1640","rate":"0.1550"},{"prize_group":"1641","rate":"0.1545"},{"prize_group":"1642","rate":"0.1540"},{"prize_group":"1643","rate":"0.1535"},{"prize_group":"1644","rate":"0.1530"},{"prize_group":"1645","rate":"0.1525"},{"prize_group":"1646","rate":"0.1520"},{"prize_group":"1647","rate":"0.1515"},{"prize_group":"1648","rate":"0.1510"},{"prize_group":"1649","rate":"0.1505"},{"prize_group":"1650","rate":"0.1500"},{"prize_group":"1651","rate":"0.1495"},{"prize_group":"1652","rate":"0.1490"},{"prize_group":"1653","rate":"0.1485"},{"prize_group":"1654","rate":"0.1480"},{"prize_group":"1655","rate":"0.1475"},{"prize_group":"1656","rate":"0.1470"},{"prize_group":"1657","rate":"0.1465"},{"prize_group":"1658","rate":"0.1460"},{"prize_group":"1659","rate":"0.1455"},{"prize_group":"1660","rate":"0.1450"},{"prize_group":"1661","rate":"0.1445"},{"prize_group":"1662","rate":"0.1440"},{"prize_group":"1663","rate":"0.1435"},{"prize_group":"1664","rate":"0.1430"},{"prize_group":"1665","rate":"0.1425"},{"prize_group":"1666","rate":"0.1420"},{"prize_group":"1667","rate":"0.1415"},{"prize_group":"1668","rate":"0.1410"},{"prize_group":"1669","rate":"0.1405"},{"prize_group":"1670","rate":"0.1400"},{"prize_group":"1671","rate":"0.1395"},{"prize_group":"1672","rate":"0.1390"},{"prize_group":"1673","rate":"0.1385"},{"prize_group":"1674","rate":"0.1380"},{"prize_group":"1675","rate":"0.1375"},{"prize_group":"1676","rate":"0.1370"},{"prize_group":"1677","rate":"0.1365"},{"prize_group":"1678","rate":"0.1360"},{"prize_group":"1679","rate":"0.1355"},{"prize_group":"1680","rate":"0.1350"},{"prize_group":"1681","rate":"0.1345"},{"prize_group":"1682","rate":"0.1340"},{"prize_group":"1683","rate":"0.1335"},{"prize_group":"1684","rate":"0.1330"},{"prize_group":"1685","rate":"0.1325"},{"prize_group":"1686","rate":"0.1320"},{"prize_group":"1687","rate":"0.1315"},{"prize_group":"1688","rate":"0.1310"},{"prize_group":"1689","rate":"0.1305"},{"prize_group":"1690","rate":"0.1300"},{"prize_group":"1691","rate":"0.1295"},{"prize_group":"1692","rate":"0.1290"},{"prize_group":"1693","rate":"0.1285"},{"prize_group":"1694","rate":"0.1280"},{"prize_group":"1695","rate":"0.1275"},{"prize_group":"1696","rate":"0.1270"},{"prize_group":"1697","rate":"0.1265"},{"prize_group":"1698","rate":"0.1260"},{"prize_group":"1699","rate":"0.1255"},{"prize_group":"1700","rate":"0.1250"},{"prize_group":"1701","rate":"0.1245"},{"prize_group":"1702","rate":"0.1240"},{"prize_group":"1703","rate":"0.1235"},{"prize_group":"1704","rate":"0.1230"},{"prize_group":"1705","rate":"0.1225"},{"prize_group":"1706","rate":"0.1220"},{"prize_group":"1707","rate":"0.1215"},{"prize_group":"1708","rate":"0.1210"},{"prize_group":"1709","rate":"0.1205"},{"prize_group":"1710","rate":"0.1200"},{"prize_group":"1711","rate":"0.1195"},{"prize_group":"1712","rate":"0.1190"},{"prize_group":"1713","rate":"0.1185"},{"prize_group":"1714","rate":"0.1180"},{"prize_group":"1715","rate":"0.1175"},{"prize_group":"1716","rate":"0.1170"},{"prize_group":"1717","rate":"0.1165"},{"prize_group":"1718","rate":"0.1160"},{"prize_group":"1719","rate":"0.1155"},{"prize_group":"1720","rate":"0.1150"},{"prize_group":"1721","rate":"0.1145"},{"prize_group":"1722","rate":"0.1140"},{"prize_group":"1723","rate":"0.1135"},{"prize_group":"1724","rate":"0.1130"},{"prize_group":"1725","rate":"0.1125"},{"prize_group":"1726","rate":"0.1120"},{"prize_group":"1727","rate":"0.1115"},{"prize_group":"1728","rate":"0.1110"},{"prize_group":"1729","rate":"0.1105"},{"prize_group":"1730","rate":"0.1100"},{"prize_group":"1731","rate":"0.1095"},{"prize_group":"1732","rate":"0.1090"},{"prize_group":"1733","rate":"0.1085"},{"prize_group":"1734","rate":"0.1080"},{"prize_group":"1735","rate":"0.1075"},{"prize_group":"1736","rate":"0.1070"},{"prize_group":"1737","rate":"0.1065"},{"prize_group":"1738","rate":"0.1060"},{"prize_group":"1739","rate":"0.1055"},{"prize_group":"1740","rate":"0.1050"},{"prize_group":"1741","rate":"0.1045"},{"prize_group":"1742","rate":"0.1040"},{"prize_group":"1743","rate":"0.1035"},{"prize_group":"1744","rate":"0.1030"},{"prize_group":"1745","rate":"0.1025"},{"prize_group":"1746","rate":"0.1020"},{"prize_group":"1747","rate":"0.1015"},{"prize_group":"1748","rate":"0.1010"},{"prize_group":"1749","rate":"0.1005"},{"prize_group":"1750","rate":"0.1000"},{"prize_group":"1751","rate":"0.0995"},{"prize_group":"1752","rate":"0.0990"},{"prize_group":"1753","rate":"0.0985"},{"prize_group":"1754","rate":"0.0980"},{"prize_group":"1755","rate":"0.0975"},{"prize_group":"1756","rate":"0.0970"},{"prize_group":"1757","rate":"0.0965"},{"prize_group":"1758","rate":"0.0960"},{"prize_group":"1759","rate":"0.0955"},{"prize_group":"1760","rate":"0.0950"},{"prize_group":"1761","rate":"0.0945"},{"prize_group":"1762","rate":"0.0940"},{"prize_group":"1763","rate":"0.0935"},{"prize_group":"1764","rate":"0.0930"},{"prize_group":"1765","rate":"0.0925"},{"prize_group":"1766","rate":"0.0920"},{"prize_group":"1767","rate":"0.0915"},{"prize_group":"1768","rate":"0.0910"},{"prize_group":"1769","rate":"0.0905"},{"prize_group":"1770","rate":"0.0900"},{"prize_group":"1771","rate":"0.0895"},{"prize_group":"1772","rate":"0.0890"},{"prize_group":"1773","rate":"0.0885"},{"prize_group":"1774","rate":"0.0880"},{"prize_group":"1775","rate":"0.0875"},{"prize_group":"1776","rate":"0.0870"},{"prize_group":"1777","rate":"0.0865"},{"prize_group":"1778","rate":"0.0860"},{"prize_group":"1779","rate":"0.0855"},{"prize_group":"1780","rate":"0.0850"},{"prize_group":"1781","rate":"0.0845"},{"prize_group":"1782","rate":"0.0840"},{"prize_group":"1783","rate":"0.0835"},{"prize_group":"1784","rate":"0.0830"},{"prize_group":"1785","rate":"0.0825"},{"prize_group":"1786","rate":"0.0820"},{"prize_group":"1787","rate":"0.0815"},{"prize_group":"1788","rate":"0.0810"},{"prize_group":"1789","rate":"0.0805"},{"prize_group":"1790","rate":"0.0800"},{"prize_group":"1791","rate":"0.0795"},{"prize_group":"1792","rate":"0.0790"},{"prize_group":"1793","rate":"0.0785"},{"prize_group":"1794","rate":"0.0780"},{"prize_group":"1795","rate":"0.0775"},{"prize_group":"1796","rate":"0.0770"},{"prize_group":"1797","rate":"0.0765"},{"prize_group":"1798","rate":"0.0760"},{"prize_group":"1799","rate":"0.0755"},{"prize_group":"1800","rate":"0.0750"},{"prize_group":"1801","rate":"0.0745"},{"prize_group":"1802","rate":"0.0740"},{"prize_group":"1803","rate":"0.0735"},{"prize_group":"1804","rate":"0.0730"},{"prize_group":"1805","rate":"0.0725"},{"prize_group":"1806","rate":"0.0720"},{"prize_group":"1807","rate":"0.0715"},{"prize_group":"1808","rate":"0.0710"},{"prize_group":"1809","rate":"0.0705"},{"prize_group":"1810","rate":"0.0700"},{"prize_group":"1811","rate":"0.0695"},{"prize_group":"1812","rate":"0.0690"},{"prize_group":"1813","rate":"0.0685"},{"prize_group":"1814","rate":"0.0680"},{"prize_group":"1815","rate":"0.0675"},{"prize_group":"1816","rate":"0.0670"},{"prize_group":"1817","rate":"0.0665"},{"prize_group":"1818","rate":"0.0660"},{"prize_group":"1819","rate":"0.0655"},{"prize_group":"1820","rate":"0.0650"},{"prize_group":"1821","rate":"0.0645"},{"prize_group":"1822","rate":"0.0640"},{"prize_group":"1823","rate":"0.0635"},{"prize_group":"1824","rate":"0.0630"},{"prize_group":"1825","rate":"0.0625"},{"prize_group":"1826","rate":"0.0620"},{"prize_group":"1827","rate":"0.0615"},{"prize_group":"1828","rate":"0.0610"},{"prize_group":"1829","rate":"0.0605"},{"prize_group":"1830","rate":"0.0600"},{"prize_group":"1831","rate":"0.0595"},{"prize_group":"1832","rate":"0.0590"},{"prize_group":"1833","rate":"0.0585"},{"prize_group":"1834","rate":"0.0580"},{"prize_group":"1835","rate":"0.0575"},{"prize_group":"1836","rate":"0.0570"},{"prize_group":"1837","rate":"0.0565"},{"prize_group":"1838","rate":"0.0560"},{"prize_group":"1839","rate":"0.0555"},{"prize_group":"1840","rate":"0.0550"},{"prize_group":"1841","rate":"0.0545"},{"prize_group":"1842","rate":"0.0540"},{"prize_group":"1843","rate":"0.0535"},{"prize_group":"1844","rate":"0.0530"},{"prize_group":"1845","rate":"0.0525"},{"prize_group":"1846","rate":"0.0520"},{"prize_group":"1847","rate":"0.0515"},{"prize_group":"1848","rate":"0.0510"},{"prize_group":"1849","rate":"0.0505"},{"prize_group":"1850","rate":"0.0500"},{"prize_group":"1851","rate":"0.0495"},{"prize_group":"1852","rate":"0.0490"},{"prize_group":"1853","rate":"0.0485"},{"prize_group":"1854","rate":"0.0480"},{"prize_group":"1855","rate":"0.0475"},{"prize_group":"1856","rate":"0.0470"},{"prize_group":"1857","rate":"0.0465"},{"prize_group":"1858","rate":"0.0460"},{"prize_group":"1859","rate":"0.0455"},{"prize_group":"1860","rate":"0.0450"},{"prize_group":"1861","rate":"0.0445"},{"prize_group":"1862","rate":"0.0440"},{"prize_group":"1863","rate":"0.0435"},{"prize_group":"1864","rate":"0.0430"},{"prize_group":"1865","rate":"0.0425"},{"prize_group":"1866","rate":"0.0420"},{"prize_group":"1867","rate":"0.0415"},{"prize_group":"1868","rate":"0.0410"},{"prize_group":"1869","rate":"0.0405"},{"prize_group":"1870","rate":"0.0400"},{"prize_group":"1871","rate":"0.0395"},{"prize_group":"1872","rate":"0.0390"},{"prize_group":"1873","rate":"0.0385"},{"prize_group":"1874","rate":"0.0380"},{"prize_group":"1875","rate":"0.0375"},{"prize_group":"1876","rate":"0.0370"},{"prize_group":"1877","rate":"0.0365"},{"prize_group":"1878","rate":"0.0360"},{"prize_group":"1879","rate":"0.0355"},{"prize_group":"1880","rate":"0.0350"},{"prize_group":"1881","rate":"0.0345"},{"prize_group":"1882","rate":"0.0340"},{"prize_group":"1883","rate":"0.0335"},{"prize_group":"1884","rate":"0.0330"},{"prize_group":"1885","rate":"0.0325"},{"prize_group":"1886","rate":"0.0320"},{"prize_group":"1887","rate":"0.0315"},{"prize_group":"1888","rate":"0.0310"},{"prize_group":"1889","rate":"0.0305"},{"prize_group":"1890","rate":"0.0300"},{"prize_group":"1891","rate":"0.0295"},{"prize_group":"1892","rate":"0.0290"},{"prize_group":"1893","rate":"0.0285"},{"prize_group":"1894","rate":"0.0280"},{"prize_group":"1895","rate":"0.0275"},{"prize_group":"1896","rate":"0.0270"},{"prize_group":"1897","rate":"0.0265"},{"prize_group":"1898","rate":"0.0260"},{"prize_group":"1899","rate":"0.0255"},{"prize_group":"1900","rate":"0.0250"},{"prize_group":"1901","rate":"0.0245"},{"prize_group":"1902","rate":"0.0240"},{"prize_group":"1903","rate":"0.0235"},{"prize_group":"1904","rate":"0.0230"},{"prize_group":"1905","rate":"0.0225"},{"prize_group":"1906","rate":"0.0220"},{"prize_group":"1907","rate":"0.0215"},{"prize_group":"1908","rate":"0.0210"},{"prize_group":"1909","rate":"0.0205"},{"prize_group":"1910","rate":"0.0200"},{"prize_group":"1911","rate":"0.0195"},{"prize_group":"1912","rate":"0.0190"},{"prize_group":"1913","rate":"0.0185"},{"prize_group":"1914","rate":"0.0180"},{"prize_group":"1915","rate":"0.0175"},{"prize_group":"1916","rate":"0.0170"},{"prize_group":"1917","rate":"0.0165"},{"prize_group":"1918","rate":"0.0160"},{"prize_group":"1919","rate":"0.0155"},{"prize_group":"1920","rate":"0.0150"},{"prize_group":"1921","rate":"0.0145"},{"prize_group":"1922","rate":"0.0140"},{"prize_group":"1923","rate":"0.0135"},{"prize_group":"1924","rate":"0.0130"},{"prize_group":"1925","rate":"0.0125"},{"prize_group":"1926","rate":"0.0120"},{"prize_group":"1927","rate":"0.0115"},{"prize_group":"1928","rate":"0.0110"},{"prize_group":"1929","rate":"0.0105"},{"prize_group":"1930","rate":"0.0100"},{"prize_group":"1931","rate":"0.0095"},{"prize_group":"1932","rate":"0.0090"},{"prize_group":"1933","rate":"0.0085"},{"prize_group":"1934","rate":"0.0080"},{"prize_group":"1935","rate":"0.0075"},{"prize_group":"1936","rate":"0.0070"},{"prize_group":"1937","rate":"0.0065"},{"prize_group":"1938","rate":"0.0060"},{"prize_group":"1939","rate":"0.0055"},{"prize_group":"1940","rate":"0.0050"},{"prize_group":"1941","rate":"0.0045"},{"prize_group":"1942","rate":"0.0040"},{"prize_group":"1943","rate":"0.0035"},{"prize_group":"1944","rate":"0.0030"},{"prize_group":"1945","rate":"0.0025"},{"prize_group":"1946","rate":"0.0020"},{"prize_group":"1947","rate":"0.0015"},{"prize_group":"1948","rate":"0.0010"},{"prize_group":"1949","rate":"0.0005"},{"prize_group":"1950","rate":"0.0000"}]
         * currentTime : 1553066964
         * availableCoefficients : {"1.000":"2元","0.500":"1元","0.100":"2角","0.050":"1角","0.010":"2分","0.001":"2厘"}
         * defaultMultiple : 1
         * defaultCoefficient : 0.500
         * prizeLimit : 300000
         * maxPrizeGroup : 1950
         * betSubmitCompress : 0
         * traceMaxTimes : 5
         * gameNumbers : [{"number":"20190320022","time":"2019-03-20 15:48:00"},{"number":"20190320023","time":"2019-03-20 16:08:00"},{"number":"20190320024","time":"2019-03-20 16:28:00"},{"number":"20190320025","time":"2019-03-20 16:48:00"},{"number":"20190320026","time":"2019-03-20 17:08:00"}]
         * currentNumber : 20190320022
         * currentNumberTime : 1553068080
         * issueHistory : {"issues":[{"issue":"20190320021","wn_number":"","offical_time":1553067000},{"issue":"20190320020","wn_number":"5,6,6","offical_time":1553065800},{"issue":"20190320019","wn_number":"1,2,4","offical_time":1553064600},{"issue":"20190320018","wn_number":"1,3,4","offical_time":1553063400},{"issue":"20190320017","wn_number":"2,3,3","offical_time":1553062200}],"last_number":{"issue":"20190320020","wn_number":"5,6,6","offical_time":1553065800},"current_issue":"20190320022"}
         */

        private int gameId;
        private int gameSeriesId;
        private String gameNameEn;
        private String gameNameCn;
        private int defaultMethodId;
        private int currentTime;
        private AvailableCoefficientsBean availableCoefficients;
        private int defaultMultiple;
        private String defaultCoefficient;
        private String prizeLimit;
        private String maxPrizeGroup;
        private String betSubmitCompress;
        private int traceMaxTimes;
        private String currentNumber;
        private int currentNumberTime;
        private IssueHistoryBean issueHistory;
        private List<WayGroupsBean> wayGroups;
        private List<OptionalPrizesBean> optionalPrizes;
        private List<GameNumbersBean> gameNumbers;

        public int getGameId() {
            return gameId;
        }

        public void setGameId(int gameId) {
            this.gameId = gameId;
        }

        public int getGameSeriesId() {
            return gameSeriesId;
        }

        public void setGameSeriesId(int gameSeriesId) {
            this.gameSeriesId = gameSeriesId;
        }

        public String getGameNameEn() {
            return gameNameEn;
        }

        public void setGameNameEn(String gameNameEn) {
            this.gameNameEn = gameNameEn;
        }

        public String getGameNameCn() {
            return gameNameCn;
        }

        public void setGameNameCn(String gameNameCn) {
            this.gameNameCn = gameNameCn;
        }

        public int getDefaultMethodId() {
            return defaultMethodId;
        }

        public void setDefaultMethodId(int defaultMethodId) {
            this.defaultMethodId = defaultMethodId;
        }

        public int getCurrentTime() {
            return currentTime;
        }

        public void setCurrentTime(int currentTime) {
            this.currentTime = currentTime;
        }

        public AvailableCoefficientsBean getAvailableCoefficients() {
            return availableCoefficients;
        }

        public void setAvailableCoefficients(AvailableCoefficientsBean availableCoefficients) {
            this.availableCoefficients = availableCoefficients;
        }

        public int getDefaultMultiple() {
            return defaultMultiple;
        }

        public void setDefaultMultiple(int defaultMultiple) {
            this.defaultMultiple = defaultMultiple;
        }

        public String getDefaultCoefficient() {
            return defaultCoefficient;
        }

        public void setDefaultCoefficient(String defaultCoefficient) {
            this.defaultCoefficient = defaultCoefficient;
        }

        public String getPrizeLimit() {
            return prizeLimit;
        }

        public void setPrizeLimit(String prizeLimit) {
            this.prizeLimit = prizeLimit;
        }

        public String getMaxPrizeGroup() {
            return maxPrizeGroup;
        }

        public void setMaxPrizeGroup(String maxPrizeGroup) {
            this.maxPrizeGroup = maxPrizeGroup;
        }

        public String getBetSubmitCompress() {
            return betSubmitCompress;
        }

        public void setBetSubmitCompress(String betSubmitCompress) {
            this.betSubmitCompress = betSubmitCompress;
        }

        public int getTraceMaxTimes() {
            return traceMaxTimes;
        }

        public void setTraceMaxTimes(int traceMaxTimes) {
            this.traceMaxTimes = traceMaxTimes;
        }

        public String getCurrentNumber() {
            return currentNumber;
        }

        public void setCurrentNumber(String currentNumber) {
            this.currentNumber = currentNumber;
        }

        public int getCurrentNumberTime() {
            return currentNumberTime;
        }

        public void setCurrentNumberTime(int currentNumberTime) {
            this.currentNumberTime = currentNumberTime;
        }

        public IssueHistoryBean getIssueHistory() {
            return issueHistory;
        }

        public void setIssueHistory(IssueHistoryBean issueHistory) {
            this.issueHistory = issueHistory;
        }

        public List<WayGroupsBean> getWayGroups() {
            return wayGroups;
        }

        public void setWayGroups(List<WayGroupsBean> wayGroups) {
            this.wayGroups = wayGroups;
        }

        public List<OptionalPrizesBean> getOptionalPrizes() {
            return optionalPrizes;
        }

        public void setOptionalPrizes(List<OptionalPrizesBean> optionalPrizes) {
            this.optionalPrizes = optionalPrizes;
        }

        public List<GameNumbersBean> getGameNumbers() {
            return gameNumbers;
        }

        public void setGameNumbers(List<GameNumbersBean> gameNumbers) {
            this.gameNumbers = gameNumbers;
        }

        public static class AvailableCoefficientsBean {
            @SerializedName("1.000")
            private String _$_10009; // FIXME check this code
            @SerializedName("0.500")
            private String _$_0500178; // FIXME check this code
            @SerializedName("0.100")
            private String _$_010036; // FIXME check this code
            @SerializedName("0.050")
            private String _$_0050125; // FIXME check this code
            @SerializedName("0.010")
            private String _$_0010312; // FIXME check this code
            @SerializedName("0.001")
            private String _$_000158; // FIXME check this code

            public String get_$_10009() {
                return _$_10009;
            }

            public void set_$_10009(String _$_10009) {
                this._$_10009 = _$_10009;
            }

            public String get_$_0500178() {
                return _$_0500178;
            }

            public void set_$_0500178(String _$_0500178) {
                this._$_0500178 = _$_0500178;
            }

            public String get_$_010036() {
                return _$_010036;
            }

            public void set_$_010036(String _$_010036) {
                this._$_010036 = _$_010036;
            }

            public String get_$_0050125() {
                return _$_0050125;
            }

            public void set_$_0050125(String _$_0050125) {
                this._$_0050125 = _$_0050125;
            }

            public String get_$_0010312() {
                return _$_0010312;
            }

            public void set_$_0010312(String _$_0010312) {
                this._$_0010312 = _$_0010312;
            }

            public String get_$_000158() {
                return _$_000158;
            }

            public void set_$_000158(String _$_000158) {
                this._$_000158 = _$_000158;
            }
        }

        public static class IssueHistoryBean {
            /**
             * issues : [{"issue":"20190320021","wn_number":"","offical_time":1553067000},{"issue":"20190320020","wn_number":"5,6,6","offical_time":1553065800},{"issue":"20190320019","wn_number":"1,2,4","offical_time":1553064600},{"issue":"20190320018","wn_number":"1,3,4","offical_time":1553063400},{"issue":"20190320017","wn_number":"2,3,3","offical_time":1553062200}]
             * last_number : {"issue":"20190320020","wn_number":"5,6,6","offical_time":1553065800}
             * current_issue : 20190320022
             */

            private LastNumberBean last_number;
            private String current_issue;
            private List<IssuesBean> issues;

            public LastNumberBean getLast_number() {
                return last_number;
            }

            public void setLast_number(LastNumberBean last_number) {
                this.last_number = last_number;
            }

            public String getCurrent_issue() {
                return current_issue;
            }

            public void setCurrent_issue(String current_issue) {
                this.current_issue = current_issue;
            }

            public List<IssuesBean> getIssues() {
                return issues;
            }

            public void setIssues(List<IssuesBean> issues) {
                this.issues = issues;
            }

            public static class LastNumberBean {
                /**
                 * issue : 20190320020
                 * wn_number : 5,6,6
                 * offical_time : 1553065800
                 */

                private String issue;
                private String wn_number;
                private String offical_time;

                public String getIssue() {
                    return issue;
                }

                public void setIssue(String issue) {
                    this.issue = issue;
                }

                public String getWn_number() {
                    return wn_number;
                }

                public void setWn_number(String wn_number) {
                    this.wn_number = wn_number;
                }

                public String getOffical_time() {
                    return offical_time;
                }

                public void setOffical_time(String offical_time) {
                    this.offical_time = offical_time;
                }
            }

            public static class IssuesBean {
                /**
                 * issue : 20190320021
                 * wn_number :
                 * offical_time : 1553067000
                 */

                private String issue;
                private String wn_number;
                private String offical_time;

                public String getIssue() {
                    return issue;
                }

                public void setIssue(String issue) {
                    this.issue = issue;
                }

                public String getWn_number() {
                    return wn_number;
                }

                public void setWn_number(String wn_number) {
                    this.wn_number = wn_number;
                }

                public String getOffical_time() {
                    return offical_time;
                }

                public void setOffical_time(String offical_time) {
                    this.offical_time = offical_time;
                }
            }
        }

        public static class WayGroupsBean {
            /**
             * id : 65
             * pid : 0
             * name_cn : 和值
             * name_en : hz
             * children : [{"id":75,"pid":65,"name_cn":"和值","name_en":"hz","children":[{"id":157,"pid":75,"series_way_id":157,"name_cn":"和值","name_en":"fs","price":2,"bet_note":"至少选择1个和值（3个号码之和）进行投注","bonus_note":"所选和值与开奖的3个号码的和值相同即中奖。和值10,11为八等奖，和值9,12为七等奖，和值8,13为六等奖，和值7,14为五等奖，和值6,15为四等奖，和值5,16为三等奖，和值4,17为二等奖，和值3,18为一等级。每个奖级(2块钱投注)对应的奖金请到 个人中心-我的奖金组-查看全部-点击对应的彩种 即可弹窗看到。","basic_methods":"61","prize":"408.31","max_multiple":734}]}]
             */

            private int id;
            private int pid;
            private String name_cn;
            private String name_en;
            private List<ChildrenBeanX> children;

            public int getId() {
                return id;
            }

            public void setId(int id) {
                this.id = id;
            }

            public int getPid() {
                return pid;
            }

            public void setPid(int pid) {
                this.pid = pid;
            }

            public String getName_cn() {
                return name_cn;
            }

            public void setName_cn(String name_cn) {
                this.name_cn = name_cn;
            }

            public String getName_en() {
                return name_en;
            }

            public void setName_en(String name_en) {
                this.name_en = name_en;
            }

            public List<ChildrenBeanX> getChildren() {
                return children;
            }

            public void setChildren(List<ChildrenBeanX> children) {
                this.children = children;
            }

            public static class ChildrenBeanX {
                /**
                 * id : 75
                 * pid : 65
                 * name_cn : 和值
                 * name_en : hz
                 * children : [{"id":157,"pid":75,"series_way_id":157,"name_cn":"和值","name_en":"fs","price":2,"bet_note":"至少选择1个和值（3个号码之和）进行投注","bonus_note":"所选和值与开奖的3个号码的和值相同即中奖。和值10,11为八等奖，和值9,12为七等奖，和值8,13为六等奖，和值7,14为五等奖，和值6,15为四等奖，和值5,16为三等奖，和值4,17为二等奖，和值3,18为一等级。每个奖级(2块钱投注)对应的奖金请到 个人中心-我的奖金组-查看全部-点击对应的彩种 即可弹窗看到。","basic_methods":"61","prize":"408.31","max_multiple":734}]
                 */

                private int id;
                private int pid;
                private String name_cn;
                private String name_en;
                private List<ChildrenBean> children;

                public int getId() {
                    return id;
                }

                public void setId(int id) {
                    this.id = id;
                }

                public int getPid() {
                    return pid;
                }

                public void setPid(int pid) {
                    this.pid = pid;
                }

                public String getName_cn() {
                    return name_cn;
                }

                public void setName_cn(String name_cn) {
                    this.name_cn = name_cn;
                }

                public String getName_en() {
                    return name_en;
                }

                public void setName_en(String name_en) {
                    this.name_en = name_en;
                }

                public List<ChildrenBean> getChildren() {
                    return children;
                }

                public void setChildren(List<ChildrenBean> children) {
                    this.children = children;
                }

                public static class ChildrenBean {
                    /**
                     * id : 157
                     * pid : 75
                     * series_way_id : 157
                     * name_cn : 和值
                     * name_en : fs
                     * price : 2
                     * bet_note : 至少选择1个和值（3个号码之和）进行投注
                     * bonus_note : 所选和值与开奖的3个号码的和值相同即中奖。和值10,11为八等奖，和值9,12为七等奖，和值8,13为六等奖，和值7,14为五等奖，和值6,15为四等奖，和值5,16为三等奖，和值4,17为二等奖，和值3,18为一等级。每个奖级(2块钱投注)对应的奖金请到 个人中心-我的奖金组-查看全部-点击对应的彩种 即可弹窗看到。
                     * basic_methods : 61
                     * prize : 408.31
                     * max_multiple : 734
                     */

                    private int id;
                    private int pid;
                    private int series_way_id;
                    private String name_cn;
                    private String name_en;
                    private int price;
                    private String bet_note;
                    private String bonus_note;
                    private String basic_methods;
                    private String prize;
                    private int max_multiple;

                    public int getId() {
                        return id;
                    }

                    public void setId(int id) {
                        this.id = id;
                    }

                    public int getPid() {
                        return pid;
                    }

                    public void setPid(int pid) {
                        this.pid = pid;
                    }

                    public int getSeries_way_id() {
                        return series_way_id;
                    }

                    public void setSeries_way_id(int series_way_id) {
                        this.series_way_id = series_way_id;
                    }

                    public String getName_cn() {
                        return name_cn;
                    }

                    public void setName_cn(String name_cn) {
                        this.name_cn = name_cn;
                    }

                    public String getName_en() {
                        return name_en;
                    }

                    public void setName_en(String name_en) {
                        this.name_en = name_en;
                    }

                    public int getPrice() {
                        return price;
                    }

                    public void setPrice(int price) {
                        this.price = price;
                    }

                    public String getBet_note() {
                        return bet_note;
                    }

                    public void setBet_note(String bet_note) {
                        this.bet_note = bet_note;
                    }

                    public String getBonus_note() {
                        return bonus_note;
                    }

                    public void setBonus_note(String bonus_note) {
                        this.bonus_note = bonus_note;
                    }

                    public String getBasic_methods() {
                        return basic_methods;
                    }

                    public void setBasic_methods(String basic_methods) {
                        this.basic_methods = basic_methods;
                    }

                    public String getPrize() {
                        return prize;
                    }

                    public void setPrize(String prize) {
                        this.prize = prize;
                    }

                    public int getMax_multiple() {
                        return max_multiple;
                    }

                    public void setMax_multiple(int max_multiple) {
                        this.max_multiple = max_multiple;
                    }
                }
            }
        }

        public static class OptionalPrizesBean {
            /**
             * prize_group : 1502
             * rate : 0.2240
             */

            private String prize_group;
            private String rate;

            public String getPrize_group() {
                return prize_group;
            }

            public void setPrize_group(String prize_group) {
                this.prize_group = prize_group;
            }

            public String getRate() {
                return rate;
            }

            public void setRate(String rate) {
                this.rate = rate;
            }
        }

        public static class GameNumbersBean {
            /**
             * number : 20190320022
             * time : 2019-03-20 15:48:00
             */

            private String number;
            private String time;

            public String getNumber() {
                return number;
            }

            public void setNumber(String number) {
                this.number = number;
            }

            public String getTime() {
                return time;
            }

            public void setTime(String time) {
                this.time = time;
            }
        }
    }
}
