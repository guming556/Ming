<?php
/**
 * Created by 孤鸣
 * User: GuMing
 * Date: 2018/9/4
 * Time: 16:55
 */
return [
    'app_id' => 'wxdad7a114a3895807',
    'app_secret' => '8da4392459327e8e0279932c96a64778',
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s'
];