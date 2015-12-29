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
use OroCRM\Bundle\SalesBundle\Entity\Lead;

class LivechatController extends Controller
{
    protected $liveChat;

    /**
     * @Route("/", name="demacmedia_livechat_chat_index")
     * @Template
     */
    public function indexAction(Request $request)
    {
        return $this->render('DemacMediaOroLivechatIntegrationBundle:LivechatInc:index.html.twig');
    }


    /**
     * @Route("/view/{chatId}", name="demacmedia_livechat_chat_view")
     * @Template
     */
    public function viewChatAction($chatId)
    {
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


    protected function loadChatTranscript(LiveChat $client, $chatId)
    {
        try {
            return $client->chats->getSingleChat($chatId);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @Route("/create-lead/{package}", name="demacmedia_livechat_integration")
     * @Template
     */
    public function integrationAction($package)
    {
        try {
            $package = (array) json_decode(
                base64_decode($package)
            );

            if (
                !isset($package['chat_id']) ||
                !isset($package['visitor_id']) ||
                !isset($package['visitor_name']) ||
                !isset($package['visitor_email'])
            ) {
                throw new Exception('Bad package data format.');
            }

            $fullName = explode(' ', $package['visitor_name']);
            if (sizeof($fullName) > 1){
                $firstName = $fullName[0];
                $lastName = end($fullName);
            } else {
                $firstName = $package['visitor_name'];
                $lastName = '';
            }

            $leadName  = $package['visitor_name'];
            $leadEmail = $package['visitor_email'];

            $lead = new Lead();
            $lead->setName($leadName);
            $lead->setFirstName($firstName);
            $lead->setLastName($lastName);
            $lead->setEmail($leadName);
            $lead->setSource('livechatinc');

            $response = $this->forward('OroCRMSalesBundle:Lead:update', [
                'entity' => $lead
            ]);

            return $response;

        } catch( \Exception $e) {
            return $e->getMessage();
        }
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
     * @return totalChats
     */
    protected function getTotalChats()
    {
        $totalChats = $this->liveChat->reports->get(
            'chats',
            ['total_chats']
        );
        return $totalChats;
    }

    /**
     * @return totalChats
     */
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
    protected function loadLivechatIncConfig()
    {
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
