<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\InitController;
class IndexController extends InitController {
    public function index()
    {
        $this->display();
    }
    public function category()
    {
		$cid = $this->cid;
		$cates = $this->cates;
        $pid = $this->pid;
    	if ($cates) {
    		$table_name = M('content_model')->where('id = ' . $cates['model_id'])->getField('table_name');
    		$page_num = $cates['page_num'];
    		$where = array(
                'cid' => $cid
            );
    		$count = M($table_name)->where($where)->count();
            // $Page = new \Think\Page($count, $page_num);
    		$Page = new \Think\IndexPage($count, $page_num);
    		$show = $Page->show();
    		$list = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->order('art_order asc, arc_id desc')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
    		$this->list = $list;
    		$this->page = $show;
    		$this->seo_title = $this->cates['seo_title'] == "" ? $this->cates['name'] . '_' . C('WEB_NAME') : $this->cates['seo_title'];
    		$this->seo_keyword = $this->cates['seo_keyword'] == "" ? $this->cates['name'] . '_' . C('META_KEYWORD') : $this->cates['seo_keyword'];
    		$this->seo_description = $this->cates['seo_description'] == "" ? $this->cates['name'] . '_' . C('META_DESCRIPTION') : $this->cates['seo_description'];
    		$this->getTplName($cid);
    	} else {
    		echo json_encode('访问错误');
    	}
    }
    public function page()
    {
    	$cid = $this->cid;
		$cates = $this->cates;
    	if ($cates) {
    		$this->seo_title = $this->cates['seo_title'] == "" ? $this->cates['name'] . '_' . C('WEB_NAME') : $this->cates['seo_title'];
    		$this->seo_keyword = $this->cates['seo_keyword'] == "" ? $this->cates['name'] . '_' . C('META_KEYWORD') : $this->cates['seo_keyword'];
    		$this->seo_description = $this->cates['seo_description'] == "" ? $this->cates['name'] . '_' . C('META_DESCRIPTION') : $this->cates['seo_description'];
    		$this->getTplName($cid, 1);
    	} else {
    		echo json_encode('访问错误');
    	}
    }
    public function show()
    {
		$cid = $this->cid;
		$cates = $this->cates;
		$id = I('get.id');
		if ($cates) {
    		$table_name = M('content_model')->where('id = ' . $cates['model_id'])->getField('table_name');
    		$data = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->where('id = ' . $id)->find();
    		if (!$data) {
    			echo json_encode('文章不存在');
    		}
    		// 上下篇
    		$prev = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->where('id > ' . $id . ' AND cid = ' . $cid)->order('art_order asc, arc_id desc')->field('title, url')->find();
    		$next = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->where('id < ' . $id . ' AND cid = ' . $cid)->order('art_order asc, arc_id desc')->field('title, url')->find();
    		if (!$prev){
    			$prev['url'] = 'javascript:alert(\'没有上一篇了\')';
    			$prev['title'] = '没有了';
    		}
    		if (!$next){
    			$next['url'] = 'javascript:alert(\'没有下一篇了\')';
    			$next['title'] = '没有了';
    		}
    		$this->data = $data;
    		$this->seo_title = $this->cates['seo_title'] == "" ? $this->cates['name'] . '_' . C('WEB_NAME') : $this->cates['seo_title'];
    		$this->seo_keyword = $this->cates['seo_keyword'] == "" ? $this->cates['name'] . '_' . C('META_KEYWORD') : $this->cates['seo_keyword'];
    		$this->seo_description = $this->cates['seo_description'] == "" ? $this->cates['name'] . '_' . C('META_DESCRIPTION') : $this->cates['seo_description'];
    		$this->prev = $prev;
    		$this->next = $next;
    		$this->getTplName($cid, 2);
		} else {
    		echo json_encode('访问错误');
		}
    }
    public function search(){
    	$keyword = I('get.keyword');
    	$table_name = I('get.table_name');
    	$page_num = I('get.page_num');
    	$tpl = I('get.tpl');
    	if ($keyword){
    		$where['title'] = array('LIKE', '%' . $keyword . '%');
    		$count      = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->where($where)->count();
			// $Page       = new \Think\Page($count, $page_num);
            $Page       = new \Think\IndexPage($count, $page_num);
			$show       = $Page->show();
			$list = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->where($where) ->order('art_order asc, arc_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			$this->page = $show;
			$this->list = $list;
    	}
    	$this->keyword = $keyword;
    	$this->display($tpl);
    }
    private function getTplName($cid, $type = 0)
    {
        $cates = $this->cates;
    	if ($cid) {
    		if ($type == 0) {
    			$tpl_name = $cates['cate_tpl'];
    		} elseif ($type == 1) {
    			$tpl_name = $cates['page_tpl'];
    		} elseif ($type == 2) {
    			$tpl_name = $cates['show_tpl'];
    		}
    		if ($tpl_name) {
    			$tpl_name = str_replace('.html', '', $tpl_name);
    		}
    	}
    	$this->display($tpl_name);
    }
}
