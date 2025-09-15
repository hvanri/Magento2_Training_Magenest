<?php
namespace Magenest\Banner\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;

class NewAction extends Action
{
    const ADMIN_RESOURCE = 'Magenest_Banner::banner';

    public function execute()
    {
        $this->_forward('edit');
    }
}
