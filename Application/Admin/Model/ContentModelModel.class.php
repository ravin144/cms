<?php
namespace Admin\Model;
use Think\Model;

/**
 * 文章模型model
 *
 * @author shixu Ravin
 *
 */
class ContentModelModel extends Model {

    protected $_validate = array(
        array(
            'model_name',
            'require',
            '请填写模型名称'
        ),
        array(
            'table_name',
            'require',
            '请填写模型表名'
        ),
        array(
            'table_name',
            '/^[a-z][a-z0-9_]*[a-z0-9]$/',
            '模型表名格式不正确',
            0,
            'regex'
        )
    );

    // 基本字段
    public $default_value = array(
        array(
            'field_name' => 'title',
            'field_title' => '标题',
            'input_type' => 'input_text',
            'field_type' => 'VARCHAR',
            'field_length' => 250,
            'field_options' => '',
            'preg_params' => '*1-250',
            'is_system' => 1,
            'is_must' => 1,
            'is_disabled' => 0,
            'listorder' => 1
        ),
        // array(
        // 'field_name' => 'description',
        // 'field_title' => '描述',
        // 'input_type' => 'textarea',
        // 'field_type' => 'VARCHAR',
        // 'field_length' => 300,
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 1,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 2
        // ),
        // array(
        // 'field_name' => 'thumb',
        // 'field_title' => '缩略图',
        // 'input_type' => 'image',
        // 'field_type' => 'VARCHAR',
        // 'field_length' => 100,
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 1,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 3
        // ),
        // array(
        // 'field_name' => 'author',
        // 'field_title' => '作者',
        // 'input_type' => 'input_text',
        // 'field_type' => 'CHAR',
        // 'field_length' => 30,
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 1,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 4
        // ),
        // array(
        // 'field_name' => 'source',
        // 'field_title' => '来源',
        // 'input_type' => 'input_text',
        // 'field_type' => 'CHAR',
        // 'field_length' => 50,
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 1,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 5
        // ),
        // array(
        // 'field_name' => 'click_count',
        // 'field_title' => '点击数量',
        // 'input_type' => 'input_text',
        // 'field_type' => 'TINYINT',
        // 'field_length' => 5,
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 1,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 6
        // ),
        // array(
        // 'field_name' => 'content',
        // 'field_title' => '内容',
        // 'input_type' => 'editor_full',
        // 'field_type' => 'TEXT',
        // 'field_length' => '',
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 0,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 7
        // ),
        array(
            'field_name' => 'seo_title',
            'field_title' => 'SEO标题',
            'input_type' => 'input_text',
            'field_type' => 'VARCHAR',
            'field_length' => 120,
            'field_options' => '',
            'preg_params' => '',
            'is_system' => 1,
            'is_must' => 0,
            'is_disabled' => 1,
            'listorder' => 99
        ),
        array(
            'field_name' => 'seo_keyword',
            'field_title' => 'SEO关键字',
            'input_type' => 'input_text',
            'field_type' => 'VARCHAR',
            'field_length' => 120,
            'field_options' => '',
            'preg_params' => '',
            'is_system' => 1,
            'is_must' => 0,
            'is_disabled' => 1,
            'listorder' => 99
        ),
        array(
            'field_name' => 'seo_description',
            'field_title' => 'SEO描述',
            'input_type' => 'textarea',
            'field_type' => 'VARCHAR',
            'field_length' => 300,
            'field_options' => '',
            'preg_params' => '',
            'is_system' => 1,
            'is_must' => 0,
            'is_disabled' => 1,
            'listorder' => 99
        ),
        // array(
        // 'field_name' => 'relation',
        // 'field_title' => '相关文章',
        // 'input_type' => 'input_text',
        // 'field_type' => 'VARCHAR',
        // 'field_length' => 100,
        // 'field_options' => '',
        // 'preg_params' => '',
        // 'is_system' => 1,
        // 'is_must' => 0,
        // 'is_disabled' => 0,
        // 'listorder' => 99
        // ),
        array(
            'field_name' => 'url',
            'field_title' => '链接',
            'input_type' => 'input_text',
            'field_type' => 'VARCHAR',
            'field_length' => 120,
            'field_options' => '',
            'preg_params' => '',
            'is_system' => 1,
            'is_must' => 0,
            'is_disabled' => 1,
            'listorder' => 99
        ),
        array(
            'field_name' => 'add_time',
            'field_title' => '添加时间',
            'input_type' => 'date_time',
            'field_type' => 'INT',
            'field_length' => 11,
            'field_options' => '',
            'preg_params' => '',
            'is_system' => 1,
            'is_must' => 0,
            'is_disabled' => 1,
            'listorder' => 99
        )
    );

