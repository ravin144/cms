<?php
namespace Admin\Model;

use Common\Classes\Tree;
use Think\Model;

/**
 * 栏目模型
 * @author DoCan Ravin
 */
class CategoryModel extends Model
{
    //验证
    protected $_validate = array(
        array('name', 'require', '请填写栏目名称'),
    );

    private $map_path = "./Application/Common/Conf/map_rules.php"; // 路由路径

    /**
     * 获取栏目树形结构
     * @param number $pid
     *               父级id
     * @param number $select_id
     *               默认为0
     * @param string $str
     *               结构html,下拉为空即可
     * @param array  $icon
     *               图标,一般为空即可
     * @param array  $arr
     *               栏目信息
     * @param array  $where
     *               查询数据条件
     * @return string html结构
     */
    public function getCateTree($pid = 0, $select_id = 0, $str = "", $icon = array(), $arr = array(), $where = '')
    {
        $str           = isset($str) && $str != '' ? $str : "<option value=\$id \$selected>\$spacer\$name</option>";
        $where         = $where ? $where : '';
        $arr           = isset($arr) && $arr != '' ? $arr : $this->where($where)->order('cate_order ASC, id ASC')->select();
        $cate_tree_arr = $arr;
        $tree          = new Tree();
        if (is_array($icon) && count($icon) > 0) {
            $tree->icon = $icon;
        }
        $tree->tree($cate_tree_arr);
        $cate_tree_html = $tree->get_tree($pid, $select_id, $str);
        return $cate_tree_html;
    }

    /**
     * 添加栏目
     * @param array $data
     * @return string html结构
     */
    public function cateAdd($data = array())
    {
        // 检测路由名是否重复
        if ($data['route']) {
            $route = $this->getField('route', true);
            foreach ($route as $key => $value) {
                if ($value == $data['route']) {
                    return array('status' => false, 'msg' => '路由名重复');
                }
            }
        }
        if ($id = $this->add($data)) {
            // 上级栏目
            if ($data['pid'] == 0) {
                $data['parents_id'] = 0;
            } else {
                $parentPath         = $this->where('id = ' . $data['pid'])->getField('parents_id');
                $data['parents_id'] = $parentPath . ',' . $data['pid'];
            }

            // 下级栏目
            $data['childs_id'] = $id;

            // 更新父级栏目的下级栏目
            if ($data['pid'] != 0) {
                $parentArr = $this->where('id in(' . $data['parents_id'] . ')')->select();
                $childs    = array();
                foreach ($parentArr as $k => $v) {
                    $now_childArr        = $this->where('id = ' . $v['id'])->getField('childs_id');
                    $childs['childs_id'] = $now_childArr . ',' . $id;
                    $this->where('id = ' . $v['id'])->save($childs);
                }
            }

            // 栏目分页
            $data['page_num'] = $data['page_num'] == '' ? 9 : $data['page_num'];

            // 更新图片alt
            if ($data['image']) {
                M('UploadImage')->where(array('url' => $data['image']))->setField('title', $data['alt']);
            }

            // 栏目url && 栏目模板 && 栏目模型
            if ($data['type'] == 0) {
                if ($data['route'] != null) {
                    $data['url'] = __ROOT__ . '/' . $data['route'] . '.html';
                } else {
                    $data['url'] = __ROOT__ . '/Category/' . $id . '.html';
                }
                $data['page_tpl'] = '';
            } elseif ($data['type'] == 1) {
                if ($data['route'] != null) {
                    $data['url'] = __ROOT__ . '/' . $data['route'] . '.html';
                } else {
                    $data['url'] = __ROOT__ . '/Page/' . $id . '.html';
                }
                $data['cate_tpl'] = '';
                $data['show_tpl'] = '';
            } elseif ($data['type'] == 2) {
                $data['url']      = $data['url'];
                $data['cate_tpl'] = '';
                $data['page_tpl'] = '';
                $data['show_tpl'] = '';
            }

            // 栏目路由
            if ($data['route']) {
                $this->addRoute($data['type'], $data['route'], $id);
            }

            $this->where('id = ' . $id)->save($data);
            return array('status' => true, 'msg' => '栏目添加成功');
        } else {
            return array('status' => false, 'msg' => '栏目添加失败');
        }
    }

    /**
     * 修改栏目
     * @param int $id
     * @param array $data
     * @return array('status'=>bool, 'msg'=>string);
     */
    public function cateUpdate($id, $data = array())
    {
        $old    = $this->where('id = ' . $id)->find(); // 旧数据
        $childs = explode(',', $old['childs_id']); // 子栏目id

        // 检测路由名是否重复
        if ($data['route']) {
            $map['id'] = array('neq', $id);
            $route     = $this->where($map)->getField('route', true);
            foreach ($route as $key => $value) {
                if ($value == $data['route']) {
                    return array('status' => false, 'msg' => '路由名重复');
                }
            }
        }

        if ($this->where('id = ' . $id)->save($data)) {
            // 更新子栏目模型
            if ($data['model_inherit']) {
                foreach ($childs as $v) {
                    $this->where('id = ' . $v)->setField('model_id', $data['model_id']);
                }
            }

            // 更新子栏目模板
            if ($data['tpl_inherit']) {
                if ($data['type'] == 0) {
                    foreach ($childs as $v) {
                        $this->where('id = ' . $v)->setField(array(
                            'cate_tpl' => $data['cate_tpl'],
                            'show_tpl' => $data['show_tpl'],
                        ));
                    }
                } elseif ($data['type'] == 1) {
                    $this->where('id = ' . $v)->setField(array('page_tpl' => $data['page_tpl']));
                }
            }

            // 更新图片alt
            if ($data['image']) {
                M('UploadImage')->where(array('url' => $data['image']))->setField('title', $data['alt']);
            }

            // 更新栏目路由
            if ($data['route']) {
                if ($old['route']) {
                    $this->updateRoute($old['route'], $data['route']);
                } else {
                    $this->addRoute($data['type'], $data['route'], $id);
                }
                $data['url'] = __ROOT__ . '/' . $data['route'] . '.html';
                $this->where('id = ' . $id)->setField('url', $data['url']);
            } else {
                if ($old['route']) {
                    if ($data['type'] == 0) {
                        $data['url'] = __ROOT__ . '/Category/' . $id . '.html';
                    } elseif ($data['type'] == 1) {
                        $data['url'] = __ROOT__ . '/Page/' . $id . '.html';
                    }
                    $this->where('id = ' . $id)->setField('url', $data['url']);
                    $this->deleteRoute($old['route']);
                }
            }

            return array('status' => true, 'msg' => '栏目修改成功');
        } else {
            // 更新图片alt
            if ($data['image']) {
                M('UploadImage')->where(array('url' => $data['image']))->setField('title', $data['alt']);
                return array('status' => true, 'msg' => '栏目修改成功');
            }
            return array('status' => false, 'msg' => '栏目修改失败');
        }
    }

