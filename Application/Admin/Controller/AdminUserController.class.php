<?php
namespace Admin\Controller;

use Admin\Common\InitController;
use Admin\Model\AdminAuthRuleModel;
use Admin\Model\AdminAuthGroupModel;
use Admin\Model\AdminUserModel;

class AdminUserController extends InitController
{
    /*管理员管理*/
    public function index()
    {
        $AdminUserModel = new AdminUserModel();
        $get = I('get.');
        if ($get['group_id'] && $get['group_id'] != '') {
        	$where['group_id'] = $get['group_id'];
        }
        if ($get['user_name'] && $get['user_name'] != '') {
        	$where['user_name'] = array('LIKE', '%'.$get['user_name'].'%');
        }
        $list = $AdminUserModel->alias('a')
                               ->where($where)
                                ->join('__ADMIN_AUTH_GROUP__ AS g ON a.group_id = g.id')
                                ->field('a.*,g.title AS group_name')
                                ->select();
        $this->assign('list', $list);
    	$this->display();
    }
    public function add()
    {
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            $AdminUserModel = new AdminUserModel();
            if ($post['password'] != $post['repassword']) {
            	$this->ajax(false, '两次密码输入不一致');
            }
        	$data = $AdminUserModel->create();
            if (!$data) {
            	$this->ajax(false, $AdminUserModel->getError());
            } else {
            	$result = $AdminUserModel->userAdd($data);
            	if ($result['status']) {
            		$auth_group_access['uid'] = $AdminUserModel->getLastInsID();
            		$auth_group_access['group_id'] = $post['group_id'];
            		M('AdminAuthGroupAccess')->add($auth_group_access);
            	}
            	$this->ajax($result['status'], $result['msg']);
            }
        }
        $this->assign('group_list', M('admin_auth_group')->where('status = 1')->order('listorder ASC, id DESC')->field('title,id')->select());
    	$this->display();
    }
    public function update()
    {
        $AdminUserModel = new AdminUserModel();
        $id = I('get.id');
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
        	if ($id == 1) {
        		$this->ajax(false, '总管理员不可编辑');
        	}
        	if ($post['password'] && $post['password'] != '') {
        		if ($post['password'] != $post['repassword']) {
        			$this->ajax(false, '两次密码输入不一致');
        		}
        	} else {
        		unset($post['password']);
        	}
        	$data = $AdminUserModel->create($post);
        	if (!$data) {
        		$this->ajax(false, $AdminUserModel->getError());
        	} else {
        	    $result = $AdminUserModel->userUpdate($id, $data);
        	    if ($result['status']) {
        	    	$auth_group_access['group_id'] = $post['group_id'];
            		M('AdminAuthGroupAccess')->where('uid = '.$id)->save($auth_group_access);
        	    }
        	    $this->ajax($result['status'], $result['msg']);
        	}
        }
        if ($id && $data = $AdminUserModel->where('id = '.$id)->find()) {
        	$this->assign('data', $data);
        } else {
        	$this->error('管理员不存在');
        }
        $this->assign('group_list', M('admin_auth_group')->where('status = 1')->order('listorder ASC, id DESC')->field('title,id')->select());
        $this->display();
    }
    public function delete(){
        $id = I('get.id');
    	if ($id) {
    		if ($id == 1) {
    		    $this->ajax(false, '总超级管理员不能删除');
    		} else if (M('AdminUser')->where('id = '.$id)->delete()) {
    			$this->ajax(true, '删除成功');
    		} else {
    		    $this->ajax(false, '删除失败，请重试');
    		}
    	} else {
    	    $this->ajax(false, '非法操作');
    	}
    }
    /*更新管理员个人信息*/
    public function saveInfo(){
        $AdminUserModel = new AdminUserModel();
        if (IS_AJAX && IS_POST) {
        	$post = I('post.');
        	if ($post['password'] && $post['password'] != '') {
        		if ($post['password'] != $post['repassword']) {
        			$this->ajax(false, '两次密码输入不一致');
        		}
        	} else {
        		unset($post['password']);
        	}
        	$data = $AdminUserModel->create($post);
        	if (!$data) {
        		$this->ajax(false, $AdminUserModel->getError());
        	} else {
        	    unset($data['id']);
        	    $data['group_id'] = session('admin.group_id');
        		$result = $AdminUserModel->userUpdate(session('admin.id'), $data);
        		$this->ajax($result['status'], $result['msg']);
        	}
        }
        $data = session('admin');
        $this->assign('data', $data);
    	$this->display();
    }
    /*管理组及权限*/
    public function group()
    {
        $AdminAuthRuleModel = new AdminAuthGroupModel();
        if (IS_AJAX && IS_POST) {
        	$listorder = I('post.listorder');
        	foreach ($listorder as $k=>$v) {
        		$AdminAuthRuleModel->where('id = '.$k)->save(array('listorder'=>$v));
        	}
        	$this->ajax(true, '排序成功');
        }
        $admin_group = $AdminAuthRuleModel->group();
        $this->assign('list', $admin_group['list']);
        $this->assign('page', $admin_group['page']);
        $this->display();
    }

    public function groupAdd()
    {
        if (IS_AJAX && IS_POST) {
            $AdminAuthRuleModel = new AdminAuthGroupModel();
        	$data = $AdminAuthRuleModel->create();
        	if (!$data) {
        		$this->ajax(false, $AdminAuthRuleModel->getError());
        	} else {
        		$result = $AdminAuthRuleModel->groupAdd($data);
        		if ($result['status']) {
        			 $CacheController = new CacheController();
        	         $CacheController->updateGroupAuth($AdminAuthRuleModel->getLastInsID());
        		}
        		$this->ajax($result['status'], $result['msg']);
        	}
        }
        $icon = array(
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico1.png" style="height:20px;"/>',
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico2.png" style="height:20px;"/>',
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico3.png" style="height:20px;"/>'
        );
        $str = "<tr>
				<td width='30%'>\$spacer<label><input type='checkbox' \$checked name='rules[]' class='rules' value='\$id' data-id='\$id' data-pid='\$pid'/>\$title</label></td>
				<td>{child_\$id}</td>
			 </tr>";
        $AdminAuthRuleModel = new AdminAuthRuleModel();
        $rule_html = $AdminAuthRuleModel->getNavTree(0, 0, $str, $icon, 'attr_id < 4');
        $rule_3 = $AdminAuthRuleModel->where('attr_id = 3')
                                     ->field('id')
                                     ->select();
        foreach ($rule_3 as $k => $v) {
            $child_arr = $AdminAuthRuleModel->where('pid = ' . $v['id'])->field('id,title,pid')->select();
            $child_html = '';
            foreach ($child_arr as $kk => $vv) {
                $child_html .= '<label><input type="checkbox" name="rules[]" value="'.$vv['id'].'" class="rules" data-id="'.$vv['id'].'" data-pid="'.$vv['pid'].'"/>'.$vv['title'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
            }
            $rule_html = str_replace('{child_'.$v['id'].'}', $child_html, $rule_html);
        }
        $rule_html = preg_replace('/\{child_\d+\}/', '', $rule_html);
        $this->assign('rule_html', $rule_html);
        $this->display();
    }
    public function groupUpdate()
    {
        $id = I('get.id');
        $AdminAuthRuleModel = new AdminAuthGroupModel();
        if (IS_AJAX && IS_POST) {
        	$data = $AdminAuthRuleModel->create();
        	if (!$data) {
        		$this->ajax(false, $AdminAuthRuleModel->getError());
        	} else {
        	    $result = $AdminAuthRuleModel->groupUpdate($id, $data);
        	    if ($result['status']) {
        	    	 $CacheController = new CacheController();
        	         $CacheController->updateGroupAuth($id);
        	    }
        		$this->ajax($result['status'], $result['msg']);
        	}
        }
    	if ($id && $data = $AdminAuthRuleModel->where('id = '.$id)->find()) {
    		$data['rules'] = explode(',', $data['rules']);
    		$icon = array(
    				'<img src="' . __ROOT__ . '/Public/admin/images/menu_ico1.png" style="height:20px;"/>',
    				'<img src="' . __ROOT__ . '/Public/admin/images/menu_ico2.png" style="height:20px;"/>',
    				'<img src="' . __ROOT__ . '/Public/admin/images/menu_ico3.png" style="height:20px;"/>'
    		);
    		$str = "<tr>
        				<td width='30%'>\$spacer<label><input type='checkbox' \$checked name='rules[]' class='rules' value='\$id' data-id='\$id' data-pid='\$pid'/>\$title</label></td>
        				<td>{child_\$id}</td>
        			 </tr>";
    		$AdminAuthRuleModel = new AdminAuthRuleModel();
    		$rule_html = $AdminAuthRuleModel->getNavTree(0, $data['rules'], $str, $icon, 'attr_id < 4');
    		$rule_3 = $AdminAuthRuleModel->where('attr_id = 3')
                                		 ->field('id')
                                		 ->select();
    		foreach ($rule_3 as $k => $v) {
    			$child_arr = $AdminAuthRuleModel->where('pid = ' . $v['id'])->field('id,title,pid')->select();
    			$child_html = '';
    			foreach ($child_arr as $kk => $vv) {
    			    $checked = in_array($vv['id'], $data['rules']) ? 'checked' : '';
    				$child_html .= '<label><input type="checkbox" name="rules[]" '.$checked.' value="'.$vv['id'].'" class="rules" data-id="'.$vv['id'].'" data-pid="'.$vv['pid'].'"/>'.$vv['title'].'</label>&nbsp;&nbsp;&nbsp;&nbsp;';
    			}
    			$rule_html = str_replace('{child_'.$v['id'].'}', $child_html, $rule_html);
    		}
    		$rule_html = preg_replace('/\{child_\d+\}/', '', $rule_html);
    		$this->assign('data', $data);
    		$this->assign('rule_html', $rule_html);
    	} else {
    		$this->error('管理组不存在');
    	}
    	$this->display();
    }
    public function groupDelete()
    {
    	$id = I('get.id');
    	if ($id == 1) {
    		$this->ajax(false, '超级管理员组不能删除');
    	}
    	if ($id && M('AdminAuthGroup')->where('id = '.$id)->delete()) {
    		$this->ajax(true, '删除成功');
    	} else {
    	    $this->ajax(false, '删除失败，请重试');
    	}
    }
}