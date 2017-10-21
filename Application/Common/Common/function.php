<?php
//公共函数库
/**
 * cache 永久缓存，主要对F函数进行加密，用法和F()一样
 * @param string $name
 * @param string $value
 * @return mixed
 */
function cache($name, $value = '')
{
    $name = $name . '_' . md5(C('system_key') . $name);
    if ($value && $value != '') {
        return F($name, $value);
    } else {
        return F($name);
    }
}
/**
 * curl post方式请求数据
 * @param string $url
 * @param array $data
 * @return string
 */
function curl_post($url, $data)
{
    $curl = curl_init();
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    //设置post数据
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    //执行命令
    $output = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    return $output;
}
/**
 * [str_cut 字符串截取函数]
 * @Author DoCan
 * @param  [type] $str      [要截取的字符串]
 * @param  [type] $length   [要截取的长度]
 * @param  string $char_set [字符编码]
 * @param  string $end      [追加的字符]
 * @return [type]           [截取后的字符串]
 */
function str_cut($str, $length, $char_set = 'utf-8', $end = '...')
{
    if (mb_strlen($str, $char_set) > $length) {
        $str = mb_substr($str, 0, $length, $char_set) . $end;
    }
    return $str;
}
/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean
 */
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null)
{
    vendor('PHPMailer.class#phpmailer'); // 从PHPMailer目录导class.phpmailer.php类文件
    $mail = new PHPMailer(); // PHPMailer对象
    $mail->CharSet = 'UTF-8'; // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP(); // 设定使用SMTP服务
    $mail->SMTPDebug = 0; // 关闭SMTP调试功能
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true; // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl'; // 使用安全协议
    $mail->Host = C('SMTP_HOST'); // SMTP 服务器
    $mail->Port = C('SMTP_PORT'); // SMTP服务器的端口号
    $mail->Username = C('SMTP_USER'); // SMTP服务器用户名
    $mail->Password = C('SMTP_PASS'); // SMTP服务器密码
    $mail->SetFrom(C('FROM_EMAIL'), C('FROM_NAME'));
    $replyEmail = C('REPLY_EMAIL') ? C('REPLY_EMAIL') : C('FROM_EMAIL');
    $replyName = C('REPLY_NAME') ? C('REPLY_NAME') : C('FROM_NAME');
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
    $mail->MsgHTML($body);
    $mail->AddAddress($to, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}
