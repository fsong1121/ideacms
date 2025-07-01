<?php
// 应用公共文件

use think\facade\Db;

/**
 * 返回成功
 * @param array $data
 * @param string $msg
 * @return array
 */
if (!function_exists('success')) {
    function success(array $data = [], string $msg = ''): array
    {
        return [
            'code' => 0,
            'msg' => !empty($msg) ? $msg : 'success',
            'data' => !empty($data) ? $data : [],
        ];
    }
}

/**
 * 返回失败
 * @param string $msg
 * @param int $code
 * @param array $data
 * @return array
 */
if (!function_exists('fail')) {
    function fail(string $msg = '', int $code = 500, array $data = []) : array
    {
        return [
            'code' => $code,
            'msg' => !empty($msg) ? $msg : 'error',
            'data' => !empty($data) ? $data : [],
        ];
    }
}

/**
 * 判断是否手机端访问
 * @return bool
 */
if (!function_exists('isMobile')) {
    function isMobile() : bool
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
            return true;
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
                return true;
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            }
        }
        return false;
    }
}

/**
 * 检查表是否存在
 * @param $table
 * @return mixed
 */
if (!function_exists('tableExist')) {
    function tableExist($table)
    {
        $table = env('DB_PREFIX', '') . $table;
        return Db::query('show tables like "' . $table . '"');
    }
}

/**
 * 是否无效用户名
 * @param string $uid
 * @return bool
 */
if (!function_exists('isInvalidUid')) {
    function isInvalidUid(string $uid = ''): bool
    {
        $isInvalid = false;
        $reservedUser = config('site.user_reserved_word');
        if (!empty($reservedUser)) {
            $reservedUserArr = explode(",", $reservedUser);
            foreach ($reservedUserArr as $key => $value) {
                if ($value == $uid) {
                    $isInvalid = true;
                    break;
                }
            }
        }
        return $isInvalid;
    }
}

/**
 * 获取图片地址
 * @param $pic
 * @param int $type 0:一般图片 1:头像
 * @return string
 */
if (!function_exists('getPic')) {
    function getPic($pic = '', int $type = 0): string
    {
        $path = request()->domain() . '/upload';
        if (empty($pic)) {
            if ($type == 1) {
                return $path . '/pic/public/tx.jpg';
            } else {
                return $path . '/pic/public/nopic.jpg';
            }
        } else {
            if (strpos($pic, "http") !== false) {
                return $pic;
            } else {
                return $path . '/pic/' . $pic;
            }
        }
    }
}

/**
 * 创建UUID
 * @param string $prefix
 * @return string
 */
if (!function_exists('makeUuid')) {
    function makeUuid(string $prefix = '') : string
    {
        $str = md5(uniqid(mt_rand(), true));
        $uuid = substr($str, 0, 8) . '-';
        $uuid .= substr($str, 8, 4) . '-';
        $uuid .= substr($str, 12, 4) . '-';
        $uuid .= substr($str, 16, 4) . '-';
        $uuid .= substr($str, 20, 12);
        return strtoupper($prefix . $uuid);
    }
}

/**
 * 统一密码加密方式，如需变动直接修改此处
 * @param string $password
 * @param string $type
 * @return string
 */
if (!function_exists('makePassword')) {
    function makePassword(string $password, string $type = 'md5') : string
    {
        if ($type == 'md5') {
            return md5(md5('Idea' . $password));
        } else {
            return password_hash($password, PASSWORD_DEFAULT);
        }
    }
}

/**
 * 获取随机数
 * @param int $num
 * @return string
 */
if (!function_exists('makeRandStr')) {
    function makeRandStr(int $num = 8) : string
    {
        return substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, $num);
    }
}

/**
 * 生成订单号(雪花算法)
 * @param string $prefix
 * @return string
 */
if (!function_exists('makeOrderSn')) {
    function makeOrderSn(string $prefix = '') : string
    {
        return $prefix . SnowFlake::createOnlyId();
    }
}

/**
 * 时间格式化
 * @param $time
 * @param int $format
 * @param int $type 1:时间戳 2:普通时间
 * @return false|int|mixed|string
 */
if (!function_exists('formatDate')) {
    function formatDate($time, int $format = 1, int $type = 1)
    {
        if ($type == 2) $time = strtotime($time);
        switch ($format) {
            case 1:
                $time = date("Y-m-d H:i:s", $time);
                break;
            case 2:
                $time = date("Y.m.d", $time);
                break;
            case 3:
                $time = date("Y-m-d", $time);
                break;
            case 4:
                $time = date("Y年m月d日", $time);
                break;
        }
        return $time;
    }
}

