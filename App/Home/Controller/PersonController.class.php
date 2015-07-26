<?php
namespace Home\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class PersonController extends Controller {
    public function index(){
        // $this->show();
        $this->personInfo();
    }
    // public function forgetPword(){
    //     // $this->show();
    //     $this->display("forgetpword");
    // }
    // public function orderManage(){
    //     // $this->show();
    //     $this->display("orderManage");
    // }

    public function personInfo($active = "0"){
        $Users  = M("users");
        $where = "phone=".$_SESSION['username'];
        // $where  = "phone='13040101273'";
        $field  = "nickname,sex,academy,qq,weixin,img_url";
        $data   = $Users->where($where)->field($field)->find();

        if ($data !== false)
        {
            // dump($data);
            $this->assign("data",$data);
            $this->assign("active",$active);
            $this->display("personInfo");
        }
        else
        {
            $this->assign("active",$active);
            $this->display("personInfo");
        }

    }

    public function savePersonInfo($nickname,
                                   $usersex,
                                   $academy,
                                   $qq,
                                   $weixin      ){

        $user = $_SESSION['username'];
        // $user = '13040101273';

        $data = array(
            'nickname'  =>  $nickname,
            'sex'       =>  $usersex,
            'academy'   =>  $academy,
            'qq'        =>  $qq,
            'weixin'    =>  $weixin
            );

        session('nickname',$data['nickname']);
        session('academy',$data['academy']);

        $Users = M('users');
        $where = "phone=".$user;
        $result = $Users->where($where)->save($data);     

        if ($result !== false)
        {
            $res = array(
                'result' => 1
                );
            $this->ajaxReturn($res);
        }
        else
        {
            $res = array(
                'result' => 0
                );
            $this->ajaxReturn($res);
        }
    }

    public function submit(){
        $nickname   = I('nick-name');
        $usersex    = I('user-sex');
        $academy    = I('academy');
        $qqnum      = I('qq-num');
        $weixinnum  = I('weixin-num');
  
        $_SESSION['nickname']   = $nickname;
        $_SESSION['academy']    = $academy;

        // echo $User->username;

        $Users = M("users");
        $where = "phone=".$_SESSION['username'];
        // $where = "phone='13040101273'";

        $data['nickname']   = $nickname;
        $data['sex']        = $usersex;
        $data['academy']    = $academy;
        $data['qq']         = $qqnum;
        $data['weixin']     = $weixinnum;
        $Users->where($where)->save($data);

        // $this->assign("active","0");
        $this->personInfo("0");
    }

    public function savePortrait(){
        $upload             = new \Think\Upload();
        $upload->maxSize    = 4194304;
        $upload->exts       = array('jpg','gif','jpeg','bmp');
        $upload->rootPath   = './Public/';
        $upload->savePath   = '/Uploads/';
        
        $info = $upload->uploadOne($_FILES['img']);

        if ($info)
        {
            $data['img_url'] = '/foryou/Public'.$info['savepath'].$info['savename'];

            $Users      = M("users");
            $where = "phone=".$_SESSION['username'];
            // $where      = "phone='13040101273'";
            $img_url    = $Users->where($where)->field("img_url")->find();
            // unlink($img_url);
            $result = $Users->where($where)->save($data);

            if ($result !== false)
            {
                $this->personInfo("1");
                // $this->redirect('/Home/Person/personInfo');
            }
            else
            {
                // 数据库操作失败
            }
        }
        else
        {
            // $info = $upload->uploadOne($_FILES['img'])操作失败
        }
        
         // $this->assign("url",$img_url);
        
    }

    public function locaManage(){
        $user = $_SESSION['username'];
        // $user   = "13040101273";

        $Receiver = M("receiver");
        $where  = "phone=".$user." and "."is_out="."0";
        $data   = $Receiver->where($where)->select();

        // dump($data);

        if ($data !== false)
        {
            $this->assign("data",$data);
            $this->display("locamanage");
        }
        else
        {
            $this->display("locaManage");
        }
    }

    // public function addOrReviseButton(){
    //     // $abc['status']=1;
    //     // $this->ajaxReturn($abc);
    //     $this->locaManage("1");
    // }

    public function saveLocation($userName,
                                 $location1,
                                 $location2,
                                 $location3,
                                 $detailedLoc,
                                 $phoneNum      ){
        $user = $_SESSION['username'];
        // $tag  = session('tag');
        // $user = '13040101273';
        $tag  = 0;
        $rank = time();

        $data = array(
            'phone'      =>     $user,
            'rank'       =>     $rank,
            'name'       =>     $userName,
            'address'    =>     $location1."***".
                                $location2."***".
                                $location3."***".
                                $detailedLoc,
            'phone_id'   =>     $phoneNum,
            // 'tag'        =>     $tag,
            'is_out'     =>     0
            );

        $Receiver   =   M('receiver');
        $where      =   'phone='.$user.' and '.'rank='.$rank;
        
        // echo $where;
        // dump($data);

        $result     =   $Receiver->data($data)->add();

        // $this->locaManage();

        if ($result !== false)
        {
            $res = array(
                'result' => 1,
                'phone'  => $user,
                'rank'   => $rank
                );
            $this->ajaxReturn($res);
        }
        else
        {
            $res = array(
                'result' => 0
                );
            $this->ajaxReturn($res);
        }
    }

