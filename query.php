<?php

require_once('NeteaseMusic.class.php');

if(isset($_POST['songid'])){
    $songid = $_POST['songid'];
    $NeteaseMusic = new NeteaseMusic;
    $dfsId = $NeteaseMusic -> get_music_dfsId($songid);
    if(!$dfsId){
        header('Location: index.html');
    }else{
        $mp3_url = $NeteaseMusic -> get_hd_mp3_url($dfsId);
		header('Location: ' . $mp3_url);
    }
}else{
    header('Location: index.html');
}

?>