<?php

    $nonce     = $_GET['nonce'];
    $token     = 'hh';
    $timestamp = $_GET['timestamp'];
    $echostr   = $_GET['echostr'];
    $signature = $_GET['signature'];
    
    $array = array();
    $array = array($nonce, $timestamp, $token);
    sort($array);
    
    $str = sha1( implode( $array ) );
    if( $str == $signature && $echostr ){
        
        echo  $echostr;
        exit;
    }else{
      
      $postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
      $postObj = simplexml_load_string( $postArr );
      
      $fp = fopen("./data.txt","a");
      $str = "发送到：\n".$postObj->MsgType;
      fwrite($fp, $str);
      fclose($fp);
      
      if( strtolower( $postObj->MsgType) == 'event'){
            
            if( strtolower($postObj->Event == 'subscribe') ){
                
                $toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $content  = '欢迎关注我们的微信公众账号'.$postObj->FromUserName.'-'.$postObj->ToUserName;
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
            }
      }else if(strtolower( $postObj->MsgType) == 'text'){
        
        		
        		$content = $postObj->Content;
				if($content == '北京天气'){
    				$content = "北京天气\n\n"."今天最低气温-1°，最高气温11°\n\n"."烘云托月\n\n"."空气质量指数72，良\n"
   					."PM2.5 : 31    PM10:93\n"."北京空气质量还不错，对出行没有太大影响";
				}else{
    				$content  = "你发送的内容是：\n".$postObj->Content;
				}

				$toUser   = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time     = time();
                $msgType  =  'text';
                $template = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <Content><![CDATA[%s]]></Content>
                                </xml>";
                $info     = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
                echo $info;
      }
      
        
    
    }


	
