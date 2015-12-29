<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LivechatIntegrationController extends Controller
{
    /**
     * @Route("/livechat-integration", name="integration")
     */
    public function indexAction(Request $request)
    {
        $website = $this->get('request')->getSchemeAndHttpHost();

        $contentData = '';
        $content = $request->getContent();

        if (!empty($content))
        {
            $contentData = json_decode($content);
        } else {
            $return = 'nothing sent';
        }

        if ($contentData->event_type === 'chat_started') {
            /**
             * Todo: Get customer information based on email
             */
                #   if (isset($contentData->visitor->email)){
                #       $email = $contentData->visitor->email;
                #   } else {
                #       $email = '';
                #   }

                $fields = array();
                $visitor = $contentData->visitor;

                $package = base64_encode(
                    json_encode([
                        'chat_id'       => $contentData->chat->id,
                        'visitor_id'    => $contentData->visitor->id,
                        'visitor_name'  => $contentData->visitor->name,
                        'visitor_email' => $contentData->visitor->email
                    ])
                );

                $fields[] = (object)array(
                    'name' => 'Create OroCRM Lead',
                    'value' => $website. "/livechat/create-lead/" .$package
                );

                $curlFields = http_build_query(array(
                    'license_id'  => $contentData->license_id,
                    'token'       => $contentData->token,
                    'id'          => 'orocrm-integration',
                    'icon'        => 'raw.githubusercontent.com/DemacMedia/OroLivechatIntegrationBundle/master/Resources/public/img/oro_64.png',
                    'fields'      => $fields
                ));

            $this->responseToLivechatInc($contentData->visitor->id, $curlFields);
        }
        $return = 'ok';

        return new Response($return);
    }

    protected function responseToLivechatInc($visitorId, $curlFields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.livechatinc.com/visitors/' .$visitorId. '/details');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlFields);
        //disable sssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-API-Version: 2'));
        $result = curl_exec($ch);
        curl_close($ch);
    }
}
