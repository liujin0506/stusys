<?php
// 违纪信息库管理模块
class WeijiAction extends CommonAction {
	function _filter(&$map)    {
        if ($_POST['date']) {
            $map['rdata1'] = array('elt',strtotime($_POST['date']));
            $map['rdata2'] = array('egt',strtotime($_POST['date']));
        }
        if ($_POST['reason']) {
        	$map['reason'] = array('like', "%" . $_POST['reason'] . "%");
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

    public function _before_add(){
        $weiji = M("Weijirulls")->where('status=1')->select();
        $this->assign('weiji',$weiji);
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
        $weiji = M("Weijirulls")->where('status=1')->select();
        $this->assign('weiji',$weiji);
        $this->display ();
    }

    public function rprint(){
        $name=$this->getActionName();
        $model = M ( $name );
        $id = $_REQUEST [$model->getPk ()];
        $vo = $model->getById ( $id );
        if ($vo['type'] == '1') {
            $data = M ('Config')->where('name="wjcl_mb"')->find();
            $title = "违纪处理通知书";
            $content = $data['data'];
        }else{
            $data = M ('Config')->where('name="cfjd_mb"')->find();
            $title = "处分决定通知书";
            $content = $data['data'];
        }
        //dump($content);
        $name = getStudentinfo($vo['sid'],'nickname');
        $xuehao = getStudentinfo($vo['sid'],'account');
        $class = getClass(getStudentinfo($vo['sid'],'class_id'));
        $gender = getStudentinfo($vo['sid'],'gender');
        $tel = getStudentinfo($vo['sid'],'tel');
        $wjtime = qtDatet($vo['wjtime']);
        $cltime = qtDatet($vo['cltime']);
        $reason = getWeijirull($vo['reason']);
        $content = str_replace('{name}', $name, $content);
        $content = str_replace('{xuehao}', $xuehao, $content);
        $content = str_replace('{class}', $class, $content);
        $content = str_replace('{gender}', $gender, $content);
        $content = str_replace('{tel}', $tel, $content);
        $content = str_replace('{wjtime}', $wjtime, $content);
        $content = str_replace('{cltime}', $cltime, $content);
        $content = str_replace('{reason}', $reason, $content);
        $content = str_replace('{result}', $value['result'], $content);
        $this->assign ( 'title', $title );
        $this->assign ( 'content', $content );
        $this->display ();
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
                   $wjtime = qtDatet($value['wjtime']);
                   $cltime = qtDatet($value['cltime']);
                   $reason = getWeijirull($value['reason']);
                   $content = str_replace('{name}', $name, $mb['content']);
                   $content = str_replace('{xuehao}', $xuehao, $content);
                   $content = str_replace('{class}', $class, $content);
                   $content = str_replace('{gender}', $gender, $content);
                   $content = str_replace('{tel}', $tel, $content);
                   $content = str_replace('{wjtime}', $wjtime, $content);
                   $content = str_replace('{cltime}', $cltime, $content);
                   $content = str_replace('{reason}', $reason, $content);
                   $content = str_replace('{result}', $value['result'], $content);
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
        $_POST['wjtime'] = strtotime($_POST['wjtime']);
        $_POST['cltime'] = strtotime($_POST['cltime']);
        $User = D("Weiji");
        if (!$User->create())
        {
            $this->error($User->getError());
        }
        else
        {
            // 写入帐号数据
            if ($result = $User->add())
            {
                $this->success('违纪信息提交成功！');
            }
            else
            {
                $this->error('违纪信息提交失败！');
            }
        }
    }

    public function update() {
        $_POST['wjtime'] = strtotime($_POST['wjtime']);
        $_POST['cltime'] = strtotime($_POST['cltime']);
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

    public function wjcl_mb(){
        $model = M ('Config');
        $vo = $model->where('name="wjcl_mb"')->find();
        if ($vo) {
            $this->assign ( 'vo', $vo );
        }
        if ($_POST) {
            $data['name'] = 'wjcl_mb';
            $data['data'] = $_POST['data'];
            if (!$_POST['id']) {
                $model->add($data);
                $this->success('添加成功');
            }else{
                $model->where('id='.$_POST['id'])->save($data); 
                $this->success('修改成功');
            }
        }
        $this->display();
    }

    public function cfjd_mb(){
        $model = M ('Config');
        $vo = $model->where('name="cfjd_mb"')->find();
        if ($vo) {
            $this->assign ( 'vo', $vo );
        }
        if ($_POST) {
            $data['name'] = 'cfjd_mb';
            $data['data'] = $_POST['data'];
            if (!$_POST['id']) {
                $model->add($data);
                $this->success('添加成功');
            }else{
                $model->where('id='.$_POST['id'])->save($data); 
                $this->success('修改成功');
            }
        }
        $this->display();
    }
}
?>