<?php
namespace GithubRepoBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="githubrepo")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */

class Githubrepo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="integer", unique=true)
     */
    public $githubId;

    /**
     * @ORM\Column(type="string", length=250, unique=false)
     * @Assert\NotBlank()
     */
    public $fullName;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $description;

    /**
     * @ORM\Column(type="integer", unique=false)
     */
    public $stargazersCount;

    /**
     * @ORM\Column(type="integer", unique=false)
     */
    private $page;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    public $url;

    /**
    * @ORM\Column(type="datetime")
    */
    public $dateCreated;

    /**
    * @ORM\Column(type="datetime")
    */
    public $lastPush;

    /**
    * @ORM\Column(type="datetime")
    */
    public $dateStored;

    public function __construct()
    {
    }

    /**
     * @ORM\PrePersist
     */
    public function prePresistValues()
    {
        $this->setDateStored(new \DateTime());
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdateValues()
    {
        $this->setDateStored(new \DateTime());
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set githubId
     *
     * @param integer $githubId
     *
     * @return Githubrepo
     */
    public function setGithubId($githubId)
    {
        $this->githubId = $githubId;

        return $this;
    }

    /**
     * Get githubId
     *
     * @return integer
     */
    public function getGithubId()
    {
        return $this->githubId;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return Githubrepo
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Githubrepo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set stargazersCount
     *
     * @param integer $stargazersCount
     *
     * @return Githubrepo
     */
    public function setStargazersCount($stargazersCount)
    {
        $this->stargazersCount = $stargazersCount;

        return $this;
    }

    /**
     * Get stargazersCount
     *
     * @return integer
     */
    public function getStargazersCount()
    {
        return $this->stargazersCount;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Githubrepo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Githubrepo
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set lastPush
     *
     * @param \DateTime $lastPush
     *
     * @return Githubrepo
     */
    public function setLastPush($lastPush)
    {
        $this->lastPush = $lastPush;

        return $this;
    }

    /**
     * Get lastPush
     *
     * @return \DateTime
     */
    public function getLastPush()
    {
        return $this->lastPush;
    }

    /**
     * Set dateStored
     *
     * @param \DateTime $dateStored
     *
     * @return Githubrepo
     */
    public function setDateStored($dateStored)
    {
        $this->dateStored = $dateStored;

        return $this;
    }

    /**
     * Get dateStored
     *
     * @return \DateTime
     */
    public function getDateStored()
    {
        return $this->dateStored;
    }

    /**
     * Set page
     *
     * @param integer $page
     *
     * @return Githubrepo
     */
    public function setPage($page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return integer
     */
    public function getPage()
    {
        return $this->page;
    }
}
