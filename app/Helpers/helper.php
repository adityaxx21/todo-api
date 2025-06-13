<?php

if (!function_exists('commaSplit')) {
    /**
     * Split a comma separated string into an array.
     *
     * @param string $value
     * @return array
     */
    function commaSplit($value)
    {
        $cleaning = str_replace(' ', '', $value);
        return explode(',', strtolower($cleaning));
    }
}

if (!function_exists('dateRange')) {
    /**
     * Add a where between condition to the query builder.
     *
     * @param string $startDate
     * @param string $endDate
     * @param int $years
     * @return array
     */
    function dateRange($startDate, $endDate, $years = 100)
    {
        $start_date_default = \Carbon\Carbon::today()->subYears(abs($years));
        $end_date_default = \Carbon\Carbon::today()->addYears($years);
        $start = $startDate ? \Carbon\Carbon::parse($startDate) : $start_date_default->copy();
        $end = $endDate ? \Carbon\Carbon::parse($endDate) : $end_date_default->copy();
        return [$start->toDateString(), $end->toDateString()];
    }
}
    