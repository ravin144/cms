<?php
namespace Admin\Controller;

use Admin\Common\InitController;

/**
 * 站点设置
 * @author DoCan Ravin
 */
class SiteController extends InitController
{

    // 站点设置表单 & 表单提交
    public function index()
    {
        $site = M('SystemSetting');
        $list = $site->select();

        // 提交
        if (IS_AJAX) {
            $post = I('post.');
            $post['FROM_EMAIL'] = $post['SMTP_USER'];
            $arr  = array();
            foreach ($list as $k => $v) {
                $arr[] = $v['name'];
            }
            // 提交的图片地址为空，则对应的图片标题也为空
            if (!$post['QQ_IMAGE']) {
                $post['QQ_IMAGE_ALT'] = '';
            }
            if (!$post['WEIXIN_IMAGE']) {
                $post['WEIXIN_IMAGE_ALT'] = '';
            }
            // 改变数据库upload_image中图片对应的标题
            if ($qq = M('UploadImage')->where('url = "' . $post['QQ_IMAGE'] . '"')->find()) {
                $qq['title'] = $post['QQ_IMAGE_ALT'];
                M('UploadImage')->where('url = "' . $post['QQ_IMAGE'] . '"')->save($qq);
            }
            if ($wx = M('UploadImage')->where('url = "' . $post['WEIXIN_IMAGE'] . '"')->find()) {
                $wx['title'] = $post['WEIXIN_IMAGE_ALT'];
                M('UploadImage')->where('url = "' . $post['WEIXIN_IMAGE'] . '"')->save($wx);
            }

            foreach ($post as $k => $v) {
                if (in_array($k, $arr)) {
                    $data['value'] = $v;
                    $site->where('name=\'' . $k . '\'')->save($data);
                } else {
                    $data['name']  = $k;
                    $data['value'] = $v;
                    $site->add($data);
                }
            }
            $content = file_get_contents('./Application/Common/Conf/site_config.php');
            foreach ($post as $k => $v) {
                $pattern[]     = '/' . $k . '\'(.*)=>(.*),/U';
                $replacement[] = is_numeric($v) ? $k . '\'$1=>' . $v . ',' : $k . '\'$1=>\'' . $v . '\',';
            }
            $content = preg_replace($pattern, $replacement, $content);
            file_put_contents('./Application/Common/Conf/site_config.php', $content);
            $result['status'] = true;
            $result['msg']    = '保存成功';
            $this->ajaxReturn($result);
        }

        $settings = M('SystemSetting')->select();
        foreach ($settings as $k => $v) {
            $settings[$v['name']] = htmlspecialchars_decode($v['value']);
        }
        $this->assign('settings', $settings);
        $this->display();
    }
    public function mailTest()
    {
        if (I('email')) {
            if (think_send_mail(I('email'), '', 'test', 'just a try') == 1) {
                echo json_encode(array('status' => true, 'msg' => '发送成功'));
            } else {
                echo json_encode(array('status' => false, 'msg' => think_send_mail(I('email'), '', 'test', 'just a try')));
                // echo json_encode(array('status' => false, 'msg' => '发送失败'));
            }
        } else {
            echo json_encode(array('status' => false, 'msg' => '非法请求'));
        }
    }
}
