<?php
namespace Magenest\Movie\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class RatingStars extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if(isset($item['rating'])) {
                    $rating = (int)$item['rating'];
                    $maxStars = 10;

                    // HTML hiển thị sao
                    $starsHtml = '<div class="rating-stars">';
                    for ($i = 1; $i <= $maxStars; $i++) {
                        if ($i <= $rating) {
                            $starsHtml .= '<span class="star filled">&#9733;</span>'; // ★ filled
                        } else {
                            $starsHtml .= '<span class="star">&#9734;</span>'; // ☆ empty
                        }
                    }
                    $starsHtml .= '</div>';

                    $item['rating'] = $starsHtml;
                }
            }
        }
        return $dataSource;
    }
}
