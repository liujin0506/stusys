<?php
// 组织关系管理模块
class OrganizeAction extends CommonAction {
	function _filter(&$map)    {
        if ($_POST['d_time']) {
            $map['d_time'] = array('egt',strtotime($_POST['d_time1']));
            $map['d_time'] = array('elt',strtotime($_POST['d_time2']));
        }
        if ($_POST['jsr_1']) {
        	$map['jsr_1'] = array('like', "%" . $_POST['jsr_1'] . "%");
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
        $this->assign('class',$class);
    }

	public function add(){
        $class = M("Classes")->where('status=1')->select();
        $this->assign('class',$class);
        $this->display ();
    }

    public function edit(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        $this->assign ( 'vo', $vo );
        $userinfo = M("Student")->where('id='.$vo['sid'])->find();
        $this->assign('userinfo',$userinfo);
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

	// 插入数据
	public function insert() {
		// 创建数据对象
		$_POST['sid'] = $_POST['orgLookup_sid'];
		$_POST['d_time'] = strtotime($_POST['d_time']);
		$_POST['j_time'] = strtotime($_POST['j_time']);
		$_POST['z_time'] = strtotime($_POST['z_time']);
		$_POST['y_time'] = strtotime($_POST['y_time']);
		$_POST['r_time'] = strtotime($_POST['r_time']);
		$_POST['c_time'] = strtotime($_POST['c_time']);
		$_POST['create_time'] = time();
		$User	 =	 D("Organize");
		if(!$User->create()) {
			$this->error($User->getError());
		}else{
			// 写入帐号数据
			if($result	 =	 $User->add()) {
				$this->success('组织关系添加成功！');
			}else{
				$this->error('组织关系添加失败！');
			}
		}
	}   

	 public function update() {
		$_POST['d_time'] = strtotime($_POST['d_time']);
		$_POST['j_time'] = strtotime($_POST['j_time']);
		$_POST['z_time'] = strtotime($_POST['z_time']);
		$_POST['y_time'] = strtotime($_POST['y_time']);
		$_POST['r_time'] = strtotime($_POST['r_time']);
		$_POST['c_time'] = strtotime($_POST['c_time']);
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