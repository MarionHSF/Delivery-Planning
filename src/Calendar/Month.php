<?php
namespace Calendar;

use Translation\Translation;

class Month {

    private $days;
    private $months;
    public $month;
    public $year;

    /**
     * @param int $month month between 1 and 12
     * @param int $year
     */
    public function __construct(?int $month = null, ?int $year = null)
    {
        if ($month === null || $month < 1 || $month > 12) {
            $month = intval(date('m'));
        }
        if ($year === null) {
            $year = intval(date('Y'));
        }
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Returns the first day of the month
     * @return \DateTime
     */
    public function getStartingDay(): \DateTime {
        return new \DateTime("{$this->year}-{$this->month}-01");
    }

    /**
     * Return the month in letters (ex: March 2018)
     * @return string
     */
    public function toString(): string{
        $this->months = Translation::of('months');
        return $this->months[$this->month - 1] . ' ' . $this->year;
    }

    /**
     * Return week days in letters (ex: Monday)
     * @return array
     */
    public function getDays(): array{
        $this->days = Translation::of('days');
        return $this->days;
    }

    /**
     * Returns the number of weeks in the month
     * @return int
     */
    public function getWeeks(): int{
        $start = $this->getStartingDay();
        $end = (clone $start)->modify('+1 month -1 day');
        $startWeek = intval($start->format('W'));
        $endWeek = intval($end->format('W'));
        if ($endWeek === 1) {
            $endWeek = intval($end->modify('- 7 days')->format('W')) + 1;
        }
        $weeks = $endWeek - $startWeek + 1;
        if($weeks < 0){ //problem on January month which takes week 52
            $weeks = intval($end->format('W'));
        }
        return $weeks;
    }

    /**
     * Is the day in the current month
     * @param \DateTime $date
     * @return bool
     */
    public function withInMonth(\DateTime $date):bool {
        return $this->getStartingDay()->format('Y-m') === $date->format('Y-m');
    }

    /**
     * Return the next month
     * @return Month
     */
    public function nextMonth(): Month{
        $month = $this->month + 1;
        $year = $this->year;
        if($month > 12){
            $month = 1;
            $year += 1;
        }
        return new Month($month, $year);
    }

    /**
     * Return the previous month
     * @return Month
     */
    public function previousMonth(): Month{
        $month = $this->month - 1;
        $year = $this->year;
        if($month < 1){
            $month = 12;
            $year -= 1;
        }
        return new Month($month, $year);
    }
}