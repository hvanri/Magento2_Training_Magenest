<?php

namespace Magenest\Banner\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class BannerImage extends Column
{
    protected $urlBuilder;

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name'); // upload_image
            foreach ($dataSource['data']['items'] as & $item) {
                if (!empty($item[$fieldName])) {
                    $item[$fieldName . '_src'] = $this->getMediaUrl($item[$fieldName]);
                    $item[$fieldName . '_orig_src'] = $this->getMediaUrl($item[$fieldName]);
                    $item[$fieldName . '_alt'] = $item['title'] ?? 'Banner Image';
                }
            }
        }
        return $dataSource;
    }

    protected function getMediaUrl($file)
    {
        return $this->urlBuilder->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $file;
    }
}
