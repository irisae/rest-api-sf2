<?php

namespace TestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\VirtualProperty;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="TestBundle\Entity\GameRepository")
 *
 */
class Game
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"list_games", "list_games_detail"})
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Groups({"list_games", "list_games_detail"})
     */
    private $name;

    /**
     * @var integer
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="nb_player", type="integer")
     *
     * @Groups({"list_games", "list_games_detail"})
     */
    private $nbPlayer;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "1",
     *      max = "255",
     *      minMessage = "The platform has to be at least {{ limit }} characters",
     *      maxMessage = "The platform can't be more than {{ limit }} characters"
     * )
     * @ORM\Column(name="platform", type="string", length=255)
     *
     * @Groups({"list_games_detail"})
     */
    private $platform;

    /**
     * @var \DateTime
     *
     * @Assert\DateTime()
     * @ORM\Column(name="date", type="datetime")
     *
     * @Groups({"list_games_detail"})
     */
    private $launchDate;

    /**
     * Set id
     *
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * Set name
     *
     * @param string $name
     *
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set nbPlayer
     *
     * @param integer $nbPlayer
     *
     * @return Game
     */
    public function setNbPlayer($nbPlayer)
    {
        $this->nbPlayer = $nbPlayer;

        return $this;
    }

    /**
     * Get nbPlayer
     *
     * @return integer
     */
    public function getNbPlayer()
    {
        return $this->nbPlayer;
    }

    /**
     * Set platform
     *
     * @param string $platform
     *
     * @return Game
     */
    public function setPlatform($platform)
    {
        $this->platform = $platform;

        return $this;
    }

    /**
     * Get platform
     *
     * @return string
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * Set launchDate
     *
     * @param \DateTime $launchDate
     *
     * @return Game
     */
    public function setLaunchDate(\DateTime $launchDate)
    {
        $this->launchDate = $launchDate;

        return $this;
    }

    /**
     * Get launchDate
     *
     * @return \DateTime
     */
    public function getLaunchDate()
    {
        return $this->launchDate;
    }
}

