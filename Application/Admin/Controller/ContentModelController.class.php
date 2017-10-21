<?php
namespace Admin\Controller;

use Admin\Common\InitController;
use Admin\Model\ContentModelModel;
use Admin\Model\ContentModelFieldModel;

/**
 * 模型管理
 * 模型管理，模型字段管理的增删改查等
 *
 * @author shixu Ravin
 *
 */
class ContentModelController extends InitController{
    private $model;
    private $field_type = array(
        // value_type是数据库字段类型，value_length是默认字段长度
        'input_text' => array(
            'input_title' => '单行输入框[type=text]',
            'value_type' => 'VARCHAR',
            'value_length' => '250'
        ),
        'input_radio' => array(
            'input_title' => '单选[type=radio]',
            'value_type' => 'VARCHAR',
            'value_length' => '80'
        ),
        'input_checkbox' => array(
            'input_title' => '多选[type=checkbox]',
            'value_type' => 'VARCHAR',
            'value_length' => '80'
        ),
        'textarea' => array(
            'input_title' => '多行输入框textarea',
            'value_type' => 'VARCHAR',
            'value_length' => '250'
        ),
        'editor_full' => array(
            'input_title' => 'UEditor编辑器标准版',
            'value_type' => 'TEXT',
            'value_length' => ''
        ),
        'editor_short' => array(
            'input_title' => 'UEditor编辑器简洁版 ',
            'value_type' => 'TEXT',
            'value_length' => ''
        ),
        // 时间格式的数据库都以时间戳存储
        // 'date' => array(
        //     'input_title' => '日期[xxxx-xx-xx]',
        //     'value_type' => 'INT',
        //     'value_length' => '11'
        // ),
        // 'time' => array(
        //     'input_title' => '时间[xx:xx]',
        //     'value_type' => 'INT',
        //     'value_length' => '11'
        // ),
        // 'date_time' => array(
        //     'input_title' => '日期时间[xxxx-xx-xx xx:xx]',
        //     'value_type' => 'INT',
        //     'value_length' => '11'
        // ),
        // 多图和多文件的数据库都用逗号分隔链接地址形式存储
        'image' => array(
            'input_title' => '单图上传',
            'value_type' => 'CHAR',
            'value_length' => '80'
        ),
        'images' => array(
            'input_title' => '多图上传',
            'value_type' => 'TEXT',
            'value_length' => ''
        ),
        'file' => array(
            'input_title' => '单文件上传',
            'value_type' => 'CHAR',
            'value_length' => '80'
        ),
        'files' => array(
            'input_title' => '多文件上传',
            'value_type' => 'TEXT',
            'value_length' => ''
        )
    );

    public function __construct()
    {
        parent::__construct();
        $this->model = new ContentModelModel();
    }

    /**
     * 模型列表
     */
    public function index()
    {
        $list = $this->model->select();
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 新增模型
     */
    public function add() {
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            $post['add_time'] = time();
            $post['update_time'] = time();
            $data = $this->model->create($post);
            if (! $data) {
                $this->ajax(false, $this->model->getError());
            } else {
                $result = $this->model->modelAdd($data);
                if ($result['status']) {
                    $model_id = $this->model->getLastInsID(); // 获取自动增长的id
                    $this->updateModelCache($model_id);
                }
                $this->ajax($result['status'], $result['msg']);
            }
        }
        $default_value = $this->model->default_value;
        $this->assign('default_value', $default_value);
        $this->display();
    }

