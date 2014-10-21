<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: luxingzhan
// +----------------------------------------------------------------------
// $Id: ArticleAction.class.php 1 2011-11-17 02:14:12Z luofei614@126.com $

class SiteinfoAction extends CommonAction{

	public function index(){
		if ($_POST) {
			$setfile = './Admin/siteconfig.inc.php';
			$config = array(
				'SITENAME'=>$_POST['SITENAME'],
				'EMAIL'=>$_POST['EMAIL'],
				'CONTACT'=>$_POST['CONTACT'],
				'COMPANY'=>$_POST['COMPANY'],
				'PHONE'=>$_POST['PHONE'],
				'FAX'=>$_POST['FAX'],
				'ADDRESS'=>'',
				'OFFLINEMESSAGE'=>'本站正在维护中，暂不能访问。<br /> 请稍后再访问本站。',
				'SITEURL'=>$_POST['SITEURL'],
				'DEMOURL'=>'http://demo.imaumm.com',
				'BBSOURL'=>'http://bbs.imaumm.com',
				'SMSUSER'=>$_POST['SMSUSER'],
				'SMSKEY'=>$_POST['SMSKEY'],
				);
			$settingstr="<?php \nif (!defined('THINK_PATH')) exit();\n return array(\n";
			foreach($config as $key=>$v){
				$settingstr.="\t'".$key."'=>'".$v."',\n";
			}
			$settingstr.=");\n?>\n";
			if(file_put_contents($setfile,$settingstr)){
				$this->success ('编辑成功!');
			}else{
				$this->error ('编辑失败!');
			}			
		}
		$this->display();
	}
}


