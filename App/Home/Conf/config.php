<?php
return array(
    'DB_TYPE' =>  'mysql',
    //'DB_HOST' =>  '120.26.213.172',
    'DB_HOST' =>  '127.0.0.1',
    'DB_NAME' =>  'foryou',
    'DB_USER' =>  'root',
    'DB_PWD'  =>  'zc0829',
    'DB_PORT'  =>  '3306',
    'SHOW_PAGE_TRACE' => true,  //开启调试模式

    'LOG_RECORD' => true, // 开启日志记录
    'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误

    'view_filter' => array('Behavior\TokenBuild'),    //开启表单令牌功能，防止表单的重复提交

     'THINK_EMAIL' => array(
        //网易邮箱有问题
       /*'SMTP_HOST'   => 'smtp.163.com', //SMTP服务器
       'SMTP_PORT'   => '465', //SMTP服务器端口
       'SMTP_USER'   => 'eshuhui@163.com', //SMTP服务器用户名
       'SMTP_PASS'   => 'lquarwobqvupkffz', //SMTP服务器密码
       'FROM_EMAIL'  => 'eshuhui@163.com', //发件人EMAIL
       'FROM_NAME'   => '艺塾汇', //发件人名称
       'REPLY_EMAIL' => 'eshuhui@163.com', //回复EMAIL（留空则为发件人EMAIL）
       'REPLY_NAME'  => '艺塾汇', //回复名称（留空则为发件人名称）*/


        //qq邮箱验证通过
       'SMTP_HOST'   => 'smtp.exmail.qq.com', //SMTP服务器
       'SMTP_PORT'   => '465', //SMTP服务器端口
       'SMTP_USER'   => 'zhihao@silverhand.cn', //SMTP服务器用户名
       'SMTP_PASS'   => 'zc0829.', //SMTP服务器密码
       'FROM_EMAIL'  => 'zhihao@silverhand.cn', //发件人EMAIL
       'FROM_NAME'   => 'foryou,专属于您的线上超市', //发件人名称
       'REPLY_EMAIL' => 'zhihao@silverhand.cn', //回复EMAIL（留空则为发件人EMAIL）
       'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）

      /* 'SMTP_HOST'   => 'smtp.exmail.qq.com', //SMTP服务器
       'SMTP_PORT'   => '465', //SMTP服务器端口
       'SMTP_USER'   => '1131363476@qq.cn', //SMTP服务器用户名
       'SMTP_PASS'   => 'jing&hao0829', //SMTP服务器密码
       'FROM_EMAIL'  => '1131363476@qq.cn', //发件人EMAIL
       'FROM_NAME'   => 'ForU', //发件人名称
       'REPLY_EMAIL' => '1131363476@qq.cn', //回复EMAIL（留空则为发件人EMAIL）
       'REPLY_NAME'  => 'ForU', //回复名称（留空则为发件人名称）*/

      /* 'SMTP_HOST'   => 'smtp.126.com', //SMTP服务器
       'SMTP_PORT'   => '465', //SMTP服务器端口
       'SMTP_USER'   => 'coryphaei@126.com', //SMTP服务器用户名
       'SMTP_PASS'   => 'myymvnopqpqbymfm', //SMTP服务器密码
       'FROM_EMAIL'  => 'coryphaei@126.com', //发件人EMAIL
       'FROM_NAME'   => 'ForU', //发件人名称
       'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
       'REPLY_NAME'  => '', //回复名称（留空则为发件人名称）*/
      
    ), 
);