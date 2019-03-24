package com.cfcp.a01.data;

import com.cfcp.a01.common.utils.GameShipHelper;
import com.contrarywind.interfaces.IPickerViewData;

import java.util.List;

public class LowerSetDataResult {


    /**
     * iUserId : 5526
     * aUserPrizeSet : {"username":"daniel05","nickname":"丹尼尔","name":"","is_agent":0,"is_agent_formatted":"玩家","available_formatted":"0.00","bet_max_prize":"300000"}
     * sCurrentUserPrizeGroup :
     * iMinPrizeGroup : 1502
     * iMaxPrizeGroup : 1950
     * fRebateLevel : 24.9
     * sAllPossiblePrizeGroups : [{"id":525,"type":1,"name":"1502","classic_prize":1502,"water":0.249},{"id":526,"type":1,"name":"1503","classic_prize":1503,"water":0.2485},{"id":527,"type":1,"name":"1504","classic_prize":1504,"water":0.248},{"id":528,"type":1,"name":"1505","classic_prize":1505,"water":0.2475},{"id":529,"type":1,"name":"1506","classic_prize":1506,"water":0.247},{"id":530,"type":1,"name":"1507","classic_prize":1507,"water":0.2465},{"id":531,"type":1,"name":"1508","classic_prize":1508,"water":0.246},{"id":532,"type":1,"name":"1509","classic_prize":1509,"water":0.2455},{"id":533,"type":1,"name":"1510","classic_prize":1510,"water":0.245},{"id":534,"type":1,"name":"1511","classic_prize":1511,"water":0.2445},{"id":535,"type":1,"name":"1512","classic_prize":1512,"water":0.244},{"id":536,"type":1,"name":"1513","classic_prize":1513,"water":0.2435},{"id":537,"type":1,"name":"1514","classic_prize":1514,"water":0.243},{"id":538,"type":1,"name":"1515","classic_prize":1515,"water":0.2425},{"id":539,"type":1,"name":"1516","classic_prize":1516,"water":0.242},{"id":540,"type":1,"name":"1517","classic_prize":1517,"water":0.2415},{"id":541,"type":1,"name":"1518","classic_prize":1518,"water":0.241},{"id":542,"type":1,"name":"1519","classic_prize":1519,"water":0.2405},{"id":543,"type":1,"name":"1520","classic_prize":1520,"water":0.24},{"id":544,"type":1,"name":"1521","classic_prize":1521,"water":0.2395},{"id":545,"type":1,"name":"1522","classic_prize":1522,"water":0.239},{"id":546,"type":1,"name":"1523","classic_prize":1523,"water":0.2385},{"id":547,"type":1,"name":"1524","classic_prize":1524,"water":0.238},{"id":548,"type":1,"name":"1525","classic_prize":1525,"water":0.2375},{"id":549,"type":1,"name":"1526","classic_prize":1526,"water":0.237},{"id":550,"type":1,"name":"1527","classic_prize":1527,"water":0.2365},{"id":551,"type":1,"name":"1528","classic_prize":1528,"water":0.236},{"id":552,"type":1,"name":"1529","classic_prize":1529,"water":0.2355},{"id":553,"type":1,"name":"1530","classic_prize":1530,"water":0.235},{"id":554,"type":1,"name":"1531","classic_prize":1531,"water":0.2345},{"id":555,"type":1,"name":"1532","classic_prize":1532,"water":0.234},{"id":556,"type":1,"name":"1533","classic_prize":1533,"water":0.2335},{"id":557,"type":1,"name":"1534","classic_prize":1534,"water":0.233},{"id":558,"type":1,"name":"1535","classic_prize":1535,"water":0.2325},{"id":559,"type":1,"name":"1536","classic_prize":1536,"water":0.232},{"id":560,"type":1,"name":"1537","classic_prize":1537,"water":0.2315},{"id":561,"type":1,"name":"1538","classic_prize":1538,"water":0.231},{"id":562,"type":1,"name":"1539","classic_prize":1539,"water":0.2305},{"id":563,"type":1,"name":"1540","classic_prize":1540,"water":0.23},{"id":564,"type":1,"name":"1541","classic_prize":1541,"water":0.2295},{"id":565,"type":1,"name":"1542","classic_prize":1542,"water":0.229},{"id":566,"type":1,"name":"1543","classic_prize":1543,"water":0.2285},{"id":567,"type":1,"name":"1544","classic_prize":1544,"water":0.228},{"id":568,"type":1,"name":"1545","classic_prize":1545,"water":0.2275},{"id":569,"type":1,"name":"1546","classic_prize":1546,"water":0.227},{"id":570,"type":1,"name":"1547","classic_prize":1547,"water":0.2265},{"id":571,"type":1,"name":"1548","classic_prize":1548,"water":0.226},{"id":572,"type":1,"name":"1549","classic_prize":1549,"water":0.2255},{"id":573,"type":1,"name":"1550","classic_prize":1550,"water":0.225},{"id":574,"type":1,"name":"1551","classic_prize":1551,"water":0.2245},{"id":575,"type":1,"name":"1552","classic_prize":1552,"water":0.224},{"id":576,"type":1,"name":"1553","classic_prize":1553,"water":0.2235},{"id":577,"type":1,"name":"1554","classic_prize":1554,"water":0.223},{"id":578,"type":1,"name":"1555","classic_prize":1555,"water":0.2225},{"id":579,"type":1,"name":"1556","classic_prize":1556,"water":0.222},{"id":580,"type":1,"name":"1557","classic_prize":1557,"water":0.2215},{"id":581,"type":1,"name":"1558","classic_prize":1558,"water":0.221},{"id":582,"type":1,"name":"1559","classic_prize":1559,"water":0.2205},{"id":583,"type":1,"name":"1560","classic_prize":1560,"water":0.22},{"id":584,"type":1,"name":"1561","classic_prize":1561,"water":0.2195},{"id":585,"type":1,"name":"1562","classic_prize":1562,"water":0.219},{"id":586,"type":1,"name":"1563","classic_prize":1563,"water":0.2185},{"id":587,"type":1,"name":"1564","classic_prize":1564,"water":0.218},{"id":588,"type":1,"name":"1565","classic_prize":1565,"water":0.2175},{"id":589,"type":1,"name":"1566","classic_prize":1566,"water":0.217},{"id":590,"type":1,"name":"1567","classic_prize":1567,"water":0.2165},{"id":591,"type":1,"name":"1568","classic_prize":1568,"water":0.216},{"id":592,"type":1,"name":"1569","classic_prize":1569,"water":0.2155},{"id":593,"type":1,"name":"1570","classic_prize":1570,"water":0.215},{"id":594,"type":1,"name":"1571","classic_prize":1571,"water":0.2145},{"id":595,"type":1,"name":"1572","classic_prize":1572,"water":0.214},{"id":596,"type":1,"name":"1573","classic_prize":1573,"water":0.2135},{"id":597,"type":1,"name":"1574","classic_prize":1574,"water":0.213},{"id":598,"type":1,"name":"1575","classic_prize":1575,"water":0.2125},{"id":599,"type":1,"name":"1576","classic_prize":1576,"water":0.212},{"id":600,"type":1,"name":"1577","classic_prize":1577,"water":0.2115},{"id":601,"type":1,"name":"1578","classic_prize":1578,"water":0.211},{"id":602,"type":1,"name":"1579","classic_prize":1579,"water":0.2105},{"id":603,"type":1,"name":"1580","classic_prize":1580,"water":0.21},{"id":604,"type":1,"name":"1581","classic_prize":1581,"water":0.2095},{"id":605,"type":1,"name":"1582","classic_prize":1582,"water":0.209},{"id":606,"type":1,"name":"1583","classic_prize":1583,"water":0.2085},{"id":607,"type":1,"name":"1584","classic_prize":1584,"water":0.208},{"id":608,"type":1,"name":"1585","classic_prize":1585,"water":0.2075},{"id":609,"type":1,"name":"1586","classic_prize":1586,"water":0.207},{"id":610,"type":1,"name":"1587","classic_prize":1587,"water":0.2065},{"id":611,"type":1,"name":"1588","classic_prize":1588,"water":0.206},{"id":612,"type":1,"name":"1589","classic_prize":1589,"water":0.2055},{"id":613,"type":1,"name":"1590","classic_prize":1590,"water":0.205},{"id":614,"type":1,"name":"1591","classic_prize":1591,"water":0.2045},{"id":615,"type":1,"name":"1592","classic_prize":1592,"water":0.204},{"id":616,"type":1,"name":"1593","classic_prize":1593,"water":0.2035},{"id":617,"type":1,"name":"1594","classic_prize":1594,"water":0.203},{"id":618,"type":1,"name":"1595","classic_prize":1595,"water":0.2025},{"id":619,"type":1,"name":"1596","classic_prize":1596,"water":0.202},{"id":620,"type":1,"name":"1597","classic_prize":1597,"water":0.2015},{"id":621,"type":1,"name":"1598","classic_prize":1598,"water":0.201},{"id":622,"type":1,"name":"1599","classic_prize":1599,"water":0.2005},{"id":623,"type":1,"name":"1600","classic_prize":1600,"water":0.2},{"id":624,"type":1,"name":"1601","classic_prize":1601,"water":0.1995},{"id":625,"type":1,"name":"1602","classic_prize":1602,"water":0.199},{"id":626,"type":1,"name":"1603","classic_prize":1603,"water":0.1985},{"id":627,"type":1,"name":"1604","classic_prize":1604,"water":0.198},{"id":628,"type":1,"name":"1605","classic_prize":1605,"water":0.1975},{"id":629,"type":1,"name":"1606","classic_prize":1606,"water":0.197},{"id":630,"type":1,"name":"1607","classic_prize":1607,"water":0.1965},{"id":631,"type":1,"name":"1608","classic_prize":1608,"water":0.196},{"id":632,"type":1,"name":"1609","classic_prize":1609,"water":0.1955},{"id":633,"type":1,"name":"1610","classic_prize":1610,"water":0.195},{"id":634,"type":1,"name":"1611","classic_prize":1611,"water":0.1945},{"id":635,"type":1,"name":"1612","classic_prize":1612,"water":0.194},{"id":636,"type":1,"name":"1613","classic_prize":1613,"water":0.1935},{"id":637,"type":1,"name":"1614","classic_prize":1614,"water":0.193},{"id":638,"type":1,"name":"1615","classic_prize":1615,"water":0.1925},{"id":639,"type":1,"name":"1616","classic_prize":1616,"water":0.192},{"id":640,"type":1,"name":"1617","classic_prize":1617,"water":0.1915},{"id":641,"type":1,"name":"1618","classic_prize":1618,"water":0.191},{"id":642,"type":1,"name":"1619","classic_prize":1619,"water":0.1905},{"id":643,"type":1,"name":"1620","classic_prize":1620,"water":0.19},{"id":644,"type":1,"name":"1621","classic_prize":1621,"water":0.1895},{"id":645,"type":1,"name":"1622","classic_prize":1622,"water":0.189},{"id":646,"type":1,"name":"1623","classic_prize":1623,"water":0.1885},{"id":647,"type":1,"name":"1624","classic_prize":1624,"water":0.188},{"id":648,"type":1,"name":"1625","classic_prize":1625,"water":0.1875},{"id":649,"type":1,"name":"1626","classic_prize":1626,"water":0.187},{"id":650,"type":1,"name":"1627","classic_prize":1627,"water":0.1865},{"id":651,"type":1,"name":"1628","classic_prize":1628,"water":0.186},{"id":652,"type":1,"name":"1629","classic_prize":1629,"water":0.1855},{"id":653,"type":1,"name":"1630","classic_prize":1630,"water":0.185},{"id":654,"type":1,"name":"1631","classic_prize":1631,"water":0.1845},{"id":655,"type":1,"name":"1632","classic_prize":1632,"water":0.184},{"id":656,"type":1,"name":"1633","classic_prize":1633,"water":0.1835},{"id":657,"type":1,"name":"1634","classic_prize":1634,"water":0.183},{"id":658,"type":1,"name":"1635","classic_prize":1635,"water":0.1825},{"id":659,"type":1,"name":"1636","classic_prize":1636,"water":0.182},{"id":660,"type":1,"name":"1637","classic_prize":1637,"water":0.1815},{"id":661,"type":1,"name":"1638","classic_prize":1638,"water":0.181},{"id":662,"type":1,"name":"1639","classic_prize":1639,"water":0.1805},{"id":663,"type":1,"name":"1640","classic_prize":1640,"water":0.18},{"id":664,"type":1,"name":"1641","classic_prize":1641,"water":0.1795},{"id":665,"type":1,"name":"1642","classic_prize":1642,"water":0.179},{"id":666,"type":1,"name":"1643","classic_prize":1643,"water":0.1785},{"id":667,"type":1,"name":"1644","classic_prize":1644,"water":0.178},{"id":668,"type":1,"name":"1645","classic_prize":1645,"water":0.1775},{"id":669,"type":1,"name":"1646","classic_prize":1646,"water":0.177},{"id":670,"type":1,"name":"1647","classic_prize":1647,"water":0.1765},{"id":671,"type":1,"name":"1648","classic_prize":1648,"water":0.176},{"id":672,"type":1,"name":"1649","classic_prize":1649,"water":0.1755},{"id":673,"type":1,"name":"1650","classic_prize":1650,"water":0.175},{"id":674,"type":1,"name":"1651","classic_prize":1651,"water":0.1745},{"id":675,"type":1,"name":"1652","classic_prize":1652,"water":0.174},{"id":676,"type":1,"name":"1653","classic_prize":1653,"water":0.1735},{"id":677,"type":1,"name":"1654","classic_prize":1654,"water":0.173},{"id":678,"type":1,"name":"1655","classic_prize":1655,"water":0.1725},{"id":679,"type":1,"name":"1656","classic_prize":1656,"water":0.172},{"id":680,"type":1,"name":"1657","classic_prize":1657,"water":0.1715},{"id":681,"type":1,"name":"1658","classic_prize":1658,"water":0.171},{"id":682,"type":1,"name":"1659","classic_prize":1659,"water":0.1705},{"id":683,"type":1,"name":"1660","classic_prize":1660,"water":0.17},{"id":684,"type":1,"name":"1661","classic_prize":1661,"water":0.1695},{"id":685,"type":1,"name":"1662","classic_prize":1662,"water":0.169},{"id":686,"type":1,"name":"1663","classic_prize":1663,"water":0.1685},{"id":687,"type":1,"name":"1664","classic_prize":1664,"water":0.168},{"id":688,"type":1,"name":"1665","classic_prize":1665,"water":0.1675},{"id":689,"type":1,"name":"1666","classic_prize":1666,"water":0.167},{"id":690,"type":1,"name":"1667","classic_prize":1667,"water":0.1665},{"id":691,"type":1,"name":"1668","classic_prize":1668,"water":0.166},{"id":692,"type":1,"name":"1669","classic_prize":1669,"water":0.1655},{"id":693,"type":1,"name":"1670","classic_prize":1670,"water":0.165},{"id":694,"type":1,"name":"1671","classic_prize":1671,"water":0.1645},{"id":695,"type":1,"name":"1672","classic_prize":1672,"water":0.164},{"id":696,"type":1,"name":"1673","classic_prize":1673,"water":0.1635},{"id":697,"type":1,"name":"1674","classic_prize":1674,"water":0.163},{"id":698,"type":1,"name":"1675","classic_prize":1675,"water":0.1625},{"id":699,"type":1,"name":"1676","classic_prize":1676,"water":0.162},{"id":700,"type":1,"name":"1677","classic_prize":1677,"water":0.1615},{"id":701,"type":1,"name":"1678","classic_prize":1678,"water":0.161},{"id":702,"type":1,"name":"1679","classic_prize":1679,"water":0.1605},{"id":703,"type":1,"name":"1680","classic_prize":1680,"water":0.16},{"id":704,"type":1,"name":"1681","classic_prize":1681,"water":0.1595},{"id":705,"type":1,"name":"1682","classic_prize":1682,"water":0.159},{"id":706,"type":1,"name":"1683","classic_prize":1683,"water":0.1585},{"id":707,"type":1,"name":"1684","classic_prize":1684,"water":0.158},{"id":708,"type":1,"name":"1685","classic_prize":1685,"water":0.1575},{"id":709,"type":1,"name":"1686","classic_prize":1686,"water":0.157},{"id":710,"type":1,"name":"1687","classic_prize":1687,"water":0.1565},{"id":711,"type":1,"name":"1688","classic_prize":1688,"water":0.156},{"id":712,"type":1,"name":"1689","classic_prize":1689,"water":0.1555},{"id":713,"type":1,"name":"1690","classic_prize":1690,"water":0.155},{"id":714,"type":1,"name":"1691","classic_prize":1691,"water":0.1545},{"id":715,"type":1,"name":"1692","classic_prize":1692,"water":0.154},{"id":716,"type":1,"name":"1693","classic_prize":1693,"water":0.1535},{"id":717,"type":1,"name":"1694","classic_prize":1694,"water":0.153},{"id":718,"type":1,"name":"1695","classic_prize":1695,"water":0.1525},{"id":719,"type":1,"name":"1696","classic_prize":1696,"water":0.152},{"id":720,"type":1,"name":"1697","classic_prize":1697,"water":0.1515},{"id":721,"type":1,"name":"1698","classic_prize":1698,"water":0.151},{"id":722,"type":1,"name":"1699","classic_prize":1699,"water":0.1505},{"id":1,"type":1,"name":"1700","classic_prize":1700,"water":0.15},{"id":2,"type":1,"name":"1701","classic_prize":1701,"water":0.1495},{"id":3,"type":1,"name":"1702","classic_prize":1702,"water":0.149},{"id":4,"type":1,"name":"1703","classic_prize":1703,"water":0.1485},{"id":5,"type":1,"name":"1704","classic_prize":1704,"water":0.148},{"id":6,"type":1,"name":"1705","classic_prize":1705,"water":0.1475},{"id":7,"type":1,"name":"1706","classic_prize":1706,"water":0.147},{"id":8,"type":1,"name":"1707","classic_prize":1707,"water":0.1465},{"id":9,"type":1,"name":"1708","classic_prize":1708,"water":0.146},{"id":10,"type":1,"name":"1709","classic_prize":1709,"water":0.1455},{"id":11,"type":1,"name":"1710","classic_prize":1710,"water":0.145},{"id":12,"type":1,"name":"1711","classic_prize":1711,"water":0.1445},{"id":13,"type":1,"name":"1712","classic_prize":1712,"water":0.144},{"id":14,"type":1,"name":"1713","classic_prize":1713,"water":0.1435},{"id":15,"type":1,"name":"1714","classic_prize":1714,"water":0.143},{"id":16,"type":1,"name":"1715","classic_prize":1715,"water":0.1425},{"id":17,"type":1,"name":"1716","classic_prize":1716,"water":0.142},{"id":18,"type":1,"name":"1717","classic_prize":1717,"water":0.1415},{"id":19,"type":1,"name":"1718","classic_prize":1718,"water":0.141},{"id":20,"type":1,"name":"1719","classic_prize":1719,"water":0.1405},{"id":21,"type":1,"name":"1720","classic_prize":1720,"water":0.14},{"id":22,"type":1,"name":"1721","classic_prize":1721,"water":0.1395},{"id":23,"type":1,"name":"1722","classic_prize":1722,"water":0.139},{"id":24,"type":1,"name":"1723","classic_prize":1723,"water":0.1385},{"id":25,"type":1,"name":"1724","classic_prize":1724,"water":0.138},{"id":26,"type":1,"name":"1725","classic_prize":1725,"water":0.1375},{"id":27,"type":1,"name":"1726","classic_prize":1726,"water":0.137},{"id":28,"type":1,"name":"1727","classic_prize":1727,"water":0.1365},{"id":29,"type":1,"name":"1728","classic_prize":1728,"water":0.136},{"id":30,"type":1,"name":"1729","classic_prize":1729,"water":0.1355},{"id":31,"type":1,"name":"1730","classic_prize":1730,"water":0.135},{"id":32,"type":1,"name":"1731","classic_prize":1731,"water":0.1345},{"id":33,"type":1,"name":"1732","classic_prize":1732,"water":0.134},{"id":34,"type":1,"name":"1733","classic_prize":1733,"water":0.1335},{"id":35,"type":1,"name":"1734","classic_prize":1734,"water":0.133},{"id":36,"type":1,"name":"1735","classic_prize":1735,"water":0.1325},{"id":37,"type":1,"name":"1736","classic_prize":1736,"water":0.132},{"id":38,"type":1,"name":"1737","classic_prize":1737,"water":0.1315},{"id":39,"type":1,"name":"1738","classic_prize":1738,"water":0.131},{"id":40,"type":1,"name":"1739","classic_prize":1739,"water":0.1305},{"id":41,"type":1,"name":"1740","classic_prize":1740,"water":0.13},{"id":42,"type":1,"name":"1741","classic_prize":1741,"water":0.1295},{"id":43,"type":1,"name":"1742","classic_prize":1742,"water":0.129},{"id":44,"type":1,"name":"1743","classic_prize":1743,"water":0.1285},{"id":45,"type":1,"name":"1744","classic_prize":1744,"water":0.128},{"id":46,"type":1,"name":"1745","classic_prize":1745,"water":0.1275},{"id":47,"type":1,"name":"1746","classic_prize":1746,"water":0.127},{"id":48,"type":1,"name":"1747","classic_prize":1747,"water":0.1265},{"id":49,"type":1,"name":"1748","classic_prize":1748,"water":0.126},{"id":50,"type":1,"name":"1749","classic_prize":1749,"water":0.1255},{"id":51,"type":1,"name":"1750","classic_prize":1750,"water":0.125},{"id":52,"type":1,"name":"1751","classic_prize":1751,"water":0.1245},{"id":53,"type":1,"name":"1752","classic_prize":1752,"water":0.124},{"id":54,"type":1,"name":"1753","classic_prize":1753,"water":0.1235},{"id":55,"type":1,"name":"1754","classic_prize":1754,"water":0.123},{"id":56,"type":1,"name":"1755","classic_prize":1755,"water":0.1225},{"id":57,"type":1,"name":"1756","classic_prize":1756,"water":0.122},{"id":58,"type":1,"name":"1757","classic_prize":1757,"water":0.1215},{"id":59,"type":1,"name":"1758","classic_prize":1758,"water":0.121},{"id":60,"type":1,"name":"1759","classic_prize":1759,"water":0.1205},{"id":61,"type":1,"name":"1760","classic_prize":1760,"water":0.12},{"id":62,"type":1,"name":"1761","classic_prize":1761,"water":0.1195},{"id":63,"type":1,"name":"1762","classic_prize":1762,"water":0.119},{"id":64,"type":1,"name":"1763","classic_prize":1763,"water":0.1185},{"id":65,"type":1,"name":"1764","classic_prize":1764,"water":0.118},{"id":66,"type":1,"name":"1765","classic_prize":1765,"water":0.1175},{"id":67,"type":1,"name":"1766","classic_prize":1766,"water":0.117},{"id":68,"type":1,"name":"1767","classic_prize":1767,"water":0.1165},{"id":69,"type":1,"name":"1768","classic_prize":1768,"water":0.116},{"id":70,"type":1,"name":"1769","classic_prize":1769,"water":0.1155},{"id":71,"type":1,"name":"1770","classic_prize":1770,"water":0.115},{"id":72,"type":1,"name":"1771","classic_prize":1771,"water":0.1145},{"id":73,"type":1,"name":"1772","classic_prize":1772,"water":0.114},{"id":74,"type":1,"name":"1773","classic_prize":1773,"water":0.1135},{"id":75,"type":1,"name":"1774","classic_prize":1774,"water":0.113},{"id":76,"type":1,"name":"1775","classic_prize":1775,"water":0.1125},{"id":77,"type":1,"name":"1776","classic_prize":1776,"water":0.112},{"id":78,"type":1,"name":"1777","classic_prize":1777,"water":0.1115},{"id":79,"type":1,"name":"1778","classic_prize":1778,"water":0.111},{"id":80,"type":1,"name":"1779","classic_prize":1779,"water":0.1105},{"id":81,"type":1,"name":"1780","classic_prize":1780,"water":0.11},{"id":82,"type":1,"name":"1781","classic_prize":1781,"water":0.1095},{"id":83,"type":1,"name":"1782","classic_prize":1782,"water":0.109},{"id":84,"type":1,"name":"1783","classic_prize":1783,"water":0.1085},{"id":85,"type":1,"name":"1784","classic_prize":1784,"water":0.108},{"id":86,"type":1,"name":"1785","classic_prize":1785,"water":0.1075},{"id":87,"type":1,"name":"1786","classic_prize":1786,"water":0.107},{"id":88,"type":1,"name":"1787","classic_prize":1787,"water":0.1065},{"id":89,"type":1,"name":"1788","classic_prize":1788,"water":0.106},{"id":90,"type":1,"name":"1789","classic_prize":1789,"water":0.1055},{"id":91,"type":1,"name":"1790","classic_prize":1790,"water":0.105},{"id":92,"type":1,"name":"1791","classic_prize":1791,"water":0.1045},{"id":93,"type":1,"name":"1792","classic_prize":1792,"water":0.104},{"id":94,"type":1,"name":"1793","classic_prize":1793,"water":0.1035},{"id":95,"type":1,"name":"1794","classic_prize":1794,"water":0.103},{"id":96,"type":1,"name":"1795","classic_prize":1795,"water":0.1025},{"id":97,"type":1,"name":"1796","classic_prize":1796,"water":0.102},{"id":98,"type":1,"name":"1797","classic_prize":1797,"water":0.1015},{"id":99,"type":1,"name":"1798","classic_prize":1798,"water":0.101},{"id":100,"type":1,"name":"1799","classic_prize":1799,"water":0.1005},{"id":101,"type":1,"name":"1800","classic_prize":1800,"water":0.1},{"id":102,"type":1,"name":"1801","classic_prize":1801,"water":0.0995},{"id":103,"type":1,"name":"1802","classic_prize":1802,"water":0.099},{"id":104,"type":1,"name":"1803","classic_prize":1803,"water":0.0985},{"id":105,"type":1,"name":"1804","classic_prize":1804,"water":0.098},{"id":106,"type":1,"name":"1805","classic_prize":1805,"water":0.0975},{"id":107,"type":1,"name":"1806","classic_prize":1806,"water":0.097},{"id":108,"type":1,"name":"1807","classic_prize":1807,"water":0.0965},{"id":109,"type":1,"name":"1808","classic_prize":1808,"water":0.096},{"id":110,"type":1,"name":"1809","classic_prize":1809,"water":0.0955},{"id":111,"type":1,"name":"1810","classic_prize":1810,"water":0.095},{"id":112,"type":1,"name":"1811","classic_prize":1811,"water":0.0945},{"id":113,"type":1,"name":"1812","classic_prize":1812,"water":0.094},{"id":114,"type":1,"name":"1813","classic_prize":1813,"water":0.0935},{"id":115,"type":1,"name":"1814","classic_prize":1814,"water":0.093},{"id":116,"type":1,"name":"1815","classic_prize":1815,"water":0.0925},{"id":117,"type":1,"name":"1816","classic_prize":1816,"water":0.092},{"id":118,"type":1,"name":"1817","classic_prize":1817,"water":0.0915},{"id":119,"type":1,"name":"1818","classic_prize":1818,"water":0.091},{"id":120,"type":1,"name":"1819","classic_prize":1819,"water":0.0905},{"id":121,"type":1,"name":"1820","classic_prize":1820,"water":0.09},{"id":122,"type":1,"name":"1821","classic_prize":1821,"water":0.0895},{"id":123,"type":1,"name":"1822","classic_prize":1822,"water":0.089},{"id":124,"type":1,"name":"1823","classic_prize":1823,"water":0.0885},{"id":125,"type":1,"name":"1824","classic_prize":1824,"water":0.088},{"id":126,"type":1,"name":"1825","classic_prize":1825,"water":0.0875},{"id":127,"type":1,"name":"1826","classic_prize":1826,"water":0.087},{"id":128,"type":1,"name":"1827","classic_prize":1827,"water":0.0865},{"id":129,"type":1,"name":"1828","classic_prize":1828,"water":0.086},{"id":130,"type":1,"name":"1829","classic_prize":1829,"water":0.0855},{"id":131,"type":1,"name":"1830","classic_prize":1830,"water":0.085},{"id":132,"type":1,"name":"1831","classic_prize":1831,"water":0.0845},{"id":133,"type":1,"name":"1832","classic_prize":1832,"water":0.084},{"id":134,"type":1,"name":"1833","classic_prize":1833,"water":0.0835},{"id":135,"type":1,"name":"1834","classic_prize":1834,"water":0.083},{"id":136,"type":1,"name":"1835","classic_prize":1835,"water":0.0825},{"id":137,"type":1,"name":"1836","classic_prize":1836,"water":0.082},{"id":138,"type":1,"name":"1837","classic_prize":1837,"water":0.0815},{"id":139,"type":1,"name":"1838","classic_prize":1838,"water":0.081},{"id":140,"type":1,"name":"1839","classic_prize":1839,"water":0.0805},{"id":141,"type":1,"name":"1840","classic_prize":1840,"water":0.08},{"id":142,"type":1,"name":"1841","classic_prize":1841,"water":0.0795},{"id":143,"type":1,"name":"1842","classic_prize":1842,"water":0.079},{"id":144,"type":1,"name":"1843","classic_prize":1843,"water":0.0785},{"id":145,"type":1,"name":"1844","classic_prize":1844,"water":0.078},{"id":146,"type":1,"name":"1845","classic_prize":1845,"water":0.0775},{"id":147,"type":1,"name":"1846","classic_prize":1846,"water":0.077},{"id":148,"type":1,"name":"1847","classic_prize":1847,"water":0.0765},{"id":149,"type":1,"name":"1848","classic_prize":1848,"water":0.076},{"id":150,"type":1,"name":"1849","classic_prize":1849,"water":0.0755},{"id":151,"type":1,"name":"1850","classic_prize":1850,"water":0.075},{"id":152,"type":1,"name":"1851","classic_prize":1851,"water":0.0745},{"id":153,"type":1,"name":"1852","classic_prize":1852,"water":0.074},{"id":154,"type":1,"name":"1853","classic_prize":1853,"water":0.0735},{"id":155,"type":1,"name":"1854","classic_prize":1854,"water":0.073},{"id":156,"type":1,"name":"1855","classic_prize":1855,"water":0.0725},{"id":157,"type":1,"name":"1856","classic_prize":1856,"water":0.072},{"id":158,"type":1,"name":"1857","classic_prize":1857,"water":0.0715},{"id":159,"type":1,"name":"1858","classic_prize":1858,"water":0.071},{"id":160,"type":1,"name":"1859","classic_prize":1859,"water":0.0705},{"id":161,"type":1,"name":"1860","classic_prize":1860,"water":0.07},{"id":162,"type":1,"name":"1861","classic_prize":1861,"water":0.0695},{"id":163,"type":1,"name":"1862","classic_prize":1862,"water":0.069},{"id":164,"type":1,"name":"1863","classic_prize":1863,"water":0.0685},{"id":165,"type":1,"name":"1864","classic_prize":1864,"water":0.068},{"id":166,"type":1,"name":"1865","classic_prize":1865,"water":0.0675},{"id":167,"type":1,"name":"1866","classic_prize":1866,"water":0.067},{"id":168,"type":1,"name":"1867","classic_prize":1867,"water":0.0665},{"id":169,"type":1,"name":"1868","classic_prize":1868,"water":0.066},{"id":170,"type":1,"name":"1869","classic_prize":1869,"water":0.0655},{"id":171,"type":1,"name":"1870","classic_prize":1870,"water":0.065},{"id":172,"type":1,"name":"1871","classic_prize":1871,"water":0.0645},{"id":173,"type":1,"name":"1872","classic_prize":1872,"water":0.064},{"id":174,"type":1,"name":"1873","classic_prize":1873,"water":0.0635},{"id":175,"type":1,"name":"1874","classic_prize":1874,"water":0.063},{"id":176,"type":1,"name":"1875","classic_prize":1875,"water":0.0625},{"id":177,"type":1,"name":"1876","classic_prize":1876,"water":0.062},{"id":178,"type":1,"name":"1877","classic_prize":1877,"water":0.0615},{"id":179,"type":1,"name":"1878","classic_prize":1878,"water":0.061},{"id":180,"type":1,"name":"1879","classic_prize":1879,"water":0.0605},{"id":181,"type":1,"name":"1880","classic_prize":1880,"water":0.06},{"id":182,"type":1,"name":"1881","classic_prize":1881,"water":0.0595},{"id":183,"type":1,"name":"1882","classic_prize":1882,"water":0.059},{"id":184,"type":1,"name":"1883","classic_prize":1883,"water":0.0585},{"id":185,"type":1,"name":"1884","classic_prize":1884,"water":0.058},{"id":186,"type":1,"name":"1885","classic_prize":1885,"water":0.0575},{"id":187,"type":1,"name":"1886","classic_prize":1886,"water":0.057},{"id":188,"type":1,"name":"1887","classic_prize":1887,"water":0.0565},{"id":189,"type":1,"name":"1888","classic_prize":1888,"water":0.056},{"id":190,"type":1,"name":"1889","classic_prize":1889,"water":0.0555},{"id":191,"type":1,"name":"1890","classic_prize":1890,"water":0.055},{"id":192,"type":1,"name":"1891","classic_prize":1891,"water":0.0545},{"id":193,"type":1,"name":"1892","classic_prize":1892,"water":0.054},{"id":194,"type":1,"name":"1893","classic_prize":1893,"water":0.0535},{"id":195,"type":1,"name":"1894","classic_prize":1894,"water":0.053},{"id":196,"type":1,"name":"1895","classic_prize":1895,"water":0.0525},{"id":197,"type":1,"name":"1896","classic_prize":1896,"water":0.052},{"id":198,"type":1,"name":"1897","classic_prize":1897,"water":0.0515},{"id":199,"type":1,"name":"1898","classic_prize":1898,"water":0.051},{"id":200,"type":1,"name":"1899","classic_prize":1899,"water":0.0505},{"id":201,"type":1,"name":"1900","classic_prize":1900,"water":0.05},{"id":202,"type":1,"name":"1901","classic_prize":1901,"water":0.0495},{"id":203,"type":1,"name":"1902","classic_prize":1902,"water":0.049},{"id":204,"type":1,"name":"1903","classic_prize":1903,"water":0.0485},{"id":205,"type":1,"name":"1904","classic_prize":1904,"water":0.048},{"id":206,"type":1,"name":"1905","classic_prize":1905,"water":0.0475},{"id":207,"type":1,"name":"1906","classic_prize":1906,"water":0.047},{"id":208,"type":1,"name":"1907","classic_prize":1907,"water":0.0465},{"id":209,"type":1,"name":"1908","classic_prize":1908,"water":0.046},{"id":210,"type":1,"name":"1909","classic_prize":1909,"water":0.0455},{"id":211,"type":1,"name":"1910","classic_prize":1910,"water":0.045},{"id":212,"type":1,"name":"1911","classic_prize":1911,"water":0.0445},{"id":213,"type":1,"name":"1912","classic_prize":1912,"water":0.044},{"id":214,"type":1,"name":"1913","classic_prize":1913,"water":0.0435},{"id":215,"type":1,"name":"1914","classic_prize":1914,"water":0.043},{"id":216,"type":1,"name":"1915","classic_prize":1915,"water":0.0425},{"id":217,"type":1,"name":"1916","classic_prize":1916,"water":0.042},{"id":218,"type":1,"name":"1917","classic_prize":1917,"water":0.0415},{"id":219,"type":1,"name":"1918","classic_prize":1918,"water":0.041},{"id":220,"type":1,"name":"1919","classic_prize":1919,"water":0.0405},{"id":221,"type":1,"name":"1920","classic_prize":1920,"water":0.04},{"id":222,"type":1,"name":"1921","classic_prize":1921,"water":0.0395},{"id":223,"type":1,"name":"1922","classic_prize":1922,"water":0.039},{"id":224,"type":1,"name":"1923","classic_prize":1923,"water":0.0385},{"id":225,"type":1,"name":"1924","classic_prize":1924,"water":0.038},{"id":226,"type":1,"name":"1925","classic_prize":1925,"water":0.0375},{"id":227,"type":1,"name":"1926","classic_prize":1926,"water":0.037},{"id":228,"type":1,"name":"1927","classic_prize":1927,"water":0.0365},{"id":229,"type":1,"name":"1928","classic_prize":1928,"water":0.036},{"id":230,"type":1,"name":"1929","classic_prize":1929,"water":0.0355},{"id":231,"type":1,"name":"1930","classic_prize":1930,"water":0.035},{"id":232,"type":1,"name":"1931","classic_prize":1931,"water":0.0345},{"id":233,"type":1,"name":"1932","classic_prize":1932,"water":0.034},{"id":234,"type":1,"name":"1933","classic_prize":1933,"water":0.0335},{"id":235,"type":1,"name":"1934","classic_prize":1934,"water":0.033},{"id":236,"type":1,"name":"1935","classic_prize":1935,"water":0.0325},{"id":237,"type":1,"name":"1936","classic_prize":1936,"water":0.032},{"id":238,"type":1,"name":"1937","classic_prize":1937,"water":0.0315},{"id":239,"type":1,"name":"1938","classic_prize":1938,"water":0.031},{"id":240,"type":1,"name":"1939","classic_prize":1939,"water":0.0305},{"id":241,"type":1,"name":"1940","classic_prize":1940,"water":0.03},{"id":242,"type":1,"name":"1941","classic_prize":1941,"water":0.0295},{"id":243,"type":1,"name":"1942","classic_prize":1942,"water":0.029},{"id":244,"type":1,"name":"1943","classic_prize":1943,"water":0.0285},{"id":245,"type":1,"name":"1944","classic_prize":1944,"water":0.028},{"id":246,"type":1,"name":"1945","classic_prize":1945,"water":0.0275},{"id":247,"type":1,"name":"1946","classic_prize":1946,"water":0.027},{"id":248,"type":1,"name":"1947","classic_prize":1947,"water":0.0265},{"id":249,"type":1,"name":"1948","classic_prize":1948,"water":0.026},{"id":250,"type":1,"name":"1949","classic_prize":1949,"water":0.0255},{"id":251,"type":1,"name":"1950","classic_prize":1950,"water":0.025}]
     * bSetable : true
     */

