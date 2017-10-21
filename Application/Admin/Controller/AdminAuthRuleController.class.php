<?php
namespace Admin\Controller;

use Admin\Common\InitController;
use Admin\Model\AdminAuthRuleModel;

class AdminAuthRuleController extends InitController
{
    /**
     * 后台菜单管理
     */
    public function nav()
    {
        $AdminAuthRuleModel = new AdminAuthRuleModel();
        // 排序
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            if ($post['listorder']) {
                foreach ($post['listorder'] as $k => $v) {
                    $AdminAuthRuleModel->where('id=' . $k)->save(array(
                        'listorder' => $v
                    ));
                }
                $this->ajax(true, '排序成功');
            }
        }
        if (IS_AJAX && IS_GET) {
            $type = I('get.type');
            $id = I('get.id');
            if ($type == 'change_status' && $id) {
                $status = $AdminAuthRuleModel->where('id = ' . $id)->getField('status');
                if ($status == 1) {
                    $AdminAuthRuleModel->where('id = ' . $id)->save(array(
                        'status' => 0
                    ));
                } else {
                    $AdminAuthRuleModel->where('id = ' . $id)->save(array(
                        'status' => 1
                    ));
                }
                $this->ajax(true, '状态修改成功');
            }
            if ($type == 'change_is_show' && $id) {
                $is_show = $AdminAuthRuleModel->where('id = ' . $id)->getField('is_show');
                if ($is_show == 1) {
                    $AdminAuthRuleModel->where('id = ' . $id)->save(array(
                        'is_show' => 0
                    ));
                } else {
                    $AdminAuthRuleModel->where('id = ' . $id)->save(array(
                        'is_show' => 1
                    ));
                }
                $this->ajax(true, '状态修改成功');
            }
        }
        $icon = array(
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico1.png" style="height:20px;"/>',
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico2.png" style="height:20px;"/>',
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico3.png" style="height:20px;"/>'
        );
        $str = "<tr>
				<td><input type='text' class='form-control input-sm' name='listorder[\$id]' value='\$listorder' style='width:40px;text-align:center;'/></td>
				<td>\$id</td>
				<td>\$spacer\$title</td>
                <td class='status' data-id='\$id' data-status='\$status'></td>
                <td class='is_show' data-id='\$id' data-is-show='\$is_show'></td>
				<td class='last-menu'><a href='" . U('navAdd') . "?pid=\$id' title=''>新增子菜单</a><a href='" . U('navUpdate') . "?id=\$id'>编辑</a><a href='' class='ajax-get' data-url='" . U('delete') . "?id=\$id' data-location='reload' data-confirm='确定要删除吗？'>删除</a></td>
			 </tr>";
        $list_html = $AdminAuthRuleModel->getNavTree(0, 0, $str, $icon);
        $this->assign('list', $list_html);
        $this->display();
    }

    /**
     * 添加菜单
     */
    public function navAdd()
    {
        $AdminAuthRuleModel = new AdminAuthRuleModel();
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            if (! $data = $AdminAuthRuleModel->create($post)) {
                $this->ajax(false, $AdminAuthRuleModel->getError());
            } else {
                $result = $AdminAuthRuleModel->navAdd($data);
                $this->updateNavCache(true);
                $this->ajax($result['status'], $result['msg']);
            }
        }
        $select_id = I('get.pid') ? I('get.pid') : 0;
        $admin_nav_str = $AdminAuthRuleModel->getNavTree(0, $select_id, '', '', 'attr_id < 4');
        $this->assign('admin_nav', $admin_nav_str);
        $this->display();
    }

    /**
     * 编辑菜单
     */
    public function navUpdate()
    {
        $id = I('get.id');
        $AdminAuthRuleModel = new AdminAuthRuleModel();
        if ($id && IS_AJAX && IS_POST) {
            $post = I('post.');
            if (! $data = $AdminAuthRuleModel->create($post)) {
                $this->ajax(false, $AdminAuthRuleModel->getError());
            } else {
                $result = $AdminAuthRuleModel->navUpdate($id, $data);
                $this->updateNavCache(true);
                $this->ajax($result['status'], $result['msg']);
            }
        }
        if ($id && $data = M('AdminAuthRule')->where('id=' . $id)->find()) {
            if ($data['attr_id'] < 3) {
                $data['name'] = '';
            }
            $admin_nav_str = $AdminAuthRuleModel->getNavTree(0, $data['pid'], '', '', 'attr_id < 4');
            $this->assign('admin_nav', $admin_nav_str);
            $this->assign('data', $data);
        } else {
            $this->error('菜单不存在');
        }
        $this->display();
    }

    /**
     * 删除菜单
     */
    public function navDelete()
    {
        $id = I('get.id');
        if ($id && M('AdminAuthRule')->where('id = ' . $id)->delete()) {
            $this->updateNavCache(true);
            $this->ajax(true, '删除成功');
        } else {
            $this->ajax(false, '删除失败，请重试');
        }
    }

    /**
     * 更新后台菜单缓存
     * $not_ajax 主要用于本控制调用，比如修改菜单后直接调用
     */
    public function updateNavCache($not_ajax = false)
    {
        $cacheControl = new CacheController();
        $cacheControl->updateGroupAuth(1);
        if (!$not_ajax && IS_AJAX) {
        	$this->ajax(true, '更新缓存成功');
        }
    }
}