<?php
namespace Admin\Controller;

use Admin\Common\InitController;

class UploadController extends InitController
{

    /**
     * 编辑器上传
     */
    public function index($action = 'uploadimage')
    {
        $get = I('get.');
        switch ($action) {
            case 'config':
                $config = preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents('./Public/statics/ueditor/php/config.json'));
                echo $config;
                exit();
                break;
            /* 上传图片 */
            case 'uploadimage':
                $result = $this->uploadImage();
                $this->ajaxReturn($result);
                break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                $result = $this->uploadFile();
                $this->ajaxReturn($result);
                break;
            /* 列出图片 */
            case 'listimage':
                $result = $this->listImages($get['start'], $get['size']);
                $this->ajaxReturn($result);
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->listFiles($get['start'], $get['size']);
                $this->ajaxReturn($result);
                break;
            
            /* 抓取远程文件 */
            case 'catchimage':
                $result = include ("action_crawler.php");
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
    }

    /**
     * 上传图片
     * 
     * @return array
     */
    private function uploadImage()
    {
        $editor_img_attr = C('editor_img_attr');
        if (! ! $attr_name = $editor_img_attr[I('get.action_name')]) {
            $data['attr'] = I('get.action_name');
        } else {
            $data['attr'] = 'default';
            $attr_name = '默认分类';
        }
        $upload = new \Think\Upload(); // 实例化上传
        $upload->maxSize = C('upload_img_max_size'); // 设置附件上传大小
        $upload->exts = C('allow_upload_img'); // 设置附件上传类型
        $upload->rootPath = './Public/uploads/'; // 设置附件上传根目录
        $upload->savePath = 'editor/image/'; // 设置附件上传（子）目录
        // 上传图片
        $info = $upload->upload();
        $result = array(
            'msg' => $info,
            // "state" => "", //上传状态，上传成功时必须返回"SUCCESS"
            "url" => str_replace('./', '/', $upload->rootPath) . $info['upfile']['savepath'] . $info['upfile']['savename'], // 返回的地址
            "title" => $info['upfile']['savename'], // 新文件名
            "original" => $info['upfile']['name'], // 原始文件名
            "type" => $info['upfile']['ext'], // 文件类型
            "size" => $info['upfile']['size'], // 文件大小
            "attr_name" => $attr_name
        );
        if (! $info) { // 上传错误提示错误信息
            $result['state'] = $upload->getError();
        } else { // 上传成功
            $data['url'] = $result['url'];
            $data['title'] = $info['upfile']['savename'];
            $data['size'] = $info['upfile']['size'];
            $data['type'] = $result['type'];
            $data['mtime'] = time();
            if (M('UploadImage')->add($data)) {
                $result['state'] = "SUCCESS";
            } else {
                $result['state'] = "插入数据库失败";
            }
        }
        return $result;
    }

    /**
     * 上传文件
     */
    private function uploadFile()
    {
        $upload = new \Think\Upload(); // 实例化上传
        $upload->maxSize = C('upload_file_max_size'); // 设置附件上传大小
        $upload->exts = C('allow_upload_file'); // 设置附件上传类型
        $upload->rootPath = './Public/uploads/'; // 设置附件上传根目录
        $upload->savePath = 'editor/file/'; // 设置附件上传（子）目录
                                                 // 上传文件
        $info = $upload->upload();
        $result = array(
            'msg' => $info,
            // "state" => "", //上传状态，上传成功时必须返回"SUCCESS"
            "url" => str_replace('./', '/', $upload->rootPath) . $info['upfile']['savepath'] . $info['upfile']['savename'], // 返回的地址
            "title" => $info['upfile']['name'], // 新文件名
            "original" => $info['upfile']['name'], // 原始文件名
            "type" => $info['upfile']['ext'], // 文件类型
            "size" => $info['upfile']['size'] // 文件大小
                );
        if (! $info) { // 上传错误提示错误信息
            $result['state'] = $upload->getError();
        } else { // 上传成功
            $data['attr'] = 'default';
            $data['url'] = $result['url'];
            $data['title'] = $info['upfile']['name'];
            $data['type'] = $result['type'];
            $data['size'] = $info['upfile']['size'];
            $data['mtime'] = time();
            if (M('UploadFile')->add($data)) {
                $result['state'] = "SUCCESS";
            } else {
                $result['state'] = "插入数据库失败";
            }
        }
        $result['exts'] = $upload->exts;
        return $result;
    }
    //列出图片
    private function listImages($start = 0, $size = 20)
    {
    	$list = M('UploadImage')->order('id DESC')->limit($start, $size)->field('url,title,mtime')->select();
    	if ($list) {
    		$result['list'] = $list;
    		$result['start'] = $start;
    		$result['total'] = M('UploadImage')->count();
    		$result['state'] = 'SUCCESS';
    	} else {
    	    $result['state'] = 'NULL';
    	}
    	return $result;
    }
    //列出文件
    private function listFiles($start = 0, $size = 20)
    {
    	$list = M('UploadFile')->order('id DESC')->limit($start, $size)->field('url,mtime')->select();
    	if ($list) {
    		$result['list'] = $list;
    		$result['start'] = $start;
    		$result['total'] = M('UploadImage')->count();
    		$result['state'] = 'SUCCESS';
    	} else {
    		$result['state'] = 'NULL';
    	}
    	return $result;
    }
    //裁切图片
    public function cutImg(){
    	if(IS_POST && IS_AJAX){
    		$post=I('post.');
    		$arr=explode('.', $post['img_src']);
    		$imgfirst=$arr[0];
    		$imgtype=$arr[1];
    		if(__ROOT__ == ''){
    			$img_src='.'.$post['img_src'];
    		}else{
    			$img_src=str_replace(__ROOT__,'.',$post['img_src']);
    		}
    		$image = new \Think\Image();
    		$image->open($img_src);
    		$percent=$post['width']/$image->width();
    		$width=intval($post['w']/$percent);
    		$height=intval($post['h']/$percent);
    		$x=$post['x']/$percent;
    		$y=$post['y']/$percent;
    		$image->crop($width,$height,$x,$y)->save(str_replace('.'.$imgtype,$width.'x'.$height.'.'.$imgtype,$img_src));
    		$result['status']=true;
    		$result['img_src']=str_replace('.'.$imgtype,$width.'x'.$height.'.'.$imgtype, $post['img_src']);
    		$this->ajaxReturn($result);
    	}
    }
}