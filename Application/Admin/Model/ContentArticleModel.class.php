<?php
namespace Admin\Model;

use Think\Model;

/**
 * 文章模型
 * @author Ravin
 */
class ContentArticleModel extends Model
{
    // 验证
    protected $_validate = array(
        array('title', 'require', '请填写文章标题'),
    );
    // 字段html化
    public $default_tpl = array(
        'input_text'     => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" name="{value_name}" value="{value}" {data_type} class="form-control">
                                </div>
                            </div>',
        'input_radio'    => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10">
                                    {radio}
                                </div>
                            </div>',
        'input_checkbox' => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10">
                                    {checkbox}
                                </div>
                            </div>',
        'textarea'       => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10">
                                    <textarea name="{value_name}" {data_type} class="form-control">{value}</textarea>
                                </div>
                            </div>',
        'editor_full'    => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10">
                                    <script id="{value_name}" name="{value_name}" {data_type} type="text/plain" style="max-width:750px;height:800px;">{value}</script>
                                    <script>
                                        $(function(){
                                            var ue = UE.getEditor("{value_name}");
                                        });
                                    </script>
                                </div>
                            </div>',
        'editor_short'   => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10">
                                    <script id="{value_name}" name="{value_name}" {data_type} type="text/plain" style="max-width:750px;height:100px;">{value}</script>
                                    <script>
                                        $(function(){
                                            var ue = UE.getEditor("{value_name}", {
                                                toolbars: [],
                                                enableAutoSave: false,
                                                pasteplain: true,
                                                enableContextMenu: false,
                                            });
                                        });
                                    </script>
                                </div>
                            </div>',
        'image'          => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10 thumbnails">
                                    <div class="row thumbnails-list">
                                        {value}
                                    </div>
                                    <div class="row">
                                        <a class="btn btn-default upload-img" data-auto-create="1" data-aspect-ratio="" data-title-name="{value_name}_alt" data-name="{value_name}" data-max-upload="1">上传</a>
                                    </div>
                                </div>
                            </div>',
        'images'         => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10 thumbnails">
                                    <div class="row thumbnails-list">
                                        {value}
                                    </div>
                                    <div class="row">
                                        <a class="btn btn-default upload-img" data-auto-create="1" data-aspect-ratio="1.5" data-title-name="{value_name}_alts[]" data-name="{value_name}[]" data-max-upload="12">上传</a>
                                    </div>
                                </div>
                            </div>',
        'file'           => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10 files">
                                    {value}
                                    <a class="btn btn-default upload-file" data-auto-create="1" data-title-name="{value_name}_title" data-name="{value_name}" data-max-upload="1">上传</a>
                                </div>
                            </div>',
        'files'          => '<div class="form-group">
                                <label class="col-sm-2 control-label">
                                    {tip_msg} {value_title}
                                </label>
                                <div class="col-sm-10 files">
                                    {value}
                                    <a class="btn btn-default upload-file" data-auto-create="1" data-title-name="{value_name}_titles[]" data-name="{value_name}[]" data-max-upload="9">上传</a>
                                </div>
                            </div>',
    );
    /**
     * [tree_func 栏目树]
     * @Author   DoCan
     * @param    array      $list   栏目数组
     * @param    integer    $id     栏目id
     * @param    integer    $num    [description]
     * @return   array              栏目树
     */
    public function cateTree($list, $id = 0, $num = 0)
    {
        $num++;
        foreach ($list as $key => $value) {
            if ($value['pid'] == $id) {
                if (num < 3) {
                    $state = array('opened' => true);
                } else {
                    $state = array('opened' => false);
                }
                $icon = ($value['type'] == 1 ? 'jstree-file' : '');
                if ($t = $this->cateTree($list, $value['id'], $num)) {
                    $tree[] = array('id' => $value['id'], 'text' => $value['name'], 'children' => $t, 'state' => $state);
                } else {
                    $tree[] = array('id' => $value['id'], 'text' => $value['name'], 'icon' => $icon);
                }
            }
        }
        return $tree;
    }
    /**
     * [getTable 获取模型的数据库表名称]
     * @Author Ravin
     * @param  integer $model_id 模型id
     * @return array             表名
     */
    public function tableName($model_id)
    {
        $model = M('ContentModel');
        if ($model_id) {
            $table_name = $model->where('id=' . $model_id)->getField('table_name');
        } else {
            return array(
                'status' => false,
                'msg'    => '此栏目没有选择模型',
            );
        }
        $tablen = explode('_', $table_name);
        $tableN = strtoupper($table_name);
        foreach ($tablen as $k => $v) {
            $tablem .= ucfirst($v);
        }
        return array(
            'status' => true,
            'tableN' => $tableN, // 全大写 CONTENT_ARTICLE_TEST
            'tablem' => $tablem, // 首字母大写 ContentArticleTest
            'tablen' => $table_name // 字母小写 content_article_test
        );
    }
    /**
     * [preAdd 文章要展示的字段]
     * @Author Ravin
     * @param  integer $model_id 模型id
     * @return string  $html     字段html拼装
     */
    public function preAdd($model_id, $data = '')
    {
        $fields = M('content_model_field')->where(
            array(
                'model_id'    => $model_id,
                'is_disabled' => 0,
            ))->order('listorder asc')->select();
        foreach ($fields as $k => $v) {
            foreach ($this->default_tpl as $key => $value) {
                if ($v['input_type'] == $key) {
                    //对表单中的相应值进行替换和form表单语句的拼装
                    $con     = '';
                    $options = explode(PHP_EOL, trim($v['field_options']));
                    $default = explode('|', $v['default_value']);
                    if ($v['input_type'] == 'input_radio') {
                        foreach ($options as $kf => $vf) {
                            $options_val[$kf] = explode('|', $vf);
                            if ($data[$v['field_name']] == $options_val[$kf][0]) {
                                $checked = "checked='checked'";
                            } elseif ($default[0] == $options_val[$kf][0]) {
                                $checked = "checked='checked'";
                            } else {
                                $checked = "";
                            }
                            $con .= '<label class="radio-inline">
                                        <input type="radio" ' . $checked . ' name="' . $v['field_name'] . '" value="' . $options_val[$kf][0] . '">
                                        ' . $options_val[$kf][1] . '
                                     </label>';
                        }
                    } elseif ($v['input_type'] == 'input_checkbox') {
                        foreach ($options as $kf => $vf) {
                            $options_val[$kf] = explode('|', $vf);
                            if ($data[$v['field_name']]) {
                                $default = explode(',', $data[$v['field_name']]);
                            }
                            if (in_array($options_val[$kf][0], $default)) {
                                $checked = "checked='checked'";
                            } else {
                                $checked = "";
                            }
                            $con .= '<label class="checkbox-inline">
                                        <input type="checkbox" ' . $checked . ' name="' . $v['field_name'] . '[]" value="' . $options_val[$kf][0] . '">
                                        ' . $options_val[$kf][1] . '
                                     </label>';
                        }
                    } elseif ($v['input_type'] == 'editor_full' || $v['input_type'] == 'editor_short') {
                        $data[$v['field_name']] = htmlspecialchars_decode($data[$v['field_name']]);
                    } elseif ($v['input_type'] == 'image' && $data[$v['field_name']]) {
                        if ($data[$v['field_name']] != 'NULL') {
                            $data[$v['field_name']] = '<img src="' . $data[$v['field_name']] . '" alt="' . get_alt($data[$v['field_name']]) . '">';
                        }
                    } elseif ($v['input_type'] == 'images' && $data[$v['field_name']]) {
                        $images                 = explode(',', $data[$v['field_name']]);
                        $data[$v['field_name']] = '';
                        foreach ($images as $ki => $vi) {
                            $data[$v['field_name']] .= '<img src="' . $vi . '" alt="' . get_alt($vi) . '">';
                        }
                    } elseif ($v['input_type'] == 'file' && $data[$v['field_name']]) {
                        if ($data[$v['field_name']] != 'NULL') {
                            $data[$v['field_name']] = '<input type="text" value="' . $data[$v['field_name']] . '" title="' . get_file_title($data[$v['field_name']]) . '"/>';
                        } else {
                            $data[$v['field_name']] = '';
                        }
                    } elseif ($v['input_type'] == 'files' && $data[$v['field_name']]) {
                        $files                  = explode(',', $data[$v['field_name']]);
                        $data[$v['field_name']] = '';
                        foreach ($files as $ki => $vi) {
                            $data[$v['field_name']] .= '<input type="text" value="' . $vi . '" title="' . get_file_title($vi) . '"/>';
                        }
                    }
                    if (!$v['field_length'] && !$v['preg_params'] && !$v['is_must']) {
                        $data_type = " ";
                    } else {
                        // 是否必填
                        if ($v['is_must']) {
                            $tp        = '*1-';
                            $other_msg = "' nullmsg='" . $v['field_title'] . "不能为空' errormsg='" . $v['error_msg'] . "'";
                        } else {
                            $tp        = '*0-';
                            $other_msg = "' nullmsg='' errormsg='" . $v['error_msg'] . "'";
                        }
                        // 匹配
                        if ($v['field_length'] && $v['preg_params']) {
                            $tp .= $v['field_length'] . ',' . $v['preg_params'];
                        } elseif ($v['preg_params']) {
                            $tp .= $v['preg_params'];
                        } else {
                            $tp .= $v['field_length'];
                        }

                        $data_type    = "datatype='" . $tp . $other_msg;
                        $v['tip_msg'] = $v['field_tip'] ? '<span class="glyphicon glyphicon-info-sign" data-toggle="tooltip" data-placement="bottom" data-original-title="' . $v['field_tip'] . '"></span>' : '';
                    }
                    $html .= str_replace('{data_type}', $data_type, str_replace('{value}', $data[$v['field_name']], str_replace('{tip_msg}', $v['tip_msg'], str_replace('{value_name}', $v['field_name'], str_replace('{value_title}', $v['field_title'], str_replace(array('{option}', '{checkbox}', '{radio}'), $con, $value))))));
                }
            }
        }
        return $html;
    }
    /**
     * [arcAdd 添加文章]
     * @Author Ravin
     * @param  [string] $table_name [数据表名]
     * @param  [array]  $post       [数据]
     * @return [array]              [操作信息]
     */
    public function arcAdd($table_name, $post)
    {
        $data = array(
            'model_id'        => $post['model_id'],
            'title'           => $post['title'],
            'seo_title'       => $post['seo_title'],
            'seo_keyword'     => $post['seo_keyword'],
            'seo_description' => $post['seo_description'],
            'add_time'        => $post['add_time'],
        );
        if ($id = $this->add($data)) {
            // content_article
            $data['url']      = $post['url'] ? $post['url'] : __ROOT__ . '/show/' . $post['cid'] . '/' . $id . '.html';
            $data['clone_id'] = $id;
            // 将post中数组的数据组成string
            foreach ($post as $key => $value) {
                if (is_array($value)) {
                    $post[$key] = implode(',', $value);
                    // 修改多图的alt
                    $has_alt = strpos($key, '_alts');
                    if ($has_alt !== false) {
                        $pre     = explode('_alts', $key);
                        $pre_alt = $pre[0];
                        $val     = explode(',', $post[$pre_alt]);
                        $alt     = explode(',', $post[$key]);
                        foreach ($val as $k => $v) {
                            M('UploadImage')->where(array('url' => $v))->setField('title', $alt[$k]);
                        }
                    }
                    // 修改多文件上传的title
                    $has_title = strpos($key, '_titles');
                    if ($has_title !== false) {
                        $pre       = explode('_titles', $key);
                        $pre_title = $pre[0];
                        $val       = explode(',', $post[$pre_title]);
                        $title     = explode(',', $post[$key]);
                        foreach ($val as $k => $v) {
                            M('UploadFile')->where(array('url' => $v))->setField('title', $title[$k]);
                        }
                    }
                } else {
                    $has_alt = strpos($key, '_alt');
                    if ($has_alt !== false) {
                        $pre     = explode('_alt', $key);
                        $pre_alt = $pre[0];
                        M('UploadImage')->where(array('url' => $post[$pre_alt]))->setField('title', $post[$key]);
                    }
                    $has_title = strpos($key, '_title');
                    if ($has_title !== false) {
                        $pre       = explode('_title', $key);
                        $pre_title = $pre[0];
                        M('UploadFile')->where(array('url' => $post[$pre_title]))->setField('title', $post[$key]);
                    }
                }
            }
            // table_name
            $table                = M($table_name);
            $table_data           = $table->create($post);
            $table_data['arc_id'] = $id;
            if (!$table->add($table_data)) {
                return array('status' => false, 'msg' => '文章添加失败');
            }
            $this->where('id = ' . $id)->save($data);
            return array('status' => true, 'msg' => '文章添加成功');
        } else {
            return array('status' => false, 'msg' => '文章添加失败');
        }
    }
    /**
     * [arcUpdate 更新文章]
     * @Author Ravin
     * @param  [string] $table_name [表名]
     * @param  [string] $table      [表名大写]
     * @param  [array]  $post       [数据]
     * @param  [int]    $id         [文章id]
     * @return [array]              [信息]
     */
    public function arcUpdate($table_name, $tableN, $post, $id)
    {
        $post['add_time'] = strtotime($post['add_time']);
        $clone_id         = $this->where('id = ' . $id)->getField('clone_id');
        $id_list          = $this->where('clone_id = ' . $clone_id)->getField('id', true);
        // 更新图片alt、上传文件title
        foreach ($post as $key => $value) {
            // 将post中数组的数据组成string
            if (is_array($value)) {
                $post[$key] = implode(',', $value);
                // 修改多图的alt
                $has_alt = strpos($key, '_alts');
                if ($has_alt !== false) {
                    $pre     = explode('_alts', $key);
                    $pre_alt = $pre[0];
                    $val     = explode(',', $post[$pre_alt]);
                    $alt     = explode(',', $post[$key]);
                    foreach ($val as $k => $v) {
                        M('UploadImage')->where(array('url' => $v))->setField('title', $alt[$k]);
                    }
                }
                // 修改多文件上传的title
                $has_title = strpos($key, '_titles');
                if ($has_title !== false) {
                    $pre       = explode('_titles', $key);
                    $pre_title = $pre[0];
                    $val       = explode(',', $post[$pre_title]);
                    $title     = explode(',', $post[$key]);
                    foreach ($val as $k => $v) {
                        M('UploadFile')->where(array('url' => $v))->setField('title', $title[$k]);
                    }
                }
            } else {
                $has_alt = strpos($key, '_alt');
                if ($has_alt !== false) {
                    $pre     = explode('_alt', $key);
                    $pre_alt = $pre[0];
                    M('UploadImage')->where(array('url' => $post[$pre_alt]))->setField('title', $post[$key]);
                }
                $has_title = strpos($key, '_title');
                if ($has_title !== false) {
                    $pre       = explode('_title', $key);
                    $pre_title = $pre[0];
                    M('UploadFile')->where(array('url' => $post[$pre_title]))->setField('title', $post[$key]);
                }
            }
        }
        $data       = $this->create($post);
        $table      = M($table_name);
        $table_data = $table->create($post);
        if ($data && $table_data) {
            $this->where('id = ' . $id)->save($data);
            $table->where('arc_id = ' . $id)->save($table_data);
            // 更新克隆文章
            if ($post['is_clone']) {
                $where = array(
                    'id'       => array('neq', $id),
                    'clone_id' => $clone_id,
                );
                foreach ($id_list as $key => $value) {
                    $clone_data = $data;
                    if (strpos($data['url'], '/show') !== false) {
                        unset($clone_data['url']);
                    }
                    $this->where($where)->save($clone_data);
                }
                $arc_id_list = $table->join('dc_content_article ON __' . $tableN . '__.arc_id = dc_content_article.id')->where($where)->getField('arc_id', true);
                if ($arc_id_list) {
                    $map['arc_id'] = array('in', $arc_id_list);
                    $table->where($map)->save($table_data);
                }
            }
            return array('status' => true, 'msg' => '文章更新成功');
        } else {
            return array('status' => false, 'msg' => '文章更新失败');
        }
    }
    /**
     * [arcCopy 推送文章]
     * @Author Ravin
     * @param  [type] $clone_id [待推送文章id]
     * @param  [type] $nid      [当前栏目id]
     * @param  [type] $cid      [待推送栏目id]
     * @return [type]           [操作信息]
     */
    public function arcCopy($clone_id, $nid, $cid)
    {
        $mid      = M('category')->where('id = ' . $nid)->getField('model_id');
        $show_tpl = M('category')->where('id = ' . $nid)->getField('show_tpl');
        $table    = $this->tableName($mid);
        if ($table['status']) {
            $tablem = $table['tablem']; // 首字母大写
            $tableN = $table['tableN']; // 全大写
        } else {
            return array(
                'status' => $table['status'],
                'msg'    => $table['msg'],
            );
        }
        $where['id'] = array('in', $clone_id);
        $list_data   = array();
        $index       = 0;
        $clone_list  = M($tablem)->join('dc_content_article ON __' . $tableN . '__.arc_id = dc_content_article.id')->where($where)->select();
        foreach ($clone_list as $key => $value) {
            foreach ($cid as $k => $v) {
                $cid_show_tpl      = M('category')->where('id = ' . $v)->getField('show_tpl');
                $list_data[$index] = $value;
                unset($list_data[$index]['id']);
                unset($list_data[$index]['arc_id']);
                if ($show_tpl != $cid_show_tpl && strpos($list_data[$index]['url'], '/show') !== false) {
                    unset($list_data[$index]['url']);
                    $update_url = 1;
                }
                $list_data[$index]['arc_order'] = 999;
                $list_data[$index]['cid']       = $v;
                //插入新数据
                $id                          = M('content_article')->data($list_data[$index])->add();
                $list_data[$index]['arc_id'] = $id;
                $arc_id                      = M($tablem)->data($list_data[$index])->add();
                //更新新数据url
                if ($update_url) {
                    M('content_article')->where('id = ' . $id)->save(array('url' => __ROOT__ . '/show/' . $list_data[$index]['cid'] . '/' . $id . '.html'));
                }
            }
            $index++;
        }
        return array('status' => true, 'msg' => '推送成功');
    }
    /**
     * [arcDel 删除文章]
     * @Author Ravin
     * @param  [type] $id  [文章id]
     * @param  [type] $cid [栏目id]
     * @return [type]      [操作信息]
     */
    public function arcDel($id, $cid)
    {
        $mid   = M('category')->where('id = ' . $cid)->getField('model_id');
        $table = $this->tableName($mid);
        if ($table['status']) {
            $tablem = $table['tablem']; // 首字母大写
        } else {
            return array(
                'status' => $table['status'],
                'msg'    => $table['msg'],
            );
        }
        $where['id']   = array('in', $id);
        $map['arc_id'] = array('in', $id);
        $this->where($where)->delete();
        M($tablem)->where($map)->delete();
        return array('status' => true, 'msg' => '删除成功');
    }
}
