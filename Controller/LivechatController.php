<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LiveChat\Api\Client as LiveChat;
use Symfony\Component\Security\Acl\Exception\Exception;
use Oro\Bundle\SecurityBundle\SecurityFacade;

class LivechatController extends Controller
{
    protected $liveChat;

    /**
     * @Route("/", name="demacmedia_livechat_chat_index")
     * @Template
     */
    public function indexAction(Request $request) {
        return $this->render('DemacMediaOroLivechatIntegrationBundle:LivechatInc:index.html.twig');
    }


    /**
     * @Route("/view/{chatId}", name="demacmedia_livechat_chat_view")
     * @Template
     */
    public function viewChatAction($chatId) {
        try {
          $config = $this->loadLivechatIncConfig();

          if (!$config['apiUser'] || !$config['apiKey']) {
              throw new InvalidConfigurationException(
                "LivechatInc REST transport
                  require 'api_key' and 'api_user' settings to be defined."
              );
          }

          $client = $this->liveChatLogin(
              $config['apiUser'],
              $config['apiKey']
          );

          $transcript = $this->loadChatTranscript($client, $chatId);

          return $this->render(
            'DemacMediaOroLivechatIntegrationBundle:LivechatInc:view.html.twig',
            ['transcript' => $transcript]
          );

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    protected function loadChatTranscript(LiveChat $client, $chatId) {
        try {
            return $client->chats->getSingleChat($chatId);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @Route("/create-lead/{chatId}", name="demacmedia_livechat_integration")
     * @Template
     */
    public function integrationAction($chatId) {

        $this->createsLead($parameterss);

        die('chatId: ' .$chatId);
    }


    /**
     * @return array
     */
    protected function createLead($parameters)
    {
        $request = [
            "lead" => [
                'name'          => 'lead_name_'  . mt_rand(1, 500),
                'firstName'     => 'first_name_' . mt_rand(1, 500),
                'lastName'      => 'last_name_'  . mt_rand(1, 500),
                'owner'         => '1',
                'dataChannel'   => self::$dataChannel->getId()
            ]
        ];
        $this->client->request(
            'POST',
            $this->getUrl('oro_api_post_lead'),
            $request
            );
        $result = $this->getJsonResponseContent($this->client->getResponse(), 201);
        $request['id'] = $result['id'];
        return $request;
    }


    protected function liveChatLogin($user, $key)
    {
        try {
            $this->liveChat = new LiveChat($user, $key);
            return $this->liveChat;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


     /**
     * Generate WSSE authorization header
     *
     * @param string      $userName
     * @param string      $userPassword
     * @param string|null $nonce
     *
     * @return array
     */
    protected static function generateWsseAuthHeader(
        $userName = self::USER_NAME,
        $userPassword = self::USER_PASSWORD,
        $nonce = null
    ) {
        if (null === $nonce) {
            $nonce = uniqid();
        }
        $created  = date('c');
        $digest   = base64_encode(sha1(base64_decode($nonce) . $created . $userPassword, true));
        $wsseHeader = [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_Authorization' => 'WSSE profile="UsernameToken"',
            'HTTP_X-WSSE' => sprintf(
                'UsernameToken Username="%s", PasswordDigest="%s", Nonce="%s", Created="%s"',
                $userName,
                $digest,
                $nonce,
                $created
            )
        ];
        return $wsseHeader;
    }


    protected function getTotalChats()
    {
        $totalChats = $this->liveChat->reports->get(
            'chats',
            ['total_chats']
        );

        return $totalChats;
    }


    protected function getChats()
    {
        $totalChats = $this->liveChat->reports->get(
            'chats',
            ['total_chats']
        );

        return $totalChats;
    }


    /**
     * @return array LivechatIncCredentials
     */
    protected function loadLivechatIncConfig() {
        $config = [
            'apiUser' => '',
            'apiKey'  => ''
        ];

        try {
            $channelEntity = $this
                ->getDoctrine()
                ->getRepository('OroIntegrationBundle:Channel')
                ->findOneBy(
                [
                  'type' => 'live_chat',
                  'organization' => $this->getSecurityFacade()->getOrganization()
                ],
                ['id' => 'DESC']
            );

            if ($channelEntity) {
                $integrationEntity = $this
                    ->getDoctrine()
                    ->getRepository('OroIntegrationBundle:Transport')
                    ->findOneBy([
                      'id' => $channelEntity->getTransport()
                    ]);

                $config['apiUser'] = $integrationEntity->getSettingsBag()->get('api_user');
                $config['apiKey']  = $integrationEntity->getSettingsBag()->get('api_key');
            }

            return $config;

        } catch( \Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @return SecurityFacade
     */
    protected function getSecurityFacade()
    {
        return $this->get('oro_security.security_facade');
    }
}
