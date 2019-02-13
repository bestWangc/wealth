<?php
namespace app\admins;

use think\Controller;

/**
 *文件上传类 
 */
class File extends Controller
{
        /**
         *前台单文件上传方法 
         */
	public function do_upload_img()
	{
                $whs['big_width']='280';
                $whs['big_height']='190';
                $whs['small_width']='123';
                $whs['small_height']='120';
		$result = $this->uploadImages(0, 'temp',true,$whs);
		
			$list = $result;
                        $file_url = $list[0]['savepath'];
                        $file_name= $list[0]['savename'];
                        $data=array(
                            'filename'=>$file_name,
                            'savepath'=>$file_url,
                            'big_savepath'=>$list[0]['big_savepath'],
                            'small_savepath'=>$list[0]['small_savepath']
                        );
                        echo json_encode($data);
	}
        /**
     * 上传图片的通公基础方法
     *
     * @param integer $water  0:不加水印 1:打印水印
     * @param string $dir  上传的文件夹
     * @param bool $is_thumb 是否保存为缩略图
     * @param array $whs['big_width']=大图宽度，$whs['big_height']=大图高度，$whs['small_width']=小图宽度，$whs['small_height']=小图高度
     * @return array
     */
    protected function uploadImages($water = 0, $dir = 'images', $is_thumb = false, $whs = array(), $is_swf = false) {
        $water_mark = MC('WAIER_IMAGE');
        $alpha = MC("WATER_ALPHA");
        $place = MC("WATER_POSITION");
        import('ORG.Net.UploadFile');
        import('ORG.Util.Image');
        $upload = new UploadFile();
        $image=new Image();

        //设置上传文件大小
        $max_upload = intval(MC('MAX_UPLOAD'));

        if ($max_upload > 0)
            $upload->maxSize = $max_upload * 1024; /* 配置于config */

        //设置上传文件类型
        $upload_exts = MC('ALLOW_UPLOAD_EXTS');
        if (!empty($upload_exts))
            $upload->allowExts = explode(',', MC('ALLOW_UPLOAD_EXTS')); /* 配置于config */

        if ($is_swf)
            $upload->allowExts[] = 'swf';

        if ($is_thumb)
            $save_rec_Path = $dir . '/origin/'; //上传至服务器的相对路径  
        else
            $save_rec_Path = $dir . '/'; //上传至服务器的相对路径  
        
        $root_path=C('ROOT_PATH');
        $save_path = '../public/upload/' . $save_rec_Path;  //文件存储目录
        $return_path=$root_path.'/public/upload/'. $save_rec_Path;   //文件返回路径前缀
        $date = date('Ymd');
        $save_path.=$date . '/';
        $return_path.=$date.'/';

        if (!is_dir($save_path))
            mk_dir($save_path);

        $upload->saveRule = "uniqid"; //唯一
        $upload->savePath = $save_path;

        if ($upload->upload()) {
            $upload_list = $upload->getUploadFileInfo();
            foreach ($upload_list as $k => $file_item) {
                if ($is_thumb) { //生成缩略图时
                    $file_name = $file_item['savepath'] . $file_item['savename']; //上图原图的地址
                    //开始缩放处理产品大图
                    $big_width=isset($whs['big_width'])?$whs['big_width']:MC("BIG_WIDTH");
                    $big_height=  isset($whs['big_height'])?$whs['big_height']:MC("BIG_HEIGHT");
                    $big_save_path = str_replace("origin", "big", $save_path); //替换为大图存放目录
                    $big_file_name = str_replace("origin", "big", $file_name);  //替换为大图存放目录

                    if (!is_dir($big_save_path)) mk_dir($big_save_path);

                    Image::thumb($file_name, $big_file_name, '', $big_width, $big_height);

                    if ($water && file_exists($water_mark)) {
                        Image::water($big_file_name, $water_mark, $big_file_name, $alpha, $place);
                    }

                    //开始缩放处理产品小图
                    $small_width = isset($whs['small_width']) ? $small_width = $whs['small_width'] : $small_width = MC("SMALL_WIDTH");
                    $small_height = isset($whs['small_height']) ? $small_height = $whs['small_height'] : $small_height = MC("SMALL_HEIGHT");
                    $small_save_path = str_replace("origin", "small", $save_path); //替换为小图存放目录
                    $small_file_name = str_replace("origin", "small", $file_name); //替换为小图存放目录
                    
                    if (!is_dir($small_save_path)) mk_dir($small_save_path);

                    Image::thumb($file_name, $small_file_name, '', $small_width, $small_height);
                    
                    $big_save_rec_Path = str_replace("origin", "big", $return_path); //大图存放的绝对路径
                    $small_save_rec_Path = str_replace("origin", "small", $return_path); //小图存放的绝对路径

                    $upload_list[$k]['savepath'] = $return_path;
                    $upload_list[$k]['big_savepath'] = $big_save_rec_Path;
                    $upload_list[$k]['small_savepath'] = $small_save_rec_Path;
                    
                }
                else {
                    $upload_list[$k]['recpath'] = $save_rec_Path;
                    $file_name = $file_item['savepath'] . $file_item['savename'];
                    $upload_list[$k]['savepath'] = $return_path;
                    if ($water && file_exists($water_mark)) {
                        Image::water($file_name, $water_mark, $file_name, $alpha, $place);
                    }
                }
            }
            return $upload_list;
        } else {
            return false;
        }
    }
}