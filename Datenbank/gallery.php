<?php


class Gallery
{
    private $galleryId;
    private $galleryName;
    private $galleryNativeName;
    private $galleryCity;
    private $galleryCountry;
    private $latitude;
    private $longitude;
    private $galleryWebSite;
    private $datenbank;
    private $gallery;


    public function __construct($galleryId, $galleryName, $galleryNativeName, $galleryCity, $galleryCountry, $latitude, $longitude, $galleryWebSite)
    {
        $this->galleryId = $galleryId;
        $this->galleryName = $galleryName;
        $this->galleryNativeName = $galleryNativeName;
        $this->galleryCity = $galleryCity;
        $this->galleryCountry = $galleryCountry;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->galleryWebSite = $galleryWebSite;
    }
    public function getGalleryName()
    {
        return $this->galleryName;
    }
    public function getGalleryNativeName()
    {
        return $this->galleryNativeName;
    }

    public function getGalleryCity()
    {
        return $this->galleryCity;
    }

    public function getGalleryCountry()
    {
        return $this->galleryCountry;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getGalleryWebSite()
    {
        return $this->galleryWebSite;
    }

    public static function fromState(array $gallery): Gallery
    {
        $galleryId = $gallery["GalleryID"] ?? null;
        $galleryName = $gallery["GalleryName"] ?? null;
        $galleryNativeName = $gallery["GalleryNativeName"] ?? null;
        $galleryCity = $gallery["GalleryCity"] ?? null;
        $galleryCountry = $gallery["GalleryCountry"] ?? null;
        $latitude = $gallery["Latitude"] ?? null;
        $longitude = $gallery["Longitude"] ?? null;
        $galleryWebSite = $gallery["GalleryWebSite"] ?? null;

        return new self($galleryId, $galleryName, $galleryNativeName, $galleryCity, $galleryCountry, $latitude, $longitude, $galleryWebSite);
    }

    public static function getDefaultGallery(): Gallery
    {
        return new self(-1, "", "", "", "", 0, 0, "");
    }
}
