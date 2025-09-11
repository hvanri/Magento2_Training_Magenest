<?php
namespace Magenest\Course\Ui\DataProvider\Product\Form\Modifier;
use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeSetCollection;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\DynamicRows;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class DynamicRowAttribute implements ModifierInterface
{
    public const DATA_SOURCE_DEFAULT = 'product';

    public const PRODUCT_ATTRIBUTE_OLD_CODE = 'course_materials';
    public const PRODUCT_ATTRIBUTE_FILE_CODE = 'course_file_materials';
    public const PRODUCT_ATTRIBUTE_TEXT_CODE = 'course_text_materials';

    /**
     * @param LocatorInterface $locator
     * @param AttributeSetCollection $attributeSetCollection
     * @param SerializerInterface $serializer
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        private LocatorInterface $locator,
        protected AttributeSetCollection $attributeSetCollection,
        protected SerializerInterface $serializer,
        protected ArrayManager $arrayManager,
    ) {
    }

    /**
     * Modify Data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        $model = $this->locator->getProduct();
        $modelId = $model->getId();

        $attributes = [
            //self::PRODUCT_ATTRIBUTE_OLD_CODE,
            self::PRODUCT_ATTRIBUTE_FILE_CODE,
            self::PRODUCT_ATTRIBUTE_TEXT_CODE
        ];

        foreach ($attributes as $attributeCode) {
            $attrData = $model->getData($attributeCode);

            if ($attrData) {
                // Chỉ unserialize nếu là string
                if (is_string($attrData)) {
                    try {
                        $attrData = $this->serializer->unserialize($attrData);
                    } catch (\Exception $e) {
                        // Nếu lỗi unserialize thì giữ nguyên dữ liệu
                    }
                }

                $path = $modelId . '/' . self::DATA_SOURCE_DEFAULT . '/' . $attributeCode;
                $data = $this->arrayManager->set($path, $data, $attrData);
            }
        }

        return $data;
    }

    /**
     * Modify Meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        //$meta = $this->mergeDynamicRowMeta($meta, self::PRODUCT_ATTRIBUTE_OLD_CODE, __('Course Materials'), ['title', 'file']);
        $meta = $this->mergeDynamicRowMeta($meta, self::PRODUCT_ATTRIBUTE_FILE_CODE, __('Course File Materials'), ['title', 'file']);
        $meta = $this->mergeDynamicRowMeta($meta, self::PRODUCT_ATTRIBUTE_TEXT_CODE, __('Course Text Materials'), ['title', 'content']);

        return $meta;
    }

    /**
     * Merge dynamic row meta into $meta
     *
     * @param array $meta
     * @param string $attributeCode
     * @param string $label
     * @param array $fields
     * @return array
     */
    protected function mergeDynamicRowMeta(array $meta, string $attributeCode, string $label, array $fields)
    {
        $highlightsPath = $this->arrayManager->findPath(
            $attributeCode,
            $meta,
            null,
            'children'
        );

        if ($highlightsPath) {
            $meta = $this->arrayManager->merge(
                $highlightsPath,
                $meta,
                $this->initDynamicRowsField($meta, $highlightsPath, $label, $attributeCode, $fields)
            );
            $meta = $this->arrayManager->set(
                $this->arrayManager->slicePath($highlightsPath, 0, -3)
                . '/' . $attributeCode,
                $meta,
                $this->arrayManager->get($highlightsPath, $meta)
            );
            $meta = $this->arrayManager->remove(
                $this->arrayManager->slicePath($highlightsPath, 0, -2),
                $meta
            );
        }

        return $meta;
    }

    /**
     * Init dynamic rows structure
     *
     * @param array $meta
     * @param string $highlightsPath
     * @param string $label
     * @param string $dataScope
     * @param array $fields
     * @return array
     */
    protected function initDynamicRowsField(array $meta, string $highlightsPath, string $label, string $dataScope, array $fields)
    {
        $children = [];
        $sortOrder = 10;

        foreach ($fields as $field) {
            $children[$field] = [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'formElement' => $field === 'file' ? 'fileUploader' : Input::NAME,
                            'componentType' => Field::NAME,
                            'dataType' => Text::NAME,
                            'label' => __(ucfirst($field)),
                            'dataScope' => $field,
                            'sortOrder' => $sortOrder++,
                            'uploaderConfig' => $field === 'file' ? ['url' => 'magenest_course/material/upload'] : null,
                        ],
                    ],
                ],
            ];
        }

        $children['actionDelete'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'actionDelete',
                        'dataType' => Text::NAME,
                        'label' => '',
                        'sortOrder' => $sortOrder,
                    ],
                ],
            ],
        ];

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => DynamicRows::NAME,
                        'label' => $label,
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => $dataScope,
                        'dndConfig' => ['enabled' => false],
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'isTemplate' => true,
                                'is_collection' => true,
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => $children,
                ],
            ],
        ];
    }
}
