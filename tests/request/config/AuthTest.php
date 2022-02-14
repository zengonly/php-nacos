<?php


namespace tests\request\config;


use alibaba\nacos\Nacos;
use alibaba\nacos\NacosConfig;
use alibaba\nacos\util\HttpUtil;
use alibaba\nacos\util\LogUtil;
use tests\TestCase;

class AuthTest extends TestCase
{

    public function testGetToken()
    {
        $host = ''; // 服务器地址
        $userName = 'nacos'; // 用户名
        $password = ''; // 密码

        NacosConfig::setHost($host);

        $authResponse = HttpUtil::request(
            'post',
            '/nacos/v1/auth/login',
            [
                'username' => $userName,
                'password' => $password,
            ]
        );

        $result = $authResponse->getBody()->getContents();

        LogUtil::info("获取到的结果为：" . $result);

        $resultArray = json_decode($result, true);

        $this->assertArrayHasKey("accessToken", $resultArray);
    }

    public function testGetConfigWithoutAuth()
    {
        $host = ''; // 服务器地址

        $content = Nacos::init(
            getenv("LARAVEL_NACOS_HOST") ?: $host,
            getenv("LARAVEL_ENV") ?: "dev",
            getenv("LARAVEL_NACOS_DATAID") ?: "pas",
            getenv("LARAVEL_NACOS_GROUPID") ?: "QD-DEV-A",
            getenv("LARAVEL_NACOS_NAMESPACEID") ?: "dev_0AJZ9pLr4zUCCBzh"
        )->runOnce();

        $this->assertEmpty($content);
    }

    public function testGetConfigWithAuth()
    {
        $host = ''; // 服务器地址
        $userName = 'nacos'; // 用户名
        $password = ''; // 密码

        $content = Nacos::init(
            getenv("LARAVEL_NACOS_HOST") ?: $host,
            getenv("LARAVEL_ENV") ?: "dev",
            getenv("LARAVEL_NACOS_DATAID") ?: "pas",
            getenv("LARAVEL_NACOS_GROUPID") ?: "QD-DEV-A",
            getenv("LARAVEL_NACOS_NAMESPACEID") ?: "dev_0AJZ9pLr4zUCCBzh",
            $userName,
            $password
        )->runOnce();

        $this->assertNotEmpty($content);
    }
}