    public function getPhoneRank($phone,$rank){
        $where = "phone=".$phone." and "."rank=".$rank;
        $Receiver = M('receiver');
        $result = $Receiver->where($where)->find();
        
        if ($result !== false)
        {
            $res = array(
                'result' => 1
                );
            $this->ajaxReturn($result);
        }
        else
        {
           $res = array(
                'result' => 0
                );
            $this->ajaxReturn($res);
        }
    }

    public function reviseLocation( $phone,
                                    $rank,
                                    $userName,
                                    $location1,
                                    $location2,
                                    $location3,
                                    $detailedLoc,
                                    $phoneNum       ){
        // echo $phone;
        // $data = array(
        //     'phone_id'      =>  $phoneNum,
        //     'name'          =>  $userName,
        //     'address'       =>  $location1."***".
        //                         $location2."***".
        //                         $location3."***".
        //                         $detailedLoc
        // );
        // dump($data);

        $data = array(
            'is_out'    =>  1
            );

        $Receiver = M('receiver');
        $where  = 'phone='.$phone.' and '.'rank='.$rank;
        $result = $Receiver->where($where)->save($data);

        $this->locaManage();

        // if ($result !== false)
        // {
        //     $res = array(
        //         'result' => 1
        //         );
        //     $this->ajaxReturn($res);
        // }
        // else
        // {
        //     $res = array(
        //         'result' => 0
        //         );
        //     $this->ajaxReturn($res);
        // }
    }

    public function addOrReviseSave(){
        $user    = $_SESSION['username'];
        // $tag     = session('tag');
        // $user = "13040101273";
        $rank = Time();
        $tag  = 0;
        $data = array(
            'phone'         =>  $user,
            'phone_id'      =>  I('phone-number'),
            'name'          =>  I('user-name'),
            'address'       =>  I('select-location-1')."***".
                                I('select-location-2')."***".
                                I('select-location-3')."***".
                                I('detailed-location'),
            'tag'           =>  $tag,
            'rank'          =>  $rank,
            'is_out'        =>  0
        );
        // if (($data['phone_id']  != null)    &&
        //     ($data['name']      != null)    &&
        //     ($data['address']   != null)        ){
            
            $Receiver = M("receiver");
            $result = $Receiver->data($data)->add();

            if ($result !== false)
            {
                $this->redirect('/Home/Person/locaManage');
            }
            else
            {
                // 数据库操作失败
            }
            // if ($Receiver->data($data)->add() == false){
            //     echo "添加失败";
            // }
            // else{
            //     echo "添加失败添加成功";
            // }
            
        // }
    }

    public function deleteLocation($phone,$rank){
        $data = array(
            'is_out'    =>  1
            );

        $Receiver = M('receiver');
        $where  = "phone=".$phone." and "."rank=".$rank;
        $result = $Receiver->where($where)->save($data);

        if ($result !== false)
        {
            $res = array(
                'result' => 1
                );
            $this->ajaxReturn($res);
        }
        else
        {
            $res = array(
                'result' => 0
                );
            $this->ajaxReturn($res);
        }

        $this->locaManage();
    }
	
	
	function check_verify($code, $id = ''){
    	$verify = new \Think\Verify();
    	return $verify->check($code, $id);
	}
	public function verify(){
        // 行为验证码

        $Verify = new \Think\Verify();
        $Verify->fontSize = 23;
        $Verify->length   = 4;
        $Verify->useNoise = false;
        $Verify->codeSet = '0123456789';
        /*$Verify->imageW = 130;
        $Verify->imageH = 50;*/
        $Verify->entry();
    }
		
	public function forgetPword(){
      	$this->display("forgetpword");
     }

	public function check(){
		$check=$_POST['check'];
		$flag=$this->check_verify($check);
		if($flag){
			$state['value']='success';
			$this->ajaxReturn($state);
		}
		else{
			$state['value']='error';
			$this->ajaxReturn($state);
		}
	}
	
	
	public function phone(){
		$db=M('users');
		$user = $_SESSION['username'];
		$phone=$_POST["phone"];
		$where['phone']=$user;
		$data=$db->where($where)->field('phone')->find();
		if($data['phone']==$phone){
			$state['value']='success';
			$this->ajaxReturn($state);
		}
		else{
			$state['value']='error';
			$this->ajaxReturn($state);
		}
		
	}
	
	public function changePWord(){
		$db=M('users');
		$user = $_SESSION['username'];
		$pword=$_POST["pword"];
		$where['phone']=$user;
		$save['password']=md5($pword);
		$data=$db->where($where)->save($save);
		if($data>0){
			$state['value']='success';
			$this->ajaxReturn($state);
		}else{
			$state['value']='error';
			$this->ajaxReturn($state);
		}
	}
	
		/*li*/
}

?>