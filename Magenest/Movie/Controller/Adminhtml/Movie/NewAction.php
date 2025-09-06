<?php
namespace Magenest\Movie\Controller\Adminhtml\Movie;

use Magento\Backend\App\Action;

class NewAction extends Action
{
    const ADMIN_RESOURCE = 'Magenest_Movie::movies';

    public function execute()
    {
        $this->_forward('edit');
    }
}
