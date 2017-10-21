<?php
namespace Admin\Controller;

use Think\Controller;
use Admin\Common\InitController;

/**
 * 后台缓存数据控制器
 * 所有有关设置和更新缓存的请放在这里
 *
 * @author shixu Ravin
 */
class CacheController extends InitController
{

    /**
     * 根据管理组id更新后台菜单缓存
     *
     * @param number $groupid
     *            管理组id
     * @param bool $delete
     *            是否是删除缓存
     * @return boole
     */
    public function updateGroupAuth($groupid = 1, $delete = false)
    {
        $groupid = intval($groupid);
        if ($groupid && $delete == true) {
            return cache('admin/nav' . $groupid, null);
        }
        if ($groupid == 1 || $groupid == '' || $groupid == 0) {
            $groupid = 1;
            $admin_nav_arr = M('AdminAuthRule')->order('listorder ASC, id DESC')->select();
        } else {
            if ($groupid) {
                $rules = M('AdminAuthGroup')->where('id = ' . $groupid)->getField('rules');
                if ($rules && $rules != 0) {
                    $admin_nav_arr = M('AdminAuthRule')->order('listorder ASC, id DESC')
                        ->where('id IN (' . $rules . ')')
                        ->select();
                }
            }
        }
        if ($admin_nav_arr) {
            $new_admin_nav_arr = array();
            foreach ($admin_nav_arr as $k => $v) {
                $new_admin_nav_arr[$v['id']] = $v;
            }
            return cache('admin/nav' . $groupid, $new_admin_nav_arr);
        }
        return false;
    }

    /**
     * 更新模型缓存
     *
     * @param number $model_id
     * @param string $delete
     *            是否是删除，如果是则删除缓存
     * @return boole
     */
    public function updateModel($model_id = 1, $delete = false)
    {
        $model_id = intval($model_id);
        if ($model_id && $delete == true) {
            return cache('model/model' . $model_id, null);
        }
        if ($model_id > 0) { // 更新指定模型缓存
            $data = M('ContentModel')->where('id = ' . $model_id)->find();
            $fields = M('ContentModelField')->where('model_id = ' . $model_id)
                ->order('listorder ASC,id ASC')
                ->select();
            $model['data'] = $data;
            $model['fields'] = $fields;
            return cache('model/model' . $model_id, $model);
        } else { // 更新所有模型缓存
            $models = M('ContentModel')->field('id')->select();
            foreach ($models as $k => $v) {
                $this->updateModel($v['id']);
            }
            return true;
        }
        return false;
    }

    /**
     * 更新栏目缓存
     *
     * @param number $cate_id
     * @param string $delete
     *            是否是删除，如果是则删除缓存
     * @return boole
     */
    public function updateCate($cate_id = 1, $delete = false) {
        $cate_id = intval($cate_id);
        if ($cate_id && $delete == true) {
            return cache('category/category' . $cate_id, null);
        }
        if ($cate_id > 0) {
            $cate = M('Category')->where('id = ' . $cate_id)
                ->order('cate_order ASC,id ASC')
                ->select();
            return cache('category/category' . $cate_id, $cate);
        } else {
            $cates = M('Category')->field('id')->select();
            foreach ($cates as $k => $v) {
                $this->updateCate($v['id']);
            }
            return true;
        }
        return false;
    }
}
