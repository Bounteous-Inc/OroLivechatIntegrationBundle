<?php

namespace DemacMedia\Bundle\OroLivechatIntegrationBundle\ImportExport\Serializer;

use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\DateTimeNormalizer as BaseNormalizer;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\DenormalizerInterface;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\NormalizerInterface;
use Oro\Bundle\ImportExportBundle\Serializer\Serializer;

class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct()
    {
        $this->livechatNormalizer = new BaseNormalizer(\DateTime::ISO8601, 'Y-m-d', 'H:i:s', 'UTC');
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->livechatNormalizer->denormalize($data, $class, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $this->livechatNormalizer->normalize($object, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = array())
    {
        return $this->livechatNormalizer->supportsDenormalization($data, $type, $format, $context)
        && !empty($context[Serializer::PROCESSOR_ALIAS_KEY])
        && strpos($context[Serializer::PROCESSOR_ALIAS_KEY], 'livechat') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = array())
    {
        return $this->livechatNormalizer->supportsNormalization($data, $format, $context)
        && !empty($context[Serializer::PROCESSOR_ALIAS_KEY])
        && strpos($context[Serializer::PROCESSOR_ALIAS_KEY], 'livechat') !== false;
    }
}