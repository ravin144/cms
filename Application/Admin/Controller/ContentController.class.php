<?php
namespace Admin\Controller;

use Admin\Common\InitController;
use Admin\Model\CategoryModel;

/**
 * 栏目管理
 * 栏目分类管理的增删改查等
 *
 * @author shixu Ravin
 */
class ContentController extends InitController {

    public function __construct() {
        parent::__construct();
        $this->model = D('category');
    }

    /**
     * 栏目列表
     */
    public function category() {
        $ContentModel = M('ContentModel')->field('id, model_name')->select();

        // 排序
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            foreach ($post['cate_order'] as $k => $v) {
                $this->model->where('id = ' . $k)->save(array('cate_order' => $v));
            }
            $this->ajax(true, '排序成功');
        }

        // 栏目是否显示
        if (IS_AJAX && IS_GET) {
            $type = I('type');
            $id = I('id');
            if ($type == 'change_is_show' && $id) {
                $is_show = $this->model->where('id = ' . $id)->getField('is_show');
                if ($is_show == 1) {
                    $this->model->where('id = ' . $id)->save(array('is_show' => 0));
                } else {
                    $this->model->where('id = ' . $id)->save(array('is_show' => 1));
                }
                $this->ajax(true, '状态修改成功');
            }
        }

        $Category_arr = $this->model->order('cate_order ASC, id ASC')->select();
        foreach ($Category_arr as $k => $v) {
            // 所属模型id => 所属模型名称
            foreach ($ContentModel as $kk => $vv) {
                if (isset($v['model_id']) && $v['type'] == 0) {
                    if ($v['model_id'] == $vv['id']) {
                        $Category_arr[$k]['model_name'] = $vv['model_name'];
                    }
                } else {
                    $Category_arr[$k]['model_name'] = ''; // 隐藏单页、链接页默认的所属模型名称
                }
            }
            // 栏目id => 栏目类型
            if ($v['type'] == 0) {
                $Category_arr[$k]['type_name'] = '列表';
            } elseif ($v['type'] == 1) {
                $Category_arr[$k]['type_name'] = '<span style="color:#0060ff">单页</span>';
            } elseif ($v['type'] == 2) {
                $Category_arr[$k]['type_name'] = '<span style="color:#ff3600">链接</span>';
            }
        }

        // 栏目树
        $icon = array(
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico1.png" style="height:20px;"/>',
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico2.png" style="height:20px;"/>',
            '<img src="' . __ROOT__ . '/Public/admin/images/menu_ico3.png" style="height:20px;"/>'
            );

        // 栏目操作
        $str = "
            <tr>
                <td>
                    <input type='text' class='form-control input-sm' name='cate_order[\$id]' value='\$cate_order' style='width:40px; text-align:center;'/>
                </td>
                <td>\$id</td>
                <td>\$spacer\$name</td>
                <td>\$type_name</td>
                <td>\$model_name</td>
                <td class='is_show' data-id='\$id' data-is-show='\$is_show'></td>
                <td class='last-menu'>
                    <a href='\" . U('categoryAdd', array('pid' => \$id, 'type' => \$type)) . \"'>添加子分类</a>
                    <a href='\" . U('categoryUpdate', array('id' => \$id, 'pid' => \$pid, 'type' => \$type)) . \"'>编辑</a>
                    <a href='' class='ajax-get' data-url='\" . U('categoryDelete', array('id' => \$id)) .
                        \"' data-location='reload'
                        data-confirm='确定要删除吗？'>删除</a>
                </td>
            </tr>";
        $list = $this->model->getCateTree(0, 0, $str, $icon, $Category_arr);
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 模板名的遍历
     */
    public function displayModel() {
        $hostdir = dirname('./Application/Home/View/Index/index.html'); // 获取本文件目录的文件夹地址
        $filearr = array(
            'cate' => array(
                'cate.html'
                ),
            'show' => array(
                'show.html'
                ),
            'page' => array(
                'page.html'
                )
            );
        $filesnames = array();
        if($handle = opendir($hostdir)) {
            while (false !== ($file = readdir($handle))) {
                if($file != '.' && $file != '..') {
                    $path = $file;
                    $filesnames[$i] = $path;
                    $i++;
                }
            }
        } else {
            return $filesnames;
        }
        foreach ($filesnames as $k => $v) {
            if (stristr($v, '.html')) {
                $filestr = substr($v, 0, 5);
                switch ($filestr) {
                    case 'cate_':
                    $filearr['cate'][] = $v;
                    break;
                    case 'show_':
                    $filearr['show'][] = $v;
                    break;
                    case 'page_':
                    $filearr['page'][] = $v;
                    break;
                    default:
                    break;
                }
            }
        }
        $this->assign('filearr', $filearr);
    }

    /**
     * 栏目页、单页、链接页的添加
     */
    public function categoryAdd() {
        // 提交
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            $data = $this->model->create($post);
            $data['alt'] = $post['alt'];
            if (! $data) {
                $this->ajax(false, $this->model->getError());
            } else {
                $result = $this->model->cateAdd($data);
                if ($result['status']) {
                    $cate_id = $this->model->getLastInsID(); // 获取自动增长的id
                    $this->updateCateCache($cate_id);
                }
                $this->ajax($result['status'], $result['msg']);
            }
        }
        // 上级栏目列表
        $select_id = I('pid') ? I('pid') : 0;
        $cate_str = $this->model->getCateTree(0, $select_id, '', '', '', '');
        $this->assign('cate_str', $cate_str);

        // 所属模型列表
        // 父级栏目的模型
        if ($_GET['pid']) {
            $parentData = $this->model->where('id=' . $_GET['pid'])->find();
            $this->assign('parentData', $parentData);
        }
        $this->assign('model_list', M('ContentModel')->select());

        $this->displayModel();
        $this->display();
    }

    /**
     * 栏目页、单页、链接页的编辑
     */
    public function categoryUpdate() {
        $id = I('get.id');
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            $create_data = $this->model->create($post);
            $create_data['alt'] = $post['alt'];
            $create_data['model_inherit'] = $post['model_inherit'];
            $create_data['tpl_inherit'] = $post['tpl_inherit'];
            if (! $create_data) {
                $this->ajax(false, $this->model->getError());
            } else {
                $result = $this->model->cateUpdate($id, $create_data);
                if ($result['status']) {
                    $this->updateCateCache($id);
                }
                $this->ajax($result['status'], $result['msg']);
            }
        }

        // 上级栏目列表
        $select_id = I('get.pid') ? I('get.pid') : 0;
        $cate_str = $this->model->getCateTree(0, $select_id, '', '', '', '');
        $this->assign('cate_str', $cate_str);

        // 所属模型列表
        $this->assign('model_list', M('content_model')->select());

        $data = $this->model->where('id=' . $id)->find();
        $data['alt'] = M('UploadImage')->where(array('url' => $data['image']))->getField('title');
        $this->assign('data', $data);
        $this->displayModel();
        $this->display();
    }

    /**
     * 栏目页、单页、链接页的删除
     */
    public function categoryDelete() {
        $id = I('get.id');
        if ($id) {
            $result = $this->model->cateDelete($id);
            if ($result['status']) {
                $this->updateCateCache($id);
            }
            $this->ajax($result['status'], $result['msg']);
        } else {
           $this->ajax(false, '参数缺失');
        }
    }

    /**
     * 更新栏目缓存
     */
    private function updateCateCache($cate_id) {
        $cacheControl = new CacheController();
        $cacheControl->updateCate($cate_id);
    }
}
