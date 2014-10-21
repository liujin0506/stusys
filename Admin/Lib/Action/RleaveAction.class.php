<?php
// 请假管理模块
class RleaveAction extends CommonAction {
	function _filter(&$map)    {
        if ($_POST['date']) {
            $map['rdata1'] = array('elt',strtotime($_POST['date']));
            $map['rdata2'] = array('egt',strtotime($_POST['date']));
        }
        if ($_POST['reason']) {
        	$map['reason'] = array('like', "%" . $_POST['reason'] . "%");
        }
		if ($_POST['status']) {
        	$map['status'] = array('like', "%" . $_POST['status'] . "%");
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
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
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
	
	 public function xiaojia(){
        $id = $this->_get('id');
		$Data = M('rleave');
		$data['status'] = '4';
		$data['xjtime'] = time();
		$Data->where("id='$id'")->save($data);
		echo 1;
    }

	public function zhunjia(){
        $id = $this->_get('id');
		$Data = M('rleave');
		$data['status'] = '2';
		$data['zjtime'] = time();
		$Data->where("id='$id'")->save($data);
		echo 1;
    }

	public function nozhunjia(){
        $id = $this->_get('id');
		$Data = M('rleave');
		$data['status'] = '3';
		$Data->where("id='$id'")->save($data);
		echo 1;
    }
	
	 public function alladd(){
		if($_POST){
			$s_time = strtotime($_POST['rdata1']);
			$e_time = strtotime($_POST['rdata2']);
			$ids = substr($_POST['id'],0,strlen($_POST['id'])-1);
			//echo $ids;
			//print_r($_POST);
			$ids = explode(',',$ids);
			for($i=0;$i<count($ids);$i++) {
				$data['sid'] = $ids[$i];
				$data['rdata1'] = $s_time;
				$data['rdata2'] = $e_time;
				$data['teacher'] = $this->_post('teacher');
				$data['zjteacher'] = $this->_post('zjteacher');
				$data['days'] = $this->_post('days');
				$data['reason'] = $this->_post('reason');
				$data['createtime'] = time();
				$data['remark'] = $this->_post('remark');
				$data['status'] = $this->_post('status');
				
				M('rleave')->add($data);

       
				
			}
			$this->success("请假成功");
			exit;
		}
	 
		$this->display();
    }

    public function sms(){
        $model = M ('Sms_mb');
        $mb = $model->where('model="Rleave"')->find();
        if ($mb) {
            $map['id'] = array('in',$_POST['ids']);
            $name=$this->getActionName();
            $model = M ( $name );
            $vo = $model->where($map)->select();
            if ($vo>getSmsNum()) {
                $this->error('您的短信剩余条数不足以完成本次发送，剩余条数'.getSmsNum());
            }else{
                foreach ($vo as $key => $value) {
                   $name = getStudentinfo($value['sid'],'nickname');
                   $xuehao = getStudentinfo($value['sid'],'account');
                   $class = getClass(getStudentinfo($value['sid'],'class_id'));
                   $gender = getStudentinfo($value['sid'],'gender');
                   $tel = getStudentinfo($value['sid'],'tel');
                   $time = qtDatet($value['rdata1'])."至".qtDatet($value['rdata2']);
                   $reason = $value['reason'];
                   $content = str_replace('{name}', $name, $mb['content']);
                   $content = str_replace('{xuehao}', $xuehao, $content);
                   $content = str_replace('{class}', $class, $content);
                   $content = str_replace('{gender}', $gender, $content);
                   $content = str_replace('{tel}', $tel, $content);
                   $content = str_replace('{time}', $time, $content);
                   $content = str_replace('{reason}', $reason, $content);
                   sendSms($tel,$content);
                }
                $this->success('发送成功！');
            }
        }else{
            $this->error('请先设置模板');
        }
    }
    
	 public function insert()
    {
        // 创建数据对象
        $_POST['sid'] = $_POST['orgLookup_sid'];
        $_POST['createtime'] = time();
        $_POST['rdata1'] = strtotime($_POST['rdata1']);
        $_POST['rdata2'] = strtotime($_POST['rdata2']);
        $User = D("Rleave");
        if (!$User->create())
        {
            $this->error($User->getError());
        }
        else
        {
            // 写入帐号数据
            if ($result = $User->add())
            {
                $this->success('请假信息提交成功！');
            }
            else
            {
                $this->error('请假信息提交失败！');
            }
        }
    }

    public function update() {
        $_POST['rdata1'] = strtotime($_POST['rdata1']);
        $_POST['rdata2'] = strtotime($_POST['rdata2']);
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