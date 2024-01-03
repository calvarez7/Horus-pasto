<?php
namespace App\Formats;

use App\Modelos\citapaciente;
use App\Modelos\Departamento;
use App\Modelos\Municipio;
use App\Modelos\Paciente;
use App\Modelos\Sedeproveedore;
use App\User;
use Codedge\Fpdf\Fpdf\Fpdf;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FichaEpidemiologa extends FPDF
{
    public static $datos;
    public static $paciente;
    public static $municipio;
    public static $departamento;
    public static $sedeproveedores;
    public static $citaPaciente;
    public static $medicoatiende;

    public function generar($data)
    {
        self::$datos = $data;
        self::$paciente = Paciente::where('id',self::$datos["paciente_id"])->first();
        self::$citaPaciente = citapaciente::where('id',self::$datos["cita_paciente_id"])->first();
        self::$medicoatiende = User::where('id',self::$citaPaciente->user_medico_atiende)->first();
        self::$municipio = Municipio::where('id',self::$paciente->Mpio_Atencion)->first();
        self::$departamento = Departamento::where('id',self::$municipio->Departamento_id)->first();
        self::$sedeproveedores = Sedeproveedore::where('id',self::$paciente->IPS)->first();

        $pdf = new FichaEpidemiologa('P','mm','A4');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $this->datosPersonales($pdf);

        if(self::$datos["id"] == '1' || self::$datos["id"] == '1412' || self::$datos["id"] == '1413' || self::$datos["id"] == '1414' || self::$datos["id"] == '1415' || self::$datos["id"] == '1416' || self::$datos["id"] == '1417' || self::$datos["id"] == '1418' || self::$datos["id"] == '1419' || self::$datos["id"] == '1420' || self::$datos["id"] == '1421' || self::$datos["id"] == '1422' || self::$datos["id"] == '1423' || self::$datos["id"] == '1424' || self::$datos["id"] == '1425' || self::$datos["id"] == '1426' || self::$datos["id"] == '1427' || self::$datos["id"] == '1428' || self::$datos["id"] == '1429' || self::$datos["id"] == '1430' || self::$datos["id"] == '1431' || self::$datos["id"] == '1432' || self::$datos["id"] == '1433' || self::$datos["id"] == '1434' ||
        self::$datos["id"] == '1435' || self::$datos["id"] == '1436' || self::$datos["id"] == '1437' || self::$datos["id"] == '1438' || self::$datos["id"] == '1439' || self::$datos["id"] == '1440' || self::$datos["id"] == '1441' || self::$datos["id"] == '1442' || self::$datos["id"] == '1443' || self::$datos["id"] == '1444' || self::$datos["id"] == '1445' || self::$datos["id"] == '1446' || self::$datos["id"] == '1447' || self::$datos["id"] == '1448' || self::$datos["id"] == '1449' || self::$datos["id"] == '1450' || self::$datos["id"] == '1451' || self::$datos["id"] == '1452' || self::$datos["id"] == '1453' || self::$datos["id"] == '1454' || self::$datos["id"] == '1455' || self::$datos["id"] == '1456' || self::$datos["id"] == '1457' || self::$datos["id"] == '1458' || self::$datos["id"] == '1459' || self::$datos["id"] == '1460' || self::$datos["id"] == '1461' || self::$datos["id"] == '1462' || self::$datos["id"] == '1463' || self::$datos["id"] == '1464' ||
        self::$datos["id"] == '1465' || self::$datos["id"] == '1466' || self::$datos["id"] == '1467' || self::$datos["id"] == '1468' || self::$datos["id"] == '1469' || self::$datos["id"] == '1470' || self::$datos["id"] == '1471' || self::$datos["id"] == '1472' || self::$datos["id"] == '1473' || self::$datos["id"] == '1474' || self::$datos["id"] == '1475' || self::$datos["id"] == '1476' || self::$datos["id"] == '1477' || self::$datos["id"] == '1478' || self::$datos["id"] == '1479' || self::$datos["id"] == '1480' || self::$datos["id"] == '1481' || self::$datos["id"] == '1482' ||
        self::$datos["id"] == '1483' || self::$datos["id"] == '1484' || self::$datos["id"] == '1485' || self::$datos["id"] == '1486' || self::$datos["id"] == '1487' || self::$datos["id"] == '1488' || self::$datos["id"] == '1489' || self::$datos["id"] == '1490' || self::$datos["id"] == '1491' || self::$datos["id"] == '1492' || self::$datos["id"] == '1493' || self::$datos["id"] == '1494' || self::$datos["id"] == '1495' || self::$datos["id"] == '1496' || self::$datos["id"] == '1497' || self::$datos["id"] == '1498' || self::$datos["id"] == '1499' || self::$datos["id"] == '1500' || self::$datos["id"] == '1501' || self::$datos["id"] == '1502' || self::$datos["id"] == '1503' || self::$datos["id"] == '1504' || self::$datos["id"] == '1505' || self::$datos["id"] == '1506' || self::$datos["id"] == '1507' || self::$datos["id"] == '1508' || self::$datos["id"] == '1509' || self::$datos["id"] == '1510' || self::$datos["id"] == '1511' ||
        self::$datos["id"] == '1512' || self::$datos["id"] == '1513' || self::$datos["id"] == '1514' || self::$datos["id"] == '1515' || self::$datos["id"] == '1516' || self::$datos["id"] == '1517' || self::$datos["id"] == '1518' || self::$datos["id"] == '1519' || self::$datos["id"] == '1520' || self::$datos["id"] == '1521' || self::$datos["id"] == '1522' || self::$datos["id"] == '1523' || self::$datos["id"] == '1524' || self::$datos["id"] == '1525' || self::$datos["id"] == '1526' || self::$datos["id"] == '1527' || self::$datos["id"] == '1528' || self::$datos["id"] == '1529' || self::$datos["id"] == '1530' || self::$datos["id"] == '1531' || self::$datos["id"] == '1532' || self::$datos["id"] == '1533' || self::$datos["id"] == '1534' || self::$datos["id"] == '1535' || self::$datos["id"] == '1536' || self::$datos["id"] == '1537' || self::$datos["id"] == '1538' || self::$datos["id"] == '1539' || self::$datos["id"] == '1540' ||
        self::$datos["id"] == '1541' || self::$datos["id"] == '1542' || self::$datos["id"] == '1543' || self::$datos["id"] == '1544' || self::$datos["id"] == '1545' || self::$datos["id"] == '1546' || self::$datos["id"] == '1547' || self::$datos["id"] == '1548' || self::$datos["id"] == '1549' || self::$datos["id"] == '1550' || self::$datos["id"] == '1551' || self::$datos["id"] == '1552' || self::$datos["id"] == '1553' || self::$datos["id"] == '1554' || self::$datos["id"] == '1555' || self::$datos["id"] == '1556' || self::$datos["id"] == '1557' || self::$datos["id"] == '1558' || self::$datos["id"] == '1559' || self::$datos["id"] == '1560' || self::$datos["id"] == '1561' || self::$datos["id"] == '1562' || self::$datos["id"] == '1563' || self::$datos["id"] == '1564' || self::$datos["id"] == '1565' || self::$datos["id"] == '1566' || self::$datos["id"] == '1567' || self::$datos["id"] == '1568' || self::$datos["id"] == '1569' || self::$datos["id"] == '1570' || self::$datos["id"] == '1571' || self::$datos["id"] == '1572' || self::$datos["id"] == '1573' || self::$datos["id"] == '1574' || self::$datos["id"] == '1575' ||
        self::$datos["id"] == '1576' || self::$datos["id"] == '1577' || self::$datos["id"] == '1578' || self::$datos["id"] == '1579' || self::$datos["id"] == '1580' || self::$datos["id"] == '1581' || self::$datos["id"] == '1582' || self::$datos["id"] == '1583' || self::$datos["id"] == '1584' || self::$datos["id"] == '1585' || self::$datos["id"] == '1586' || self::$datos["id"] == '1587' || self::$datos["id"] == '1588' || self::$datos["id"] == '1589' || self::$datos["id"] == '1590' || self::$datos["id"] == '1591' || self::$datos["id"] == '1592' || self::$datos["id"] == '1593' || self::$datos["id"] == '1594' || self::$datos["id"] == '1595' || self::$datos["id"] == '1596' || self::$datos["id"] == '1597' || self::$datos["id"] == '1598' || self::$datos["id"] == '1599' || self::$datos["id"] == '1600' || self::$datos["id"] == '1601' || self::$datos["id"] == '1602' || self::$datos["id"] == '1603' || self::$datos["id"] == '1604' || self::$datos["id"] == '1605' || self::$datos["id"] == '1606' || self::$datos["id"] == '1607' || self::$datos["id"] == '1608' ||
        self::$datos["id"] == '1609' || self::$datos["id"] == '1610' || self::$datos["id"] == '1611' || self::$datos["id"] == '1612' || self::$datos["id"] == '1613' || self::$datos["id"] == '1614' || self::$datos["id"] == '1615' || self::$datos["id"] == '1616' || self::$datos["id"] == '1617' || self::$datos["id"] == '1618' || self::$datos["id"] == '1619' || self::$datos["id"] == '1620' || self::$datos["id"] == '1621' || self::$datos["id"] == '1622' || self::$datos["id"] == '1623' || self::$datos["id"] == '1624' || self::$datos["id"] == '1625' || self::$datos["id"] == '1626' || self::$datos["id"] == '1627' || self::$datos["id"] == '1628' || self::$datos["id"] == '1629' || self::$datos["id"] == '1630' || self::$datos["id"] == '1631' || self::$datos["id"] == '1632' || self::$datos["id"] == '1633' ||
        self::$datos["id"] == '1634' || self::$datos["id"] == '1635' || self::$datos["id"] == '1636' || self::$datos["id"] == '1637' || self::$datos["id"] == '1638' || self::$datos["id"] == '1647' || self::$datos["id"] == '1648' || self::$datos["id"] == '1649' || self::$datos["id"] == '1650' || self::$datos["id"] == '1651' || self::$datos["id"] == '1652' || self::$datos["id"] == '1657' || self::$datos["id"] == '1658' || self::$datos["id"] == '1659' || self::$datos["id"] == '1660' || self::$datos["id"] == '1661' || self::$datos["id"] == '1662' || self::$datos["id"] == '1664' || self::$datos["id"] == '1665' || self::$datos["id"] == '1666' || self::$datos["id"] == '1667' || self::$datos["id"] == '1668' || self::$datos["id"] == '1669' || self::$datos["id"] == '1670' || self::$datos["id"] == '1671' || self::$datos["id"] == '1672' ||
        self::$datos["id"] == '1673' || self::$datos["id"] == '1674' || self::$datos["id"] == '1675' || self::$datos["id"] == '1676' || self::$datos["id"] == '1677' || self::$datos["id"] == '1678' || self::$datos["id"] == '1679' || self::$datos["id"] == '1680' || self::$datos["id"] == '1681' || self::$datos["id"] == '1682' || self::$datos["id"] == '1683' || self::$datos["id"] == '1684' || self::$datos["id"] == '1685' || self::$datos["id"] == '1686' || self::$datos["id"] == '1687' || self::$datos["id"] == '1688' || self::$datos["id"] == '1689' || self::$datos["id"] == '1690' || self::$datos["id"] == '1691' || self::$datos["id"] == '1692' || self::$datos["id"] == '1693' ||
        self::$datos["id"] == '1694' || self::$datos["id"] == '1695' || self::$datos["id"] == '1696' || self::$datos["id"] == '1697' || self::$datos["id"] == '1698' || self::$datos["id"] == '1699' || self::$datos["id"] == '1700' || self::$datos["id"] == '1701' || self::$datos["id"] == '1702' || self::$datos["id"] == '1703' || self::$datos["id"] == '1704' || self::$datos["id"] == '1705' || self::$datos["id"] == '1706' || self::$datos["id"] == '1707' || self::$datos["id"] == '1708' || self::$datos["id"] == '1709' || self::$datos["id"] == '1710' || self::$datos["id"] == '1711' || self::$datos["id"] == '1712' || self::$datos["id"] == '1713' || self::$datos["id"] == '1714' || self::$datos["id"] == '1715' || self::$datos["id"] == '1716' || self::$datos["id"] == '1717' || self::$datos["id"] == '1718' || self::$datos["id"] == '1719' || self::$datos["id"] == '1720' || self::$datos["id"] == '1721' ||
        self::$datos["id"] == '1722' || self::$datos["id"] == '1723' || self::$datos["id"] == '1724' || self::$datos["id"] == '1725' || self::$datos["id"] == '1726' || self::$datos["id"] == '1727' || self::$datos["id"] == '1728' || self::$datos["id"] == '1729' || self::$datos["id"] == '1730' || self::$datos["id"] == '1731' || self::$datos["id"] == '1732' || self::$datos["id"] == '1733' || self::$datos["id"] == '1734' || self::$datos["id"] == '1735' || self::$datos["id"] == '1736' || self::$datos["id"] == '1737' || self::$datos["id"] == '1738' || self::$datos["id"] == '1739' || self::$datos["id"] == '1740' || self::$datos["id"] == '1741' || self::$datos["id"] == '1742' || self::$datos["id"] == '1743' || self::$datos["id"] == '1744' || self::$datos["id"] == '1745' || self::$datos["id"] == '1746' || self::$datos["id"] == '1747' || self::$datos["id"] == '1748' ||
        self::$datos["id"] == '1749' || self::$datos["id"] == '1750' || self::$datos["id"] == '1751' || self::$datos["id"] == '1752' || self::$datos["id"] == '1753' || self::$datos["id"] == '1754' || self::$datos["id"] == '1755' || self::$datos["id"] == '1756' || self::$datos["id"] == '1757' || self::$datos["id"] == '1758' || self::$datos["id"] == '1759' || self::$datos["id"] == '1760' || self::$datos["id"] == '1761' || self::$datos["id"] == '1762' || self::$datos["id"] == '1763' || self::$datos["id"] == '1764' || self::$datos["id"] == '1765' || self::$datos["id"] == '1766' || self::$datos["id"] == '1767' || self::$datos["id"] == '1768' || self::$datos["id"] == '1769' || self::$datos["id"] == '1770' || self::$datos["id"] == '1771' || self::$datos["id"] == '1772' || self::$datos["id"] == '1773' || self::$datos["id"] == '1774' || self::$datos["id"] == '1775' ||
        self::$datos["id"] == '1776' || self::$datos["id"] == '1777' || self::$datos["id"] == '1778' || self::$datos["id"] == '1779' || self::$datos["id"] == '1780' || self::$datos["id"] == '1781' || self::$datos["id"] == '1785' || self::$datos["id"] == '1786' || self::$datos["id"] == '1787' || self::$datos["id"] == '1788' || self::$datos["id"] == '1790' || self::$datos["id"] == '1791' || self::$datos["id"] == '1792' || self::$datos["id"] == '1793' || self::$datos["id"] == '1794' || self::$datos["id"] == '1799' || self::$datos["id"] == '1800' || self::$datos["id"] == '1801' || self::$datos["id"] == '1802' || self::$datos["id"] == '1803' || self::$datos["id"] == '1804' || self::$datos["id"] == '1805' || self::$datos["id"] == '1806' || self::$datos["id"] == '1807' || self::$datos["id"] == '1808' || self::$datos["id"] == '1810' || self::$datos["id"] == '1811' ||
        self::$datos["id"] == '1816' || self::$datos["id"] == '1818' || self::$datos["id"] == '1819' || self::$datos["id"] == '1827' || self::$datos["id"] == '1828' || self::$datos["id"] == '1829' || self::$datos["id"] == '1831' || self::$datos["id"] == '1832' || self::$datos["id"] == '1833' || self::$datos["id"] == '1834' || self::$datos["id"] == '1835' || self::$datos["id"] == '1837' || self::$datos["id"] == '1838' || self::$datos["id"] == '1839' || self::$datos["id"] == '1840' || self::$datos["id"] == '1841' || self::$datos["id"] == '1843' || self::$datos["id"] == '1845' || self::$datos["id"] == '1846' || self::$datos["id"] == '1847' || self::$datos["id"] == '1848' || self::$datos["id"] == '1849' || self::$datos["id"] == '1850' || self::$datos["id"] == '1851' ||
        self::$datos["id"] == '1853' || self::$datos["id"] == '1855' || self::$datos["id"] == '1856' || self::$datos["id"] == '1857' || self::$datos["id"] == '1859' || self::$datos["id"] == '1860' || self::$datos["id"] == '1861' || self::$datos["id"] == '1862' || self::$datos["id"] == '1863' || self::$datos["id"] == '1864' || self::$datos["id"] == '1866' || self::$datos["id"] == '1867' || self::$datos["id"] == '1868' || self::$datos["id"] == '1869' || self::$datos["id"] == '1870' || self::$datos["id"] == '1872' || self::$datos["id"] == '1876' || self::$datos["id"] == '1878' || self::$datos["id"] == '1879' || self::$datos["id"] == '1880' || self::$datos["id"] == '1881' || self::$datos["id"] == '1882' || self::$datos["id"] == '1883' || self::$datos["id"] == '1884' || self::$datos["id"] == '1885' || self::$datos["id"] == '1886' || self::$datos["id"] == '1887' || self::$datos["id"] == '1888' || self::$datos["id"] == '1889' || self::$datos["id"] == '1890' || self::$datos["id"] == '1891' || self::$datos["id"] == '1892' || self::$datos["id"] == '1893' || self::$datos["id"] == '1894' || self::$datos["id"] == '1895' || self::$datos["id"] == '1896' || self::$datos["id"] == '1897' || self::$datos["id"] == '1898' || self::$datos["id"] == '1899' ||
        self::$datos["id"] == '1900' || self::$datos["id"] == '1901' || self::$datos["id"] == '1902' || self::$datos["id"] == '1903' || self::$datos["id"] == '1904' || self::$datos["id"] == '1905' || self::$datos["id"] == '1906' || self::$datos["id"] == '1907' || self::$datos["id"] == '1908' || self::$datos["id"] == '1909' || self::$datos["id"] == '1910' || self::$datos["id"] == '1911' || self::$datos["id"] == '1912' || self::$datos["id"] == '1913' || self::$datos["id"] == '1914' || self::$datos["id"] == '1915' || self::$datos["id"] == '1916' || self::$datos["id"] == '1917' || self::$datos["id"] == '1924' || self::$datos["id"] == '1925' || self::$datos["id"] == '1926' || self::$datos["id"] == '1927' ||
        self::$datos["id"] == '1928' || self::$datos["id"] == '1929' || self::$datos["id"] == '1930' || self::$datos["id"] == '1931' || self::$datos["id"] == '1932' || self::$datos["id"] == '1933' || self::$datos["id"] == '1934' || self::$datos["id"] == '1935' || self::$datos["id"] == '1936' || self::$datos["id"] == '2068' || self::$datos["id"] == '2069' || self::$datos["id"] == '2070' || self::$datos["id"] == '2071' || self::$datos["id"] == '2072' || self::$datos["id"] == '2073' || self::$datos["id"] == '2074' || self::$datos["id"] == '2075' || self::$datos["id"] == '2076' || self::$datos["id"] == '2077' || self::$datos["id"] == '2093' || self::$datos["id"] == '2094' || self::$datos["id"] == '2095' || self::$datos["id"] == '2096' || self::$datos["id"] == '2097' || self::$datos["id"] == '2098' || self::$datos["id"] == '2099' ||
        self::$datos["id"] == '2100' || self::$datos["id"] == '2101' || self::$datos["id"] == '2102' || self::$datos["id"] == '2103' || self::$datos["id"] == '2104' || self::$datos["id"] == '2105' || self::$datos["id"] == '2106'
        ){
            $this->cancerMenores18($pdf);
        }elseif(self::$datos["id"] == '2'){
            $this->cancerMama($pdf);
        }elseif(self::$datos["id"] == '994' || self::$datos["id"] == '995' || self::$datos["id"] == '1001' || self::$datos["id"] == '1002'){
            $this->dengue($pdf);
        }elseif(self::$datos["id"]== '635' || self::$datos["id"]== '636' || self::$datos["id"]== '637' || self::$datos["id"]== '638' || self::$datos["id"]== '639' || self::$datos["id"]== '640' || self::$datos["id"]== '641' || self::$datos["id"]== '642' || self::$datos["id"]== '643' || self::$datos["id"]== '644' || self::$datos["id"]== '645' || self::$datos["id"]== '646' || self::$datos["id"]== '647' || self::$datos["id"]== '648' || self::$datos["id"]== '649' || self::$datos["id"]== '650' || self::$datos["id"]== '651' || self::$datos["id"]== '652' || self::$datos["id"]== '653' || self::$datos["id"]== '654' || self::$datos["id"]== '655' || self::$datos["id"]== '656' || self::$datos["id"]== '657' || self::$datos["id"]== '658' || self::$datos["id"]== '659' || self::$datos["id"]== '660' || self::$datos["id"]== '661' || self::$datos["id"]== '662' || self::$datos["id"]== '663' || self::$datos["id"]== '664' || self::$datos["id"]== '665' || self::$datos["id"]== '666' || self::$datos["id"]== '667' || self::$datos["id"]== '668' || self::$datos["id"]== '676' || self::$datos["id"]== '677' || self::$datos["id"]== '678' || self::$datos["id"]== '679' || self::$datos["id"]== '680' || self::$datos["id"]== '681' || self::$datos["id"]== '682' || self::$datos["id"]== '683' || self::$datos["id"]== '684' || self::$datos["id"]== '685' || self::$datos["id"]== '686' || self::$datos["id"]== '687' || self::$datos["id"]== '688' || self::$datos["id"]== '12577'){
            $this->EnfermedadTransmitidaPorAlimentos($pdf);
        }
        $pdf->Output();

    }

    public function header()
    {
        $this->SetFont('Arial', 'B', 9);
        $logo = public_path() . "/images/encabezadoEpidemiologia.png";
        $this->Image($logo, 5, 5, -99);

        $this->SetXY(10, 26);
        $this->SetFont('Arial', '', 11);
        $this->Cell(192, 6, utf8_decode('SISTEMA NACIONAL DE VIGILANCIA EN SALUD PÚBLICA - Subsistema de información Sivigila '), 0, 0, 'C');
        $this->ln();

    }

    public function footer(){
        $this->SetXY(180,282);
        $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
    }

    public function datosPersonales($pdf){
        $pdf->Cell(192, 6, utf8_decode('Ficha de notificación individual'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(192, 6, utf8_decode('Datos básicos'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(192, 6, utf8_decode('FOR-R02.0000-001 V:11 2022-06-08'), 0, 0, 'C');

        $pdf->SetXY(5, 54);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(192, 4, utf8_decode('La ficha de notificación es para fines de vigilancia en salud pública y todas las entidades que participen en el proceso deben garantizar la confidencialidad de la información LEY 1273/09 y 1266/09'), 0, 0, 'l');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 4, utf8_decode('1. INFORMACIÓN GENERAL'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $y1 = $pdf->GetY();

        $pdf->SetXY(5,$y1+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(40, 4, utf8_decode('1.1 Código de la UPGD'), 0, 0, 'l');
        $y2 = $pdf->GetY();

        $pdf->SetXY(14,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,2),0,1), 1, 'C');
        $pdf->SetXY(19,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,2),1,1), 1, 'C');
        $pdf->SetXY(11, $y2+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(40, 4, utf8_decode('Departamento'), 0, 0, 'l');

        $pdf->SetXY(30,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$municipio->codigo_Dane?self::$municipio->codigo_Dane:'',0,2),0,1), 1, 'C');
        // $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(35,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$municipio->codigo_Dane?self::$municipio->codigo_Dane:'',0,2),1,1), 1, 'C');
        // $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(40,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$municipio->codigo_Dane?self::$municipio->codigo_Dane:'',0,3),2,1), 1, 'C');
        $pdf->SetXY(31, $y2+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(40, 4, utf8_decode('Municipio'), 0, 0, 'l');

        $pdf->SetXY(50,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),0,1), 1, 'C');
        $pdf->SetXY(55,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),1,1), 1, 'C');
        $pdf->SetXY(60,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),2,1), 1, 'C');
        $pdf->SetXY(65,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),3,1), 1, 'C');
        $pdf->SetXY(70,$y2+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),4,1), 1, 'C');
        $pdf->SetXY(48, $y2+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('Código'), 0, 0, 'C');

        $pdf->SetXY(80,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),11,1), 1, 'C');
        $pdf->SetXY(85,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$sedeproveedores->Codigo_habilitacion?self::$sedeproveedores->Codigo_habilitacion:'',0,14),12,1), 1, 'C');
        $pdf->SetXY(78, $y2+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(40, 4, utf8_decode('Sub-Índice'), 0, 0, 'l');

        $pdf->SetXY(115, $y1+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('Razón social de la unidad primaria generadora del dato'), 0, 0, 'C');
        $pdf->SetXY(97,$y2+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(104, 4, utf8_decode('SUMIMEDICAL S.A.S'), 0, 'l');
        $y3 = $pdf->GetY();

        $pdf->SetXY(4, $y3+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('1.2 Nombre del evento'), 0, 0, 'C');
        $pdf->SetXY(6,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(105, 4, utf8_decode('Caso sospechoso covid-19'), 0, 'l');
        $pdf->SetXY(124, $y3+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(16, 4, utf8_decode('Código del evento'), 0, 0, 'C');
        $pdf->SetXY(125,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, utf8_decode('3'), 1, 'C');
        $pdf->SetXY(130,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, utf8_decode('4'), 1, 'C');
        $pdf->SetXY(135,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, utf8_decode('6'), 1, 'C');


        $pdf->SetXY(160, $y3+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('1.3 Fecha de la notificación (dd/mm/aaaa)'), 0, 0, 'C');
        $pdf->SetXY(153,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),8,1), 1, 'C');
        $pdf->SetXY(158,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),9,1), 1, 'C');
        $pdf->SetXY(162, $y3+7);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
        $pdf->SetXY(166,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),5,1), 1, 'C');
        $pdf->SetXY(171,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),6,1), 1, 'C');
        $pdf->SetXY(175, $y3+7);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
        $pdf->SetXY(179,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),0,1), 1, 'C');
        $pdf->SetXY(184,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),1,1), 1, 'C');
        $pdf->SetXY(189,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),2,1), 1, 'C');
        $pdf->SetXY(194,$y3+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$citaPaciente->Datetimeingreso?self::$citaPaciente->Datetimeingreso:'',0,20),3,1), 1, 'C');

        $y4 = $pdf->GetY();
        $pdf->Line(5,$y1+4,5,$y4+2); #LINEA VERTICAL IZQUIERDA
        $pdf->Line(205,$y1+4,205,$y4+2); #LINEA VERTICAL DERECHA
        $pdf->Line(5,$y4+2,205,$y4+2); #LIENA HORIZONTAL ABAJO
        $pdf->Line(5,$y1+4,205,$y1+4); #lINEA HORIZONTAL ARRIBA
        $pdf->Line(5,$y3+4,205,$y3+4); #LINEA HORIZONTAL MEDIA
        $pdf->Line(95,$y1+4,95,$y3+4); #LINEA VERTICAL DENTRO 1
        $pdf->Line(149,$y3+4,149,$y4+2);#LINEA VERTICAL DENTRO 2

        #MARGENES HOJA 2
        $pdf->SetXY(5,$y4+3);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 4, utf8_decode('2. IDENTIFICACIÓN DEL PACIENTE'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y5 = $pdf->GetY();
        $pdf->SetXY(5, $y5+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.1 Tipo de documento'), 0, 0, 'C');
        $y6 = $pdf->GetY();

        $pdf->SetXY(7, $y6+4);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'RC'?'X':''), 1, 'C');
        $pdf->SetXY(12,  $y6+4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(4, 3, utf8_decode('RC'), 0, 0, 'C');
        $pdf->SetXY(17, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'TI'?'X':''), 1, 'C');
        $pdf->SetXY(21,  $y6+4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(4, 3, utf8_decode('TI'), 0, 0, 'C');
        $pdf->SetXY(25, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'CC'?'X':''), 1, 'C');
        $pdf->SetXY(30,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('CC'), 0, 0, 'C');
        $pdf->SetXY(35, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'CE'?'X':''), 1, 'C');
        $pdf->SetXY(40,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('CE'), 0, 0, 'C');
        $pdf->SetXY(45, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'PA'?'X':''), 1, 'C');
        $pdf->SetXY(50,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('PA'), 0, 0, 'C');
        $pdf->SetXY(55, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(60,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('MS'), 0, 0, 'C');
        $pdf->SetXY(65, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('AS'), 0, 0, 'C');
        $pdf->SetXY(75, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'PE'?'X':''), 1, 'C');
        $pdf->SetXY(80,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('PE'), 0, 0, 'C');
        $pdf->SetXY(85, $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(4, 3, (self::$paciente->Tipo_Doc === 'CN'?'X':''), 1, 'C');
        $pdf->SetXY(90,  $y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(4, 3, utf8_decode('CN'), 0, 0, 'C');

        $pdf->SetXY(100, $y5+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.2 Número de identificación'), 0, 0, 'C');
        $pdf->SetXY(98,$y6+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(92, 4, self::$paciente->Num_Doc, 0, 'l');

        $y7 = $pdf->GetY();
        $pdf->SetXY(5, $y7+2);
        $pdf->SetFont('Arial', 'B', 4.5);
        $pdf->Cell(200, 4, utf8_decode('*RC : REGISTRO CIVIL | TI : TARJETA IDENTIDAD | CC : CÉDULA CIUDADANÍA | CE : CÉDULA EXTRANJERÍA |- PA : PASAPORTE | MS : MENOR SIN ID | AS : ADULTO SIN ID | PE : PERMISO ESPECIAL DE PERMANENCIA | CN : CERTIFICADO DE NACIDO VIVO'), 1, 0, 'l');

        $y8 = $pdf->GetY();
        $pdf->SetXY(5, $y8+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.3 Nombres y apellidos del paciente'), 0, 0, 'l');
        $pdf->SetXY(6,$y8+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(140, 4, self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape, 0, 'l');
        $pdf->SetXY(150, $y8+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.4 Teléfono'), 0, 0, 'C');
        $pdf->SetXY(160,$y8+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(40, 4, self::$paciente->Telefono?self::$paciente->Telefono:'', 0, 'l');

        $y9 = $pdf->GetY();
        $pdf->SetXY(5, $y9);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.5 Fecha de nacimiento (dd/mm/aaaa)'), 0, 0, 'l');
        $pdf->SetXY(7,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),0,1), 1, 'C');
        $pdf->SetXY(12,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),1,1), 1, 'C');
        $pdf->SetXY(16, $y9+3);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
        $pdf->SetXY(20,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),3,1), 1, 'C');
        $pdf->SetXY(25,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),4,1), 1, 'C');
        $pdf->SetXY(29, $y9+3);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
        $pdf->SetXY(33,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),6,1), 1, 'C');
        $pdf->SetXY(38,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),7,1), 1, 'C');
        $pdf->SetXY(43,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),8,1), 1, 'C');
        $pdf->SetXY(48,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, substr(substr(self::$paciente->Fecha_Naci?self::$paciente->Fecha_Naci:'',0,10),9,1), 1, 'C');

        $pdf->SetXY(46, $y9);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.6 Edad'), 0, 0, 'C');
        $pdf->SetXY(56,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(10, 4, self::$paciente->Edad_Cumplida?self::$paciente->Edad_Cumplida:'', 0, 'C');

        $pdf->SetXY(67, $y9);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.7 Unidad de medida de la edad'), 0, 0, 'l');
        $pdf->SetXY(68,$y9+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, ('X'), 1, 'C');
        $pdf->SetXY(71, $y9+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('1. Años'), 0, 0, 'l');

        $pdf->SetXY(68,$y9+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (''), 1, 'C');
        $pdf->SetXY(71, $y9+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2. Meses'), 0, 0, 'l');

        $pdf->SetXY(83,$y9+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (''), 1, 'C');
        $pdf->SetXY(86, $y9+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('3. Días'), 0, 0, 'l');

        $pdf->SetXY(83,$y9+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (''), 1, 'C');
        $pdf->SetXY(86, $y9+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('4. Horas'), 0, 0, 'l');

        $pdf->SetXY(98,$y9+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (''), 1, 'C');
        $pdf->SetXY(101, $y9+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('5. Minutos'), 0, 0, 'l');
        $pdf->SetXY(98,$y9+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (''), 1, 'C');
        $pdf->SetXY(101, $y9+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('0. No aplica'), 0, 0, 'l');

        $pdf->SetXY(118, $y9);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.8 Sexo'), 0, 0, 'l');
        $pdf->SetXY(119,$y9+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (substr(self::$paciente->Sexo,0,1) === 'M'?'X':''), 1, 'C');
        $pdf->SetXY(122, $y9+4);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('M. Masculino'), 0, 0, 'l');

        $pdf->SetXY(119,$y9+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, (substr(self::$paciente->Sexo,0,1) === 'F'?'X':''), 1, 'C');
        $pdf->SetXY(122, $y9+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('F. Femenino'), 0, 0, 'l');

        $pdf->SetXY(138,$y9+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(141, $y9+4);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('I. Indeterminado'), 0, 0, 'l');
        $pdf->SetXY(161, $y9);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.9 Nacionalidad'), 0, 0, 'l');
        $pdf->SetXY(162,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(23, 4, utf8_decode('Colombia'), 0, 'l');

        $pdf->SetXY(189,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, utf8_decode('1'), 1, 'C');
        $pdf->SetXY(194,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, utf8_decode('7'), 1, 'C');
        $pdf->SetXY(199,$y9+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, utf8_decode('0'), 1, 'C');

        $y10 = $pdf->GetY();
        $pdf->SetXY(5, $y10+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.10 País de ocurrencia del caso'), 0, 0, 'l');

        $pdf->SetXY(7, $y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(25, 4, utf8_decode('Colombia'), 0, 'l');
        $pdf->SetXY(35, $y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode('1'), 1, 'C');
        $pdf->SetXY(40, $y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode('7'), 1, 'C');
        $pdf->SetXY(45, $y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode('0'), 1, 'C');
        $pdf->SetXY(38,  $y10+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(40, 4, utf8_decode('Codigo'), 0, 0, 'l');
        $pdf->SetXY(51, $y10+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.11 Departamento y municipio de procedencia/ocurrencia'), 0, 0, 'l');


        $pdf->SetXY(52,$y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(47, 4, utf8_decode(self::$municipio->Nombre?self::$departamento->Nombre.' / '.self::$municipio->Nombre:''), 0, 'l');
        $pdf->SetXY(103,$y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),0,1), 1, 'C');
        $pdf->SetXY(108,$y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),1,1), 1, 'C');
        $pdf->SetXY(100, $y10+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(40, 4, utf8_decode('Departamento'), 0, 0, 'l');

        $pdf->SetXY(116,$y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),0,1), 1, 'C');
        $pdf->SetXY(121,$y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),1,1), 1, 'C');
        $pdf->SetXY(126,$y10+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),2,1), 1, 'C');;
        $pdf->SetXY(118, $y10+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(40, 4, utf8_decode('Municipio'), 0, 0, 'l');

        $pdf->SetXY(135, $y10+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.12 Área de ocurrencia del caso'), 0, 0, 'l');
        $pdf->SetXY(141,$y10+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode('X'), 1, 'C');
        $pdf->SetXY(144, $y10+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('1. Cabecera municipal'), 0, 0, 'l');

        $pdf->SetXY(141,$y10+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(144, $y10+12);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('2. Centro poblado'), 0, 0, 'l');

        $pdf->SetXY(175,$y10+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(178, $y10+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('3. Rural disperso'), 0, 0, 'l');

        $y11 = $pdf->GetY();
        $pdf->SetXY(5, $y11+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.13 Localidad de ocurrencia del caso'), 0, 0, 'l');

        $pdf->SetXY(7,$y11+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(45, 4, utf8_decode(self::$municipio->Nombre?self::$municipio->Nombre:''), 0, 'L');

        $pdf->SetXY(55, $y11+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.14 Barrio de ocurrencia del caso'), 0, 0, 'l');

        $pdf->SetXY(56,$y11+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(42, 4, utf8_decode(''), 0, 'C');

        $pdf->SetXY(100, $y11+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.15 Cabecera municipal/centro poblado/rural disperso'), 0, 0, 'l');

        $pdf->SetXY(101,$y11+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(65, 4, utf8_decode(self::$municipio->Nombre?self::$municipio->Nombre:''), 0, 'l');

        $pdf->SetXY(168, $y11+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.16 Vereda/zona'), 0, 0, 'l');

        $pdf->SetXY(169,$y11+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(35, 4, utf8_decode(''), 0, 'C');

        $y12 = $pdf->GetY();
        $pdf->SetXY(5, $y12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.17 Ocupación del paciente'), 0, 0, 'l');

        $pdf->SetXY(7,$y12+4);
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(26, 4, utf8_decode(self::$paciente->Ocupacion ? self::$paciente->Ocupacion:''), 0, 'l');
        $pdf->SetXY(35,$y12+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode(''), 1, 'C');
        $pdf->SetXY(40,$y12+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode(''), 1, 'C');
        $pdf->SetXY(45,$y12+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode(''), 1, 'C');
        $pdf->SetXY(50,$y12+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4,utf8_decode(''), 1, 'C');
        $pdf->SetXY(34, $y12+8);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(24, 4, utf8_decode('Código'), 0, 0, 'C');

        $pdf->SetXY(58, $y12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.18 Tipo de régimen en salud'), 0, 0, 'l');

        $pdf->SetXY(59,$y12+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(62, $y12+4);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('P. Excepción'), 0, 0, 'l');

        $pdf->SetXY(59,$y12+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode('X'), 1, 'C');
        $pdf->SetXY(62, $y12+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('E. Especial'), 0, 0, 'l');

        $pdf->SetXY(78,$y12+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(81, $y12+4);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('C. Contributivo'), 0, 0, 'l');

        $pdf->SetXY(78,$y12+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(81, $y12+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('S. Subsidiado'), 0, 0, 'l');

        $pdf->SetXY(99,$y12+4);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(102, $y12+4);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('N. No Asegurado'), 0, 0, 'l');

        $pdf->SetXY(99,$y12+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(102, $y12+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('I. Indeterminado/ pendiente'), 0, 0, 'l');

        $pdf->SetXY(132, $y12);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.19 Nombre de la administradora de Planes de beneficios'), 0, 0, 'l');

        $pdf->SetXY(133,$y12+4);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(37, 4, utf8_decode(self::$paciente->Ut?self::$paciente->Ut:''), 0, 'l');
            // EAS027
        if (self::$paciente->Ut == 'REDVITAL UT') {
            $pdf->SetXY(173,$y12+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('R'), 1, 'l');
            $pdf->SetXY(178,$y12+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('E'), 1, 'l');
            $pdf->SetXY(183,$y12+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('S'), 1, 'l');
            $pdf->SetXY(188,$y12+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('0'), 1, 'l');
            $pdf->SetXY(193,$y12+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('0'), 1, 'l');
            $pdf->SetXY(198,$y12+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('4'), 1, 'l');
            $pdf->SetXY(170, $y12+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(36, 4, utf8_decode('Código'), 0, 0, 'C');
        }elseif (self::$paciente->Ut == 'FERROCARRILES NACIONALES') {
            $pdf->SetXY(173,$y12+4);
            $pdf->SetFont('Arial', 'E', 7);
            $pdf->MultiCell(5, 4, utf8_decode('E'), 1, 'l');
            $pdf->SetXY(178,$y12+4);
            $pdf->SetFont('Arial', 'A', 7);
            $pdf->MultiCell(5, 4, utf8_decode('A'), 1, 'l');
            $pdf->SetXY(183,$y12+4);
            $pdf->SetFont('Arial', 'S', 7);
            $pdf->MultiCell(5, 4, utf8_decode('S'), 1, 'l');
            $pdf->SetXY(188,$y12+4);
            $pdf->SetFont('Arial', '0', 7);
            $pdf->MultiCell(5, 4, utf8_decode('0'), 1, 'l');
            $pdf->SetXY(193,$y12+4);
            $pdf->SetFont('Arial', '2', 7);
            $pdf->MultiCell(5, 4, utf8_decode('2'), 1, 'l');
            $pdf->SetXY(198,$y12+4);
            $pdf->SetFont('Arial', '7', 7);
            $pdf->MultiCell(5, 4, utf8_decode('7'), 1, 'l');
            $pdf->SetXY(170, $y12+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(36, 4, utf8_decode('Código'), 0, 0, 'C');
        }

        $y13 = $pdf->GetY();


        $pdf->SetXY(5, $y13+4);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('2.20 Pertenencia étnica'), 0, 0, 'l');

            $pdf->SetXY(7,$y13+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(10, $y13+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('1. Indígena'), 0, 0, 'l');

            $pdf->SetXY(27, $y13+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Grupo ètnico'), 0, 0, 'l');
            $pdf->SetXY(27,$y13+12);
            $pdf->SetFont('Arial', '', 8);
            $pdf->MultiCell(15, 3, utf8_decode('indigena'), 0, 'C');

            $pdf->SetXY(48,$y13+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(51, $y13+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('2. Rom, Gitano'), 0, 0, 'l');

            $pdf->SetXY(71,$y13+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(74, $y13+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3. Raiza'), 0, 0, 'l');

            $pdf->SetXY(86,$y13+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(89, $y13+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('4. Palenquero'), 0, 0, 'l');

            $pdf->SetXY(108,$y13+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(111, $y13+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('5. Negro, mulato afro colombiano'), 0, 0, 'l');

            $pdf->SetXY(153,$y13+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(156, $y13+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode(' 6. Otro'), 0, 0, 'l');

            $pdf->SetXY(175, $y13+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('2.21 Estrato'), 0, 0, 'l');

            $pdf->SetXY(175,$y13+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(25, 4, utf8_decode(''), 0, 'C');

            $y14 = $pdf->GetY();
            $pdf->SetXY(5, $y14+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('2.22 Seleccione los grupos poblacionales a los que pertenece el paciente'), 0, 0, 'l');

            $pdf->SetXY(12,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode((self::$paciente->Discapacidad?'X':'')), 1, 'C');
            $pdf->SetXY(15, $y14+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Discapacitados'), 0, 0, 'l');

            $pdf->SetXY(12,$y14+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(15, $y14+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Desplazados'), 0, 0, 'l');

            $pdf->SetXY(34,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(37, $y14+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Migrantes'), 0, 0, 'l');

            $pdf->SetXY(34,$y14+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(37, $y14+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Carcelario'), 0, 0, 'l');

            $pdf->SetXY(51,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(54, $y14+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Gestantes'), 0, 0, 'l');

            $pdf->SetXY(51,$y14+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(54, $y14+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Indigentes'), 0, 0, 'l');

            $pdf->SetXY(68,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(7, 7, utf8_decode(''), 1, 'C');
            $pdf->SetXY(75, $y14+10);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Sem.de gestaciòn'), 0, 0, 'l');

            $pdf->SetXY(96,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(99, $y14+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Población infantil a cargo del ICBF'), 0, 0, 'l');

            $pdf->SetXY(96,$y14+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(99, $y14+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Madres comunitarias'), 0, 0, 'l');

            $pdf->SetXY(136,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(139, $y14+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Desmovilizados'), 0, 0, 'l');

            $pdf->SetXY(136,$y14+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(139, $y14+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Centros psiquiátricos'), 0, 0, 'l');

            $pdf->SetXY(165,$y14+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(168, $y14+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Víctimas de violencia armada'), 0, 0, 'l');

            $pdf->SetXY(165,$y14+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(168, $y14+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Otros grupos poblacionales'), 0, 0, 'l');
            $y15 = $pdf->GetY();

            #MARGENES IDENTIFICACION PACIENTE
            $pdf->Line(5, $y5+4, 5, $y15+4); #LINA VERTICAL IZQUIERDA
            $pdf->Line(205, $y5+4, 205, $y15+4); #LINEA VERTICAL DERECHA
            $pdf->Line(5, $y5+4, 205, $y5+4); #LINEA HORIZONTAL ARRIBA
            $pdf->Line(5, $y15+4, 205, $y15+4); #LINEA HORIZONTAL ABAJO
            // #LINEA 1
            $pdf->Line(95, $y5+4, 95, $y7+2); #LINEA VERTICAL 1
            // #LIENA 2
            $pdf->Line(155, $y8+4, 155, $y9);#LIENA VERTICAL 1
            $pdf->Line(5, $y9, 205, $y9);#LINEA HORIZONTAL ABAJO
            // #LINEA 3
            $pdf->Line(5, $y10+4, 205, $y10+4);
            $pdf->Line(55, $y9, 55, $y10+4); #LINEA VERTICAL 1
            $pdf->Line(67, $y9, 67, $y10+4); #LINEA VERTICAL 2
            $pdf->Line(117, $y9, 117, $y10+4); #LINEA VERTICAL 3
            $pdf->Line(160, $y9, 160,$y10+4); #LINEA VERTICAL 4
            // #linea 4
            $pdf->Line(5, $y11+8, 205, $y11+8);
            $pdf->Line(51, $y10+4, 51,$y11+8); #lINEA VERTICAL 1
            $pdf->Line(134, $y10+4, 134,$y11+8); #LINEA VERTICAL 2
            // #Linea 5
            $pdf->Line(5, $y12, 205, $y12);
            $pdf->Line(54, $y11+8, 54,$y12); #LINEA VERTICAL 1
            $pdf->Line(100, $y11+8, 100,$y12); #LINEA VERTICAL 2
            $pdf->Line(168, $y11+8, 168,$y12); #LINEA VERTICAL 3
            // #linea 6
            $pdf->Line(5, $y13+4, 205, $y13+4);
            $pdf->Line(57, $y12, 57,$y13+4);
            $pdf->Line(132, $y12, 132,$y13+4);
            // #linea 7
            $pdf->Line(5, $y14+4, 205, $y14+4);
            $pdf->Line(170, $y13+4, 170,$y14+4);

            $pdf->SetXY(5,$y15+5);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(200, 4, utf8_decode('3. NOTIFICACIÓN'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $y16 = $pdf->GetY();

            $pdf->SetXY(5, $y16+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.1 Fuente'), 0, 0, 'l');
            $pdf->SetXY(7,$y16+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode('X'), 1, 'C');
            $pdf->SetXY(10, $y16+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('1. Notificación rutinaria'), 0, 0, 'l');

            $pdf->SetXY(7,$y16+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(10, $y16+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('2. Búsqueda activa Inst.'), 0, 0, 'l');

            $pdf->SetXY(36,$y16+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(39, $y16+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('3. Vigilancia Intensificada'), 0, 0, 'l');

            $pdf->SetXY(36,$y16+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(39, $y16+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('4. Búsqueda activa com.'), 0, 0, 'l');

            $pdf->SetXY(67,$y16+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(70, $y16+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('5. Investigaciones'), 0, 0, 'l');

            $pdf->SetXY(91, $y16+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.2 País, departamento y municipio de residencia del paciente'), 0, 0, 'l');

            $pdf->SetXY(92,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(61, 4, utf8_decode('Colombia / '.self::$departamento->Nombre.' / '.self::$paciente->Mpio_Residencia), 0, 'l');

            $pdf->SetXY(157,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('1'), 1, 'C');
            $pdf->SetXY(162,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('7'), 1, 'C');
            $pdf->SetXY(167,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode('0'), 1, 'C');
            $pdf->SetXY(161, $y16+12);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(40, 4, utf8_decode('Pais'), 0, 0, 'l');

            $pdf->SetXY(175,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),0,1), 1, 'C');
            $pdf->SetXY(180,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4,substr(substr(self::$departamento->codigo_Dane?self::$departamento->codigo_Dane:'',0,3),1,1), 1, 'C');
            $pdf->SetXY(171, $y16+12);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(40, 4, utf8_decode('Departamento'), 0, 0, 'l');

            $pdf->SetXY(188,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4,substr(substr(self::$municipio->codigo_Dane?self::$municipio->codigo_Dane:'',0,3),0,1), 1, 'C');
            $pdf->SetXY(193,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4,substr(substr(self::$municipio->codigo_Dane?self::$municipio->codigo_Dane:'',0,3),1,1), 1, 'C');
            $pdf->SetXY(198,$y16+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4,substr(substr(self::$municipio->codigo_Dane?self::$municipio->codigo_Dane:'',0,3),2,1), 1, 'C');;
            $pdf->SetXY(190, $y16+12);
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(40, 4, utf8_decode('Municipio'), 0, 0, 'l');

            $y17 = $pdf->GetY();
            $pdf->SetXY(5, $y17+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(34, 4, utf8_decode('3.3 Dirección de residencia'), 0, 0, 'l');
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(162, 4, utf8_decode(self::$paciente->Direccion_Residencia?self::$paciente->Direccion_Residencia:''), 1, 'l');

            $y18 = $pdf->GetY();
            $pdf->SetXY(5, $y18);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.4 Fecha de consulta (dd/mm/aaaa)'), 0, 0, 'l');
            $pdf->SetXY(7,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,8,1), 1, 'l');
            $pdf->SetXY(12,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,9,1), 1, 'l');
            $pdf->SetXY(16, $y18+3);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(20,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,5,1), 1, 'l');
            $pdf->SetXY(25,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,6,1), 1, 'l');
            $pdf->SetXY(29, $y18+3);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(33,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,0,1), 1, 'l');
            $pdf->SetXY(38,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,1,1), 1, 'l');
            $pdf->SetXY(43,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,2,1), 1, 'l');
            $pdf->SetXY(48,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr(self::$citaPaciente->Datetimeingreso,3,1), 1, 'l');

            $pdf->SetXY(55, $y18);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.5 Fecha de inicio de síntomas (dd/mm/aaaa)'), 0, 0, 'l');
            $pdf->SetXY(56,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-2,1), 1, 'l');
            $pdf->SetXY(61,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-1,1), 1, 'l');
            $pdf->SetXY(65, $y18+3);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(69,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-5,1), 1, 'l');
            $pdf->SetXY(74,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-4,1), 1, 'l');
            $pdf->SetXY(78, $y18+3);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(82,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-10,1), 1, 'l');
            $pdf->SetXY(87,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-9,1), 1, 'l');
            $pdf->SetXY(92,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-8,1), 1, 'l');
            $pdf->SetXY(97,$y18+4);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-7,1), 1, 'l');

            $pdf->SetXY(111, $y18);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.6 Clasificación inicial de caso'), 0, 0, 'l');

            $pdf->SetXY(113,$y18+4);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(116, $y18+4);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('1. Sospechoso'), 0, 0, 'l');

            $pdf->SetXY(113,$y18+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(116, $y18+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('2. Probable'), 0, 0, 'l');

            $pdf->SetXY(133,$y18+4);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(136, $y18+4);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('3. Conf. por laboratorio'), 0, 0, 'l');

            $pdf->SetXY(133,$y18+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(136, $y18+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('4. Conf. Clínica'), 0, 0, 'l');

            $pdf->SetXY(113,$y18+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(116, $y18+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('5. Conf. nexo epidemiológico'), 0, 0, 'l');

            $pdf->SetXY(164, $y18);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.7 Hospitalizado'), 0, 0, 'l');

            $pdf->SetXY(165,$y18+4);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(168, $y18+4);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('Si'), 0, 0, 'l');

            $pdf->SetXY(172,$y18+4);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(175, $y18+4);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('No'), 0, 0, 'l');

            $y19 = $pdf->GetY();
            $pdf->SetXY(5, $y19+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.8 Fecha de hospitalización (dd/mm/aaaa)'), 0, 0, 'l');
            $pdf->SetXY(7,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-2,1), 1, 'l');
            $pdf->SetXY(12,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-1,1), 1, 'l');
            $pdf->SetXY(16, $y19+15);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(20,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-5,1), 1, 'l');
            $pdf->SetXY(25,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-4,1), 1, 'l');
            $pdf->SetXY(29, $y19+15);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(33,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-10,1), 1, 'l');
            $pdf->SetXY(38,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-9,1), 1, 'l');
            $pdf->SetXY(43,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-8,1), 1, 'l');
            $pdf->SetXY(48,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-7,1), 1, 'l');

            $pdf->SetXY(57, $y19+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.9 Condición final'), 0, 0, 'l');

            $pdf->SetXY(58,$y19+16);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode('X'), 1, 'C');
            $pdf->SetXY(61, $y19+16);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('1. Vivo'), 0, 0, 'l');

            $pdf->SetXY(58,$y19+20);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(61, $y19+20);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('2. Muerto'), 0, 0, 'l');

            $pdf->SetXY(72,$y19+16);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(74, $y19+16);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode(' 0. No sabe, no responde'), 0, 0, 'l');

            $pdf->SetXY(102, $y19+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.10 Fecha de defunción (dd/mm/aaaa)'), 0, 0, 'l');

            $pdf->SetXY(103,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-2,1), 1, 'l');
            $pdf->SetXY(108,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-1,1), 1, 'l');
            $pdf->SetXY(112, $y19+15);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(116,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-5,1), 1, 'l');
            $pdf->SetXY(121,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-4,1), 1, 'l');
            $pdf->SetXY(125, $y19+15);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(129,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-10,1), 1, 'l');
            $pdf->SetXY(134,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-9,1), 1, 'l');
            $pdf->SetXY(139,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-8,1), 1, 'l');
            $pdf->SetXY(144,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, substr('',-7,1), 1, 'l');

            $pdf->SetXY(150, $y19+12);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.11 Número certificado de defunción'), 0, 0, 'l');

            $pdf->SetXY(151,$y19+16);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(50, 4, '', 0, 'l');

            $y20 = $pdf->GetY();
            $pdf->SetXY(5,  $y20+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.12 Causa básica de muerte '), 0, 0, 'l');

            $pdf->SetXY(7,  $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(64, 4, utf8_decode(''), 0, 'l');
            $pdf->SetXY(73,  $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'l');
            $pdf->SetXY(78,  $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'l');
            $pdf->SetXY(83,  $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'l');
            $pdf->SetXY(88,  $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'l');

            $pdf->SetXY(95,  $y20+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.13 Nombre del profesional que diligenció la ficha'), 0, 0, 'l');

            $pdf->SetXY(97, $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(66, 4, utf8_decode(self::$medicoatiende->name.' '.self::$medicoatiende->apellido), 0, 'l');

            $pdf->SetXY(167,  $y20+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('3.14 Teléfono'), 0, 0, 'l');

            $pdf->SetXY(169, $y20+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(32, 4, utf8_decode('520-10-44'), 0, 'l');

            $pdf->Line(5, $y16+4, 5,  $y20+12); #LINA VERTICAL IZQUIERDA
            $pdf->Line(205, $y16+4, 205,  $y20+12); #LINEA VERTICAL DERECHA
            $pdf->Line(5, $y16+4, 205, $y16+4); #LINEA HORIZONTAL ARRIBA
            $pdf->Line(5,  $y20+12, 205,  $y20+12); #LINEA HORIZONTAL ABAJO
            // #linea 1
            $pdf->Line(91, $y16+4, 91, $y17+4); #LINA VERTICAL 1
            $pdf->Line(5, $y17+4, 205, $y17+4); #LINA HORIZONTAL 1
            // #LINEA 2
            $pdf->Line(5, $y18, 205, $y18); #LINA VERTICAL 1
            // #LINEA 3
            $pdf->Line(55, $y18, 55, $y19+12); #LINA VERTICAL 1
            $pdf->Line(111, $y18, 111, $y19+12); #LINA VERTICAL 2
            $pdf->Line(163, $y18, 163, $y19+12); #LINA VERTICAL 3
            $pdf->Line(5, $y19+12, 205, $y19+12); #LINA HORIZONTAL 1
            // #LINEA 4
            $pdf->Line(57, $y19+12, 57, $y20+4); #LINA VERTICAL 1
            $pdf->Line(102, $y19+12, 102, $y20+4); #LINA VERTICAL 2
            $pdf->Line(150, $y19+12, 150, $y20+4); #LINA VERTICAL 3
            $pdf->Line(5, $y20+4, 205, $y20+4); #LINA HORIZONTAL 1
            // #linea 5
            $pdf->Line(95, $y20+4, 95, $y20+12); #LINA VERTICAL 1
            $pdf->Line(166, $y20+4, 166, $y20+12); #LINA VERTICAL 2

            $y21 = $pdf->GetY();
            $pdf->SetXY(5,$y21+1);
            $pdf->SetFont('Arial', 'B', 9);
            $pdf->SetDrawColor(0,0,0);
            $pdf->SetFillColor(214, 214, 214);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(200, 4, utf8_decode('4. ESPACIO EXCLUSIVO PARA USO DE LOS ENTES TERRITORIALES'), 1, 0, 'C',1);
            $pdf->SetTextColor(0,0,0);
            $pdf->SetDrawColor(0,0,0);

            $y22 = $pdf->GetY();
            $pdf->SetXY(5, $y22+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('4.1 Seguimiento y clasificación final del caso'), 0, 0, 'l');

            $pdf->SetXY(7,$y22+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(10, $y22+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('0. No aplica'), 0, 0, 'l');

            $pdf->SetXY(7,$y22+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(10, $y22+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('3. Conf. por laboratorio'), 0, 0, 'l');

            $pdf->SetXY(37,$y22+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(40, $y22+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('4. Conf. Clínica'), 0, 0, 'l');

            $pdf->SetXY(37,$y22+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(40, $y22+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('5. Conf. nexo epidemiológico'), 0, 0, 'l');

            $pdf->SetXY(73,$y22+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(76, $y22+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('6. Descartado'), 0, 0, 'l');

            $pdf->SetXY(73,$y22+12);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(76, $y22+12);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('7. Otra actualización'), 0, 0, 'l');

            $pdf->SetXY(99,$y22+8);
            $pdf->SetFont('Arial', '', 4);
            $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
            $pdf->SetXY(102, $y22+8);
            $pdf->SetFont('Arial', 'B', 6);
            $pdf->Cell(30, 4, utf8_decode('D. Descartado por error de digitación'), 0, 0, 'l');

            $pdf->SetXY(150, $y22+4);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(30, 4, utf8_decode('4.2 Fecha de ajuste (dd/mm/aaaa)'), 0, 0, 'l');
            $pdf->SetXY(150,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(155,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(159, $y22+7);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(163,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(168,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(172, $y22+7);
            $pdf->SetFont('Arial', '', 14);
            $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');
            $pdf->SetXY(176,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(181,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(186,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
            $pdf->SetXY(191,$y22+8);
            $pdf->SetFont('Arial', '', 7);
            $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');


            $pdf->Line(5, $y21, 5, $y22+16); #LINA VERTICAL IZQUIERDA
            $pdf->Line(205, $y21, 205, $y22+16); #LINEA VERTICAL DERECHA
            $pdf->Line(5, $y21, 205, $y21); #LINEA HORIZONTAL ARRIBA
            $pdf->Line(5, $y22+16, 205, $y22+16); #LINEA HORIZONTAL ABAJO
    }

    public function cancerMenores18($pdf){
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(192, 6, utf8_decode('Ficha de notificación individual - Datos complementarios'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(192, 6, utf8_decode('Cod INS 115. Cáncer en menores de 18 años'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(192, 6, utf8_decode('FOR-R02.0000-075 V:02 2022-06-08'), 0, 0, 'C');

        $pdf->SetXY(5, 54);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(192, 4, utf8_decode('La ficha de notificación es para fines de vigilancia en salud pública y todas las entidades que participen en el proceso deben garantizar la confidencialidad de la información LEY 1273/09 y 1266/09'), 0, 0, 'l');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('RELACIÓN CON DATOS BÁSICOS'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y1 = $pdf->GetY();
        $pdf->SetXY(5, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(105, 4, utf8_decode('A. Nombres y apellidos del paciente'), 0, 0, 'l');
        $pdf->SetXY(6,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(105, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 0, 'l');
        $pdf->SetXY(95, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('B. Tipo de ID'), 0, 0, 'C');
        $pdf->SetXY(96,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Tipo_Doc), 0, 'l');
        $pdf->SetXY(155, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('C. Número de documento'), 0, 0, 'C');
        $pdf->SetXY(146,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');

        $y2 = $pdf->GetY();
        $pdf->Line(5, $y1, 5, $y2+1); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y1, 205, $y2+1); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y2+1, 205, $y2+1); #LINEA HORIZONTAL ABAJO
        $pdf->Line(90,$y1+5, 90, $y2+1);
        $pdf->Line(140,$y1+5, 140, $y2+1);

        $pdf->SetXY(5,$y2+3);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5.TIPO DE CÁNCER'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetXY(5, $y2+3);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(16, 4, utf8_decode('(marque con una X el grupo que corresponda según la presunción diagnónstica)'), 0, 0, 'l');

        $pdf->ln();
        $y3 = $pdf->GetY();
        $pdf->SetXY(5, $y3+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('5.1 Tipo de cáncer'), 0, 0, 'l');

        $pdf->SetXY(7,$y3+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y3+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('1. Leucemia linfoide aguda'), 0, 0, 'l');

        $pdf->SetXY(7,$y3+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y3+12);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('2. Leucemia mieloide aguda'), 0, 0, 'l');

        $pdf->SetXY(7,$y3+16);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y3+16);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('3. Otras leucemias'), 0, 0, 'l');

        $pdf->SetXY(7,$y3+20);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y3+20);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('4. Linfomas y neoplasias reticuloendoteliales'), 0, 0, 'l');

        $pdf->SetXY(7,$y3+24);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y3+24);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('5. Tumores del sistema nervioso central'), 0, 0, 'l');

        $pdf->SetXY(67,$y3+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y3+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('6. Neuroblastoma y otros tumores de células nerviosas periféricas'), 0, 0, 'l');

        $pdf->SetXY(67,$y3+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y3+12);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('7. Retinoblastoma'), 0, 0, 'l');

        $pdf->SetXY(67,$y3+16);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y3+16);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('8. Tumores renales'), 0, 0, 'l');

        $pdf->SetXY(67,$y3+20);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y3+20);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('9. Tumores hepáticos'), 0, 0, 'l');

        $pdf->SetXY(67,$y3+24);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y3+24);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('10. Tumores óseos malignos'), 0, 0, 'l');

        $pdf->SetXY(142,$y3+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(145, $y3+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('11. Sarcomas de tejidos blandos y extra óseos'), 0, 0, 'l');

        $pdf->SetXY(142,$y3+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(145, $y3+12);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('12. Tumores germinales trofoblásticos y otros gonadales'), 0, 0, 'l');

        $pdf->SetXY(142,$y3+16);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(145, $y3+16);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('13. Tumores epiteliales malignos y melanoma'), 0, 0, 'l');

        $pdf->SetXY(142,$y3+20);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(145, $y3+20);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('14. Otras neoplasias malignas no especificadas'), 0, 0, 'l');

        $pdf->ln();
        $y4 = $pdf->GetY();
        $pdf->Line(5, $y3, 5, $y4+4); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y3, 205, $y4+4); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y4+4, 205, $y4+4); #LINEA HORIZONTAL ABAJO
        $pdf->Line(65,$y3+1, 65, $y4+4);
        $pdf->Line(140,$y3+1, 140, $y4+4);

        $pdf->SetXY(5, $y4+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(50, 4, utf8_decode('5.2  Fecha de inicio de tratamiento (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(8,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(13,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(17, $y4+13);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(21,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(26,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(30, $y4+13);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(34,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(39,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(44,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(49,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');


        $pdf->SetXY(60, $y4+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(50, 4, utf8_decode('5.3 ¿Consulta actual por segunda neoplasia?'), 0, 'l');

        $pdf->SetXY(69,$y4+14);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(75, $y4+14);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('Si'), 0, 0, 'l');

        $pdf->SetXY(81,$y4+14);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(86, $y4+14);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('No'), 0, 0, 'l');

        $pdf->SetXY(100, $y4+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('5.4 ¿Consulta actual por recaída?'), 0, 0, 'l');

        $pdf->SetXY(114,$y4+14);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(120, $y4+14);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('Si'), 0, 0, 'l');

        $pdf->SetXY(128,$y4+14);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(5, 4, utf8_decode(''), 1, 'C');
        $pdf->SetXY(134, $y4+14);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('No'), 0, 0, 'l');

        $pdf->SetXY(155, $y4+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(40, 4, utf8_decode('5.5 Fecha de diagnóstico Inicial (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(157,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(162,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(166, $y4+13);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(170,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(175,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(179, $y4+13);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(183,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(188,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(193,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(198,$y4+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $y5 = $pdf->GetY();
        $pdf->Line(5, $y4, 5, $y5+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y4, 205, $y5+2); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y5+2, 205, $y5+2); #LINEA HORIZONTAL ABAJO
        $pdf->Line(58,$y4+4, 58, $y5+2);
        $pdf->Line(99,$y4+4, 99, $y5+2);
        $pdf->Line(153,$y4+4, 153, $y5+2);


        $pdf->SetXY(5,$y5+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('6. DATOS DE LABORATORIO - MÉTODOS DIAGNÓSTICOS'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->ln();
        $y5 = $pdf->GetY();
        $pdf->SetXY(5, $y5+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('6.1 Criterio de diagnóstico probable'), 0, 0, 'l');

        $pdf->SetXY(7,$y5+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y5+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Extendido de sangre periférica'), 0, 0, 'l');

        $pdf->SetXY(7,$y5+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y5+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Radiología diagnóstica'), 0, 0, 'l');

        $pdf->SetXY(77,$y5+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(80, $y5+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. Gammagrafía'), 0, 0, 'l');

        $pdf->SetXY(77,$y5+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(80, $y5+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('4. Marcadores tumorales'), 0, 0, 'l');

        $pdf->SetXY(127,$y5+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(130, $y5+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('5. Clínica sin otra ayuda diagnóstica'), 0, 0, 'l');

        $pdf->ln();
        $y6 = $pdf->GetY();
        $pdf->SetXY(5, $y6+7);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode('5.5 Fecha de diagnóstico Inicial (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(10,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(15,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(19, $y6+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(23,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(28,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(32, $y6+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(36,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(41,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(46,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(51,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->ln();
        $pdf->SetXY(120, $y6+7);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode('6.1.2 Fecha de resultado (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(122,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(127,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(131, $y6+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(135,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(140,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(144, $y6+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(148,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(153,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(158,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(163,$y6+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->ln();
        $y7 = $pdf->GetY();
        $pdf->SetXY(5, $y7);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(30, 4, utf8_decode('6.2 Criterio de confirmación del diagnóstico'), 0, 0, 'l');

        $pdf->SetXY(7,$y7+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y7+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Mielograma'), 0, 0, 'l');

        $pdf->SetXY(7,$y7+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y7+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Histopatología o citología de fluido corporal'), 0, 0, 'l');

        $pdf->SetXY(74,$y7+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(78, $y7+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. Inmunotipificación'), 0, 0, 'l');

        $pdf->SetXY(74,$y7+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(78, $y7+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('4. Criterio médico especializado'), 0, 0, 'l');

        $pdf->SetXY(124,$y7+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(128, $y7+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('5. Certificado de defunción'), 0, 0, 'l');

        $pdf->SetXY(124,$y7+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(128, $y7+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('7. Citogenética'), 0, 0, 'l');

        $pdf->SetXY(167,$y7+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(170, $y7+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('8. Radiología diagnóstica'), 0, 0, 'l');

        $pdf->ln();
        $y8 = $pdf->GetY();
        $pdf->SetXY(5, $y8+7);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode('6.2.1 Fecha de toma (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(10,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(15,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(19, $y8+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(23,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(28,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(32, $y8+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(36,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(41,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(46,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(51,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->ln();
        $pdf->SetXY(120, $y8+7);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode('6.2.2 Fecha de resultado (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(122,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(127,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(131, $y8+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(135,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(140,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(144, $y8+12);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(148,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(153,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(158,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(163,$y8+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $y9 = $pdf->GetY();
        $pdf->Line(5, $y5, 5, $y9+2); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y5, 205, $y9+2); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y9+2, 205, $y9+2); #LINEA HORIZONTAL ABAJO
        $pdf->Line(5,$y7-1, 205, $y7-1);

    }

    public function cancerMama($pdf){

        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(192, 6, utf8_decode('Ficha de notificación individual - Datos complementarios'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(192, 6, utf8_decode('Cod INS 155. Cáncer de la mama y cuello uterino'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(192, 6, utf8_decode('FOR-R02.0000-075 V:02 2022-06-08'), 0, 0, 'C');

        $pdf->SetXY(5, 54);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(192, 4, utf8_decode('La ficha de notificación es para fines de vigilancia en salud pública y todas las entidades que participen en el proceso deben garantizar la confidencialidad de la información LEY 1273/09 y 1266/09'), 0, 0, 'l');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('RELACIÓN CON DATOS BÁSICOS'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y1 = $pdf->GetY();
        $pdf->SetXY(5, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(105, 4, utf8_decode('A. Nombres y apellidos del paciente'), 0, 0, 'l');
        $pdf->SetXY(6,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(105, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 0, 'l');
        $pdf->SetXY(95, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('B. Tipo de ID'), 0, 0, 'C');
        $pdf->SetXY(96,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Tipo_Doc), 0, 'l');
        $pdf->SetXY(155, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('C. Número de documento'), 0, 0, 'C');
        $pdf->SetXY(146,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');

        $y2 = $pdf->GetY();
        $pdf->Line(5, $y1, 5, $y2+1); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y1, 205, $y2+1); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y2+1, 205, $y2+1); #LINEA HORIZONTAL ABAJO
        $pdf->Line(90,$y1+5, 90, $y2+1);
        $pdf->Line(140,$y1+5, 140, $y2+1);

        $pdf->SetXY(5,$y2+3);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->MultiCell(200, 3, utf8_decode('Definición de caso:
Cancer de mama: Mujer u hombre con diagnóstico de carcinoma ductal in situ, lobulillar in situ o carcinoma infiltrante por biopsia de acuerdo con la clasificación CIE-10* C 50.0-C50.9.
Cancer de cuello uterino: Mujer con diagnóstico de LEI AG (NIC II displasia moderada, NIC III displasia severa y carcinoma in situ), carcinoma infiltrante o anormalidades en células glandulares por biopsia de acuerdo con el sistema Bethesda 2001 y los códigos CIE-10* C 53-C55. '), 1, 'l',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5.1. Tipo de cáncer'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(7,$y3+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(10, $y3+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. CA Mama'), 0, 0, 'l');

        $pdf->SetXY(37,$y3+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(40, $y3+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. CA Cuello uterino'), 0, 0, 'l');

        $pdf->SetXY(77,$y3+8);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(80, $y3+8);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. Ambos'), 0, 0, 'l');

        $y4 = $pdf->GetY();
        $pdf->Line(5, $y3+3, 5, $y4+4); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y3+3, 205, $y4+4); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y4+4, 205, $y4+4); #LINEA HORIZONTAL ABAJO

        $pdf->SetXY(5,$y4+6);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5.2. Examen de confirmación diagnóstica de cáncer de mama'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(5, $y4+13);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode('5.2.1 Fecha de procedimiento (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(10,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(15,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(19, $y4+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(23,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(28,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(32, $y4+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(36,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(41,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(46,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(51,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->ln();
        $pdf->SetXY(120, $y4+13);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(40, 4, utf8_decode('5.2.2 Fecha resultado (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(122,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(127,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(131, $y4+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(135,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(140,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(144, $y4+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(148,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(153,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(158,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(163,$y4+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->SetXY(5,$y4+25);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.2.3. Resultado biopsia'), 0, 0, 'L');

        $pdf->SetXY(67,$y4+25);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y4+25);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Carcinoma ductal'), 0, 0, 'l');

        $pdf->SetXY(107,$y4+25);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(110, $y4+25);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Carcinoma lobulillar'), 0, 0, 'l');

        $pdf->SetXY(5,$y4+35);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.2.3.1 Grado histopatológico'), 0, 0, 'L');

        $pdf->SetXY(67,$y4+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y4+35);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. In-situ'), 0, 0, 'l');

        $pdf->SetXY(97,$y4+35);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y4+35);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Infiltrante'), 0, 0, 'l');

        $pdf->SetXY(127,$y4+35);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(130, $y4+35);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. No indicado'), 0, 0, 'l');

        $pdf->ln();
        $y5 = $pdf->GetY();
        $pdf->SetXY(5,$y5+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5.3. Examen de confirmación diagnóstica de cáncer de cuello uterino'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);


        $pdf->SetXY(5, $y5+13);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 4, utf8_decode('5.3.1 Fecha de toma de muestra (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(10,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(15,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(19, $y5+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(23,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(28,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(32, $y5+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(36,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(41,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(46,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(51,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->ln();
        $pdf->SetXY(77, $y5+13);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 4, utf8_decode('5.3.2. Fecha de resultado (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(80,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(85,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(89, $y5+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(93,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(98,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(102, $y5+16);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(106,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(111,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(116,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(121,$y5+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $pdf->SetXY(140, $y5+13);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->Cell(30, 4, utf8_decode('5.3.3 Biopsia de exocervix y zona de transformación'), 0, 0, 'l');

        $pdf->SetXY(165,$y5+17);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(168, $y5+17);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('Si'), 0, 0, 'l');

        $pdf->SetXY(172,$y5+17);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(175, $y5+17);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(30, 4, utf8_decode('No'), 0, 0, 'l');

        $pdf->SetXY(5,$y5+23);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.3.4.1 Resultado biopsia endocervix'), 0, 0, 'L');

        $pdf->SetXY(67,$y5+23);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y5+23);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. LEI AG'), 0, 0, 'l');

        $pdf->SetXY(97,$y5+23);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y5+23);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Carcinoma escamocelular'), 0, 0, 'l');

        $pdf->SetXY(5,$y5+28);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.3.4.2 Grado histopatológico'), 0, 0, 'L');

        $pdf->SetXY(67,$y5+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y5+28);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. In-situ'), 0, 0, 'l');

        $pdf->SetXY(97,$y5+28);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y5+28);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Infiltrante'), 0, 0, 'l');

        $pdf->SetXY(127,$y5+28);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(130, $y5+28);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. No indicado'), 0, 0, 'l');

        $pdf->SetXY(5,$y5+34);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.3.4 Biopsia de endocervix'), 0, 0, 'L');

        $pdf->SetXY(67,$y5+34);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y5+34);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Si'), 0, 0, 'l');

        $pdf->SetXY(97,$y5+34);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y5+34);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. No'), 0, 0, 'l');

        $pdf->SetXY(5,$y5+40);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.3.4.1 Resultado biopsia endocervix'), 0, 0, 'L');

        $pdf->SetXY(10,$y5+46);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('Adenocarcinoma'), 0, 0, 'L');

        $pdf->SetXY(67,$y5+46);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y5+46);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Positivo'), 0, 0, 'l');

        $pdf->SetXY(97,$y5+46);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y5+46);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Negativo'), 0, 0, 'l');

        $pdf->SetXY(5,$y5+53);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.3.4.2 Grado histopatológico'), 0, 0, 'L');

        $pdf->SetXY(67,$y5+53);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y5+53);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. In-situ'), 0, 0, 'l');

        $pdf->SetXY(97,$y5+53);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y5+53);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Infiltrante'), 0, 0, 'l');

        $pdf->SetXY(127,$y5+53);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(130, $y5+53);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. No indicado'), 0, 0, 'l');

        $y6 = $pdf->GetY();
        $pdf->Line(5, $y4+10, 5, $y6+10); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y4+10, 205, $y6+10); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y4+23, 205, $y4+23);
        $pdf->Line(5, $y4+33, 205, $y4+33);
        $pdf->Line(5, $y4+43, 205, $y4+43);
        $pdf->Line(5, $y5+22, 205, $y5+22);
        $pdf->Line(5, $y5+33, 205, $y5+33);
        $pdf->Line(5, $y5+39, 205, $y5+39);




        $pdf->SetXY(5,$y6+8);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5.4. Seguimiento'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(5,$y6+17);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.4.1. Tratamiento inicial del tumor'), 0, 0, 'L');

        $pdf->SetXY(67,$y6+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y6+17);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Positivo'), 0, 0, 'l');

        $pdf->SetXY(97,$y6+17);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y6+17);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Negativo'), 0, 0, 'l');

        $pdf->SetXY(5,$y6+23);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('Seleccione uno o varios de los tipos de tratamiento'), 0, 0, 'L');

        $pdf->SetXY(5,$y6+30);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.4.1. Tratamiento inicial del tumor'), 0, 0, 'L');

        $pdf->SetXY(67,$y6+30);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y6+30);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('Radioterapia'), 0, 0, 'l');

        $pdf->SetXY(67,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y6+35);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('Quirúrgico'), 0, 0, 'l');

        $pdf->SetXY(67,$y6+40);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70, $y6+40);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('Quimioterapia'), 0, 0, 'l');

        $pdf->SetXY(97,$y6+30);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y6+30);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('Hormonoterapia'), 0, 0, 'l');

        $pdf->SetXY(97,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y6+35);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('Cuidados paliativos'), 0, 0, 'l');

        $pdf->SetXY(97,$y6+40);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(100, $y6+40);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('Inmunoterapia'), 0, 0, 'l');

        $pdf->SetXY(140, $y6+30);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(40, 4, utf8_decode('5.3.2. Fecha de resultado (dd/mm/aaaa)'), 0, 'l');

        $pdf->SetXY(140,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(145,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(149, $y6+34);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(153,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(158,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(162, $y6+34);
        $pdf->SetFont('Arial', '', 14);
        $pdf->Cell(5, 6, utf8_decode('/'), 0, 0, 'C');

        $pdf->SetXY(166,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(171,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(176,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');
        $pdf->SetXY(181,$y6+35);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 4, '', 1, 'C');

        $y7 = $pdf->GetY();
        $pdf->Line(5, $y6+10, 5, $y7+10); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y6+10, 205, $y7+10); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y6+23, 205, $y6+23);
        $pdf->Line(5, $y6+49, 205, $y6+49);
    }
    public function dengue($pdf){
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(192, 6, utf8_decode('Ficha de notificación individual - Datos complementarios'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(192, 6, utf8_decode('Cod INS 210. Dengue | Cod INS 220. Dengue grave | Cod INS 580. Mortalidad por dengue'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(192, 6, utf8_decode('FOR-R02.0000-075 V:02 2022-06-08'), 0, 0, 'C');

        $pdf->SetXY(5, 54);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(192, 4, utf8_decode('La ficha de notificación es para fines de vigilancia en salud pública y todas las entidades que participen en el proceso deben garantizar la confidencialidad de la información LEY 1273/09 y 1266/09'), 0, 0, 'l');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+4);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('RELACIÓN CON DATOS BÁSICOS'), 1, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y1 = $pdf->GetY();
        $pdf->SetXY(5, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(105, 4, utf8_decode('A. Nombres y apellidos del paciente'), 0, 0, 'l');
        $pdf->SetXY(6,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(105, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 0, 'l');
        $pdf->SetXY(95, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('B. Tipo de ID'), 0, 0, 'C');
        $pdf->SetXY(96,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Tipo_Doc), 0, 'l');
        $pdf->SetXY(155, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('C. Número de documento'), 0, 0, 'C');
        $pdf->SetXY(146,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');

        $y2 = $pdf->GetY();
        $pdf->Line(5, $y1, 5, $y2+1); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y1, 205, $y2+1); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y2+1, 205, $y2+1); #LINEA HORIZONTAL ABAJO
        $pdf->Line(90,$y1+5, 90, $y2+1);
        $pdf->Line(140,$y1+5, 140, $y2+1);

        $pdf->SetXY(5,$y2+3);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->MultiCell(200, 3, utf8_decode('Los casos probables y confirmados de dengue deben notificarse semanalmente de acuerdo con la estructura y contenidos mínimos establecidos, en el subsistema de información para la vigilancia de los eventos de interés en salud pública. La notificación de los casos de dengue grave y mortalidad por dengue se exige desde su clasificación como probables, y en el nivel local es inmediata.'), 1, 'l',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5. DATOS ESPECÍFICOS'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(5,$y3+8);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(200, 5, utf8_decode('5.1 ¿Desplazamiento en los últimos 15 días?'), 0, 0, 'L');

        $pdf->SetXY(30,$y3+14);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(35, $y3+14);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Si'), 0, 0, 'l');

        $pdf->SetXY(30,$y3+19);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(35, $y3+19);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. No'), 0, 0, 'l');

        $pdf->SetXY(80,$y3+12);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(60, 5, utf8_decode('5.1.1 País/Municipio/departamento al que se desplazó'), 0, 'L');

        $pdf->SetXY(145,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(152,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(159,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(152,$y3+8);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(60, 4, utf8_decode('País'), 0, 'L');

        $pdf->SetXY(168,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(175,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(150, $y3+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(50, 4, utf8_decode('Departamento'), 0, 0, 'C');

        $pdf->SetXY(184,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(191,$y3+13);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(7, 8, '', 1, 'C');
        $pdf->SetXY(189, $y3+8);
        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(5, 4, utf8_decode('Municipio'), 0, 0, 'C');

        $pdf->SetXY(5,$y3+24);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->MultiCell(110, 5, utf8_decode('5.2. ¿Algún familiar o conviviente ha tenido sintomatología de dengue en los últimos 15 días?'), 0, 'L');

        $pdf->SetXY(125,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(130, $y3+28);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1.Si'), 0, 0, 'l');

        $pdf->SetXY(140,$y3+28);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(145, $y3+28);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2.No'), 0, 0, 'l');

        $pdf->SetXY(155,$y3+28);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(160, $y3+28);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3.Desconocido'), 0, 0, 'l');

        $pdf->SetXY(5, $y3+36);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(105, 4, utf8_decode('5.3 Nombre del establecimiento donde estudia o trabaja:'), 0, 0, 'l');
        $pdf->SetXY(95,$y3+36);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(105, 4, utf8_decode(''), 0, 'l');

        $y4 = $pdf->GetY();
        $pdf->Line(5, $y3+7, 5, $y4+1); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y3+7, 205, $y4+1); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y4+1, 205, $y4+1); #LINEA HORIZONTAL ABAJO
        $pdf->Line(77,$y3+7, 77, $y3+23);
        $pdf->Line(5,$y3+23, 205, $y3+23);
        $pdf->Line(5,$y3+35, 205, $y3+35);

    }


    public function EnfermedadTransmitidaPorAlimentos($pdf){
        $pdf->AddPage();
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(192, 5, utf8_decode('Ficha de notificación individual - Datos complementarios'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(192, 5, utf8_decode('Cod INS 355. Enfermedad transmitida por alimentos o agua (ETA)'), 0, 0, 'C');
        $pdf->ln();
        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(192, 5, utf8_decode('FOR-R02.0000-075 V:02 2022-06-08'), 0, 0, 'C');

        $pdf->SetXY(5, 47);
        $pdf->SetFont('Arial', '', 6.5);
        $pdf->Cell(192, 4, utf8_decode('La ficha de notificación es para fines de vigilancia en salud pública y todas las entidades que participen en el proceso deben garantizar la confidencialidad de la información LEY 1273/09 y 1266/09'), 0, 0, 'l');

        $y = $pdf->GetY();
        $pdf->SetXY(5,$y+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('RELACIÓN CON DATOS BÁSICOS'), 0, 0, 'L',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $y1 = $pdf->GetY();
        $pdf->SetXY(5, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(105, 4, utf8_decode('A. Nombres y apellidos del paciente'), 0, 0, 'l');
        $pdf->SetXY(6,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(105, 4, utf8_decode(self::$paciente->Primer_Nom.(self::$paciente->SegundoNom?' '.self::$paciente->SegundoNom:'').' '.self::$paciente->Primer_Ape.' '.self::$paciente->Segundo_Ape), 0, 'l');
        $pdf->SetXY(95, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('B. Tipo de ID'), 0, 0, 'C');
        $pdf->SetXY(96,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Tipo_Doc), 0, 'l');
        $pdf->SetXY(155, $y1+6);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(16, 4, utf8_decode('C. Número de documento'), 0, 0, 'C');
        $pdf->SetXY(146,$y1+10);
        $pdf->SetFont('Arial', '', 9);
        $pdf->MultiCell(100, 4, utf8_decode(self::$paciente->Num_Doc), 0, 'l');

        $y2 = $pdf->GetY();
        $pdf->Line(5, $y1, 5, $y2+1); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y1, 205, $y2+1); #LINEA VERTICAL DERECHA
        $pdf->Line(5, $y2+1, 205, $y2+1); #LINEA HORIZONTAL ABAJO
        $pdf->Line(90,$y1+5, 90, $y2+1);
        $pdf->Line(140,$y1+5, 140, $y2+1);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+2);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('5. DATOS CLÍNICOS'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(5,$y3+8);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(200, 5, utf8_decode('5.1 Signos y síntomas'), 0, 0, 'L');
        $pdf->SetXY(43,$y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(48, $y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('2. Náuseas'), 0, 0, 'l');

        $pdf->SetXY(43,$y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(48, $y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('3. Vómito '), 0, 0, 'l');

        $pdf->SetXY(43,$y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(48, $y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('4. Diarrea '), 0, 0, 'l');

        $pdf->SetXY(43,$y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(48, $y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('5. Fiebre  '), 0, 0, 'l');

        $pdf->SetXY(68,$y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(72, $y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('6. Calambres abdominales'), 0, 0, 'l');

        $pdf->SetXY(68,$y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(72, $y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('7. Cefalea '), 0, 0, 'l');

        $pdf->SetXY(68,$y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(72, $y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('8. Deshidratación '), 0, 0, 'l');

        $pdf->SetXY(68,$y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(72, $y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('9. Cianosis  '), 0, 0, 'l');

        $pdf->SetXY(105,$y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(110, $y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('10. Mialgias'), 0, 0, 'l');

        $pdf->SetXY(105,$y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(110, $y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('11. Artralgias '), 0, 0, 'l');

        $pdf->SetXY(105,$y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(110, $y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('12. Mareo '), 0, 0, 'l');

        $pdf->SetXY(105,$y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(110, $y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('13. Lesiones maculopapulares  '), 0, 0, 'l');

        $pdf->SetXY(147,$y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(150, $y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('14. Escálofrios'), 0, 0, 'l');

        $pdf->SetXY(147,$y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(150, $y3+12);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('16. Parestesias '), 0, 0, 'l');

        $pdf->SetXY(147,$y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(150, $y3+16);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('17. Sialorrea '), 0, 0, 'l');

        $pdf->SetXY(147,$y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(150, $y3+20);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('18. Espasmos musculares  '), 0, 0, 'l');

        $pdf->SetXY(182,$y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->MultiCell(2.5, 2.5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(185, $y3+8);
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(30, 4, utf8_decode('19. Otros'), 0, 0, 'l');


        $pdf->SetXY(5,$y3+26);
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->MultiCell(110, 5, utf8_decode('5.2. Si marcó otros. Registre cuál '), 0, 'L');

        $pdf->SetXY(8,$y3+29);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(110, 5, utf8_decode('_________________________________________'), 0, 'L');

        $pdf->SetXY(90,$y3+26);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell (110, 5, utf8_decode('5.3. Hora de inicio de los síntomas '), 0, 'L');
        $pdf->SetXY(142,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(147,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(155,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(172,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(177,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(185,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');

        $y4 = $pdf->GetY();
        $pdf->Line(5, $y3+7, 5, $y4+3); #LINA VERTICAL IZQUIERDA
        $pdf->Line(205, $y3+7, 205, $y4+3); #LINEA VERTICAL DERECHA
        $pdf->Line(5,$y3+25, 205, $y3+25);
        $pdf->Line(5,$y3+34, 205, $y3+34);
        $pdf->Line(87,$y3+25, 87, $y3+34);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('6. DATOS DE LA EXPOSICIÓN'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(6, $y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(105, 4, utf8_decode('6.1 Alimentos ingeridos el dia de los síntomas'), 0, 0, 'l');
        $pdf->SetXY(80, $y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(105, 4, utf8_decode('6.2 Alimentos ingeridos el dia anterior'), 0, 0, 'l');
        $pdf->SetXY(145, $y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(105, 4, utf8_decode('6.2 Alimentos ingeridos dos dias antes'), 0, 0, 'l');
        #Horas y minutos
        $pdf->SetXY(53,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(48,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(48,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(65,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(60,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(60,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(57,$y3+21);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(15,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(15,$y3+36);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');

        #LINEAS CUADRO EXTERNO
        #Horizontales
        $pdf->Line(5,$y3+11, 205, $y3+11);
        $pdf->Line(5,$y3+94, 205, $y3+94);
        #verticales
        $pdf->Line(5, $y3+11, 5, $y4+94);
        $pdf->Line(205, $y3+11, 205, $y4+94);

        #LINEAS CUADRO INTERNO
        #HORIZONTALES
        $pdf->Line(7,$y3+18, 72, $y3+18);
        $pdf->Line(9,$y3+27, 47, $y3+27); #nombre alimento
        $pdf->Line(9,$y3+36, 70, $y3+36);
        $pdf->Line(7,$y3+42, 72, $y3+42);
        #verticales
        $pdf->Line(7, $y3+18, 7, $y4+42);
        $pdf->Line(72, $y3+18, 72, $y4+42);

        #segundo cuadro a la derecha
        #Horas y minutos
        $pdf->SetXY(116,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(121,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(118,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(134,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(129,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(128,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(126,$y3+21);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(83,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(84,$y3+36);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(75,$y3+18, 140, $y3+18);
        $pdf->Line(77,$y3+27, 115, $y3+27); #nombre alimento
        $pdf->Line(77,$y3+36, 138, $y3+36);
        $pdf->Line(75,$y3+42, 140, $y3+42);
        #verticales
        $pdf->Line(75, $y3+18, 75, $y4+42);
        $pdf->Line(140, $y3+18, 140, $y4+42);

        #tercera columna
        #Horas y minutos
        $pdf->SetXY(179,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(184,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(181,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(192,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(197,$y3+20);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(191,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(189,$y3+21);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(147,$y3+27);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(147,$y3+36);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(142,$y3+18, 203, $y3+18);
        $pdf->Line(144,$y3+27, 178, $y3+27); #nombre alimento
        $pdf->Line(144,$y3+36, 202, $y3+36);
        $pdf->Line(142,$y3+42, 203, $y3+42);
        #verticales
        $pdf->Line(142, $y3+18, 142, $y4+42);
        $pdf->Line(203, $y3+18, 203, $y4+42);

        #segunda fila
        #Horas y minutos
        $pdf->SetXY(53,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(48,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(48,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(65,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(60,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(60,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(57,$y3+47);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(15,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(15,$y3+61);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(7,$y3+44, 72, $y3+44);
        $pdf->Line(9,$y3+52, 47, $y3+52); #nombre alimento
        $pdf->Line(9,$y3+61, 70, $y3+61);
        $pdf->Line(7,$y3+67, 72, $y3+67);
        #verticales
        $pdf->Line(7, $y3+44, 7, $y4+67);
        $pdf->Line(72, $y3+44, 72, $y4+67);

        #segundo cuadro a la derecha
        #Horas y minutos
        $pdf->SetXY(116,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(121,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(118,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(134,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(129,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(128,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(126,$y3+47);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(83,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(84,$y3+61);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(75,$y3+44, 140, $y3+44);
        $pdf->Line(77,$y3+52, 115, $y3+52); #nombre alimento
        $pdf->Line(77,$y3+61, 138, $y3+61);
        $pdf->Line(75,$y3+67, 140, $y3+67);
        #verticales
        $pdf->Line(75, $y3+44, 75, $y4+67);
        $pdf->Line(140, $y3+44, 140, $y4+67);

        #tercera columna
        #Horas y minutos
        $pdf->SetXY(179,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(184,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(181,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(192,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(197,$y3+45);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(191,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(189,$y3+47);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(147,$y3+52);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(147,$y3+61);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(142,$y3+44, 203, $y3+44);
        $pdf->Line(144,$y3+52, 178, $y3+52); #nombre alimento
        $pdf->Line(144,$y3+61, 202, $y3+61);
        $pdf->Line(142,$y3+67, 203, $y3+67);
        #verticales
        $pdf->Line(142, $y3+44, 142, $y4+67);
        $pdf->Line(203, $y3+44, 203, $y4+67);

        #tercera fila
        #Horas y minutos
        $pdf->SetXY(53,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(48,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(48,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(65,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(60,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(60,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(57,$y3+72);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(15,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(15,$y3+86);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(7,$y3+69, 72, $y3+69);
        $pdf->Line(9,$y3+77, 47, $y3+77); #nombre alimento
        $pdf->Line(9,$y3+86, 70, $y3+86);
        $pdf->Line(7,$y3+92, 72, $y3+92);
        #verticales
        $pdf->Line(7, $y3+69, 7, $y4+92);
        $pdf->Line(72, $y3+69, 72, $y4+92);
        #segundo cuadro a la derecha
        #Horas y minutos
        $pdf->SetXY(116,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(121,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(118,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(134,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(129,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(128,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(126,$y3+72);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(83,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(84,$y3+86);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(75,$y3+69, 140, $y3+69);
        $pdf->Line(77,$y3+77, 115, $y3+77); #nombre alimento
        $pdf->Line(77,$y3+86, 138, $y3+86);
        $pdf->Line(75,$y3+92, 140, $y3+92);
        #verticales
        $pdf->Line(75, $y3+69, 75, $y4+92);
        $pdf->Line(140, $y3+69, 140, $y4+92);

        #tercera columna
        #Horas y minutos
        $pdf->SetXY(179,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(184,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(181,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Hora'), 0, 'L');
        $pdf->SetXY(192,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(197,$y3+70);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 6, '', 1, 'C');
        $pdf->SetXY(191,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(60, 4, utf8_decode('Minutos'), 0, 'L');
        $pdf->SetXY(189,$y3+72);
        $pdf->SetFont('Arial', '', 18);
        $pdf->MultiCell(60, 4, utf8_decode(':'), 0, 'L');
        $pdf->SetXY(147,$y3+77);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Nombre del alimento'), 0, 'L');
        $pdf->SetXY(147,$y3+86);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(50, 4, utf8_decode('Lugar del consumo'), 0, 'L');
        #Horizontales
        $pdf->Line(142,$y3+69, 203, $y3+69);
        $pdf->Line(144,$y3+77, 178, $y3+77); #nombre alimento
        $pdf->Line(144,$y3+86, 202, $y3+86);
        $pdf->Line(142,$y3+92, 203, $y3+92);
        #verticales
        $pdf->Line(142, $y3+69, 142, $y4+92);
        $pdf->Line(203, $y3+69, 203, $y4+92);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+5);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('7. DATOS DE LABORATORIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(8,$y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(210, 5, utf8_decode('7.1 Nombre del lugar de consumo implicado ____________________________________________________________________________________'), 0, 'L');
        $pdf->SetXY(8,$y3+16);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(210, 5, utf8_decode('7.2 Dirección _______________________________________________________________________________________________________________'), 0, 'L');
        #LINEAS CUADRO
        #Horizontales
        $pdf->Line(5,$y3+11, 205, $y3+11);
        $pdf->Line(5,$y3+21, 205, $y3+21);
        #verticales
        $pdf->Line(5, $y3+11, 5, $y3+21);
        $pdf->Line(205, $y3+11, 205, $y3+21);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+1);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('8. ASOCIACIÓN CON BROTE'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(8,$y3+7);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(210, 5, utf8_decode('8.1 ¿Caso asociado a un brote?'), 0, 'L');

        $pdf->SetXY(16,$y3+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(20, $y3+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Si'), 0, 0, 'l');

        $pdf->SetXY(40,$y3+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(44, $y3+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. No'), 0, 0, 'l');


        $pdf->SetXY(85,$y3+7);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(210, 5, utf8_decode('8.2 Caso captado por'), 0, 'L');

        $pdf->SetXY(78,$y3+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(82, $y3+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1.UPGD'), 0, 0, 'l');

        $pdf->SetXY(106,$y3+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(110, $y3+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2.Búsqueda'), 0, 0, 'l');

        $pdf->SetXY(150,$y3+7);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(210, 5, utf8_decode('8.3 Relación con la exposición'), 0, 'L');

        $pdf->SetXY(148,$y3+12);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(152, $y3+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1. Comensal'), 0, 0, 'l');

        $pdf->SetXY(175,$y3+12);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(179     , $y3+12);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2. Manipulador'), 0, 0, 'l');

        #LINEAS CUADRO
        #Horizontales
        $pdf->Line(5,$y3+7, 205, $y3+7);
        $pdf->Line(5,$y3+17, 205, $y3+17);
        #verticales
        $pdf->Line(5, $y3+7, 5, $y3+17);
        $pdf->Line(205, $y3+7, 205, $y3+17);
        $pdf->Line(70, $y3+7,70, $y3+17);
        $pdf->Line(140, $y3+7, 140, $y3+17);

        $y3 = $pdf->GetY();
        $pdf->SetXY(5,$y3+6);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(200, 5, utf8_decode('9. LABORATORIO'), 1, 0, 'C',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);

        $pdf->SetXY(8,$y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(50, 5, utf8_decode('9.1 ¿Se recolectó muestra biológica?'), 0, 'L');

        $pdf->SetXY(26,$y3+18);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(30, $y3+18);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('1.Si'), 0, 0, 'l');

        $pdf->SetXY(40,$y3+18);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(44, $y3+18);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('2.No'), 0, 0, 'l');

        $pdf->SetXY(63,$y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(90, 5, utf8_decode('9.2 Tipo de muestra'), 0, 'L');

        $pdf->SetXY(64,$y3+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(67, $y3+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('1. Heces'), 0, 0, 'l');

        $pdf->SetXY(85,$y3+17);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(88, $y3+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('2. Vómito'), 0, 0, 'l');

        $pdf->SetXY(105,$y3+17);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(108, $y3+17);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('3. Sangre'), 0, 0, 'l');

        $pdf->SetXY(130,$y3+17);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(3, 3, utf8_decode(''), 1, 'C');
        $pdf->SetXY(133, $y3+17);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(30, 4, utf8_decode('4. Otro'), 0, 0, 'l');

        $pdf->SetXY(146,$y3+12);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(70, 5, utf8_decode('9.2.1 ¿Cuál?  ____________________________________'), 0, 'L');

        #LINEAS CUADRO
        #Horizontales
        $pdf->Line(5,$y3+12, 205, $y3+12);
        $pdf->Line(5,$y3+22, 205, $y3+22);
        $pdf->Line(5,$y3+34, 205, $y3+34);
        #verticales
        $pdf->Line(5, $y3+12, 5, $y3+34);
        $pdf->Line(205, $y3+12, 205, $y3+34);
        $pdf->Line(60, $y3+12, 60, $y3+22);
        $pdf->Line(50, $y3+22, 50, $y3+34);
        $pdf->Line(85, $y3+22, 85, $y3+34);
        $pdf->Line(120, $y3+22, 120, $y3+34);
        $pdf->Line(160, $y3+22, 160, $y3+34);


        $pdf->SetXY(8,$y3+23);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(90, 5, utf8_decode('9.3 Agente identificado'), 0, 'L');

        $pdf->SetXY(25,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(30,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');

        $pdf->SetXY(15, $y3+29);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('Código'), 0, 0, 'l');
        $pdf->SetXY(35, $y3+29);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 4, utf8_decode('1'), 0, 0, 'l');

        $pdf->SetXY(50,$y3+23);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(90, 5, utf8_decode('9.4 Agente identificado'), 0, 'L');

        $pdf->SetXY(65,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(70,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');

        $pdf->SetXY(55, $y3+29);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('Código'), 0, 0, 'l');
        $pdf->SetXY(75, $y3+29);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 4, utf8_decode('2'), 0, 0, 'l');

        $pdf->SetXY(86,$y3+23);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(90, 5, utf8_decode('9.5 Agente identificado'), 0, 'L');

        $pdf->SetXY(101,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(106,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');

        $pdf->SetXY(90, $y3+29);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('Código'), 0, 0, 'l');
        $pdf->SetXY(111, $y3+29);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 4, utf8_decode('3'), 0, 0, 'l');

        $pdf->SetXY(121,$y3+23);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(90, 5, utf8_decode('9.6 Agente identificado'), 0, 'L');

        $pdf->SetXY(136,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');
        $pdf->SetXY(141,$y3+28);
        $pdf->SetFont('Arial', '', 7);
        $pdf->MultiCell(5, 5, utf8_decode(''), 1, 'C');

        $pdf->SetXY(126, $y3+29);
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(30, 4, utf8_decode('Código'), 0, 0, 'l');
        $pdf->SetXY(146, $y3+29);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(30, 4, utf8_decode('4'), 0, 0, 'l');

        $pdf->SetXY(162,$y3+23);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(50, 5, utf8_decode('Si marcó 77 otro: ¿Cuál otro?  __________________________'), 0, 'L');

        #LINEAS CUADRO
        #Horizontales
        $pdf->Line(5,$y3+35, 205, $y3+35);
        $pdf->Line(5,$y3+57, 205, $y3+57);
        #verticales
        $pdf->Line(5, $y3+35, 5, $y3+57);
        $pdf->Line(205, $y3+35, 205, $y3+57);

        $y2 = $pdf->GetY();
        $pdf->SetXY(6,$y2+3);
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetFillColor(214, 214, 214);
        $pdf->SetTextColor(0,0,0);
        $pdf->Cell(29, 20, utf8_decode(''), 1, 0, 'c',1);
        $pdf->SetTextColor(0,0,0);
        $pdf->SetDrawColor(0,0,0);
        $pdf->SetXY(10,$y2+3);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->MultiCell(30, 5, utf8_decode('Agentes'), 0, 'L');
        $pdf->SetXY(10,$y2+7);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(30, 5, utf8_decode('77. Otro                      78. Pendiente              79. No detectado'), 0, 'L');

        $pdf->SetXY(36,$y3+37);
        $pdf->SetFont('Arial', '', 4);
        $pdf->MultiCell(170, 3, utf8_decode('10Coliformes fecales, 20Coliformes totales, 30Bacillus cereus,40Bacillus anthracis, 50Staphylococcus aureus,60Streptococcus sp, 70Clostridium perfringens, 0Aeromonas hydrophila, 90Campylobacter jejuni , 113Escherichia coli,123Shigella sp, 130Salmonella spp, 140Salmonella Typhi, 150Salmonella Paratyphi, 160Clostridiumbotulinum170Vibrio sp, 180Vibrio parahaemolyticus, 190Brucella abortus, 200Mycobacterium bovis, 210Listeria monocytogenes, 220Proteus sp, 240Norovirus, 250Rotavirus, 260Parvovirus, 270Astrovirus, 280Adenovirus, 290Hepatitis A, 300Hepatitis E, 320Ascaris lumbricoides, 330Complejo Entamoeba histolytica/dispar, 340Fasciola hepática, 350Taenia saginata, 360Cyclospora, 370Giardia duodenalis, 380Taenia solium, 390Trichinella spiralis, 400Balantidium coli, 410Cryptosporidium, 420Isospora belli,430Trichuris trichiura, 440Uncinarias, 450Enterobius vermicularis, 460Strongyloides stercolaris, 470Hymenolepis nana, 480Hymenolepis diminuta, 490Dipylidium caninum, 500Entamoeba hartamanni, 510Entamoeba coli , 520Endolimax nana , 530Iodamoeba butschlii 540Chilomastix mesnili , 550Trichomonas hominis , 560Antimonio, 570Cadmio, 580Cobre, 590Fluoruro,600Plomo, 610Estaño 620Zinc, 630Nitritos o Nitratos, 640Cloruros, 650Hidroxido de sodio, 660Organofosforados, 670Carbamatos, 680Acido okadaico, 690Saxitoxina, 700Alcaloides, 710Hidrocarburo clorado, 720Mercurio, 73 Fostato de triortocresilo, 740 Glutamatomonosodico, 750Micotinato sóDico, 85- T-Cruzi, 86-Toxinas de algas marinas'), 0, 'L');
    }


}
