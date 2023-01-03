<?php
namespace App\Helpers;

use function App\NepaliCalender\dateeng;
use function App\NepaliCalender\datenep;

class NepaliDate {

    protected string $date;

    protected array $months = ['Baisakh', 'Jestha', 'Aasar', 'Shrawan', 'Bhadra', 'Ashoj', 'Kartik', 'Mangshir', 'Poush', 'Magh', 'Falgun', 'Chaitra'];

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function getMonthName()
    {

        $engtoday = $this->date;
        $date_in_nepali = datenep($engtoday);
        $explodenepali = explode('-', $date_in_nepali);

        $nepdate = (int)$explodenepali[1] - 1;
        return $this->months[$nepdate];
    }
}
