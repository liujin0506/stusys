<?php
class PinfoAction extends CommonAction {

	public function index(){
		$User	 =	 M("Student");
		$vo	=	$User->getById($_SESSION[C('USER_AUTH_KEY')]);
		$class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
        $this->assign ( 'vo', $vo );
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
		$this->display();
	}

	public function update() {
		//B('FilterString');
		$model = CM('Student');
		if (false === $model->create ()) {
			$this->error ( $model->getError () );
		}
		// 更新数据
		$list=$model->save ();
		if (false !== $list) {
			//成功提示
			$this->assign ( 'jumpUrl', Cookie::get ( '_currentUrl_' ) );
			$this->success ('编辑成功!');
		} else {
			//错误提示
			$this->error ('编辑失败!');
		}
	}

}
?>