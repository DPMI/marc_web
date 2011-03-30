<?
require("config.inc");

?>

<head>
<title>Standardframsida - Blekinge Institute of Technolgy</title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">

<meta HTTP-EQUIV="Expires" CONTENT="Wen, 17 Mar 2004 23:59:59 GMT">
<meta NAME="KeyWords" CONTENT="university, education, research, applied information technology, applied IT, Karlskrona, Ronneby, Karlshamn, Blekinge, IT, Sweden">
<meta NAME="Description" CONTENT="DEFAULT FRAMSIDA Jag dr default framsidan. Dndra mig!">
<meta NAME="GENERATOR" CONTENT="Lotus Notes 6.x">
<meta NAME="Author" CONTENT="Blekinge Institute of Technology">
<meta http-equiv="content-language" content="en">
<meta name="rating" content="General">
<link REL="SHORTCUT ICON" HREF="/favicon.ico">
<link href="bth.css" rel="stylesheet" type="text/css">

<!-- Implementerat av IT & Nya Media vid Blekinge Tekniska Hvgskola -->

<script language="JavaScript" type="text/javascript">
<!-- 
document._domino_target = "_self";
function _doClick(v, o, t) {
  var url="/ht/twebeng.nsf!OpenDatabase&Click=" + v;
  if (o.href != null)
    o.href = url;
  else {
    if (t == null)
      t = document._domino_target;
    window.open(url, t);
  }

}
// -->
</script>
</head>

<? 
print $pageStyle;
?>


<form action=""><!-- Bvrjan sidan -->
<map name="link">
<area shape="circle" coords="25, 20, 18" href="http://www.bth.se/eng/">
<area shape="rect" coords="600, 20, 700, 41" href="http://www.bth.se/eng/">
</map>
<table width=700 border=0 align="center" cellpadding=0 cellspacing=0><tr><td><img src="lighthuvud.jpg" alt="" width="700" height="41" usemap="#link" border="0"></td></tr>
<tr><td width="700" style="background: #b9b3a7; border-style: none none solid none; border-bottom-width: 1px; border-color: #b9b3a7;">

<img SRC="header_image.php" WIDTH=700 HEIGHT=59 ALT="Internet Next Generation Analysis - <? print $projectName; ?>"></td></tr> 

</table>


</html>
