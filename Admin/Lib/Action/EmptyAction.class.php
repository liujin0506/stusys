<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luofei614 <www.3g4k.com>　
// +----------------------------------------------------------------------
// $Id: EmptyAction.class.php 1 2011-11-17 02:14:12Z luofei614@126.com $
class EmptyAction extends CommonAction{

    protected function getActionName(){
        return MODULE_NAME;
    }
//判断是否有模版文件，用于以后的扩展
private function hasTpl($templateFile) {
        if(''==$templateFile) {
            // 如果模板文件名为空 按照默认规则定位
            $templateFile = C('TMPL_FILE_NAME');
        }elseif(false === strpos($templateFile,'.')){
            $templateFile  = str_replace(array('@',':'),'/',$templateFile);
            $count   =  substr_count($templateFile,'/');
            $path   = dirname(C('TMPL_FILE_NAME'));
            for($i=0;$i<$count;$i++)
                $path   = dirname($path);
            $templateFile =  $path.'/'.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
        }
        if(!file_exists_case($templateFile))
            return false;
        return true;
    }

}

