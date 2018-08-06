<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

/**
 * Class AppExtension
 * @package App\Twig
 */
class AppExtension extends AbstractExtension implements GlobalsInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * AppExtension constructor.
     * @param string $locale
     */
    public function __construct(string $locale)
    {

        $this->locale = $locale;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
            return [new TwigFilter('price', [
                $this, 'priceFilter'
            ])];
    }

    /**
     * @return array
     */
    public function getGlobals(): array
    {
        return [
            'locale' => $this->locale
        ];
    }

    /**
     * @param $number
     * @return string
     */
    public function priceFilter($number): string
    {
        return '$' . number_format($number, 2, '.', ',');
    }
}
