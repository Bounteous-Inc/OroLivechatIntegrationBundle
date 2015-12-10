<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Serializer;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Oro\Bundle\ImportExportBundle\Field\FieldHelper;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\ConfigurableEntityNormalizer;

use DemacMedia\Bundle\OroLivechatIntegrationBundle\Entity\Chat;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ChatNormalizer extends ConfigurableEntityNormalizer
{
    /** @var RegistryInterface */
    protected $registry;
    
    /**
     * @param FieldHelper       $fieldHelper
     * @param RegistryInterface $registry
     */
    public function __construct(FieldHelper $fieldHelper, RegistryInterface $registry)
    {
        parent::__construct($fieldHelper);
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = array())
    {
        return $data instanceof Chat;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = array())
    {
        return $type == 'DemacMedia\\Bundle\\OroLivechatIntegrationBundle\\Entity\\Chat';
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        /** @var Chat $customer */
        $chat = parent::denormalize($data, $class, $format, $context);

        $integration = $this->getIntegrationFromContext($context);
        $chat->setChannel($integration);
        $chat->setOrganization($integration->getOrganization());
        $chat->setOwner($integration->getDefaultUserOwner());

        return $chat;
    }

    /**
     * @param array $context
     *
     * @return Integration
     * @throws \LogicException
     */
    public function getIntegrationFromContext(array $context)
    {
        if (!isset($context['channel'])) {
            throw new \LogicException('Context should contain reference to channel');
        }

        $return = $this->registry
            ->getRepository('OroIntegrationBundle:Channel')
            ->getOrLoadById($context['channel']);

        return $return;
    }
}
