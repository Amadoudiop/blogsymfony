<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime,
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $dateCreate;

    /**
     * @var \DateTime,
     *
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $dateUpdate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="event", type="boolean")
     */
    private $event = false;

    /**
     * @var \DateTime,
     *
     * @ORM\Column(name="date_event", type="datetime")
     */
    private $dateEvent;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="catch_sentence", type="text", length=300)
     */
    private $catch_sentence;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var text,
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status = false;

    /**
     * Many Article has One Category
     **
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;




    public function __construct()
    {
        $this->dateCreate = new \DateTime('now');
        $this->dateUpdate = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreate
     *
     * @param datetime $dateCreate
     *
     * @return Article
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return datetime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param datetime $dateUpdate
     *
     * @return Article
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return datetime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set dateEvent
     *
     * @param datetime $dateEvent
     *
     * @return Article
     */
    public function setDateEvent($dateEvent)
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    /**
     * Get dateEvent
     *
     * @return datetime
     */
    public function getDateEvent()
    {
        return $this->dateEvent;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set catchSentence
     *
     * @param string $catchSentence
     *
     * @return Article
     */
    public function setCatchSentence($catchSentence)
    {
        $this->catch_sentence = $catchSentence;

        return $this;
    }

    /**
     * Get catchSentence
     *
     * @return string
     */
    public function getCatchSentence()
    {
        return $this->catch_sentence;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Article
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set content
     *
     * @param text $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return text,
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Article
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Article
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set event
     *
     * @param boolean $event
     *
     * @return Article
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return boolean
     */
    public function getEvent()
    {
        return $this->event;
    }
}
