<?php
namespace Admin\Model;
use Think\Model;
class AdminAuthGroupModel extends Model
{
    protected $_validate = array(
    	array('title', 'require', '请填写管理组名')
    );
    public function group()
    {
        $count = $this->count();
        $page = new \Think\Page($count, 30);
    	$list = $this->order('listorder ASC, id ASC')->limit($page->firstRow, $page->listRows)->select();
    	return array('list'=>$list,'page'=>$page->show);
    }
    public function groupAdd($data)
    {
        $data['rules'] = implode(',', $data['rules']);
    	if ($this->add($data)) {
    		return array('status' => true,'msg' =>'添加成功');
    	} else {
    	    return array('status' => false,'msg' =>'添加失败，请重试');
    	}
    }
    public function groupUpdate($id, $data)
    {
    	$data['rules'] = implode(',', $data['rules']);
    	if ($this->where('title = \''.$data['title'].'\' AND id != '.$id)->find()) {
    		return array('status' => false,'msg' =>'管理组名已存在');
    	}
    	if ($this->where('id = '.$id)->save($data) !== false) {
    		return array('status' => true,'msg' =>'修改成功');
    	} else {
    	    return array('status' => false,'msg' =>'修改失败，请重试');
    	}
    }
}