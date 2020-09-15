<?php


namespace Bulbulatory\Recommendations\Block;


use Bulbulatory\Recommendations\Controller\Recommendations\Add as AddAction;
use Magento\Framework\View\Element\Template;

/**
 * Class Index
 * @package Bulbulatory\Recommendations\Block
 */
class Index extends Template
{
    /**
     * @return string
     */
    public function getFormActionUrl()
    {
        return '/'.AddAction::ROUTE;
    }

    /**
     * @return string
     */
    public function getEmailInputName(){
        return AddAction::POST_PARAM_EMAIL;
    }
}
