<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;
use Oro\Bundle\BusinessEntitiesBundle\Entity\BasePerson;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\ConfigField;
use Oro\Bundle\IntegrationBundle\Model\IntegrationEntityTrait;

use OroCRM\Bundle\AccountBundle\Entity\Account;
use OroCRM\Bundle\ContactBundle\Entity\Contact;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="demacmedia_livechat_chat"
 * )
 * @ORM\HasLifecycleCallbacks()
 * @Config(
 *      defaultValues={
 *          "entity"={
 *              "icon"="icon-chat"
 *          }
 *      }
 * )
 */
class Chat
{
    use IntegrationEntityTrait;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    protected $remoteId;

    /**
     * @var Contact
     *
     * @ORM\ManyToOne(targetEntity="OroCRM\Bundle\ContactBundle\Entity\Contact", cascade="PERSIST")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $contact;


    /**
     * @var Account
     *
     * @ORM\ManyToOne(targetEntity="OroCRM\Bundle\AccountBundle\Entity\Account", cascade="PERSIST")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $account;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_type", type="string", length=32, nullable=true)
     */
    protected $chatType;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_id", type="string", length=32, nullable=false)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat ID",
     *              "plural_label"="Chats ID",
     *              "description"="Chat ID"
     *          }
     *      }
     * )
     */
    protected $chatId;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_name", type="string", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor Name",
     *              "plural_label"="Visitors Name",
     *              "description"="Visitor Name"
     *          }
     *      }
     * )
     */
    protected $chatVisitorName;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_id", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor ID",
     *              "plural_label"="Visitors ID",
     *              "description"="Visitor ID"
     *          }
     *      }
     * )
     */
    protected $chatVisitorId;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_ip", type="string", length=32, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor IP",
     *              "plural_label"="Visitors IP",
     *              "description"="Visitor IP"
     *          }
     *      }
     * )
     */
    protected $chatVisitorIp;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_email", type="string", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor Email",
     *              "plural_label"="Visitors Email",
     *              "description"="Visitor Email"
     *          }
     *      }
     * )
     */
    protected $chatVisitorEmail;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_city", type="string", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor City",
     *              "plural_label"="Visitors City",
     *              "description"="Visitor City"
     *          }
     *      }
     * )
     */
    protected $chatVisitorCity;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_region", type="string", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor Region",
     *              "plural_label"="Visitors Region",
     *              "description"="Visitor Region"
     *          }
     *      }
     * )
     */
    protected $chatVisitorRegion;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_country", type="string", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor Country",
     *              "plural_label"="Visitors Country",
     *              "description"="Visitor Country"
     *          }
     *      }
     * )
     */
    protected $chatVisitorCountry;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_country_code", type="string", length=4, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor Country Code",
     *              "plural_label"="Visitors Country Code",
     *              "description"="Visitor Country Code"
     *          }
     *      }
     * )
     */
    protected $chatVisitorCountryCode;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_visitor_timezone", type="string", length=64, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Visitor Timezone",
     *              "plural_label"="Visitors Timezone",
     *              "description"="Visitor Timezone"
     *          }
     *      }
     * )
     */
    protected $chatVisitorTimezone;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_agent_name", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Agent Name",
     *              "plural_label"="Agents Name",
     *              "description"="Agent Name"
     *          }
     *      }
     * )
     */
    protected $chatAgentName;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_agent_email", type="string", length=255, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Agent Email",
     *              "plural_label"="Agents Email",
     *              "description"="Agent Email"
     *          }
     *      }
     * )
     */
    protected $chatAgentEmail;


    /**
     * @var integer
     *
     * @ORM\Column(name="chat_duration", type="integer", options={"unsigned"=true}, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat Duration",
     *              "plural_label"="Chat Duration",
     *              "description"="Chat Duration"
     *          }
     *      }
     * )
     */
    protected $chatDuration;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chat_started", type="datetime", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat Started",
     *              "plural_label"="Chats Started",
     *              "description"="Chat Started"
     *          }
     *      }
     * )
     */
    protected $chatStarted;

    /**
     * @var integer
     *
     * @ORM\Column(name="chat_started_timestamp", type="integer", options={"unsigned"=true}, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat Started Timestamp",
     *              "plural_label"="Chats Started Timestamp",
     *              "description"="Chat Started Timestamp"
     *          }
     *      }
     * )
     */
    protected $chatStartedTimestamp;


    /**
     * @var integer
     *
     * @ORM\Column(name="chat_ended_timestamp", type="integer", options={"unsigned"=true}, nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat Ended Timestamp",
     *              "plural_label"="Chat Ended Timestamp",
     *              "description"="Chat Ended Timestamp"
     *          }
     *      }
     * )
     */
    protected $chatEndedTimestamp;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="chat_ended", type="datetime", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat Ended",
     *              "plural_label"="Chats Ended",
     *              "description"="Chat Ended"
     *          }
     *      }
     * )
     */
    protected $chatEnded;


    /**
     * @var string
     *
     * @ORM\Column(name="chat_start_url", type="string", nullable=true)
     * @ConfigField(
     *      defaultValues={
     *          "entity"={
     *              "label"="Chat Start URL",
     *              "plural_label"="Chats Start URL",
     *              "description"="Chat Start URL"
     *          }
     *      }
     * )
     */
    protected $chatStartUrl;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    protected $createdAt;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    protected $updatedAt;




    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    /**
     * @param Contact $contact
     *
     * @return Chat
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param Account $account
     *
     * @return Chat
     */
    public function setAccount($account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getChatType()
    {
        return $this->chatType;
    }

    /**
     * @param string $chatType
     * @return Chat
     */
    public function setChatType($chatType)
    {
        $this->chatType = $chatType;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatId()
    {
        return $this->chatId;
    }

    /**
     * @param string $chatId
     * @return Chat
     */
    public function setChatId($chatId)
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorName()
    {
        return $this->chatVisitorName;
    }

    /**
     * @param string $chatVisitorName
     * @return Chat
     */
    public function setChatVisitorName($chatVisitorName)
    {
        $this->chatVisitorName = $chatVisitorName;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorId()
    {
        return $this->chatVisitorId;
    }

    /**
     * @param string $chatVisitorId
     * @return Chat
     */
    public function setChatVisitorId($chatVisitorId)
    {
        $this->chatVisitorId = $chatVisitorId;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorIp()
    {
        return $this->chatVisitorIp;
    }

    /**
     * @param string $chatVisitorIp
     * @return Chat
     */
    public function setChatVisitorIp($chatVisitorIp)
    {
        $this->chatVisitorIp = $chatVisitorIp;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorEmail()
    {
        return $this->chatVisitorEmail;
    }

    /**
     * @param string $chatVisitorEmail
     * @return Chat
     */
    public function setChatVisitorEmail($chatVisitorEmail)
    {
        $this->chatVisitorEmail = $chatVisitorEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorCity()
    {
        return $this->chatVisitorCity;
    }

    /**
     * @param string $chatVisitorCity
     * @return Chat
     */
    public function setChatVisitorCity($chatVisitorCity)
    {
        $this->chatVisitorCity = $chatVisitorCity;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorCountry()
    {
        return $this->chatVisitorCountry;
    }

    /**
     * @param string $chatVisitorCountry
     * @return Chat
     */
    public function setChatVisitorCountry($chatVisitorCountry)
    {
        $this->chatVisitorCountry = $chatVisitorCountry;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorCountryCode()
    {
        return $this->chatVisitorCountryCode;
    }

    /**
     * @param string $chatVisitorCountryCode
     * @return Chat
     */
    public function setChatVisitorCountryCode($chatVisitorCountryCode)
    {
        $this->chatVisitorCountryCode = $chatVisitorCountryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatVisitorTimezone()
    {
        return $this->chatVisitorTimezone;
    }

    /**
     * @param string $chatVisitorTimezone
     * @return Chat
     */
    public function setChatVisitorTimezone($chatVisitorTimezone)
    {
        $this->chatVisitorTimezone = $chatVisitorTimezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatAgentName()
    {
        return $this->chatAgentName;
    }

    /**
     * @param string $chatAgentName
     * @return Chat
     */
    public function setChatAgentName($chatAgentName)
    {
        $this->chatAgentName = $chatAgentName;
        return $this;
    }

    /**
     * @return string
     */
    public function getChatAgentEmail()
    {
        return $this->chatAgentEmail;
    }

    /**
     * @param string $chatAgentEmail
     * @return Chat
     */
    public function setChatAgentEmail($chatAgentEmail)
    {
        $this->chatAgentEmail = $chatAgentEmail;
        return $this;
    }

    /**
     * @return int
     */
    public function getChatDuration()
    {
        return $this->chatDuration;
    }

    /**
     * @param int $chatDuration
     * @return Chat
     */
    public function setChatDuration($chatDuration)
    {
        $this->chatDuration = $chatDuration;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChatStarted()
    {
        return $this->chatStarted;
    }

    /**
     * @param mixed $chatStarted
     * @return Chat
     */
    public function setChatStarted($chatStarted)
    {
        $this->chatStarted = $chatStarted;
        return $this;
    }

    /**
     * @return int
     */
    public function getChatStartedTimestamp()
    {
        return $this->chatStartedTimestamp;
    }

    /**
     * @param int $chatStartedTimestamp
     * @return Chat
     */
    public function setChatStartedTimestamp($chatStartedTimestamp)
    {
        $this->chatStartedTimestamp = $chatStartedTimestamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getChatEndedTimestamp()
    {
        return $this->chatEndedTimestamp;
    }

    /**
     * @param int $chatEndedTimestamp
     * @return Chat
     */
    public function setChatEndedTimestamp($chatEndedTimestamp)
    {
        $this->chatEndedTimestamp = $chatEndedTimestamp;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChatEnded()
    {
        return $this->chatEnded;
    }

    /**
     * @param \DateTime $chatEnded
     * @return Chat
     */
    public function setChatEnded($chatEnded)
    {
        $this->chatEnded = $chatEnded;
        return $this;
    }


    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->createdAt = $this->createdAt ? : new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = clone $this->createdAt;
    }
    /**
     * @ORM\PreUpdate
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getTitle();
    }

    /**
     * @return mixed
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * @param mixed $remoteId
     */
    public function setRemoteId($remoteId)
    {
        $this->remoteId = $remoteId;
    }

    /**
     * @return string
     */
    public function getChatVisitorRegion()
    {
        return $this->chatVisitorRegion;
    }

    /**
     * @param string $chatVisitorRegion
     */
    public function setChatVisitorRegion($chatVisitorRegion)
    {
        $this->chatVisitorRegion = $chatVisitorRegion;
    }
}
