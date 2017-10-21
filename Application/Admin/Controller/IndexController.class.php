<?php
namespace Admin\Controller;

use Admin\Common\InitController;

class IndexController extends InitController
{
    /**
     * 后台主页
     */
    public function index()
    {
        C('LAYOUT_ON', FALSE);
        $this->display();
    }
    /**
     * 系统顶部导航
     */
    public function topNav()
    {
        C('LAYOUT_ON', FALSE);
        $nav_list = cache('admin/nav'.session('admin.group_id'));
        $top_nav = array();
        foreach ($nav_list as $k => $v) {
        	if ($v['pid'] == 0 && $v['status'] == 1 && $v['is_show'] == 1) {
        		$top_nav[] = $v;
        	}
        }
        $this->assign('top_nav', $top_nav);
    	$this->display();
    }
    /**
     * 左侧菜单
     */
    public function sideBar()
    {
        C('LAYOUT_ON', FALSE);
        $nav_list = cache('admin/nav'.session('admin.group_id'));
        $sidebar_nav = array();
        $pid = I('get.pid');
        if (!$pid) {
        	$pid = M('AdminAuthRule')->where('pid = 0 AND status = 1 AND is_show =1')->order('listorder ASC,id DESC')->getField('id');
        }
        foreach ($nav_list as $k => $v) {
        	if ($v['pid'] == $pid && $v['status'] == 1 && $v['is_show'] == 1) {
        		$sidebar_nav[] = $v;
        	}
        }
        foreach ($sidebar_nav as $k => $v) {
        	$sidebar_nav[$k]['child_nav'] = array();
        	foreach ($nav_list as $kk=>$vv) {
        		if ($vv['pid'] == $v['id'] && $vv['status'] == 1 && $vv['is_show'] == 1) {
        			$sidebar_nav[$k]['child_nav'][] = $vv;
        		}
        	}
        }
        $this->assign('sidebar_nav', $sidebar_nav);
    	$this->display();
    }
    /**
     * 主页
     */
    public function main()
    {
    	$this->display();
    }
}