    private String iUserId;
    private LowerSetDataResult.AUserPrizeSetBean aUserPrizeSet;
    private String sCurrentUserPrizeGroup;
    private String iMinPrizeGroup;
    private int iMaxPrizeGroup;
    private String fRebateLevel;
    private boolean bSetable;
    private List<LowerSetDataResult.SAllPossiblePrizeGroupsBean> sAllPossiblePrizeGroups;

    public String getIUserId() {
        return iUserId;
    }

    public void setIUserId(String iUserId) {
        this.iUserId = iUserId;
    }

    public LowerSetDataResult.AUserPrizeSetBean getAUserPrizeSet() {
        return aUserPrizeSet;
    }

    public void setAUserPrizeSet(LowerSetDataResult.AUserPrizeSetBean aUserPrizeSet) {
        this.aUserPrizeSet = aUserPrizeSet;
    }

    public String getSCurrentUserPrizeGroup() {
        return sCurrentUserPrizeGroup;
    }

    public void setSCurrentUserPrizeGroup(String sCurrentUserPrizeGroup) {
        this.sCurrentUserPrizeGroup = sCurrentUserPrizeGroup;
    }

    public String getIMinPrizeGroup() {
        return iMinPrizeGroup;
    }

    public void setIMinPrizeGroup(String iMinPrizeGroup) {
        this.iMinPrizeGroup = iMinPrizeGroup;
    }

