<?php
/**
 +------------------------------------------------------------------------------
 * Lbase标签库解析类
 * @lanfengye 蓝枫叶 <zibin_5257@163.com>
 */
class TagLibLbase extends TagLib {

    protected $tags = array(
        // 标签定义：
        //attr 属性列表 close 是否闭合（0 或者1 默认1） alias 标签别名 level 嵌套层次
        'editor' => array('attr' => 'id,import_js,name,width,height,content,type', 'level' => 1, 'close' => 0),
        'imgUpload' => array('attr' => 'name,id,value,width,height,import_js', 'level' => 1, 'close' => 0),
        'imgUpload1' => array('attr' => 'name,id,value,width,height,import_js', 'level' => 1, 'close' => 0),
    );
      
    /**
     +----------------------------------------------------------
     * editor标签解析 插入可视化编辑器
     * 格式： <lbase:editor id="editor" name="remark" width="90%" height="100" import_js="0" type="editor" content="{$vo.remark}" />
     * id:id名称 必填
     * name:name名称 必填
     * width:宽度，textarea时为cols列数 默认为98%
     * height:高度，textarea时为rows行数 默认为300
     * import_js:0-不导入ueditor脚本文件  默认为导入
     * type:editor-编辑器模式  默认为textarea文本区模式
     * content：内容，默认为空
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string|void
     +----------------------------------------------------------
     */
    public function _editor($attr) {
        $tag = $this->parseXmlAttr($attr, 'editor');

        $id_str = !empty($tag['id']) ? $tag['id'] . '_editor' : $tag['name'] . '_editor';
        $js = isset($tag['import_js']) ? $tag['import_js'] : 1;
        $name = $tag['name'];
        $width = !empty($tag['width']) ? $tag['width'] : '98%';
        $height = !empty($tag['height']) ? $tag['height'] : '300';
        $content = $tag['content'];
        $type = $tag['type'];

        $str = '';
        $root_path=C('root_path');
        $editor_upload_path=C('editor_upload_path');
        
        
        switch (strtolower($type)) {
            case 'editor':
                $width=  strpos($width, '%')?$width:$width.'px';
                //判断是否需要导入编辑器脚本
                if ($js) {
                    $str.="<script type='text/javascript'> var lfy_root_path='".$root_path."';var lfy_editor_upload_path='".$editor_upload_path."';</script>";
                    $str.='<load href="'.$root_path.'/public/ueditor/editor_config.js" />';
                    $str.='<load href="'.$root_path.'/public/ueditor/editor_all_min.js" />';
                    $str.='<load href="'.$root_path.'/public/ueditor/themes/default/ueditor.css" />';
                }

                $str.='<script type="text/plain" id="' . $id_str . '" name="'.$name.'" style="width:' . $width . ';">' . $content . '</script>';
                $str.="<script type='text/javascript'>"
                . " var  {$id_str} = new baidu.editor.ui.Editor({ minFrameHeight:'{$height}'});"
                        . "$(function(){"
                        . " $('document').ready(function(){"
                        . " {$id_str}.render('{$id_str}');"
                        .'});'
                        .'});'
                        . '</script>';
                break;
            default :
                $str = '<textarea id="' . $id_str . '" name="' . $name . '" rows="' . $height . '" cols="' . $width . '">' . $content . '</textarea>';
                break;
        }

        return $str;
    }
    /**
     +----------------------------------------------------------
     * imgUpload标签解析
     * 格式： <html:imgUpload name='name' value='value' width='width' height='height' />
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string|void
     +----------------------------------------------------------
     */
    public function _imgUpload($attr) {
        $tag = $this->parseXmlAttr($attr, 'imgUpload');
        $name = $tag['name'];
        $id = $tag['id'];
        $width = !empty($tag['width']) ? $tag['width'] : '35';
        $height = !empty($tag['height']) ? $tag['height'] : '35';
        $value = $tag['value'];
        $import_js=!empty($tag['import_js'])?$tag['import_js']:1;
        if(!empty($value)){
            $img="<a href='".$value."' target='_blank'><img width='80' height='80' src='".$value."' /></a>";
        }
        
        $root_path=C('root_path');
        $parseStr=$import_js?'<script type="text/javascript" src="'.$root_path.'/public/swfupload/swfupload.js"></script>':'';
        
        $parseStr.='<script type="text/javascript">var img_id="'.$id.'";var img_name="'.$name.'";var lfy_root_path="'.$root_path.'";</script>';
        $parseStr.='<script type="text/javascript" src="'.$root_path.'/public/swfupload/handlers.js"></script>';
        $parseStr.='<script type="text/javascript" src="'.$root_path.'/public/swfupload/config_img.js"></script>';
        $parseStr.='<div style="border: solid 1px #7FAAFF; background-color: #C5D9FF;width:80px;"><span id="spanButtonPlaceholder_'.$id.'"></span></div>';
        $parseStr.='<div id="imgupload_'.$id.'">'.$img.'</div>';
        return $parseStr;
    }
    
    /**
     +----------------------------------------------------------
     * imgUpload标签解析
     * 格式： <html:imgUpload name='name' value='value' width='width' height='height' />
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $attr 标签属性
     +----------------------------------------------------------
     * @return string|void
     +----------------------------------------------------------
     */
    public function _imgUpload1($attr) {
        $tag = $this->parseXmlAttr($attr, 'imgUpload');
        $name = $tag['name'];
        $id = $tag['id'];
        $width = !empty($tag['width']) ? $tag['width'] : '35';
        $height = !empty($tag['height']) ? $tag['height'] : '35';
        $value = $tag['value'];
        $import_js=!empty($tag['import_js'])?$tag['import_js']:1;
        if(!empty($value)){
            $img="<a href='".$value."' target='_blank'><img width='80' height='80' src='".$value."' /></a>";
        }
        
        $root_path=C('root_path');
        
        $parseStr=$import_js?'<script type="text/javascript" src="'.$root_path.'/public/swfupload/swfupload.js"></script>':'';
        
        $parseStr.='<script type="text/javascript">var img_id="'.$id.'";var img_name="'.$name.'";var lfy_root_path="'.$root_path.'";</script>';
        $parseStr.='<script type="text/javascript" src="'.$root_path.'/public/swfupload/handlers.js"></script>';
        $parseStr.='<script type="text/javascript" src="'.$root_path.'/public/swfupload/config_img1.js"></script>';
        $parseStr.='<div style="border: solid 1px #7FAAFF; background-color: #C5D9FF;width:80px;"><span id="spanButtonPlaceholder_'.$id.'"></span></div>';
        $parseStr.='<div id="imgupload_'.$id.'">'.$img.'</div>';

        return $parseStr;
    }
}
?>