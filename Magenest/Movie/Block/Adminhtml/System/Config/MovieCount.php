<?php
namespace Magenest\Movie\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\App\ResourceConnection;

class MovieCount extends Field
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

    /**
     * Render element html
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $connection = $this->resourceConnection->getConnection();
        $table = $this->resourceConnection->getTableName('magenest_movie');

        try {
            $count = (int) $connection->fetchOne("SELECT COUNT(*) FROM `{$table}`");
        } catch (\Exception $e) {
            $count = 0;
        }

        // show read-only number and store value as hidden input so path has a value (optional)
        $html = '<span>' . (int)$count . '</span>';
        $html .= '<input type="hidden" name="' . $element->getName() . '" value="' . (int)$count . '" />';
        return $html;
    }
}
