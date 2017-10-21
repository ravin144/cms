<?php
/**
 * [get_alt 获取图片标题]
 * @Author Ravin
 * @param  string $url [图片地址]
 * @return string      [description]
 */
function get_alt($url) {
	$where['url'] = $url;
	$title = M('upload_image')->where($where)->getField('title');
	return $title;
}
/**
 * [get_file_title 获取上传文件标题]
 * @Author Ravin
 * @param  string $url [文件地址]
 * @return string      [description]
 */
function get_file_title($url) {
	$where['url'] = $url;
	$title = M('upload_file')->where($where)->getField('title');
	return $title;
}