/**
 * 价格格式化
 * @param $price
 * @param int $length
 * @return string
 */
if (!function_exists('formatPrice')) {
    function formatPrice($price, int $length = 2) : string
    {
        return sprintf("%." . $length . "f", $price);
    }
}

/**
 * 过滤字符串空格
 * @param string $str
 * @return string
 */
if (!function_exists('trimStr')) {
    function trimStr(string $str = '') : string
    {
        $search = array(" ", "　", "\n", "\r", "\t");
        $replace = array("", "", "", "", "");
        return str_replace($search, $replace, $str);
    }
}

/**
 * 过滤emoji表情
 * @param string $str
 * @return string
 */
if (!function_exists('removeEmoji')) {
    function removeEmoji(string $str = '') : string
    {
        $str = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $str);
        $str = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $str);
        $str = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $str);
        $str = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $str);
        return preg_replace('/[\x{2700}-\x{27BF}]/u', '', $str);
    }
}

/**
 * post提交
 * @param string $url
 * @param array $postData
 * @param array $aHeader
 * @return mixed
 */
if (!function_exists('curlPost')) {
    function curlPost(string $url = '', array $postData = [], array $aHeader = [])
    {
        // 发送post请求
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!empty($aHeader)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
        $result = curl_exec($ch);
        curl_close($ch);
        //第二个参数为true，表示格式化输出json
        return json_decode($result, true);
    }
}

/**
 * get提交
 * @param string $url
 * @param array $data
 * @return mixed
 */
if (!function_exists('curlGet')) {
    function curlGet(string $url = '', array $data = [])
    {
        $ch = curl_init();
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        $result = preg_replace('/[\x00-\x1F\x80-\x9F]/u', '', trim($result));
        //第二个参数为true，表示格式化输出json
        return json_decode($result, true);
    }
}

/**
 * 获取起始时间戳 (0:今日 1:本周 2:本月 3:本季)
 * @param int $type
 * @return array
 */
if (!function_exists('getDateStamp')) {
    function getDateStamp(int $type = 0) : array
    {
        $data = ['b_time' => 0, 'e_time' => 0, 'title' => ''];
        switch ($type) {
            case 0:
                $data['b_time'] = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                $data['e_time'] = mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')) - 1;
                $data['title'] = '今日';
                break;
            case 1:
                $data['b_time'] = mktime(0, 0, 0, date('m'), date('d') - date('w') + 1, date('Y'));
                $data['e_time'] = mktime(23, 59, 59, date('m'), date('d') - date('w') + 7, date('Y'));
                $data['title'] = '本周';
                break;
            case 2:
                $data['b_time'] = mktime(0, 0, 0, date('m'), 1, date('Y'));
                $data['e_time'] = mktime(23, 59, 59, date('m'), date('t'), date('Y'));
                $data['title'] = '本月';
                break;
            case 3:
                $season = ceil((date('n')) / 3);//当月是第几季度
                $data['b_time'] = mktime(0, 0, 0, $season * 3 - 3 + 1, 1, date('Y'));
                $data['e_time'] = mktime(23, 59, 59, $season * 3, date('t', mktime(0, 0, 0, $season * 3, 1, date("Y"))), date('Y'));
                $data['title'] = '本季';
                break;
            default:
        }
        return $data;
    }
}

/**
 * 二维数组根据某个字段排序
 * @param array $array  要排序的数组
 * @param string $keys  要排序的键字段
 * @param string $sort  排序类型:SORT_ASC,SORT_DESC
 * @return array 排序后的数组
 */
if (!function_exists('arraySort')) {
    function arraySort(array $array = [], string $keys = '', string $sort = 'SORT_DESC') : array
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
}

/**
 * 只保留字符串首尾字符，隐藏中间用*代替（两个字符时只显示第一个）
 * @param string $user_name
 * @param int $firstNum 开始保留多少位
 * @param int $lastNum  结尾保留多少位
 * @return string
 */
