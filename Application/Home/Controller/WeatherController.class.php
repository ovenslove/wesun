<?php
namespace  Home\Controller;
use Think\Controller;

class  WeatherController extends  Controller{


    public  function index(){
        $ch = curl_init();
        $url = 'http://apis.baidu.com/apistore/weatherservice/recentweathers?cityid=101250101';
        $header = array(
            'apikey: 4879df8245f3310cb7ea1a6781fb4331',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = json_decode(curl_exec($ch),true);
////p($res);
        $this->data=$res['retData'];
        $this->hour= 12;
//        $this->hour= (int) date('H');
        $this->display('index');
    }
  public  function index2(){
        $ch = curl_init();
        $url = 'http://apis.baidu.com/apistore/weatherservice/recentweathers?cityid=101250101';
        $header = array(
            'apikey: 4879df8245f3310cb7ea1a6781fb4331',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = json_decode(curl_exec($ch),true);
////p($res);
        $this->data=$res['retData'];
        $this->hour= 12;
//        $this->hour= (int) date('H');
        $this->display('index');
    }







}