    /**
     * 新增模型
     *
     * @param array $data
     * @return array('status'=>bool, 'msg'=>string);
     */
    public function modelAdd($data) {
        $data['table_name'] = 'content_article_' . $data['table_name'];
        if ($this->where("table_name = '{$data['table_name']}'")->count()) {
            return array(
                'status' => false,
                'msg' => '表名' . $data['table_name'] . '已存在'
            );
        }

        // 此处要先新增模型数据，在新增模型默认字段数据，最后创建数据表，所以要启用事务
        $this->startTrans();
        if (! $model_id = $this->add($data)) {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '新增失败，请重试，错误信息:' . $this->getDbError()
            );
        }
        // 插入模型默认字段
        $default_value = $this->default_value;
        foreach ($default_value as $k => $v) {
            $default_value[$k]['model_id'] = $model_id;
        }
        $default_value = $default_value[0];
        if (! M('ContentModelField')->add($default_value)) {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '新增默认字段时失败，请重试，错误信息:' . M('ContentModelField')->getDbError()
            );
        }
        // 创建数据表，默认arc_id字段
        // `content` text COMMENT \'内容\',
        $create_table_sql = 'CREATE TABLE `' . C('db_prefix') . $data['table_name'] . '` (
        `arc_id` int(11) NOT NULL COMMENT \'所属文章id,关联content_article\',
        `art_order` int(11) NOT NULL DEFAULT \'999\' COMMENT \'文章排序,默认999\',
        `cid` int(11) NOT NULL COMMENT \'所属栏目id\',
        UNIQUE KEY `arc_id` (`arc_id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT=\'' . $data['model_name'] . '\';';
        if ($this->execute($create_table_sql) === false) {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '创建数据表失败，错误信息:' . $this->getDbError()
            );
        }
        // 提交事务
        $this->commit();
        return array(
            'status' => true,
            'msg' => '新增成功'
        );
    }

    /**
     * 修改模型
     *
     * @param int $id
     * @param array $data
     * @return array('status'=>bool, 'msg'=>string);
     */
    public function modelUpdate($id, $data = array()) {
        if (! $id || empty($data)) {
            return array(
                'status' => false,
                'msg' => '数据缺失'
            );
        }
        $data['table_name'] = 'content_article_' . $data['table_name'];
        if ($this->where("table_name = '{$data['table_name']}' AND id != $id")->count()) {
            return array(
                'status' => false,
                'msg' => '数据表' . $data['table_name'] . '已存在'
            );
        }
        $table_name = $this->where('id = ' . $id)->getField('table_name');
        // 如果修改了数据表名就启用事务，否则则直接修改模型标题和描述等其他数据即可
        if ($table_name == $data['table_name']) {
            if ($this->where('id = ' . $id)->save($data) === false) {
                return array(
                    'status' => false,
                    'msg' => '修改失败，请重试'
                );
            }
        } else {
            $this->startTrans();
            if ($this->where('id = ' . $id)->save($data) === false) {
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '修改失败，请重试'
                );
            }
            // 判断真实旧数据库是否存在
            if (count($this->query('SHOW TABLES LIKE \'' . C('db_prefix') . $table_name . '\''))) {
                // 判断真实新数据库是否存在
                if (count($this->query('SHOW TABLES LIKE \'' . C('db_prefix') . $data['table_name'] . '\''))) {
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '新数据表' . C('db_prefix') . $data['table_name'] . '已存在,请手动修复数据库'
                    );
                } else {
                    $sql = 'RENAME TABLE `' . C('db_prefix') . $table_name . '` TO `' . C('db_prefix') . $data['table_name'] . '`';
                    if ($this->execute($sql) === false) {
                        $this->rollback();
                        return array(
                            'status' => false,
                            'msg' => '修改数据表名失败，请重试'
                        );
                    }
                }
            } else {
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '旧数据表' . C('db_prefix') . $table_name . '不存在,请手动创建'
                );
            }
            $this->commit();
        }
        return array(
            'status' => true,
            'msg' => '修改成功'
        );
    }

    /**
     * 删除模型，有数据的模型不能删除，为了避免误删数据，必须手动进入数据库清空数据库，后台才能删除
     *
     * @param int $id
     * @return array('status'=>bool, 'msg'=>string);
     */
    public function modelDelete($id) {
        $table_name = $this->where('id = ' . $id)->getField('table_name');
        if ($table_name) {
            if (M($table_name)->count() > 0 || M('ContentArticle')->where('model_id = ' . $id)->count() > 0 ) {
                return array(
                    'status' => false,
                    'msg' => '模型数据库存有数据，为避免误删数据，请进入数据库手动清空或删除数据表' . C('db_prefix') . $table_name . '的数据以及' . C('db_prefix') . 'content_article中model_id=' . $id . '的所有数据，再进入后台删除模型'
                );
            } else if (M('Category')->where('model_id = ' . $id)->count() > 0) {
                return array(
                    'status' => false,
                    'msg' => '文章分类中有所属模型为该模型的栏目，所以无法删除'
                );
            } else {
                $this->startTrans();
                if (! $this->where("id = $id")->delete()) {
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '模型表数据删除失败，请重试'
                    );
                }
                if (count($this->query("SHOW TABLES LIKE '" . C('db_prefix') . "$table_name'")) > 0) {
                    $sql = 'DROP TABLE ' . C('db_prefix') . $table_name;
                    if ($this->execute($sql) === false) {
                        $this->rollback();
                        return array(
                            'status' => false,
                            'msg' => '删除数据表失败，请重试'
                        );
                    }
                }
                if (! M('ContentModelField')->where("model_id = " . $id)->delete()) {
                    return array(
                        'status' => false,
                        'msg' => '模型字段表数据删除失败，请重试'
                    );
                }
                $this->commit();
                return array(
                    'status' => true,
                    'msg' => '删除成功'
                );
            }
        } else {
            return array(
                'status' => false,
                'msg' => '模型不存在或模型表名不存在'
            );
        }
    }
}
