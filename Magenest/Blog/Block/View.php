<?php
namespace Magenest\Blog\Block;

use Magento\Framework\View\Element\Template;

class View extends Template
{
    protected $blog;

    public function setBlog($blog)
    {
        $this->blog = $blog;
        return $this;
    }

    public function getBlog()
    {
        return $this->blog;
    }
}
