<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LiveChat\Api\Client as LiveChat;
use Symfony\Component\Security\Acl\Exception\Exception;


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
        return $this->render('DemacMediaOroLivechatIntegrationBundle:LivechatInc:view.html.twig');
    }


    protected function liveChatLogin($user, $key)
    {
        try {
            $this->liveChat = new LiveChat($user, $key);
            return $this;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
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
        try {
            $wufooConfig = $this
                ->getDoctrine()
                ->getRepository('DemacMediaOroLivechatIntegrationBundle:WufooCredentials')
                ->findAll();

            if ($wufooConfig) {
                return $wufooConfig;
            }
        } catch( \Exception $e) {
            return $e->getMessage();
        }
    }
}
