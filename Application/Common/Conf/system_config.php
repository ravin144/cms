<?php
//系统配置，主要由后台生成
$conf_system = array(
    //上传配置
    //图片上传配置
    'upload_img_max_size'  => 10 * 1024 * 1024,
    'allow_upload_img'     => array('jpg', 'gif', 'png', 'jpeg', 'bmp'),
    //文件上传配置
    'upload_file_max_size' => 10 * 1024 * 1024,
    'allow_upload_file'    => array(
        "png", "jpg", "jpeg", "gif", "bmp",
        "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg",
        "ogg", "ogv", "mov", "wmv", "mp4", "webm", "mp3", "wav", "mid",
        "rar", "zip", "tar", "gz", "7z", "bz2", "cab", "iso",
        "doc", "docx", "xls", "xlsx", "ppt", "pptx", "pdf", "txt", "md", "xml",
    ),
    //安全配置
    //主要用与系统缓存文件等容易被访问到的文件加密
    'system_key'           => 'DAD52B5719E3202E32A6619E14D0CCEC',
    //公共配置
    $common_conf = array(
        'layout_on'   => true,
        //通用正则表达式
        'preg_params' => array(
        ),
    ),
);
