<?php

/**
 * Description of ImageDNUI
 *
 * @author nicearma
 */
class ImageDNUI implements JsonSerializable
{

    public $id; //the same id of the database
    public $name;
    public $sizeName;
    public $resolution;
    public $status;
    public $srcOriginalImage; //the origina src
    public $imageSizes; //the list of imageSize, see the ImageSize

    function __construct($id, $name, $sizeName, $srcOriginalImage, $resolution)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sizeName = $sizeName;
        $this->srcOriginalImage = $srcOriginalImage;
        $this->resolution = $resolution;
        $this->status = new StatusDNUI();
        $this->imageSizes = array();

    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSizeName()
    {
        return $this->sizeName;
    }

    /**
     * @param mixed $sizeName
     */
    public function setSizeName($sizeName)
    {
        $this->sizeName = $sizeName;
    }


    /**
     * @return mixed
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @param mixed $resolution
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    /**
     * @return StatusDNUI
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param StatusDNUI $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }


    /**
     * @return mixed
     */
    public function getSrcOriginalImage()
    {
        return $this->srcOriginalImage;
    }

    /**
     * @param mixed $srcOriginalImage
     */
    public function setSrcOriginalImage($srcOriginalImage)
    {
        $this->srcOriginalImage = $srcOriginalImage;
    }

    /**
     * @return mixed
     */
    public function getImageSizes()
    {
        return $this->imageSizes;
    }

    /**
     * @param mixed $imageSize
     */
    public function addImageSize($imageSize)
    {
        $this->imageSizes[$imageSize->getSizeName()] = $imageSize;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }


}



