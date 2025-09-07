<?php
namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class OddEven extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $config = [
            0 => [
                'text' => 'SUCCESS',
                'style' => 'background:#d0e5a9;border:1px solid #5b8116;color:#185b00;display:block;font-weight:bold;line-height:17px;padding:0 3px;text-align:center;text-transform:uppercase;'
            ],
            1 => [
                'text' => 'ERROR',
                'style' => 'background:#f9d4d4;border:1px solid #e22626;color:#e22626;display:block;font-weight:bold;line-height:17px;padding:0 3px;text-align:center;text-transform:uppercase;'
            ]
        ];

        foreach ($dataSource['data']['items'] as &$item) {
            $key = $item['entity_id'] % 2;
            $item[$this->getData('name')] = '<span style="' . $config[$key]['style'] . '">' . $config[$key]['text'] . '</span>';
        }

        return $dataSource;
    }
}
