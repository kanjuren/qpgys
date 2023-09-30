<?php
error_reporting(0);
$url='https://wwxl.lanzouw.com/iOqEX18pvgif';//蓝秦的分享地址
$pwd='';//有密码则输入。无密码留空
$out='https://sdk.cdn.aliyuncs.top/qpg_v1.0.3.9.apk';//当不为空时，解析失败自动302到这个地址
$bbb=getstrzj($url,"://","/");
$host='https://'.$bbb;

define('VS_', 300*1);
define('PATH', 'cache-lzy');
if (!file_exists(PATH)) {
mkdir(PATH, 0777, true);
}
$md5=md5($url);
$ep_file=PATH.'/'.$md5.'.ini';
$mp4url='';
if (!file_exists($ep_file) || filemtime($ep_file)+VS_ < time()){
	$u1=curlget($url);
	if($pwd==''){
		//$n1=getstrzj($u1,'</iframe>-->','</iframe>');
		//$n2=getstrzj($n1,'src="','"');
		$u1=str_replace("<!--<iframe","",$u1);
		$n1=getstrzj($u1,'<iframe','</iframe>');
		$n2=getstrzj($n1,'src="','"');//echo $n2;exit;
		if($n2){
			$u2=curlget($host.'/'.$n2);//exit($u2);
			//$u2=str_replace("//data","//",$u2);
			//$data=getstrzj($u2,'});','function(msg)').'}';//exit($data);
			$data=$u2;
			$action=getstrzj($data,"'action':'","'");//echo $action;exit;
			//$u2=str_replace("ajaxdata = 'cf'","",$u2);
			$signs=getstrzj($data,"var ajaxdata = '","'");
			$sign=getstrzj($data,"'sign':'","'");
			$ves=getstrzj($data,"'ves':"," }");
			$websign=getstrzj($data,"var iucjdsd = '","'");
			$websignkey=getstrzj($data,"var aihidcms = '","'");
			$post='action='.$action.'&signs='.$signs.'&sign='.$sign.'&ves='.$ves.'&websign='.$websign.'&websignkey='.$websignkey;
			//echo $post;
			//exit;
			$u3=curlget($host.'/ajaxm.php','',$post,'gzip',$host.$n2);
			
			//echo $u3;exit;
			$json = json_decode($u3 ,1);
			$downhost=$json['dom'];
			$downend=$json['url'];
			if($downend){$mp4url=$downhost.'/file/'.$downend;file_put_contents($ep_file,$mp4url);}
		}
	}else{
		//$u1=str_replace("//data","//",$u1)
		//$data=getstrzj($u1,"data : '","'");
		$u1=getstrzj($u1,"var skdklds = '","'");//exit($u1);
		if($u1){
			$post='action=downprocess&sign='.$u1.'&p='.$pwd;
			$u3=curlget($host.'/ajaxm.php','',$post,'gzip',$url);
			$json = json_decode($u3 ,1);
			$downhost=$json['dom'];
			$downend=$json['url'];
			if($downend){$mp4url=$downhost.'/file/'.$downend;file_put_contents($ep_file,$mp4url);}
			
		}
	}
	
}else{
	$mp4url=file_get_contents($ep_file);
}
if($mp4url==""){
	if($out!=''){
	    $mp4url=$out;
	}else{
	    $mp4url=$url;
	}
	
}
if($mp4url){
	header('HTTP/1.1 302 Moved Permanently');
	Header("Location: ".$mp4url);	
}else{echo '拉取下载地址出错，请重试';}


function curlget($str,$sj='',$post='',$gzip='',$rf='')
{
if ($sj){
$head=array(
      'Accept: */*',
	  'User-Agent:Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Mobile Safari/537.36',
	  'deviceType:2',
      'Connection: Keep-Alive');
}else{
$head=array(
      'Accept: */*',
	  'User-Agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
	  'deviceType:2',
	  'referer: '.$rf,
	  'origin: '.$host,
	  'accept: application/json, text/javascript, */*',
	  'content-type: application/x-www-form-urlencoded',
	  'x-requested-with: XMLHttpRequest',
      'Connection: Keep-Alive');

}
$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $str); 
curl_setopt($curl, CURLOPT_REFERER, $str); //伪造
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
if($gzip){
curl_setopt($curl, CURLOPT_ENCODING ,'gzip');
}
if($post){
curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
}
curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
$result = curl_exec($curl); 
curl_close($curl); 
return $result;
}
function getstrzj($str, $leftStr, $rightStr)
{
	$left = strpos($str, $leftStr);
	if($left!==false){$left = $left+ strlen($leftStr);}else{return '';}//左边位置
	$right = strpos($str, $rightStr,$left);
	if($right==false){return '';}//右边位置
	$newstr=substr($str, $left, $right-$left);
	$leftnum=strripos($newstr,$leftStr);//查找左边字符最后位置，没有返回假
	if($leftnum){$newstr=substr($newstr, -(strlen($newstr)-$leftnum-strlen($leftStr)));}
	return $newstr;
}
?>