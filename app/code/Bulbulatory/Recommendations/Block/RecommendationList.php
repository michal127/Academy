<?php


namespace Bulbulatory\Recommendations\Block;


use Bulbulatory\Recommendations\Helper\Recommendation as RecommendationHelper;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\Collection;
use Bulbulatory\Recommendations\Model\ResourceModel\Recommendation\CollectionFactory;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;

/**
 * Class Index
 * @package Bulbulatory\Recommendations\Block
 */
class RecommendationList extends Template
{
    const DEFAULT_PAGE = 1;
    const DEFAULT_PAGE_LIMIT = 5;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var RecommendationHelper
     */
    private $recommendationHelper;
    /**
     * @var Customer
     */
    private $customer;
    /**
     * @var int
     */
    private $customerConfirmedRecommendations;

    /**
     * RecommendationList constructor.
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param Session $customerSession
     * @param RecommendationHelper $recommendationHelper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        Session $customerSession,
        RecommendationHelper $recommendationHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->customer = $customerSession->getCustomer();
        $this->recommendationHelper = $recommendationHelper;
        $this->customerConfirmedRecommendations = $this->recommendationHelper->getConfirmedRecommendationsCountForCustomer($this->customer);
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return $this|RecommendationList
     * @throws LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        /* @var $pager Pager */
        $pager = $this->getLayout()->createBlock(Pager::class, 'cust.recommendations.pager');
        $pager->setAvailableLimit([5 => 5, 10 => 10, 15 => 15, 20 => 20]);
        $pager->setShowPerPage(true);
        $pager->setCollection($this->getRecommendationsCollection());
        $this->setChild('pager', $pager);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getRecommendationsCollection()
    {
        $request = $this->getRequest();
        $page = ($request->getParam('p')) ? $request->getParam('p') : self::DEFAULT_PAGE;
        $pageSize = ($request->getParam('limit')) ? $request->getParam('limit') : self::DEFAULT_PAGE_LIMIT;
        $collection = $this->collectionFactory->create();
        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);
        $collection->addFieldToFilter('customer_id', [(int)$this->customer->getId()]);

        return $collection;
    }

    /**
     * @return int
     */
    public function getConfirmedRecommendationsCount()
    {
        return $this->customerConfirmedRecommendations;
    }

    /**
     * @return string
     */
    public function getRecommendationDiscount()
    {
        return $this->recommendationHelper->getDiscountValueForCustomer($this->customerConfirmedRecommendations) . '%';
    }

    /**
     * @param int $statusID
     * @return Phrase|string
     */
    public function getRecommendationStatus(int $statusID)
    {
        return $this->recommendationHelper->getRecommendationStatusByID($statusID);
    }
}
