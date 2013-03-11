<?php
/**
 * 
 * Create the thumb image when loading an image
 * You should have a thumb.jpg in the $thumbPath
 * @author davidhhuan
 *
 */
class DLImageThumbHelper
{
    public $originalPath = "/upload/products/";
    public $thumbPath = "/upload/thumbs";
    
    /**
     * 
     * Create the thumb image
     * 
     * @param string $modelImage: The original image name
     * @param int $site: The size of the thumb image you want to create
     * @param int $resizeW
     * @param int $resizeH
     */
    public function createThumb($modelImage, $site="", $resizeW=100, $resizeH=100)
    {
        $oldImage = $modelImage;
        $modelImage = $this->_thumbName($modelImage, $site);
        $oldImageResite = $modelImage;
        
        if(!file_exists(Yii::getPathOfAlias("webroot").$this->originalPath.$oldImage))
        {
            //the default thumb image thumb.jpg if the orginal image does NOT exist
            $modelImage = "thumb.jpg";
            $oldImage = $modelImage;
            $modelImage = $this->_thumbName($modelImage, $site);
            $oldImageResite = $modelImage;
        }

		Yii::app()->thumb->setThumbsDirectory($this->thumbPath);
		if(!file_exists(Yii::getPathOfAlias("webroot").$this->thumbPath.$oldImageResite))
		{
			Yii::app()->thumb
			->load(Yii::getPathOfAlias("webroot").$this->originalPath.$oldImage)
			->resize($resizeW, $resizeH)
			->save($modelImage, "JPG", "JPG");
		}
        return $modelImage;
    }

    /**
     * 
     * Set the thumb image name
     * @param unknown_type $modelImage
     * @param unknown_type $site
     */
    private function _thumbName($modelImage, $site)
    {
        if(!empty($site))
        {
            $tem = explode(".", $modelImage);
            if(count($tem) > 1)
                $modelImage = $tem[0]."_".$site.".".$tem[1];//the file name starts with "m_", "s_", or null;
        }
        return $modelImage;
    }
}
