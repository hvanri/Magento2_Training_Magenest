<?php
namespace Magenest\Course\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Product;

class AddCourseMaterialsAttribute implements DataPatchInterface
{
    private $eavSetupFactory;
    private $moduleDataSetup;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            'course_materials',
            [
                'type' => 'text', // lưu JSON
                'label' => 'Course Materials',
                'input' => 'text', // sẽ override bằng UI Component
                'required' => false,
                'visible' => true,
                'user_defined' => true,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
                'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend::class, // hỗ trợ serialize
            ]
        );

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies() { return []; }
    public function getAliases() { return []; }
}
