<?php
/**
 * 验证码
 * Date: 2018/10/10
 */
class ValidateCode {
   // private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
    private $charset = '0123456789';//随机因子
    private $code;//验证码
    private $codelen = 4;//验证码长度
    private $width = 100;//宽度
    private $height = 30;//高度
    private $img;//图形资源句柄
    private $fontsize = 8;//指定字体大小
    private $fontcolor;//指定字体颜色

    //生成随机码
    private function createCode()
    {
        $_len = strlen($this->charset)-1;
        for ($i=0;$i<$this->codelen;$i++) {
            $this->code .= $this->charset[mt_rand(0,$_len)];
        }
    }

    //生成背景
    private function createBg()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        //$color = imagecolorallocate($this->img, 255,255,255);
        $color = imagecolorallocate($this->img, 0,0,0);
        imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
    }

    //生成文字
    private function createFont()
    {
        for ($i=0;$i<$this->codelen;$i++) {
           // $this->fontcolor = imagecolorallocate($this->img, mt_rand(0,120),mt_rand(0,120), mt_rand(0,120));
            $this->fontcolor = imagecolorallocate($this->img, 255,255, 255);
            imagestring($this->img,$this->fontsize,($i*100/4)+rand(5,10),mt_rand(5,10),$this->code[$i],$this->fontcolor);
        }
    }

    //生成线条、雪花
    private function createLine()
    {
        //线条
        for($i=0;$i<4;$i++){
            $linecolor = imagecolorallocate($this->img,mt_rand(80,220),mt_rand(80,220),mt_rand(80,220));
            imageline($this->img,mt_rand(1,99), mt_rand(1,29),mt_rand(1,99),mt_rand(1,29),$linecolor);
        }
        //雪花
//        for ($i = 0; $i < 200; $i++) {
//            $pointcolor = imagecolorallocate($this->img, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
//            imagesetpixel($this->img, mt_rand(1, 99), mt_rand(1, 29), $pointcolor);
//        }
    }

    //输出
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    //对外生成
    public function doimg()
    {
        $this->createBg();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    //获取验证码
    public function getCode()
    {
        return strtolower($this->code);
    }
}