<?php
namespace Blog;

use Carbon\Carbon;
use DB;

class AuthenticationService{
    public function verify($token){
        $token = $this->setExpireTime($token);
        return $token;
    }
    public function setExpireTime($token){
        $expireTime = Carbon::now()->addSeconds(960000)->timestamp;
        DB::table('oauth_access_tokens')->where('id', $token['access_token'])->update(['expire_time' => $expireTime]);
        $token['expires_in'] = 960000;
        return $token;
    }
}