    /**
     * 编辑模型
     */
    public function update() {
        $id = I('get.id');
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            $post['update_time'] = time();
            $data = $this->model->create($post);
            if (! $data) {
                $this->ajax(false, $this->model->getError());
            } else {
                $result = $this->model->modelUpdate($id, $data);
                if ($result['status']) {
                    $this->updateModelCache($model_id);
                }
                $this->ajax($result['status'], $result['msg']);
            }
        }
        if ($id && $data = $this->model->where('id = ' . $id)->find()) {
            $data['table_name'] = str_replace('content_article_', '', $data['table_name']);
            $this->assign('data', $data);
        } else {
            $this->error('模型不存在');
        }
        $this->display();
    }

    /**
     * 删除模型
     */
    public function delete()
    {
        $id = I('get.id');
        if ($id) {
            $result = $this->model->modelDelete($id);
            if ($result['status']) {
                $this->updateModelCache($id);
            }
            $this->ajax($result['status'], $result['msg']);
        } else {
            $this->ajax(false, '参数缺失');
        }
    }

    /**
     * 字段列表
     */
    public function field() {
        $post['listorder'] = I('post.listorder');
        if ($post['listorder']) {
            foreach ($post['listorder'] as $k => $v) {
                M('ContentModelField')->where('id = ' . $k)->save(array('listorder' => $v));
            }
            $this->ajax(true, '排序成功');
            $this->display();
        }

        $model_id = I('get.model_id');
        if ($model_id) {
            $field_list = M('ContentModelField')->where('model_id = ' . $model_id)
                ->order('listorder ASC, id ASC')
                ->select();
            foreach ($field_list as $k => $v) {
                $field_list[$k]['input_title'] = $this->field_type[$v['input_type']]['input_title'];
            }
            $this->assign('list', $field_list);
        } else {
            $this->error('模型不存在');
        }
        $this->display();
    }

    /**
     * 添加字段
     */
    public function fieldAdd() {
        $model_id = I('get.model_id');
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            $contentModelFieldModel = new ContentModelFieldModel();
            if (! $data = $contentModelFieldModel->create($post)) {
                $this->ajax(false, $contentModelFieldModel->getError());
            } else {
                $result = $contentModelFieldModel->modelFieldAdd($model_id, $data);
                if ($result['status']) {
                    $this->updateModelCache($data['model_id']);
                }
                $this->ajax($result['status'], $result['msg']);
            }
        }
        if (! $model_id) {
            $this->error('请选择一个模型');
        }
        $this->assign('field_type', $this->field_type);
        $this->display();
    }

    /**
     * 修改字段
     */
    public function fieldUpdate() {
        $field_id = I('get.id');
        $contentModelFieldModel = new ContentModelFieldModel();
        if (IS_AJAX && IS_POST) {
            $post = I('post.');
            if (! $data = $contentModelFieldModel->create($post)) {
                $this->ajax(false, $contentModelFieldModel->getError());
            } else {
                $result = $contentModelFieldModel->modelFieldUpdate($field_id, $data);
                if ($result['status']) {
                    $model_id = $contentModelFieldModel->where('id = ' . $field_id)->getField('model_id');
                    $this->updateModelCache($model_id);
                }
                $this->ajax($result['status'], $result['msg']);
            }
        }
        if ($field_id && $data = $contentModelFieldModel->where('id = ' . $field_id)->find()) {
            $this->assign('field_type', $this->field_type);
            $this->assign('data', $data);
        } else {
            $this->error('字段不存在，请重试');
        }
        $this->display();
    }

    /**
     * 删除字段
     */
    public function fieldDelete()
    {
        $field_id = I('get.id');
        if ($field_id) {
            $contentModelFieldModel = new ContentModelFieldModel();
            $model_id = $contentModelFieldModel->where('id = ' . $field_id)->getField('model_id');
            $result = $contentModelFieldModel->modelFieldDelete($field_id);
            if ($result['status']) {
                $this->updateModelCache($model_id);
            }
            $this->ajax($result['status'], $result['msg']);
        } else {
            $this->ajax(false, '字段不存在');
        }
    }

    /**
     * 字段禁用
     */
    public function fieldDisabled()
    {
        $field_id = I('get.id');
        if ($field_id && M('ContentModelField')->where('id = ' . $field_id)->setField('is_disabled', I('get.is_disabled'))) {
            $model_id = M('ContentModelField')->where('id = ' . $field_id)->getField('model_id');
            $this->updateModelCache($model_id);
            $this->ajax(true, '操作成功');
        } else {
            $this->ajax(false, '操作失败，请重试');
        }
    }

    /**
     * 更新模型缓存
     */
    private function updateModelCache($model_id)
    {
        $cacheControl = new CacheController();
        $cacheControl->updateModel($model_id);
    }
}
