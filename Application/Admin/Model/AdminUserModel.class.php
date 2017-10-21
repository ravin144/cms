<?php
namespace Admin\Model;
use Think\Model;
class AdminUserModel extends Model{
    protected $_validate = array(
    	array('title', 'require', '请填写管理员名称'),
        array('group_id', 'require', '请选择一个用户组')
    );
    /**
     * 添加管理员
     */
    public function userAdd($data){
    	if (!$data['password']) {
    		return array('status'=>false, 'msg'=>'请填写登录密码');
    	}
    	$data['encrypt'] = mb_strcut(uniqid(), -5);
    	$data['password'] = md5(md5($data['password']).$data['encrypt']);
    	if ($this->where("user_name = '{$data['user_name']}'")->getField('id')) {
    		return array('status'=>false, 'msg'=>'用户名'.$data['user_name'].'已存在');;
    	}
    	if ($this->add($data)) {
    		return array('status'=>true, 'msg'=>'新增成功');
    	} else {
    	    return array('status'=>false, 'msg'=>'新增失败，请重试');
    	}
    }
    /**
     * 修改管理员
     */
    public function userUpdate($id, $data){
    	if ($data['password'] != '') {
    		$data['encrypt'] = mb_strcut(uniqid(), -5);
    	    $data['password'] = md5(md5($data['password']).$data['encrypt']);
    	}
    	if ($this->where("user_name = '{$data['user_name']}' AND id != $id")->getField('id')) {
    		return array('status'=>false, 'msg'=>'用户名'.$data['user_name'].'已存在');;
    	}
    	if ($this->where('id = '.$id)->save($data)){
    	    return array('status'=>true, 'msg'=>'更新成功');
    	} else {
    	    return array('status'=>false, 'msg'=>'更新失败，请重试');
    	}
    }
}