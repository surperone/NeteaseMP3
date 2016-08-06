<?php
/**
 * Class NeteaseMusic
 */
class NeteaseMusic {
    /**
     * 将String转换为byteArray
     * @param  String $string 传入字符串
     * @return Array          byteArray
     */
    public static function getBytes($string) {
        $bytes = array();
        for ($i = 0; $i < strlen($string); $i++) {
            $bytes[] = ord($string[$i]);
        }
        return $bytes;
    }

    /**
     * 将byte数组转成ascii编码的字符串
     * @param  Array $bytes  byteArray
     * @return String        ascii字符串
     */
    public static function toStr($bytes) {
        $str = '';
        for ($i = 0; $i < count($bytes); $i++) {
            $str.= chr($bytes[$i]);
        }
        return $str;
    }

    /**
     * 获得加密后的下载URL
     * @param  int $id dfsId
     * @return String  mp3Url
     */
    public function get_hd_mp3_url($id) {
        $byte1[] = self::getBytes('3go8&$8*3*3h0k(2)2'); //18
        $byte2[] = self::getBytes($id); //16
        $magic = $byte1[0];
        $song_id = $byte2[0];
        for ($i = 0; $i < count($song_id); $i++) {
            $song_id[$i] = $song_id[$i] ^ $magic[$i % count($magic) ];
        }
        $result = base64_encode(md5(self::toStr($song_id) , true));
        $result = str_replace('/', '_', $result);
        $result = str_replace('+', '-', $result);
        //下面m后面的2当提取到的链接404时可以更换
        return "http://m2.music.126.net/" . $result . '/' . number_format($id, 0, '', '') . ".mp3";
    }

    /**
     * 获取歌曲的dfsId
     * @param int $id SongId
     * @return bool|string
     */
    public function get_music_dfsId($id) {
        $raw_json = file_get_contents('http://music.163.com/api/song/detail/?id=' . $id . '&ids=[' . $id . ']');
        $de_json = json_decode($raw_json, true);
        $hMusic_dfsId = !empty($de_json['songs'][0]['hMusic']) ? $de_json['songs'][0]['hMusic']['dfsId'] : null;
        $mMusic_dfsId = !empty($de_json['songs'][0]['mMusic']) ? $de_json['songs'][0]['mMusic']['dfsId'] : null;
        $lMusic_dfsId = !empty($de_json['songs'][0]['lMusic']) ? $de_json['songs'][0]['lMusic']['dfsId'] : null;
        if(!empty($hMusic_dfsId)){
            $dfsId = number_format($hMusic_dfsId, 0, '', '');
        }elseif(!empty($mMusic_dfsId)){
            $dfsId = number_format($mMusic_dfsId, 0, '', '');
        }elseif(!empty($lMusic_dfsId)){
            $dfsId = number_format($lMusic_dfsId, 0, '', '');
        }else{
            $dfsId = false;
        }
            return $dfsId;
    }
}

