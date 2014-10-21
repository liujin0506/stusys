<?php
// 综合评价管理模块
class ZhpjAction extends CommonAction {

    public function _before_insert() {
        // 格式化数据对象
        $_POST['createtime'] = time();
        $_POST['start_time'] = strtotime($_POST['start_time']);
        $_POST['end_time'] = strtotime($_POST['end_time']);
    }

    public function _before_update() {
        $_POST['start_time'] = strtotime($_POST['start_time']);
        $_POST['end_time'] = strtotime($_POST['end_time']);
    }

    public function _before_pj(){
        $zhuanye = M("Zhuanye")->where('status=1')->select();
        $class = M("Classes")->where('status=1')->select();
        $sushe = M("Sushe")->where('status=1')->select();
        $poor = M("Poor")->where('status=1')->select();
		$honorrulls = M("Honorrulls")->where('status=1')->select();
        $this->assign('zhuanye',$zhuanye);
        $this->assign('class',$class);
        $this->assign('sushe',$sushe);
        $this->assign('poor',$poor);
		$this->assign('honorrulls',$honorrulls);
    }

    public function pj(){
        $name='Student';
        $model = CM($name);
        $pk = $model->getPk ();
        $time_id = $_REQUEST [$pk];
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
            $map['id'] = array('in',$idss);
            $time_id = $_POST['time_id'];
        }
        $model = CM($name);
        if (! empty ( $model )) {
            $this->_list ( $model, $map );
        }
        $this->assign ( 'time_id', $time_id );
        $this->display ();
    }

    public function rprint(){
        //dump($_REQUEST);
        $id = $_REQUEST['id'];
        $time_id = $_REQUEST['time_id'];
        $time = M ('Zhpj')->where('id='.$time_id)->find();
        $start_time = $time['start_time'];
        $end_time = $time['end_time'];
        $userinfo = M("Student")->where('id='.$id)->find();
        $weiji = M("Weiji")->where('sid='.$id.' AND createtime >='.$start_time.' AND createtime <='.$end_time)->select();
		$honor = M("Honor")->where('sid='.$id.' AND createtime >='.$start_time.' AND createtime <='.$end_time)->select();
        $rleave = M("Rleave")->where('sid='.$id.' AND createtime >='.$start_time.' AND createtime <='.$end_time)->select();
		$active = M("Active")->where('sid='.$id.' AND createtime >='.$start_time.' AND createtime <='.$end_time)->select();
        //dump($weiji);
        $this->assign ( 'userinfo', $userinfo );
        $this->assign ( 'rleave', $rleave );
		$this->assign ( 'honor', $honor );
        $this->assign ( 'weiji', $weiji);
		$this->assign ( 'active', $active);
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
				   $type = getHonorrulls($value['type']);
                   $content = str_replace('{name}', $name, $mb['content']);
                   $content = str_replace('{xuehao}', $xuehao, $content);
                   $content = str_replace('{class}', $class, $content);
                   $content = str_replace('{gender}', $gender, $content);
                   $content = str_replace('{tel}', $tel, $content);
                   $content = str_replace('{wjtime}', $wjtime, $content);
                   $content = str_replace('{cltime}', $cltime, $content);
                   $content = str_replace('{reason}', $reason, $content);
				   $content = str_replace('{type}', $type, $content);
                   $content = str_replace('{result}', $value['result'], $content);
                   sendSms($tel,$content);
                }
                $this->success('发送成功！');
            }
        }else{
            $this->error('请先设置模板');
        }
    }
}
?>