    public int getIMaxPrizeGroup() {
        return iMaxPrizeGroup;
    }

    public void setIMaxPrizeGroup(int iMaxPrizeGroup) {
        this.iMaxPrizeGroup = iMaxPrizeGroup;
    }

    public String getFRebateLevel() {
        return fRebateLevel;
    }

    public void setFRebateLevel(String fRebateLevel) {
        this.fRebateLevel = fRebateLevel;
    }

    public boolean isBSetable() {
        return bSetable;
    }

    public void setBSetable(boolean bSetable) {
        this.bSetable = bSetable;
    }

    public List<LowerSetDataResult.SAllPossiblePrizeGroupsBean> getSAllPossiblePrizeGroups() {
        return sAllPossiblePrizeGroups;
    }

    public void setSAllPossiblePrizeGroups(List<LowerSetDataResult.SAllPossiblePrizeGroupsBean> sAllPossiblePrizeGroups) {
        this.sAllPossiblePrizeGroups = sAllPossiblePrizeGroups;
    }

    public static class AUserPrizeSetBean {
        /**
         * username : daniel05
         * nickname : 丹尼尔
         * name :
         * is_agent : 0
         * is_agent_formatted : 玩家
         * available_formatted : 0.00
         * bet_max_prize : 300000
         */

        private String username;
        private String nickname;
        private String name;
        private int is_agent;
        private String is_agent_formatted;
        private String available_formatted;
        private String bet_max_prize;

