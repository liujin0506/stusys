<?php
// 荣誉管理模块
class ActiveAction extends CommonAction {
	function _filter(&$map)    {
        if ($_POST['hdtime']) {
            $map['hdtime'] = array('elt',strtotime($_POST['hdtime']));
        }
        if ($_POST['pactive']) {
        	$map['pactive'] = array('like', "%" . $_POST['pactive'] . "%");
        }
        if ($_POST['account'] ||$_POST['nickname'] || $_POST['class_id'] || $_POST['dormitory_id'] || $_POST['gender']) {
			if ($_POST['account']) {
                $map2['account'] = array('like', "%" . $_POST['account'] . "%");
            }
            if ($_POST['nickname']) {
                $map2['nickname'] = array('like', "%" . $_POST['nickname'] . "%");
            }
            if ($_POST['gender']) {
                $map2['gender'] = array('like',"%" . $_POST['gender'] . "%");
            }
            if ($_POST['class_id']) {
                $map2['class_id'] = array('eq',$_POST['class_id']);
            }
            if ($_POST['dormitory_id']) {
                $map2['dormitory_id'] = array('eq',$_POST['dormitory_id']);
            }
            $ids = M('Student') -> where($map2)->select();
            foreach ($ids as $key => $value) {
                $idss[] = $value['id'];
            }
            $map['sid'] = array('in',$idss);
        }
    }

    public function _before_index(){
        $class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
		$activitys = M("Activitys")->where('status=1')->select();
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
		$this->assign('activitys',$activitys);
    }

	public function add(){
        $class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
		$activitys = M("Activitys")->where('status=1')->select();
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
		$this->assign('activitys',$activitys);
        $this->display ();
    }

    public function edit(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $userinfo = M("Student")->where('id='.$vo['sid'])->find();
		$activitys = M("Activitys")->where('status=1')->select();
        $this->assign('userinfo',$userinfo);
		$this->assign('activitys',$activitys);
        $this->display ();
    }

    public function look(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $userinfo = M("Student")->where('id='.$vo['sid'])->find();
        $this->assign('userinfo',$userinfo);
        $this->display ();
    }

    public function rprint(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $userinfo = M("Student")->where('id='.$vo['sid'])->find();
        $this->assign('userinfo',$userinfo);
        $this->display ();
    }
    
	 public function insert()
    {
        // 创建数据对象
        $_POST['sid'] = $_POST['orgLookup_sid'];
        $_POST['createtime'] = time();
        $_POST['hdtime'] = strtotime($_POST['hdtime']);
        $User = D("Active");
        if (!$User->create())
        {
            $this->error($User->getError());
        }
        else
        {
            // 写入帐号数据
            if ($result = $User->add())
            {
                $this->success('学生参与活动信息提交成功！');
            }
            else
            {
                $this->error('学生参与活动信息提交失败！');
            }
        }
    }

    public function update() {
        $_POST['hdtime'] = strtotime($_POST['hdtime']);
        $name=$this->getActionName();
        $model = CM( $name );
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