<?php
namespace Home\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf-8");

class PersonController extends Controller {
    
    public function _initialize() {
        if (!isset($_SESSION['username'])) {
            $this->redirect('/Home/Login/Index');
        }
    }

    public function index(){
        $this->personInfo();
    }

    public function personInfo($active = "0"){
        $user = $_SESSION['username'];

        if ($user != null)
        {
            $Users  = M("users");
            $where  = array(
                'phone' => $user
                );
            $field  = array(
                'nickname',
                'sex',
                'academy',
                'qq,weixin',
                'img_url'
                );
            $data   = $Users->where($where)
                            ->field($field)
                            ->find();

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
        else
        {
            $this->redirect('/Home/Login/index');
        }

    }

    public function savePersonInfo($nickname,
                                   $usersex,
                                   $academy,
                                   $qq,
                                   $weixin      ){

        $user = $_SESSION['username'];

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
        $where = array(
            'phone' => $user
            );
        $result = $Users->where($where)
                        ->save($data);     

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
        $user = $_SESSION['username'];

        if ($username != null)
        {
            $nickname   = I('nick-name');
            $usersex    = I('user-sex');
            $academy    = I('academy');
            $qqnum      = I('qq-num');
            $weixinnum  = I('weixin-num');
      
            $_SESSION['nickname']   = $nickname;
            $_SESSION['academy']    = $academy;

            $Users = M("users");
            $where = array(
                'phone'  => $user
                );

            $data = array(
                'nickname'   => $nickname,
                'sex'        => $usersex,
                'academy'    => $academy,
                'qq'         => $qqnum,
                'weixin'     => $weixinnum
                );

            $Users->where($where)
                  ->save($data);

            $this->personInfo("0");
        }
        else
        {
            $this->redirect('/Home/Login/index');
        }
    }

    public function savePortrait(){
        $user = $_SESSION['username'];

        if ($user != null)
        {
            $upload             = new \Think\Upload();
            $upload->maxSize    = 4194304;
            $upload->exts       = array('jpg','gif','jpeg','bmp');
            $upload->rootPath   = './Public/';
            $upload->savePath   = '/Uploads/';
            
            $info = $upload->uploadOne($_FILES['img']);

            if ($info)
            {
                $data['img_url'] = '/foryou/Public'
                                  .$info['savepath']
                                  .$info['savename'];

                $Users  = M("users");
                $where  = array(
                    'phone'  => $user
                    );

                $img_url = $Users->where($where)
                                 ->field("img_url")
                                 ->find();
                // unlink($img_url);
                $result = $Users->where($where)
                                ->save($data);

                if ($result !== false)
                {
                    $this->redirect('/Home/Person/personInfo',array('active'=>1));
                }
                else
                {
                    // 数据库操作失败
                }
            }
            else
            {
                // $info = $upload->uploadOne($_FILES['img'])操作失败
                $this->redirect('/Home/Person/personInfo',array('active'=>1));
            }
            
             // $this->assign("url",$img_url);
        }
        else
        {
            $this->redirect('/Home/Login/index');
        }
        
    }

    public function locaManage(){
        $user = $_SESSION['username'];

        if ($user != null)
        {
            $Receiver = M("receiver");
            $where  = array(
                'phone'  => $user,
                'is_out' => 0,
                '_logic'  => 'and'
                );
            $data   = $Receiver->where($where)
                               ->select();

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
        else
        {
            $this->redirect('/Home/Login/index');
        }

    }

    public function saveLocation($userName,
                                 $location1,
                                 $location2,
                                 $detailedLoc,
                                 $phoneNum      ){
        $user = $_SESSION['username'];
        $tag  = 0;
        $rank = time();

        $data = array(
            'phone'      =>     $user,
            'rank'       =>     $rank,
            'name'       =>     $userName,
            'address'    =>     $location1."^".
                                $location2."^".
                                $detailedLoc,
            'phone_id'   =>     $phoneNum,
            'is_out'     =>     0
            );

        $Receiver    =   M('receiver');
        $where       =   array(
            'phone'  => $user,
            'rank'   => $rank,
            '_logic' => 'and'
            );

        $result     =   $Receiver->data($data)
                                 ->add();

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
        $Receiver = M('receiver');
        $where = array(
            'phone'  => $phone,
            'rank'   => $rank,
            '_logic' => 'and'
            );
        $result = $Receiver->where($where)
                           ->find();
        
        if ($result !== false)
        {
            $locations = explode('^',$result['address']);

            $whereCity = array(
                'city_name' => $locations[0]
                );
            $city = M('city')->where($whereCity)
                                              ->find();

            $result['city']        = $locations[0];
            $result['campus']      = $locations[1];
            $result['detailedLoc'] = $locations[2];
            $result['city_id']     = $city['city_id'];
            $result['result']      = 1;

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
                                    $detailedLoc,
                                    $phoneNum       ){

        $data = array(
            'is_out'    =>  1
            );

        $Receiver = M('receiver');
        $where  = array(
            'phone'  => $phone,
            'rank'   => $rank,
            '_logic' => 'and'
            );
        $result = $Receiver->where($where)
                           ->save($data);

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

    public function selectCity(){
        $Person = D('Person');
        $cities = $Person->getCities();
        /*dump($cities);*/
        $this->ajaxReturn($cities);
    }

    public function selectCampus($cityID){
        $Person = D('Person');
        $campus = $Person->getCampus($cityID);

        $this->ajaxReturn($campus);
    }

    public function addOrReviseSave(){
        $Person = D('Person');

        $user   = $_SESSION['username'];
        $rank   = Time();

        if ($Person->addressIsEmpty())
        {
            $tag = 0;
        }
        else
        {
            $tag = 1;
        }

        $campus_id = $Person->getCampusID(I('select-location-2'));

        $data = array(
            'phone'         =>  $user,
            'phone_id'      =>  I('phone-number'),
            'name'          =>  I('user-name'),
            'address'       =>  I('select-location-1')."^".
                                I('select-location-2')."^".
                                I('detailed-location'),
            'tag'           =>  $tag,
            'rank'          =>  $rank,
            'is_out'        =>  0,
            'campus_id'     =>  $campus_id
        );
            
        $Receiver = M("receiver");
        $result = $Receiver->data($data)
                               ->add();

        if ($result !== false)
        {
            $page = I('page');

            if ($page != '0')
            {
                $this->redirect('/Home/Person/goodsPayment');
            }
            else
            {
                $this->redirect('/Home/Person/locaManage');
            }
        }
        else
        {
            // 数据库操作失败
        }

    }

    public function deleteLocation($phone,$rank){
        $data = array(
            'is_out'    =>  1
            );

        $Receiver = M('receiver');
        $where  = array(
            'phone'  => $phone,
            'rank'   => $rank,
            '_logic' => 'and'
            );
        $result = $Receiver->where($where)
                           ->save($data);

        if ($result !== false)
        {
            $Person = D('Person');
            $Person->deleteAddress($rank);

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
        $Verify->codeSet  = '0123456789';
        /*$Verify->imageW = 130;
        $Verify->imageH = 50;*/
        $Verify->entry();
    }
        
    public function forgetPword(){
        $user = $_SESSION['username'];

        if ($user != null)
        {
            $this->display("forgetpword");
        }
        else
        {
            $this->redirect('Home/Login/index');
        }
     }

    public function check(){
        $check  = $_POST['check'];
        $flag   = $this->check_verify($check);

        if($flag)
        {
            $state = array(
                'value' => 'success'
                );
            $this->ajaxReturn($state);
        }
        else
        {
            $state = array(
                'value' => 'error'
                );
            $this->ajaxReturn($state);
        }
    }
    
    public function phone(){
        $db=M('users');

        $user  = $_SESSION['username'];
        $phone = $_POST["phone"];

        $where = array(
            'phone' => $user
            );
        $data=$db->where($where)
                 ->field('phone')
                 ->find();

        if($data['phone'] == $phone)
        {
            $state = array(
                'value' => 'success'
                );
            $this->ajaxReturn($state);
        }
        else
        {
            $state = array(
                'value' => 'error'
                );
            $this->ajaxReturn($state);
        }
        
    }
    
    public function changePWord(){
        $db = M('users');

        $user = $_SESSION['username'];
        $pword=$_POST["pword"];

        $where = array(
            'phone' => $user
            );
        $save  = array(
            'password' => md5($pword)
            );
        $data=$db->where($where)
                 ->save($save);

        if($data>0)
        {
            $state = array(
                'value' => 'success'
                );
            $this->ajaxReturn($state);
        }
        else
        {
            $state = array(
                'value' => 'error'
                );
            $this->ajaxReturn($state);
        }
    }

    public function goodsPayment(){
        $user = $_SESSION['username'];

        if ($user != null)
        {
            $orderIDstr = I('orderIds');
            if ($orderIDstr != '')
            {
                session('orderIDstr',$orderIDstr);
            }
            else
            {
                $orderIDstr = $_SESSION['orderIDstr'];
            }            

            $Person      = D('Person');
            $together_id = $Person->setTogetherID();

            $address   = $Person->getAddress();
            $orderInfo = $Person->getOrderInfo($together_id);
            $goodsInfo = $Person->getGoodsInfo();
            $price     = $Person->getTotalPrice();
            // $cities    = $Person->getCities();
            // $campus    = $Person->getCampus($cities[0]['city_id']);

            $this->assign('orderIDstr',$orderIDstr);
            $this->assign('address',$address);
            $this->assign('orderInfo',$orderInfo);
            $this->assign('goodsInfo',$goodsInfo);
            $this->assign('price',$price);
            // $this->assign('cities',$cities);
            // $this->assign('campus',$campus);
            $this->display("goodsPayment");
        }
        else
        {
            $this->redirect('Home/Login/index');
        }
    }

    public function payAtOnce(){
        $user = $_SESSION['username'];

        if ($user != null)
        {
            $together_id = I('together-id');
            echo $together_id."<br>";

            $orderIDstr = I('orderIDstr');
            echo "Iamhere"."<br>";
            echo $orderIDstr;
            $Person = D('Person');
            $price  = $Person->getTotalPrice($together_id,$orderIDstr);
            dump($price);
            
            $pay = array(
                'address'     => I('information'),
                'payMethod'   => I('pay-way'),
                'reversetime' => I('time'),
                'message'     => I('message'),
                'totalPrice'  => $price['discountPrice']
                );

            dump($pay);
        }
        else
        {
            $this->redirect('Home/Login/index');
        }
    }
}


?>