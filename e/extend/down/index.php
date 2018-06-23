<?php
require("../../class/connect.php");
if(!defined('InEmpireCMS'))
{
exit();
}
eCheckCloseMods('member');//关闭模块
$myuserid=(int)getcvar('mluserid');
$r=array();
$mhavelogin=0;
if($myuserid)
{
include("../../class/db_sql.php");
include("../../member/class/user.php");
$link=db_connect();
$empire=new mysqlquery();
$mhavelogin=1;
//数据
$myusername=RepPostVar(getcvar('mlusername'));
$myrnd=RepPostVar(getcvar('mlrnd'));
$qcklgr=qCheckLoginAuthstr();
if(!$qcklgr['islogin'])
{
EmptyEcmsCookie();
$mhavelogin=0;
}
else
{
$r=$empire->fetch1("select ".eReturnSelectMemberF('userid,username,checked')." from ".eReturnMemberTable()." where ".egetmf('userid')."='$myuserid' and ".egetmf('rnd')."='$myrnd' limit 1");
if(empty($r[userid])||$r[checked]==0)
{
EmptyEcmsCookie();
$mhavelogin=0;
}
}
}
if($mhavelogin==1)
{
$down='';
$id=(int)$_GET['id'];
$classid=(int)$_GET['classid'];
$stb=(int)$_GET['d'];
$r_pl=$empire->fetch1("select userid,classid,id,saytime from {$dbtbpre}enewspl_1 where classid='$classid' and id='$id' and userid='$r[userid]' and saytime>UNIX_TIMESTAMP()-12*3600 order by saytime desc limit 1");
if(empty($r_pl[userid]))
{
$down="<div class=\'reply-to-read\'><p><i class=\'far fa-comment-dots\'></i> ".$myusername." 会员: 你输入用户名 ".$myusername." 和登录密码<a href=\'#respond\' title=\'评论本文\'>「评论本文」</a>，即可获得下载地址（资源有限，地址12小时内有效）。请不要回复无意义内容！</p></div>";
}
else{
$down="<div class=\'reply-to-read\'><p><i class=\'far fa-comment-dots\'></i> ".$myusername." 会员: 你刚刚获得下载地址  <a href=\'https://www.wentong.org/e/extend/down/go2dow.php?classid=".$classid."&id=".$id."&d=".$stb."&g=9\'>/go2dow.php?g=9</a>（12小时内有效，过期请重新评论获取）。请不要回复无意义内容！</p></div>";
}
?>
document.writeln("<?=$down?>");
<?php
db_close();
$empire=null;
}
else
{
?>
document.writeln("<div class=\'reply-to-read\'><p><i class=\'far fa-comment-dots\'></i> 温馨提示: 隐藏内容需要输入「用户名、密码」<a href=\'#respond\' title=\'评论本文\'>「评论本文」</a>后查看。</p><p>未注册的，请<a href=\'/e/member/register/\' title=\'会员注册\'>「注册」</a>。请不要回复无意义内容！</p></div>");
<?php
}
?>
