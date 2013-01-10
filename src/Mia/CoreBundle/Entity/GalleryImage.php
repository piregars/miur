<?php

namespace Mia\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Msi\DoctrineBehaviors\Uploadable\UploadableInterface;
use Msi\Bundle\CmfBundle\Tools\Cutter;

/**
 * @ORM\Entity
 */
class GalleryImage implements UploadableInterface
{
    use \Msi\DoctrineBehaviors\Uploadable\Traits\UploadableEntity;
    use \Msi\DoctrineBehaviors\Timestampable\Traits\TimestampableEntity;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $position;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $published;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $medium;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $size;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $year;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="Gallery", inversedBy="images")
     */
    protected $gallery;

    public function __construct()
    {
        $this->position = 1;
        $this->published = false;
    }

    public function getUploadDir()
    {
        return 'gallery/'.$this->getGallery()->getId();
    }

    public function processFile(\SplFileInfo $file)
    {
        $cutter = new Cutter;
        $cutter->setFile($file)->resize(150, 150)->save($file->getPath().'/t_'.$file->getFilename());
    }

    public function getAllowedExt()
    {
        return ['png', 'jpg', 'gif', 'jpeg'];
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getMedium()
    {
        return $this->medium;
    }

    public function setMedium($medium)
    {
        $this->medium = $medium;

        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    public function getGallery()
    {
        return $this->gallery;
    }

    public function setGallery($gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }
}