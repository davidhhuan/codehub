<?php
/**
 *
 * Handle with the string
 *
 * @author davidhhuan
 */
class DLStringHelper
{
    /**
     *
     * @param type $data
     * @param type $msg
     * @param type $status
     * @return type 
     */
    public static function json_encode($data, $msg = '', $status = 1)
    {
       $rs = array(
            'done' => $status,
            'msg' => $msg,
            'data' => $data,
        );
        return json_encode($rs);
    }
    
    
    /**
     * generate and return random string
     * 
     * @param type $onlyAlphanumeric only number and word
     * @param type $length 
     */
    public static function randString($onlyAlphanumeric = true, $length = 10)
    {
        $rs = '';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        if (!$onlyAlphanumeric)
        {
            $chars .= '!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        }
        
        $charsLen = strlen($chars);
        for ($i = 0; $i < $length; $i++)
        {
            $rs .= $chars[mt_rand(0, $charsLen) - 1];
        }
        
        return $rs;
    }
    
    
    /**
     * cut the string with utf8 or gbk
     *
     * @author davidhhuan
     * @param string $string: the string you want to handle with
     * @param int $start
     * @param int $length: how long of the string you want
     * @param string $append: The end of the output string
     * @param bool $onlyCharacters only return the number of characters, but not the length of string
     * @return string
     */
    public static function substr($string, $start = 0, $length = 0, $append = '...', $onlyCharacters = false)
    {
    	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
    	
        $stringLast = "";
        if ($start < 0 || $length < 0 || self::strlen($string, $onlyCharacters) <= $length)
        {
            $stringLast = $string;
        }
        else
        {
            $i = 0;
            $j = 0;
            if ($onlyCharacters)
            {
                $count = &$j;
            }
            else
            {
                $count = &$i;
            }
            while ($count < $length)
            {
                $stringTMP = substr($string, $i, 1);
                if ( ord($stringTMP) >=224 )
                {
                    $stringTMP = substr($string, $i, 3);
                    $i = $i + 3;
                }
                elseif( ord($stringTMP) >=192 )
                {
                    $stringTMP = substr($string, $i, 2);
                    $i = $i + 2;
                }
                else
                {
                    $i = $i + 1;
                }
                $j++;
                $stringLast[] = $stringTMP;
            }
            $stringLast = implode("", $stringLast);
            
        }
        
        $stringLast = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $stringLast);

        if(!empty($append) && self::strlen($string, $onlyCharacters) > $length)
        {
            $stringLast .= $append;
        }
        
        return $stringLast;
    }


    /**
     *
     * strlen function
     *
     * @author davidhhuan
     * @param string $string
     * @param string $encoding It will be used in mb_strlen()
     * @param bool $onlyCharacters only return the number of characters, but not the length of string
     *
     */
    public static function strlen($string, $onlyCharacters = false)
    {
        $strlen = 0;
        $length = strlen($string);
        $characterLength = 0;
        while ($strlen < $length)
        {
            $stringTMP = substr($string, $strlen, 1);
            if ( ord($stringTMP) >=224 )
            {
                $strlen = $strlen + 3;
            }
            elseif( ord($stringTMP) >=192 )
            {
                $strlen = $strlen + 2;
            }
            else
            {
                $strlen = $strlen + 1;
            }
            $characterLength++;
        }

        return $onlyCharacters ? $characterLength : $strlen;
    }

    /**
     *
     * Limit the string of rows
     *
     * @author davidhhuan
     * @param string $string
     * @param int $rows: how many rows you want to limit the output
     * @return string
     */
    public static function limitRows($string, $rows = 100)
    {
        $lines = explode("\r\n", $string);

        if (count($lines) <= $rows)
        {
            return $string;
        }

        $rs = '';

        //echo count($rows);die();
        foreach($lines as $key=>$line)
        {
            if ($key>=$rows)
                break;

            $rs .= $line."\r\n";
        }

        return $rs;
    }
    
    
    /**
     *
     * convert the characters from full-width character to half-width character
     *
     * @author davidhhuan
     * @param string $string
     * @return string
     */
     public static function makeSemiangle($string)
     {
         $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
                 '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
                 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
                 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
                 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
                 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
                 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
                 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
                 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
                 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
                 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
                 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
                 'ｙ' => 'y', 'ｚ' => 'z',
                 '（' => '(', '）' => ')', '［' => '[', '］' => ']', '【' => '[',
                 '】' => ']', '〖' => '[', '〗' => ']', '「' => '[', '」' => ']',
                 '『' => '[', '』' => ']', '｛' => '{', '｝' => '}', '《' => '<',
                 '》' => '>',
                 '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
                 '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
                 '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
                 '＂' => '"', '＇' => '`', '｀' => '`', '｜' => '|', '〃' => '"',
                 '　' => ' ');

        return strtr($string, $arr);
    }


    /**
     * Change the charset from unicode to utf8
     *
     * @author davidhhuan
     * @param string $str: The string you want to convert
     * @param int $order: Pad a string to a certain length with another string
     * @return string
     */
    public static function unicodeToUtf8($str,$order="little")
    {
        $utf8string ="";
        $n=strlen($str);
        for ($i=0;$i<$n ;$i++ )
        {
            if ($order=="little")
            {
                $val = str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT) .
                       str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT);
            }
            else
            {
                $val = str_pad(dechex(ord($str[$i])),      2, 0, STR_PAD_LEFT) .
                       str_pad(dechex(ord($str[$i+1])), 2, 0, STR_PAD_LEFT);
            }
            $val = intval($val,16);
            $i++;
            $c = "";
            if($val < 0x7F)
            { // 0000-007F
                $c .= chr($val);
            }
            elseif($val < 0x800)
            { // 0080-07F0
                $c .= chr(0xC0 | ($val / 64));
                $c .= chr(0x80 | ($val % 64));
            }
            else
            { // 0800-FFFF
                $c .= chr(0xE0 | (($val / 64) / 64));
                $c .= chr(0x80 | (($val / 64) % 64));
                $c .= chr(0x80 | ($val % 64));
            }
            $utf8string .= $c;
        }
        /* remove bom symbol */
        if (ord(substr($utf8string,0,1)) == 0xEF && ord(substr($utf8string,1,2)) == 0xBB && ord(substr($utf8string,2,1)) == 0xBF)
        {
            $utf8string = substr($utf8string,3);
        }
        return $utf8string;
    }
}
