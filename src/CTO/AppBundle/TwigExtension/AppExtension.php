<?php

namespace CTO\AppBundle\TwigExtension;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('monthTranslate', array($this, 'monthTranslateFilter')),
        );
    }

    public function monthTranslateFilter($date)
    {
        $tmpDate = explode(',', $date);

        $month['January'] = "Січень";
        $month['February'] = "Лютий";
        $month['March'] = "Березень";
        $month['April'] = "Квітень";
        $month['May'] = "Травень";
        $month['June'] = "Червень";
        $month['July'] = "Липень";
        $month['August'] = "Серпень";
        $month['September'] = "Вересень";
        $month['October'] = "Жовтень";
        $month['November'] = "Листопад";
        $month['December'] = "Грудень";

        return $month[$tmpDate[0]]. ', ' . $tmpDate[1];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'app_extension';
    }
}
