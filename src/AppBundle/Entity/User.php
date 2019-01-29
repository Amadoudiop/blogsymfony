<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * user
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * one user as one element
     *
     * @ORM\OneToOne(targetEntity="Element", mappedBy ="user", fetch="LAZY")
     */
    private $element;

    /**
     * @Assert\Regex(
     *     pattern="/@efrei.net$|@efrei.fr$|@esigetel.net$|@efreitech.net$/i",
     *     message="l'email n'est pas valide"
     * )
     */
    protected $email;
    /**
     * @var int
     *
     * @ORM\Column(name="promotion", type="integer")
     */
    private $promotion;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=100)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100)
     *
     * @Assert\NotBlank(message="entrer un nom.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=1,
     *     max=255,
     *     minMessage="Le nom est trop court.",
     *     maxMessage="Le nom est trop long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    private $token;
//     * @ORM\OneToMany(targetEntity="Article", mappedBy="user", cascade={"detach"}git, fetch="LAZY")
    /**
     * One User has Many Article
     *

     * @ORM\OneToMany(targetEntity="Article", mappedBy="user", cascade="all")
     */
    private $articles;

    /**
     * @var bool
     *
     * @ORM\Column(name="validation", type="boolean")
     */
    private $validation = false;


    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Set validation
     *
     * @param boolean $enabled
     *
     * @return Article
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get Validation
     *
     * @return bool
     */
    public function getValidation()
    {
        return $this->validation;
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
     * Set promotion
     *
     * @param integer $promotion
     *
     * @return user
     */
    public function setPromotion($promotion)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion
     *
     * @return int
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return user
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return user
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return user
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return User
     */
    public function addArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \AppBundle\Entity\Article $article
     */
    public function removeArticle(\AppBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set element
     *
     * @param \AppBundle\Entity\Element $element
     *
     * @return User
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
    public function userPersist()
    {
        $element = new Element($this);
        $this->setElement($element);
    }
    // créer les méthode pour récupérer le date creat et update
}
