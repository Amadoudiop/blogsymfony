<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \datetime;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * one user as one element
     *
     * @ORM\OneToOne(targetEntity="Element", mappedBy ="article", fetch="LAZY")
     */
    private $element;

    /**
     * @var boolean
     *
     * @ORM\Column(name="event", type="boolean")
     */
    private $event = false;

    /**
     * @var \DateTime,
     *
     * @ORM\Column(name="date_event", type="datetime", nullable=true)
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
     * @var string
     */
    private $picture_upload;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled = false;

    /**
     * Many Article has One Category
     *
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="articles")
     * @ORM\JoinColumn(name="id_category", referencedColumnName="id")
     */
    private $category;

    /**
     * Many Article has One User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="articles", fetch="LAZY")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=true)
     */
    private $user;


    public function __construct()
    {
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
     * Set picture_upload
     *
     * @param string $picture
     *
     * @return Article
     */
    public function setPictureUpload($picture_upload)
    {
        $this->picture_upload = $picture_upload;

        return $this;
    }

    /**
     * Get picture_upload
     *
     * @return string
     */
    public function getPictureUpload()
    {
        return $this->picture_upload;
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Article
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
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

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Article
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set element
     *
     * @param \AppBundle\Entity\Element $element
     *
     * @return Article
     */
    public function setElement(\AppBundle\Entity\Element $element = null)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Get element
     *
     * @return \AppBundle\Entity\Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * set element for user
     * @ORM\PrePersist
     */
    public function articlePersist()
    {
        $element = new Element($this);
        $this->setElement($element);
    }
}
