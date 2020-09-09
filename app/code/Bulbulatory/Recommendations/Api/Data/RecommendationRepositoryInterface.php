<?php


namespace Bulbulatory\Recommendations\Api\Data;

/**
 * Interface RecommendationRepositoryInterface
 */
interface RecommendationRepositoryInterface
{
    /**
     * @param int[] $ids
     * @return mixed
     */
    public function deleteByIds(array $ids);
}
