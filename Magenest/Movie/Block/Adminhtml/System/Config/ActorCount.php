<?php
namespace Magenest\Movie\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\App\ResourceConnection;

class ActorCount extends Field
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ResourceConnection $resourceConnection,
        array $data = []
    ) {
        $this->resourceConnection = $resourceConnection;
        parent::__construct($context, $data);
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName('magenest_actor');

        try {
            $count = (int) $connection->fetchOne("SELECT COUNT(*) FROM `{$table}`");
        } catch (\Exception $e) {
            $count = 0;
        }

        $html = '<span>' . (int)$count . '</span>';
        $html .= '<input type="hidden" name="' . $element->getName() . '" value="' . (int)$count . '" />';
        return $html;
    }
}
