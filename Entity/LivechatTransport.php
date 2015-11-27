<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

use Oro\Bundle\IntegrationBundle\Entity\Transport;

/**
 * @ORM\Entity
 */
class LivechatTransport extends Transport
{
    /**
     * @var string
     *
     * @ORM\Column(name="api_user", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    protected $apiUser;

    /**
     * @var string
     *
     * @ORM\Column(name="api_key", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    protected $apiKey;

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiUser
     */
    public function setApiUser($apiUser)
    {
        $this->apiUser = $apiUser;
    }

    /**
     * @return string
     */
    public function getApiUser()
    {
        return $this->apiUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsBag()
    {
        $parameterBag = new ParameterBag(
            [
                'api_user' => $this->apiUser,
                'api_key'  => $this->apiKey
            ]
        );

        return $parameterBag;
    }
}