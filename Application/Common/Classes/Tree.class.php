<?php
namespace Common\Classes;
class Tree{
	/**
	* 生成树型结构所需要的2维数组
	* @var array
	*/
	public $arr = array();

	/**
	* 生成树型结构所需修饰符号，可以换成图片
	* @var array
	*/
	public $icon = array('│','├','└');
	public $nbsp = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	public $treeArr=array();
	/**
	* @access private
	*/
	public $ret = '';

	/**
	* 构造函数，初始化类
	* @param array 2维数组，例如：
	* array(
	*      1 => array('id'=>'1','pid'=>0,'name'=>'一级栏目一'),
	*      2 => array('id'=>'2','pid'=>0,'name'=>'一级栏目二'),
	*      3 => array('id'=>'3','pid'=>1,'name'=>'二级栏目一'),
	*      4 => array('id'=>'4','pid'=>1,'name'=>'二级栏目二'),
	*      5 => array('id'=>'5','pid'=>2,'name'=>'二级栏目三'),
	*      6 => array('id'=>'6','pid'=>3,'name'=>'三级栏目一'),
	*      7 => array('id'=>'7','pid'=>3,'name'=>'三级栏目二')
	*      )
	*/
	public function tree($arr=array()){
       $this->arr = $arr;
	   $this->ret = '';
	   return is_array($arr);
	}

    /**
	* 得到父级数组
	* @param int
	* @return array
	*/
	public function get_parent($myid){
		$newarr = array();
		if(!isset($this->arr[$myid])) return false;
		$pid = $this->arr[$myid]['pid'];
		$pid = $this->arr[$pid]['pid'];
		if(is_array($this->arr)){
			foreach($this->arr as $id => $a){
				if($a['pid'] == $pid) $newarr[$id] = $a;
			}
		}
		return $newarr;
	}

    /**
	* 得到子级数组
	* @param int
	* @return array
	*/
	public function get_child($myid){
		$a = $newarr = array();
		if(is_array($this->arr)){
			foreach($this->arr as $id => $a){
				if($a['pid'] == $myid) $newarr[$id] = $a;
			}
		}
		return $newarr ? $newarr : false;
	}
    /**
	* 得到当前位置数组
	* @param int
	* @return array
	*/
	public function get_pos($myid,&$newarr){
		$a = array();
		if(!isset($this->arr[$myid])) return false;
        $newarr[] = $this->arr[$myid];
		$pid = $this->arr[$myid]['pid'];
		if(isset($this->arr[$pid])){
		    $this->get_pos($pid,$newarr);
		}
		if(is_array($newarr)){
			krsort($newarr);
			foreach($newarr as $v){
				$a[$v['id']] = $v;
			}
		}
		return $a;
	}

    /**
	* 得到树型结构
	* @param int ID，表示获得这个ID下的所有子级
	* @param int 被选中的ID，比如在做树型下拉框的时候需要用到
	* @param string 生成树型结构的基本代码，例如："<option value=\$id \$selected>\$spacer\$name</option>"
	* @return string
	*/
	public function get_tree($myid, $sid = 0, $str="", $adds = '', $str_group = ''){
		$number=1;
		$child = $this->get_child($myid);
		if(is_array($child)){
		    $total = count($child);
			foreach($child as $id=>$value){
				$j=$k='';
				if($number==$total){
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';
				if (is_array($sid)) {
					$selected = in_array($value['id'], $sid) ? 'selected' : '';
					$checked = in_array($value['id'], $sid) ? 'checked' : '';
				} else {
				    $selected = $value['id']==$sid ? 'selected' : '';
				    $checked = $value['id']==$sid ? 'checked' : '';
				    $disabled = $value['model_id']==$sid ? '' : 'disabled';
				}
				@extract($value);
				$pid == 0 && $str_group ? eval("\$nstr = \"$str_group\";") : eval("\$nstr = \"$str\";");
				$this->ret .= $nstr;
				$nbsp = $this->nbsp;
				$this->get_tree($id, $sid, $str, $adds.$k.$nbsp,$str_group);
				$number++;
			}
		}
		return $this->ret;
	}
	private function have($list,$item){
		return(strpos(',,'.$list.',',','.$item.','));
	}
}