    /**
     * 删除栏目
     * @param int $id
     * @return array('status'=>bool, 'msg'=>string);
     */
    public function cateDelete($id)
    {
        $data = $this->where('id = ' . $id)->find();

        // 是否有子栏目
        $childs = explode(',', $data['childs_id']);
        if (count($childs) > 1) {
            return array('status' => false, 'msg' => '该栏目存在子栏目，无法删除');
        }

        // 栏目下是否有文章
        $table = D('content_article')->tableName($data['model_id']);
        if ($table['status']) {
            $tablem = $table['tablem']; // 首字母大写
        } else {
            return array(
                'status' => $table['status'],
                'msg'    => $table['msg'],
            );
        }
        if (M($tablem)->where('cid = ' . $id)->find()) {
            return array('status' => false, 'msg' => '请先删除该栏目下的文章，再删除该栏目');
        }

        if ($this->where('id = ' . $id)->delete()) {
            //更新父级栏目的下级栏目
            $parents = explode(',', $data['parents_id']);
            foreach ($parents as $key => $value) {
                if ($value != 0) {
                    $p_data   = $this->where('id = ' . $value)->find();
                    $p_childs = explode(',', $p_data['childs_id']);
                    if (in_array($id, $p_childs)) {
                        $k = array_search($id, $p_childs);
                        unset($p_childs[$k]);
                        $p_childs = implode(',', $p_childs);
                        $this->where('id = ' . $value)->setField('childs_id', $p_childs);
                    }
                }
            }

            // 删除路由
            if ($data['route']) {
                $this->deleteRoute($data['route']);
            }

            return array('status' => true, 'msg' => '栏目删除成功');
        } else {
            return array('status' => false, 'msg' => '栏目删除失败');
        }
    }

    /**
     * 添加路由
     * @param int $type
     * @param string $route
     * @param int $id
     */
    private function addRoute($type, $route, $id)
    {
        $s = ''; // 写入文件的值
        if ($type == 0) {
            $s = '\'' . $route . '\' => \'Index/Category?cid=' . $id . '\',';
        } elseif ($type == 1) {
            $s = '\'' . $route . '\' => \'Index/Page?cid=' . $id . '\',';
        }

        $file_handle = fopen($this->map_path, "r");
        $arr         = array(); // 要向文件写入的重组后的字符
        $i           = 0; //当前行数
        $iLine       = 5; // 文件的第几行
        $index       = 0; // 该行的第几个字符

        while (!feof($file_handle)) {
            $line = fgets($file_handle);
            ++$i;
            if ($i == $iLine) {
                if ($index == strlen($line) - 1) {
                    $arr[] = substr($line, 0, strlen($line) - 1) . $s . PHP_EOL;
                } else {
                    // 匹配到该行开始处
                    $arr[] = substr($line, 0, $index) . str_repeat(' ', 8) . $s . PHP_EOL . substr($line, $index);
                }
            } else {
                $arr[] = $line;
            }
        }
        fclose($file_handle);
        unlink($this->map_path);
        foreach ($arr as $value) {
            file_put_contents($this->map_path, $value, FILE_APPEND);
        }
    }

    /**
     * 更新路由
     * @param string $old_route
     * @param string $new_route
     */
    private function updateRoute($old_route, $new_route)
    {
        $file_str  = file_get_contents($this->map_path);
        $old_route = '\'' . $old_route . '\'';
        $new_route = '\'' . $new_route . '\'';
        $file_str  = str_replace($old_route, $new_route, $file_str);
        file_put_contents($this->map_path, $file_str);
    }

    /**
     * 删除路由
     * @param string $route
     */
    private function deleteRoute($route)
    {
        $result      = null;
        $file_str    = file_get_contents($this->map_path);
        $route       = '\'' . $route . '\'';
        $targetIndex = strpos($file_str, $route); // 查找目标字符串的行数
        if ($targetIndex !== false) {
            $preIndex   = strrpos(substr($file_str, 0, $targetIndex + 1), PHP_EOL); // 找到target的前一个换行符
            $afterIndex = strpos(substr($file_str, $targetIndex), PHP_EOL) + $targetIndex; // 找到target的后一个换行符
            if ($preIndex !== false && $afterIndex !== false) {
                $result = substr($file_str, 0, $preIndex + 1) . substr($file_str, $afterIndex + 1); // 重新写入删掉指定行后的内容
                file_put_contents($this->map_path, $result);
            }
        }
    }
}
