<?php
namespace Home\Common;

use Think\Controller;

/**
 * 初始化
 * @Author Ravin
 */
class InitController extends Controller
{
    protected $cid; // 当前栏目id
    protected $top_pid; // 当前栏目最顶父级栏目id
    protected $pid; // 父级栏目id
    protected $cates; // 栏目数据

    public function __construct()
    {
        parent::__construct();
        $cid = (int) I('get.cid');
        $this->cid = $cid;
        $this->assign('cid', $cid);
        // $cates = array_pop(cache('category/category' . $cid));
        $cates = M('category')->where('id = ' . $cid)->find();
        $this->cates = $cates;
        $this->assign('cates', $cates);
        if ($cid) {
            $pids = $cates['parents_id'];
            if ($pids != '0') {
                $pid_arr = explode(',', $pids);
                $top_pid = $pid_arr[1];
            } else {
                $top_pid = 0;
            }
            $this->pid     = $cates['pid'];
            $this->top_pid = $top_pid;
            $this->assign('pid', $cates['pid']);
            $this->assign('top_pid', $top_pid);
        }
    }
    /**
     * 封装$this->ajaxReturn
     */
    public function ajax($status = true, $msg = 'Success', $data = array())
    {
        $result['status'] = $status;
        $result['msg']    = $msg;
        if ($data && !empty($data)) {
            $result['data'] = $data;
        }
        $this->ajaxReturn($result);
    }
}
