<?php
namespace Admin\Common;
use Think\Controller;
use Think\Auth;
/**
 * 后台入口控制器，只要需要登录和权限的操作都要先继承此控制器
 * @author shixu
 */
class InitController extends Controller{
   
	public function __construct(){
		parent::__construct();
		if (!session('admin')) {
			$this->redirect('Public/login');
		}
		$aule_name = MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		if (I('get.rule_name')) {
			$aule_name = I('get.rule_name');
		}
		$aule = M('AdminAuthRule')->where("name = '$aule_name'")->find();
		if ($aule && session('admin.group_id') != 1) {
			$auth = new Auth();
			if (!$auth->check($aule_name, session('admin.group_id'))) {
			    if (IS_AJAX) {
			    	$this->ajax(false,'您没有权限执行此操作');
			    }
				$this->error('您没有权限执行此操作');exit;
			}
		}
		$breadcrumb = array();
		if ($aule && $aule['attr_id'] > 2) {//只有最底层操作需要面包屑导航和其他操作按钮
		    //面包屑
			$breadcrumb[] = $aule['title'];
			$pid = $aule['pid'];
			$admin_nav_arr = cache('admin/nav1');
			for ($i = 0;$i <= 5; $i++){
				if ($pid == 0) {
					continue;
				} else {
					$breadcrumb[] = $admin_nav_arr[$pid]['title'];
					$pid = $admin_nav_arr[$pid]['pid'];
				}
			}
			//其他操作按钮
			$other_action = $aule['title'];
			foreach ($admin_nav_arr as $k=>$v) {
			    if ($aule['attr_id'] == 3) {
    			    if ($v['pid'] == $aule['id'] && $v['attr_id'] >2 && $v['id']!=$aule['id'] && $v['is_show'] == 1 && $v['status'] == 1) {
    			        $other_action .= '&nbsp;&nbsp;<a href="'. U($v['name']) .'" class="btn btn-primary btn-xs">'.$v['title'].'</a>';
    				}
			    } else {
			        if (($v['pid'] == $aule['pid'] || $v['pid'] == $aule['id'] || $v['id'] == $aule['pid']) && $v['attr_id'] >2 && $v['id']!=$aule['id'] && $v['is_show'] == 1 && $v['status'] == 1) {
			        	$other_action .= '&nbsp;&nbsp;<a href="'. U($v['name']) .'" class="btn btn-primary btn-xs">'.$v['title'].'</a>';
			        }
			    }
			}
		}
		
		$breadcrumb = array_reverse($breadcrumb);
		$breadcrumb_html = '<li>' .implode('</li><li>', $breadcrumb). '</li><a href="javascript:window.location.reload();" class="glyphicon glyphicon-refresh reload"></a>';
		//面包屑导航
		$this->assign('breadcrumb', $breadcrumb_html);
		//其他操作按钮
		$this->assign('other_action', $other_action);
	}
	/**
	 * 封装$this->ajaxReturn
	 */
	public function ajax($status=true, $msg='Success', $data=array()){
	    $result['status'] = $status;
	    $result['msg'] = $msg;
	    $result['data'] = $data;
		$this->ajaxReturn($result);
	}
}