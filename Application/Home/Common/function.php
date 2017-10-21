<?php
/**
 * [subcat 遍历栏目]
 * @Author Ravin
 * @param  integer $pid   [栏目父级id]
 * @param  integer $limit [限制栏目数量]
 * @return [type]         [栏目信息]
 */
function subcat($pid = 0, $limit = 0)
{
    $cate_arr = array();
    $cates    = M('category')->select();
    $index    = 0;
    foreach ($cates as $k => $v) {
        if ($v['pid'] == $pid && $v['is_show'] == 1) {
            $cate_arr[] = $v;
            $index++;
            if ($limit) {
                if ($index == $limit) {
                    break;
                }

            }
        }
    }
    return $cate_arr;
}
/**
 * [get_cate_list 获取栏目下的文章]
 * @Author Ravin
 * @param  [type] $catid [栏目id]
 * @param  string $limit [限制栏目数量]
 * @return [type]        [栏目下的文章]
 */
function get_cate_list($cid, $limit = '')
{
    if ($cid) {
        //查询当前栏目模型表名
        $model_id = M('category')->where('id = ' . $cid)->getField('model_id');
        if ($model_id) {
            $table_name = M('content_model')->where('id = ' . $model_id)->getField('table_name');
            if ($table_name) {
                $list = M($table_name)->join('dc_content_article ON dc_' . $table_name . '.arc_id = dc_content_article.id')->where('cid = ' . $cid)->order('art_order asc, arc_id desc')->limit($limit)->select();
            }
        }
    }
    return $list;
}
/**
 * [cut_images 获取多图]
 * @Author Ravin
 * @param  [type] $images_str [多图地址字符串]
 * @return [type]             [图片地址数组]
 */
function cut_images($images_str){
    $images = explode(',', $images_str);
    return $images;
}
/**
 * [get_cate_info 获取栏目信息]
 * @Author Ravin
 * @param  integer $cid [栏目id]
 * @return [type]       [栏目信息]
 */
function get_cate_info($cid) {
    $cate = M('category')->where('id = ' . $cid)->find();
    return $cate;
}
