<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \datetime;

/**
 * Element
 *
 * @ORM\Table(name="element")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ElementRepository")
 */
class Element
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
     * One Element has One or Zero User
     *
     * @ORM\OneToOne(targetEntity="User", inversedBy="element", fetch="EAGER")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * One Element has One or Zero Article
     *
     * @ORM\OneToOne(targetEntity="Article", inversedBy="element", fetch="EAGER")
     * @ORM\JoinColumn(name="id_article", referencedColumnName="id", nullable=true)
     */
    private $article;


    /**
     * @var \DateTime,
     *
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $dateUpdate;

    /**
     * @var \DateTime,
     *
     * @ORM\Column(name="date_create", type="datetime", nullable=false)
     */
    private $dateCreate;

    public function __construct($element)
    {
        if (get_class($element) == "AppBundle\Entity\Article") {
            $this->article = $element;
        } else if (get_class($element) == "AppBundle\Entity\User") {
            $this->user = $element;
        }
        $this->dateUpdate = $this->dateCreate = new \DateTime('now');
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
     * @param \DateTime $dateCreate
     *
     * @return Element
     */
    public function setDateCreate($dateCreate)
    {
        $this->dateCreate = $dateCreate;

        return $this;
    }

    /**
     * Get dateCreate
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Set dateUpdate
     *
     * @param \DateTime $dateUpdate
     *
     * @return Element
     */
    public function setDateUpdate($dateUpdate)
    {
        $this->dateUpdate = $dateUpdate;

        return $this;
    }

    /**
     * Get dateUpdate
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Element
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
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return Element
     */
    public function setArticle(\AppBundle\Entity\Article $article = null)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }
}
