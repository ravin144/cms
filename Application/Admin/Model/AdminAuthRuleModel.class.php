<?php
namespace Admin\Model;
use Think\Model;
use Common\Classes\Tree;
class AdminAuthRuleModel extends Model{
    protected $_validate = array(
    	array('title', 'require', '请填写菜单名称'),
        array('model_id', 'require', '请选择一个上级菜单')
    );
    /**
     * 获取后台菜单树形结构
     * @param number $pid
     *               父级id
     * @param number $select_id
     *               被选中的id，没有为空或0即可
     * @param string $str
     *               结构html,下拉为空即可
     * @param array  $icon
     *               图标,一般为空即可
     * @param array  $where
     *               查询数据条件            
     * @return string html结构
     */
    public function getNavTree($pid = 0, $select_id = 0, $str="", $icon=array(),$where=''){
        $str = isset($str) && $str != '' ? $str :"<option value=\$id \$selected>\$spacer\$title</option>";
        $where = $where ? $where :'';
        $nav_tree_arr = $this->where($where)->order('listorder ASC,id ASC')->select();
        $tree = new Tree();
        if (is_array($icon) && count($icon) > 0) {
        	$tree->icon = $icon;
        }
        $tree->tree($nav_tree_arr);
        $nav_tree_html = $tree->get_tree($pid, $select_id, $str);
        return $nav_tree_html;
    }
	public function navAdd($data = array()){
	    //因name不能为空不能重复，所以顶部菜单和左侧一级菜单系统自动生成nav.时间戳的形式
	    if ($data['pid'] != 0) {
	    	$ppid = $this->where(array('id = '.$data['pid']))->getField('pid');
	    	if ($ppid != 0) {
	    	    if (!$data['name']) {
	    	    	return array('status' => false,'msg' =>'您要添加的非顶部菜单或左侧一级菜单，必须填写模块名/控制器名/操作名');;
	    	    }
        	    if ($this->where(array('name'=>$data['name']))->find()) {
        	    	return array('status' => false,'msg' =>'模块名/控制器名/操作名不能重复');
        	    }
	    	} else {
	    	    $data['name'] = 'nav'.time();
	    	}
	    } else {
	    	$data['name'] = 'nav'.time();
	    }
	    $p_attr_id = $this->where('id = '.$data['pid'])->getField('attr_id');
	    $data['attr_id'] = $p_attr_id+1;
		if ($this->add($data)) {
			return array('status' => true,'msg' =>'添加成功');
		} else {
		    return array('status' => false,'msg' =>'添加失败，请重试');
		}
	}
	public function navUpdate($id, $data=array()){
	    if ($data['pid'] != 0) {
	    	$ppid = $this->where(array('id = '.$data['pid']))->getField('pid');
	    	if ($ppid != 0) {
	    		if (!$data['name']) {
	    			return array('status' => false,'msg' =>'您要添加的非顶部菜单或左侧一级菜单，必须填写模块名/控制器名/操作名');;
	    		}
	    		if ($this->where(array('name'=>$data['name'], 'id'=>array('neq',$id)))->find()) {
	    			return array('status' => false,'msg' =>'模块名/控制器名/操作名不能重复');
	    		}
	    	} else {
	    		$data['name'] = 'nav'.time();
	    	}
	    } else {
	    	$data['name'] = 'nav'.time();
	    }
	    $p_attr_id = $this->where('id = '.$data['pid'])->getField('attr_id');
	    $data['attr_id'] = $p_attr_id+1;
	    if ($this->where('id = '.$id)->save($data)) {
	    	return array('status' => true,'msg' =>'修改成功');
	    } else {
	        return array('status' => false,'msg' =>'修改失败，请重试');
	    }
	}
}