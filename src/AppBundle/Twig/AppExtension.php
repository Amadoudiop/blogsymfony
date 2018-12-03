<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('class', array($this, 'className')),
 //           new TwigFilter('sortByDate', array($this, 'sortByDate')),
        );
    }

    public function className($class)
    {
        return str_replace("AppBundle\Entity\\",'',get_class($class));
    }

  /* public function sortByDate($elements)
    {
        dump( $elements);
        usort($elements, 'date_compare');
        dump( $elements);
        die;
        return $class;
    }

    public function date_compare($a, $b)
    {

        $t1 = strtotime($a['datetime']);
        $t2 = strtotime($b['datetime']);
        return $t1 - $t2;
    }

*/

}