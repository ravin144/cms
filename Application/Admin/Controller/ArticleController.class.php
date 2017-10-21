<?php
namespace Admin\Controller;

use Admin\Common\InitController;
use Admin\Model\ContentArticleModel;
use Common\Classes\Tree;

/**
 * 文章管理
 * 文章管理的增删改查等
 *
 * @author DoCan Ravin
 */
class ArticleController extends InitController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ContentArticleModel();
    }
    // index => main => cateList +arcList
    public function index()
    {
        $this->display();
    }
    public function main()
    {
        C('LAYOUT_ON', false);
        $this->display();
    }
    // 栏目列表
    public function cateList()
    {
        C('LAYOUT_ON', false);
        $category = M('Category');
        $list     = $category->where('type <> 2')->select();
        $tree     = $this->model->cateTree($list, 0);
        $this->assign('tree', json_encode($tree));
        $this->display();
    }
    // 文章列表
    public function arcList()
    {
        C('LAYOUT_ON', false);
        $category = M('Category');
        $cid      = I('get.id');
        if ($cid) {
            $data = $category->where('id=' . $cid)->find();

            if ($data['type'] != 1) {
                $model_id = $data['model_id'];
                // 获取模型的数据库表名称
                $table = $this->model->tableName($model_id);
                if ($table['status']) {
                    $tablem = $table['tablem']; // 首字母大写
                    $tableN = $table['tableN']; // 全大写
                } else {
                    $this->ajax($table['status'], $table['msg']);
                }
                // 文章排序
                if (IS_AJAX && IS_POST) {
                    $post = I('post.');
                    foreach ($post['art_order'] as $k => $v) {
                        M($tablem)->where('arc_id = ' . $k)->save(array('art_order' => $v));
                    }
                    $this->ajax(true, '排序成功');
                }
                // 分页
                $count = M($tablem)->where('cid=' . $cid)->count();
                $page  = new \Think\Page($count, 12);
                $show  = $page->show();
                // 将content_article & $table_name 关联
                $list = M($tablem)->join('dc_content_article ON __' . $tableN . '__.arc_id = dc_content_article.id')->where('cid=' . $cid)->limit($page->firstRow . ',' . $page->listRows)->order('art_order asc, arc_id desc')->select();
                // 推送的栏目树
                $cate_tree = new Tree();
                $cate_tree->tree($category->select());
                $tree = $cate_tree->get_tree(0, $model_id, "<option value=\$id \$disabled model_id = \$model_id>\$spacer\$name</option>");
                $this->assign('mid', $model_id);
                $this->assign('tree', $tree);
                $this->assign('list', $list);
                $this->assign('page', $show);
                $this->display();
            } else {
                // $this->assign('data', $data);
                // $this->display('arcPage');
            }
        }
    }
    // 添加文章
    public function add()
    {
        $category = M('Category');
        $cid      = I('get.cid');
        $model_id = $category->where('id=' . $cid)->getField('model_id');
        // 验证
        if ($cid) {
            if (!$model_id) {
                E('请选择模型');
            }
        } else {
            E('请选择栏目');
        }
        // 添加
        if (IS_AJAX && IS_POST) {
            $table            = $this->model->tableName($model_id);
            $table_name       = $table['tablem']; // 数据表名
            $post             = I('post.');
            $post['cid']      = $cid;
            $post['model_id'] = $model_id;
            $post['add_time'] = $post['add_time'] ? strtotime($post['add_time']) : time();
            $data             = $this->model->create($post);
            if (!$data) {
                $this->ajax(false, $this->model->getError());
            } else {
                $result = $this->model->arcAdd($table_name, $post);
                $this->ajax($result['status'], $result['msg']);
            }
        } else {
            $html = $this->model->preAdd($model_id);
            $this->assign('tpls', $html);
            $this->display();
        }
    }
    // 更新文章
    public function update()
    {
        $id  = I('get.id'); // 文章id
        $cid = I('get.cid'); // 栏目id
        $mid = M('category')->where('id=' . $cid)->getField('model_id');
        if (!$id) {
            E('没有该文章');
        }
        if ($cid) {
            if (!$mid) {
                E('请选择模型');
            }
            $table = $this->model->tableName($mid);
            if ($table['status']) {
                $tablem = $table['tablem']; // 首字母大写
                $tableN = $table['tableN']; // 全大写
            } else {
                $this->ajax($table['status'], $table['msg']);
            }
        } else {
            E('请选择栏目');
        }

        // 更改
        if (IS_AJAX && IS_POST) {
            $post   = I('post.');
            $result = $this->model->arcUpdate($tablem, $tableN, $post, $id);
            $this->ajax($result['status'], $result['msg']);
        } else {
            $data = M($tablem)->join('dc_content_article ON __' . $tableN . '__.arc_id = dc_content_article.id')->where('id=' . $id)->find();
            $html = $this->model->preAdd($mid, $data);
            $this->assign('data', $data);
            $this->assign('tpls', $html);
            $this->display();
        }
    }
    // 推送文章
    public function copy()
    {
        $clone_id = I('clone_id');
        $cid      = I('cid'); // 要推送的栏目
        $nid      = I('nid'); // 当前栏目id
        if (!$clone_id) {
            $this->ajax(false, '请选择要推送的文章');
        }
        if (!$cid) {
            $this->ajax(false, '请选择要推送的栏目');
        }
        $result = $this->model->arcCopy($clone_id, $nid, $cid);
        $this->ajax($result['status'], $result['msg']);
    }
    // 删除文章
    public function delete()
    {
        $id      = I('id');
        $cid     = I('get.cid');
        $id_list = I('arc_id');
        if (!$cid) {
            $this->ajax(false, '请选择栏目');
        }
        if (IS_AJAX) {
            if ($id) {
                $result = $this->model->arcDel($id, $cid);
                $this->ajax($result['status'], $result['msg']);
            } elseif ($id_list) {
                $result = $this->model->arcDel($id_list, $cid);
                $this->ajax($result['status'], $result['msg']);
            } else {
                $this->ajax(false, '参数缺失');
            }
        }
    }
}
