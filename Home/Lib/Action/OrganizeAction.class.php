<?php
// 组织关系管理模块
class OrganizeAction extends CommonAction {
	public function index(){
		$User	 =	 M("Student");
		$Organize = M("Organize");
		$vo	=	$User->getById($_SESSION[C('USER_AUTH_KEY')]);
		$organize = $Organize->getById($vo['id']);
		if ($organize) {
			$this->assign ('vo',$vo);
        	$this->assign('vt',$organize);
        	$this->display();
		}else{
			$this->error('您的组织关系暂时不存在');
		}
	}
}
?>