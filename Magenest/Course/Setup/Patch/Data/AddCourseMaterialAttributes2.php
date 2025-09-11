<?php
namespace Magenest\Course\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Product;

class AddCourseMaterialAttributes2 implements DataPatchInterface
{
    private ModuleDataSetupInterface $moduleDataSetup;
    private EavSetupFactory $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ){
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        // Attribute lưu file materials
        $eavSetup->addAttribute(
            Product::ENTITY,
            'course_file_materials',
            [
                'type' => 'text', // lưu JSON
                'label' => 'Course File Materials',
                'input' => 'text', // override bằng UI Component
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Course Materials',
                'backend' => \Magenest\Course\Model\Attribute\Backend\JsonBackend::class, // hỗ trợ serialize/deserialize
            ]
        );

        // Attribute lưu text materials
        $eavSetup->addAttribute(
            Product::ENTITY,
            'course_text_materials',
            [
                'type' => 'text', // lưu JSON
                'label' => 'Course Text Materials',
                'input' => 'text', // override bằng UI Component
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'Course Materials',// hỗ trợ serialize/deserialize
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }
}
