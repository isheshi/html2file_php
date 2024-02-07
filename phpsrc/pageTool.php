<?php

// 服务入口：/html2file

// 请求方式：post

// 服务入参：

// {
//     "pageUrl":"https://wkhtmltopdf.org",
//     "fileType":"2",
//     "fileExt":""
//     "pageHtmlContent":""
// }
// pageUrl：目标链接，带http的链接，无登陆权限验证
// fileType：文件类型，1-img,2-pdf,3-markdown,4-word,5-excel
// fileExt：文件扩展名，图片转化可以转化为不同后缀格式的图片
// pageHtmlContent：目标页面Html内容，当不能提供pageUrl时，将html内容传入也可以转化，其中引用的css和js需要为带http的路径，不能为相对路径
// 服务出参：

// {
//     "status"：1,
//     "result"："/output/20180417/pdf/9c3fd3018bb041429bba702cd127be9e.pdf",
//     "errorMsg"：""
// }
// status：状态，1-成功,-1-失败
// result：结果，这里为转化后的文件路径
// errorMsg：错误信息

    // die("<script>alert('维护中');</script>");
$fileType = $_POST['filetype'];
$domain = trim($_POST['domain']);
if (filter_var($domain, FILTER_VALIDATE_URL) === false) {
    die("<script>alert('无效网址');window.location.href = '{$_SERVER['HTTP_REFERER']}';</script>");
}

$params = [];
$params['pageUrl'] = $domain;
$params['fileType'] = $fileType;
$params['fileExt'] = "";
$params['pageHtmlContent'] = "";

$host = "http://code6.58heshihu.com";
$url = $host."/html2file";
$rs = curl($url, $params);
$rs = json_decode($rs,true);
if (empty($rs['status']) || (!empty($rs['status']) && $rs['status'] != 1)) {
    die("<script>alert('{$rs['errorMsg']}');window.location.href = '{$_SERVER['HTTP_REFERER']}';</script>");
}
$file = $host.$rs['result'];
$extension = pathinfo($file, PATHINFO_EXTENSION);
$filename = getWebTitle($domain);
if (empty($filename)){
    // 截取问号之前的部分
    if (strpos($domain, '?') !== false) {
        $domain = strstr($domain, '?', true);
    }
    $filename = basename($domain)."_".date("Y-m-d");
}
$filename .= ".".$extension;



// 设置响应标头
header('Content-Description: File Download');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . $filename);
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . @filesize($file));

// 发送文件给用户
readfile($file);
exit;


function curl($url, $params = null, $timeout = 8)
{
    $ch = curl_init(); // 初始化curl并设置链接
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    // 设置请求头
    $headers = [
        'Content-Type: application/json',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // 设置是否为post传递
    curl_setopt($ch, CURLOPT_POST, !empty($params));
    // 对于https 设定为不验证证书和host
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 设置是否返回信息

    if ($params) {
        if (is_array($params)) {
            $params = json_encode($params);
        }
        // POST 数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    }

    PHP_SAPI != 'cli' && isset($_SERVER['HTTP_USER_AGENT']) && curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);

    $response = curl_exec($ch); // 执行并接收返回信息

    if (curl_errno($ch)) {
        // 出错则显示错误信息
        $response = 'curl_errno：'.curl_error($ch);
    }

    curl_close($ch); // 关闭curl链接
    return $response;
}


//获取网站标题
function getWebTitle($url){
    // 创建 DOMDocument 对象
    $dom = new DOMDocument();
    // 获取网页内容
    $html = file_get_contents($url); // 替换为你要获取标题的网站地址
    // 禁用错误报告，避免因为 HTML 结构问题导致的错误信息输出
    libxml_use_internal_errors(true);
    // 加载 HTML 内容到 DOMDocument 对象
    $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
    // 恢复错误报告
    libxml_use_internal_errors(false);
    // 获取标题元素
    $titleElement = $dom->getElementsByTagName('title')->item(0); // 假定标题元素是 <title> 标签
    // 获取标题文本
    $title = $titleElement->textContent;
    // 输出标题
    return $title;
}