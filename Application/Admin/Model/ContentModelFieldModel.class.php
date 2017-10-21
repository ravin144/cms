<?php
namespace Admin\Model;

use Think\Model;

/**
 * 内容模型字段model
 *
 * @author shixu
 *
 */
class ContentModelFieldModel extends Model
{

    protected $_validate = array(
        array(
            'model_id',
            'require',
            '请选择模型'
        ),
        array(
            'field_name',
            'require',
            '请填写字段名称'
        ),
        array(
            'field_name',
            '/^[a-z][a-z0-9_]*[a-z0-9]$/',
            '字段名称格式不正确',
            0,
            'regex'
        ),
        array(
            'field_title',
            'require',
            '请填写字段标题'
        ),
        array(
            'input_type',
            'require',
            '请选择表单字段类型'
        ),
        array(
            'field_type',
            'require',
            '请选择数据表字段类型'
        ),
        array(
            'field_name',
            'require',
            '请填写字段名称'
        )
    );
    // 必须有长度的字段类型
    private $field_type_need_length = array(
        'VARCHAR',
        'CHAR',
        'INT',
        'SMALLINT',
        'TINYINT'
    );

    /**
     * 新增模型字段
     *
     * @param array $data
     * @return array('status'=>bool, 'msg'=>string);
     */
    public function modelFieldAdd($model_id, $data = array()) {
        $table_name = M('ContentModel')->where('id = ' . $model_id)->getField('table_name');
        if (count($this->query('SHOW TABLES LIKE \'' . C('db_prefix') . $table_name . '\'')) <= 0) {
            return array(
                'status' => false,
                'msg' => '数据表' . C('db_prefix') . $table_name . '不存在'
            );
        }
        $data['model_id'] = $model_id;
        if ($this->where('model_id = ' . $model_id . ' AND field_name = \'' . $data['field_name'] . '\'')->find()) {
            return array(
                'status' => false,
                'msg' => '字段' . $data['field_name'] . '已存在'
            );
        }
        $field_length_sql = '';

        if (in_array($data['field_type'], $this->field_type_need_length)) {
            if (intval($data['field_length']) == '' || intval($data['field_length']) == 0) {
                return array(
                    'status' => false,
                    'msg' => '所选字段类型必须填写数据表字段长度'
                );
            }
            $field_length_sql = '(' . intval($data['field_length']) . ')';
        }
        // 添加字段sql
        if ($data['field_type'] != 'TEXT' && $data['field_type'] != 'LONGTEXT') {
            $add_field_sql = 'ALTER TABLE ' . C('db_prefix') . $table_name . '
                ADD COLUMN `' . $data['field_name'] . '` ' . $data['field_type'] . $field_length_sql . '
                NULL';
        } else {
            $data['field_length'] = null;
            $add_field_sql = 'ALTER TABLE ' . C('db_prefix') . $table_name . '
                ADD COLUMN `' . $data['field_name'] . '` ' . $data['field_type'] . '
                NOT NULL';
        }

        $this->startTrans();
        if ($this->execute($add_field_sql) === false) {
            $this->rollback();
            return array(
                'status' => true,
                'msg' => '新增数据表字段失败请重试,错误信息:' . $this->getDbError()
            );
        }
        // 也就是如果选择了TEXT等类型并且也选择了唯一,则把唯一限制去掉
        if (! in_array($data['field_type'], $this->field_type_need_length) && $data['is_unique'] == 1) {
            $data['is_unique'] = 0;
        }
        // 插入字段记录
        if (! $this->add($data)) {
            $this->rollback();
            return array(
                'status' => true,
                'msg' => '插入字段数据失败，错误信息:' . $this->getDbError()
            );
        }
        $this->commit();
        return array(
            'status' => true,
            'msg' => '新增成功'
        );
    }

    /**
     * 编辑模型字段
     *
     * @param int $field_id
     * @param array $data
     */
    public function modelFieldUpdate($field_id, $data)
    {
        if (! $field_id) {
            return array(
                'status' => false,
                'msg' => '字段不存在'
            );
        }
        $field_data = $this->where('id = ' . $field_id)->find();
        $table_name = M('ContentModel')->where('id = ' . $field_data['model_id'])->getField('table_name');
        if (! $table_name || count($this->query('SHOW TABLES LIKE \'' . C('db_prefix') . $table_name . '\'')) <= 0) {
            return array(
                'status' => false,
                'msg' => '数据表' . C('db_prefix') . $table_name . '不存在'
            );
        }
        if ($this->where('id != ' . $field_id . ' AND model_id = ' . $field_data['model_id'] . ' AND field_name = \'' . $data['field_name'] . '\'')->count()) {
            return array(
                'status' => false,
                'msg' => '字段[' . $data['field_name'] . ']已存在'
            );
        }
        $field_length_sql = '';
        if (in_array($data['field_type'], $this->field_type_need_length)) {
            if (intval($data['field_length']) == '' || intval($data['field_length']) == 0) {
                return array(
                    'status' => false,
                    'msg' => '所选字段类型必须填写数据表字段长度'
                );
            }
            $field_length_sql = '(' . intval($data['field_length']) . ')';
        }
        $this->startTrans();
        // 如果字段名、字段类型、或字段类型长度有修改，则需修改数据表对应字段
        if ($field_data['field_type'] != $data['field_type'] || $field_data['field_length'] != $data['field_length'] || $field_data['field_name'] != $data['field_name']) {
            $update_field_sql = 'ALTER TABLE ' . C('db_prefix') . $table_name . ' CHANGE ' . $field_data['field_name'] . ' ' . $data['field_name'] . ' ' . $data['field_type'] . $field_length_sql;
            if ($this->execute($update_field_sql) === false) {
                $this->rollback();
                return array(
                    'status' => false,
                    'msg' => '修改数据表字段类型或字段名失败，错误信息:' . $this->getDbError()
                );
            }
        }
        if (! $this->where('id = ' . $field_id)->save($data)) {
            $this->rollback();
            return array(
                'status' => false,
                'msg' => '字段信息修改失败，请重试'
            );
        }
        $this->commit();
        return array(
            'status' => true,
            'msg' => '修改成功'
        );
    }

    /**
     * 删除模型字段
     *
     * @param int $field_id
     *            字段id
     */
    public function modelFieldDelete($field_id)
    {
        if ($field_id && $field_data = $this->where('id = ' . $field_id)
            ->field('field_name,model_id')
            ->find()) {
            $table_name = M('ContentModel')->where('id = ' . $field_data['model_id'])->getField('table_name');
            $this->startTrans();
            if ($this->where('id = ' . $field_id)->delete()) {
                $delete_field_sql = 'ALTER TABLE ' . C('db_prefix') . $table_name . ' DROP COLUMN ' . $field_data['field_name'];
                if ($this->execute($delete_field_sql) === false) {
                    $this->rollback();
                    return array(
                        'status' => false,
                        'msg' => '删除数据表字段失败，请重试'
                    );
                }
            }
            $this->commit();
            return array(
                'status' => true,
                'msg' => '删除成功'
            );
        } else {
            return array(
                'status' => false,
                'msg' => '字段不存在'
            );
        }
    }
}