        public String getUsername() {
            return username;
        }

        public void setUsername(String username) {
            this.username = username;
        }

        public String getNickname() {
            return nickname;
        }

        public void setNickname(String nickname) {
            this.nickname = nickname;
        }

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public int getIs_agent() {
            return is_agent;
        }

        public void setIs_agent(int is_agent) {
            this.is_agent = is_agent;
        }

        public String getIs_agent_formatted() {
            return is_agent_formatted;
        }

        public void setIs_agent_formatted(String is_agent_formatted) {
            this.is_agent_formatted = is_agent_formatted;
        }

        public String getAvailable_formatted() {
            return available_formatted;
        }

        public void setAvailable_formatted(String available_formatted) {
            this.available_formatted = available_formatted;
        }

        public String getBet_max_prize() {
            return bet_max_prize;
        }

        public void setBet_max_prize(String bet_max_prize) {
            this.bet_max_prize = bet_max_prize;
        }
    }

    public static class SAllPossiblePrizeGroupsBean implements IPickerViewData {
        @Override
        public String getPickerViewText() {
            return GameShipHelper.formatNumber(String.valueOf(this.water*100))+"%--"+this.name;
        }
        /**
         * id : 525
         * type : 1
         * name : 1502
         * classic_prize : 1502
         * water : 0.249
         */

        private int id;
        private int type;
        private String name;
        private int classic_prize;
        private double water;

        public int getId() {
            return id;
        }

        public void setId(int id) {
            this.id = id;
        }

        public int getType() {
            return type;
        }

        public void setType(int type) {
            this.type = type;
        }

        public String getName() {
            return name;
        }

        public void setName(String name) {
            this.name = name;
        }

        public int getClassic_prize() {
            return classic_prize;
        }

        public void setClassic_prize(int classic_prize) {
            this.classic_prize = classic_prize;
        }

        public double getWater() {
            return water;
        }

        public void setWater(double water) {
            this.water = water;
        }
    }
}
