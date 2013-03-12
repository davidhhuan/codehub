<?php

/**
 * 服务处理类
 * 
 */
class DLServerHelper
{

    /**
     * 客户端真实 IP
     */
    public static function IPClient ()
    {
        static $realip = NULL;
        if ($realip !== NULL)
        {
            return $realip;
        }
        if (isset($_SERVER))
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip)
                {
                    $ip = trim($ip);
                    if ($ip != 'unknown')
                    {
                        $realip = $ip;
                        break;
                    }
                }
            }
            elseif (isset($_SERVER['HTTP_CLIENT_IP']))
            {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            }
            else
            {
                if (isset($_SERVER['REMOTE_ADDR']))
                {
                    $realip = $_SERVER['REMOTE_ADDR'];
                }
                else
                {
                    $realip = '0.0.0.0';
                }
            }
        }
        else
        {
            if (getenv('HTTP_X_FORWARDED_FOR'))
            {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            }
            elseif (getenv('HTTP_CLIENT_IP'))
            {
                $realip = getenv('HTTP_CLIENT_IP');
            }
            else
            {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }

    /**
     * 获取服务器的ip
     *
     *
     * @return string
     * */
    public static function IPServer ()
    {
        static $serverip = NULL;
        if ($serverip !== NULL)
        {
            return $serverip;
        }
        if (isset($_SERVER))
        {
            if (isset($_SERVER['SERVER_ADDR']))
            {
                $serverip = $_SERVER['SERVER_ADDR'];
            }
            else
            {
                $serverip = '0.0.0.0';
            }
        }
        else
        {
            $serverip = getenv('SERVER_ADDR');
        }
        return $serverip;
    }

}
