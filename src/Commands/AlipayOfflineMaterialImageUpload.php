<?php


namespace Jazor\AliPay\Commands;


use Jazor\AliPay\AlipayCommand;

class AlipayOfflineMaterialImageUpload extends AlipayCommand
{
    /**
     * 上传门店照片和视频接口
     * @param $image_type
     * @param $image_name
     * @param $image_content
     */
    public function __construct($image_type, $image_name, $image_content)
    {
        $this->method = 'alipay.offline.material.image.upload';
        $this->image_type = $image_type;
        $this->image_name = $image_name;
        $this->image_content = $image_content;
    }

    public function getParams()
    {
        return [
            'image_type' => $this->image_type,
            'image_name' => $this->image_name,
            'image_content' => '@' . $this->image_content,
        ];
    }
}

