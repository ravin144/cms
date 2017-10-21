<?php
namespace Admin\Controller;

use Think\Controller;

/**
 * 后台公共操作控制器，比如登录，退出，验证码等不需要登录的操作
 *
 * @author shixu
 */
class PublicController extends Controller
{
    /**
     * 后台登录
     */
    public function login()
    {
        C('LAYOUT_ON', false);
        if (IS_AJAX && IS_POST) {
            $post             = I('post.');
            $result['status'] = false;
            $result['msg']    = '成功';
            if (!$this->checkVerify($post['code'])) {
                $result['msg'] = '验证码填写错误';
                $this->ajaxReturn($result);
            }
            $user_data = M('AdminUser')->where('user_name=\'' . $post['user_name'] . '\'')->find();
            if ($user_data) {
                if ($user_data['password'] == md5(md5($post['password']) . $user_data['encrypt'])) {
                    if ($user_data['disabled'] == 1) {
                        $result['msg'] = '您的账号已被禁用';
                    } else {
                        unset($user_data['password']);
                        unset($user_data['encrypt']);
                        $user_group = M('AdminAuthGroup')->where('id=' . $user_data['group_id'])->field('status,title')->find();
                        if ($user_group['status'] != 1) {
                            $result['msg'] = '您所在的管理组已被禁用';
                        } else {
                            $user_data['group_name'] = $user_group['title'];
                            session('admin', $user_data);
                            M('AdminUser')->where('id=' . $user_data['id'])->setInc('login_count');
                            $SaveData = array('last_login_time' => time(), 'last_login_ip' => get_client_ip());
                            M('AdminUser')->where('id=' . $user_data['id'])->save($SaveData);
                            $result['status'] = true;
                            $result['msg']    = 'success';
                        }
                    }
                } else {
                    $result['msg'] = '登录密码错误';
                }
            } else {
                $result['msg'] = '用户不存在';
            }
            $this->ajaxReturn($result);
        } else {
            $this->display();
        }
    }
    /**
     * 退出
     */
    public function logOut()
    {
        session(null);
        echo '<script type="text/javascript">window.location.href="' . U('login') . '"</script>';
        exit;
    }
    /**
     * 输出验证码
     * 可通过get传参控制验证码大小，字体大小
     * ?imageW=宽度&imageH=高度&fontSize=字体大小
     */
    public function verify()
    {
        $get                = I('get.');
        $config['imageW']   = $get['imageW'] ? $get['imageW'] : '';
        $config['imageH']   = $get['imageH'] ? $get['imageH'] : '';
        $config['fontSize'] = $get['fontSize'] ? $get['fontSize'] : '';
        $config['useCurve'] = false;
        $config['useNoise'] = false;
        $config['length']   = 4;
        $config['codeSet']  = '0123456789'; //验证码为纯数字
        $Verify             = new \Think\Verify($config);
        $Verify->entry($get['id']);
    }

    /**
     * 验证码检测函数
     *
     * @param string $code
     *            要检测的验证码
     * @param string $id
     *            如果一个页面中含有多个验证码，需要区分id
     * @return boolean
     */
    public function checkVerify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
}