if (!function_exists('strCut')) {
    function strCut(string $user_name, int $firstNum = 1, int $lastNum = 1) : string
    {
        $str = '';
        $strLen = mb_strlen($user_name, 'utf-8');
        $allNum = $firstNum + $lastNum;
        if ($strLen <= $firstNum) {
            $str = $user_name;
        }
        if ($strLen > $firstNum && $strLen <= $allNum) {
            $firstStr = mb_substr($user_name, 0, $firstNum, 'utf-8');
            $str = $firstStr . str_repeat("*", $strLen - $firstNum);
        }
        if ($strLen > $allNum) {
            $firstStr = mb_substr($user_name, 0, $firstNum, 'utf-8');
            $lastStr = mb_substr($user_name, -1 * $lastNum, $lastNum, 'utf-8');
            $str = $firstStr . str_repeat("*", $strLen - $allNum) . $lastStr;
        }
        return $str;
    }
}

/**
 * aes加密(参数加密传输使用)
 * @param string $str
 * @param string $key
 * @param string $iv
 * @return string
 */
if (!function_exists('aesEncrypt')) {
    function aesEncrypt(string $str = '', string $key = '', string $iv = '') : string
    {
        return base64_encode(strtoupper(bin2hex(openssl_encrypt($str, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv))));
    }
}

/**
 * aes解密(参数加密传输使用)
 * @param string $str
 * @param string $key
 * @param string $iv
 * @return string
 */
if (!function_exists('aesDecrypt')) {
    function aesDecrypt(string $str = '', string $key = '', string $iv = '') : string
    {
        $str = hex2bin(base64_decode($str));
        return openssl_decrypt($str, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}

/**
 * 获取商品信息
 * @param int $goods_id
 * @param string $spec_key
 * @param string $field
 * @return array|mixed
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 */
if (!function_exists('getGoodsInfo')) {
    function getGoodsInfo(int $goods_id = 0, string $spec_key = '', string $field = '*')
    {
        $data = Db::name('goods')
            ->where('id', $goods_id)
            ->field($field)
            ->find();
        if (!empty($data)) {
            $specGoods = Db::name('goods_price')
                ->where('goods_id', $goods_id)
                ->where('spec_key', $spec_key)
                ->find();
            if (empty($specGoods)) {
                $specGoods = Db::name('goods_price')
                    ->where('goods_id', $goods_id)
                    ->where('stock','>',0)
                    ->find();
            }
            if (!empty($specGoods)) {
                $data['price'] = $specGoods['price'];
                $data['market_price'] = $specGoods['market_price'];
                $data['cost_price'] = $specGoods['cost_price'];
                $data['weight'] = $specGoods['weight'];
                $data['volume'] = $specGoods['volume'];
                $data['stock'] = $specGoods['stock'];
                $data['sku'] = $specGoods['sku'];
                $data['spec_key_name'] = $specGoods['spec_key_name'];
            }
        }
        return $data;
    }
}

/**
 * 获取会员等级信息
 * @param int $user_id
 * @return array
 * @throws \think\db\exception\DataNotFoundException
 * @throws \think\db\exception\DbException
 * @throws \think\db\exception\ModelNotFoundException
 */
if (!function_exists('getUserLevel')) {
    function getUserLevel(int $user_id = 0) : array
    {
        $result = [];
        $user = Db::name('user')
            ->where('id', $user_id)
            ->find();
        if (!empty($user)) {
            if ($user['level_id'] == 0) {
                $result['title'] = '注册会员';
                $result['rebate'] = 100;
                $result['sequence'] = 0;
            } else {
                $userLevel = Db::name('user_level')
                    ->where('id', $user['level_id'])
                    ->find();
                $result['title'] = $userLevel['title'];
                $result['rebate'] = $userLevel['rebate'];
                $result['sequence'] = $userLevel['sequence'];
            }
            $result['uid'] = $user['uid'];
        }
        return $result;
    }
}

/**
 * 解析数组到ini文件
 * @param  array 	$array 		数组
 * @param  string 	$content 	字符串
 * @return string	返回一个ini格式的字符串
 */
if (!function_exists('parseArrayIni')) {
    function parseArrayIni(array $array,string $content = '') : string
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // 分割符PHP_EOL
                $content .= PHP_EOL.'['.$key.']'.PHP_EOL;
                foreach ($value as $field => $data) {
                    $content .= $field .' = '. $data . PHP_EOL;
                }
            } else {
                $content .= $key .' = '. $value . PHP_EOL;
            }
        }
        return $content;
    }
}

/**
 * 计算活动价格
 * @param $price
 * @param int $type
 * @param $rebate
 * @return string
 */
if (!function_exists('activePrice')) {
    function activePrice($price, int $type = 0, $rebate) : string
    {
        if($type == 0) {
            $price = $price - $rebate;
        }
        if($type == 1) {
            $price = $price * $rebate / 10;
        }
        return formatPrice($price);
    }
}