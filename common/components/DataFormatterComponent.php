<?php

namespace common\components;

use yii\helpers\ArrayHelper;
use DateTime;
use yii\base\Component;
use Yii;

/**
 * Class DataFormatterHelper
 * @package common\components\helpers
 *
 * Rules of Date display:
 * до 5 минут - только что
 * от 5 минут до 1 часа - х минут назад
 * от часа до 8 часов - x часов у минут назад с округлением минут до 5
 * от 8 часов до суток - x часов назад с округлением минут до часов по правилам арифметики
 * от суток до месяца - x дней назад
 * более месяца - dd.mm.yyyy
 */
class DataFormatterComponent extends Component
{
    public $translations = [
        'ru' => [
            'years'           => ['год', 'года', 'лет'],
            'days'            => ['день', 'дня', 'дней'],
            'hours'           => ['час', 'часа', 'часов'],
            'minutes'         => ['минута', 'минуты', 'минут'],
            'answer'          => ['ответ', 'ответа', 'ответов'],
            'answers'         => ['ответ', 'ответа', 'ответов'],
            'best_answer'     => ['лучший ответ', 'лучшего ответа', 'лучших ответов'],
            'vote_for_answer' => ['голос за ответ', 'голоса за ответа', 'голосов за ответ'],
            'tags'            => ['тег', 'тега', 'тегов'],
            'filters'         => ['фильтр', 'фильтра', 'фильтров'],
            'comments'        => ['комментарий', 'комментария', 'комментариев'],
            'points'          => ['балл', 'балла', 'баллов'],
            'point'           => ['балл', 'балла', 'баллов'],
            'questions'       => ['вопрос', 'вопроса', 'вопросов'],
            'topic'           => ['вопрос', 'вопроса', 'вопросов'],
            'decisions'       => ['решение', 'решения', 'решений'],
            'people'          => ['человек', 'людей', 'людей'],
            'symbols'         => ['символ', 'символа', 'символов'],
            'books'           => ['книга', 'книги', 'книг'],
            'characters'      => ['символ', 'символа', 'символов'],
            'person'          => ['участник', 'участника', 'участников'],
        ],
        'en' => [
            'years'           => ['year', 'years', 'years'],
            'days'            => ['day', 'days', 'days'],
            'hours'           => ['hour', 'hours', 'hours'],
            'minutes'         => ['minute', 'minutes', 'minutes'],
            'answer'          => ['answer', 'answers', 'answers'],
            'answers'         => ['answer', 'answers', 'answers'],
            'best_answer'     => ['best answer', 'best answers', 'best answers'],
            'vote_for_answer' => ['vote for answer', 'vote for answers', 'vote for answers'],
            'tags'            => ['tag', 'tags', 'tags'],
            'filters'         => ['filter', 'filters', 'filters'],
            'comments'        => ['comment', 'comments', 'comments'],
            'points'          => ['point', 'points', 'points'],
            'point'           => ['point', 'points', 'points'],
            'questions'       => ['question', 'questions', 'questions'],
            'topic'           => ['question', 'questions', 'questions'],
            'decisions'       => ['decision', 'decisions', 'decisions'],
            'people'          => ['person', 'people', 'people'],
            'symbols'         => ['symbol', 'symbols', 'symbols'],
            'books'           => ['book', 'books', 'books'],
            'characters'      => ['character', 'characters', 'characters'],
            'person'          => ['person', 'persons', 'persons'],
        ]
    ];

    public static $months = [
        'ru' => [
            'January'   => 'Января',
            'February'  => 'Февраля',
            'March'     => 'Марта',
            'April'     => 'Апреля',
            'May'       => 'Мая',
            'June'      => 'Июня',
            'July'      => 'Июля',
            'August'    => 'Августа',
            'September' => 'Сентября',
            'October'   => 'Октября',
            'November'  => 'Ноября',
            'December'  => 'Декабря',

            'Sunday'    => 'Воскресенье',
            'Monday'    => 'Понедельник',
            'Tuesday'   => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday'  => 'Четверг',
            'Friday'    => 'Пятница',
            'Saturday'  => 'Суббота',

            'Sun' => 'Вс',
            'Mon' => 'Пн',
            'Tue' => 'Вт',
            'Wed' => 'Ср',
            'Thu' => 'Чт',
            'Fri' => 'Пт',
            'Sat' => 'Сб',

            'Jan'       => 'янв',
            'Feb'       => 'фев',
            'Mar'       => 'мар',
            'Apr'       => 'апр',
            'May_short' => 'май',    // Short representation of "May". May_short used because in English the short and long date are the same for May.
            'Jun'       => 'июн',
            'Jul'       => 'июл',
            'Aug'       => 'авг',
            'Sep'       => 'сен',
            'Oct'       => 'окт',
            'Nov'       => 'ноя',
            'Dec'       => 'дек',
        ]
    ];

    /**
     * Convert $value provided  in $format to common project time format (d.m.Y H:i)
     * @param string|\DateTime $value
     * @param string           $formatFrom
     * @param string           $formatTo
     * @return string
     */
    public function getFormattedTime($value, $formatFrom = 'Y-m-d H:i:s', $formatTo = 'd.m.Y H:i')
    {
        if ($value == '0000-00-00 00:00:00') {
            return '';
        }

        if ($created = ($value instanceof DateTime) ? $value : DateTime::createFromFormat($formatFrom, $value)) {
            $res = $created->format($formatTo);
            return (isset(self::$months[Yii::$app->language])) ? strtr($res, self::$months[\Yii::$app->language]) : $res;
        }

        return '';
    }

    /**
     * Convert $value provided  in $format to common project date format
     * @param string|\DateTime $value
     * @param string           $formatFrom
     * @param string           $formatTo
     * @return string
     */
    public function getFormattedDate($value, $formatFrom = 'Y-m-d', $formatTo = 'd.m.Y')
    {
        if (strpos($value, '0000-00-00') !== false) {
            return '';
        }

        if ($created = ($value instanceof DateTime) ? $value : DateTime::createFromFormat($formatFrom, $value)) {
            $res = $created->format($formatTo);
            return (isset(self::$months[Yii::$app->language])) ? strtr($res, self::$months[\Yii::$app->language]) : $res;
        }

        return '';
    }

    /**
     * @param $engName
     * @return string
     */
    public function getMonthName($engName)
    {
        if (isset(self::$months[Yii::$app->language][$engName])) {
            return self::$months[Yii::$app->language][$engName];
        }
        return Yii::t('app', $engName);
    }

    /**
     * Convert $value provided in $format in number of spent days|hours|minutes etc. to due to appropriate rules
     * @param string|\DateTime $value
     * @param string           $format
     * @return string
     */
    public function getSpentTime($value, $format = 'Y-m-d H:i:s')
    {
        $res = '';
        if ($created = ($value instanceof DateTime) ? $value : DateTime::createFromFormat($format, $value)) {
            $today = new DateTime();
            $diff = $today->diff($created);
            $period = [];
            $lang = Yii::$app->language;
            if ($diff->m > 0) {
                $period[] = $created->format('d.m.Y');
            } elseif ($diff->d > 0) {
                $period[] = $this->getDays($diff->d, null, $lang);
                $period[] = \Yii::t('app', 'ago');
            } elseif ($diff->h >= 8) {
                $period[] = $this->getHours($diff->h + ($diff->i >= 30 ? 1 : 0), null, $lang);
                $period[] = \Yii::t('app', 'ago');
            } elseif ($diff->h > 0) {
                $period[] = $this->getHours($diff->h, null, $lang);
                $i = $diff->i - $diff->i % 5;
                if ($i > 0) {
                    $period[] = $this->getMinutes($i, null, $lang);
                }
                $period[] = \Yii::t('app', 'ago');
            } elseif ($diff->i >= 5) {
                $period[] = $this->getMinutes($diff->i, null, $lang);
                $period[] = \Yii::t('app', 'ago');
            }

            if (!empty($period)) {
                $res = implode(' ', $period);
            } else {
                $res = \Yii::t('app', 'just now');
            }
        }
        return $res;
    }

    /**
     * Convert $value from $formatFrom to $formatTo
     * @param mixed  $value
     * @param string $formatTo
     * @param string $formatFrom
     * @return string
     */
    public function getDateTime($value, $formatTo = 'F d, Y', $formatFrom = 'Y-m-d H:i:s')
    {
        $res = $value;
        if ($created = ($value instanceof DateTime) ? $value : DateTime::createFromFormat($formatFrom, $value)) {
            $res = $created->format($formatTo);
        }

        return (isset(self::$months[Yii::$app->language])) ? strtr($res, self::$months[\Yii::$app->language]) : $res;
    }

    /**
     * Get translatable representation of number of days spend due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getDays($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'days', $lang);
    }

    /**
     * Get translatable representation of number of hours spend due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getHours($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'hours', $lang);
    }

    /**
     * Get translatable representation of number of minutes spend due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getMinutes($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'minutes', $lang);
    }

    /**
     * Get translatable representation of number of days spend due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getAnswers($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return $this->getMeasureSimple($n, 'answers', $lang);
    }

    /**
     * Get translatable representation of number of comments posted due to hardcoded translations for particular
     * language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getComments($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'comments', $lang);
    }

    /**
     * Get translatable representation of number of tags applied due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getTags($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'tags', $lang);
    }

    /**
     * Get translatable representation of number of people due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getPeople($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'people', $lang);
    }

    /**
     * Get translatable representation of number of filters applied spend due to hardcoded translations for particular
     * language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getFilters($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'filters', $lang);
    }

    /**
     * Get translatable representation of number of symbols left due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getSymbols($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'symbols', $lang);
    }

    /**
     * Get translatable representation of number of points earned due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getPoints($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'points', $lang);
    }

    /**
     * Get translatable representation of number of points earned due to hardcoded translations for particular language
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getPersons($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'person', $lang);
    }

    /**
     * Get translatable representation of number of books
     * @param int    $n   defines declension
     * @param string $alt return if empty n
     * @param string $lang
     * @return string
     */
    public function getBooks($n, $alt = null, $lang = null)
    {
        if (empty($n) && !empty($alt)) {
            return $alt;
        }
        return "$n " . $this->getMeasureSimple($n, 'books', $lang);
    }


    /**
     * Get translatable representation of particular term due to hardcoded translations for particular language
     * @param int    $n       defines declension
     * @param string $measure hardcoded term key
     * @param string $lang
     * @return string
     */
    public function getMeasureSimple($n, $measure, $lang = null)
    {
        if (!$lang) {
            $lang = \Yii::$app->language;
        }
        $form = isset($this->translations[$lang][$measure]) ? $this->translations[$lang][$measure] : $this->translations[\Yii::$app->sourceLanguage][$measure];

        $count = abs($n) % 100;
        $lcount = $count % 10;
        if ($count >= 11 && $count <= 19) {
            return ($form[2]);
        }
        if ($lcount >= 2 && $lcount <= 4) {
            return ($form[1]);
        }
        if ($lcount == 1) {
            return ($form[0]);
        }
        return $form[2];
    }

    /**
     * Return formated file size string
     * @param int $filesize (file size in byte)
     * @return string
     */
    public function getFormattedFileSize($filesize)
    {
        $result = '';
        if ($filesize > 0) {
            $unit = intval(log($filesize, 1024));
            $units = ['B', 'KB', 'MB', 'GB'];

            if (array_key_exists($unit, $units) === true) {
                $result = sprintf('%d %s', $filesize / pow(1024, $unit), $units[$unit]);
            }
        }

        return $result;
    }

//    /**
//     * Get translatable representation of number of days spend due to hardcoded translations for particular language
//     */
//    public function getMeasure($n, $measure, $lang = null)
//    {
//        if (!$lang){
//            $lang = \Yii::$app->language;
//        }
//        $trs = isset($this->{$measure}[$lang])?$this->{$measure}[$lang]:$this->{$measure}[\Yii::$app->sourceLanguage];
//
//        $tr = $trs['other'];
//        if(($n==1) && isset($trs['1'])){
//            $tr =  $trs['1'];
//        }
//        elseif($n > 10 && $n < 20){
//            $tr = $trs['many'];
//        }
//        elseif((($n % 10) == 1) && isset($trs['one'])){
//            $tr = $trs['one'];
//        }
//        elseif((($n % 10) > 0) && (($n % 10) < 5) && isset($trs['few'])){
//            $tr = $trs['few'];
//        }
//        elseif((intval($n) == $n) && isset($trs['many'])){
//            $tr = $trs['many'];
//        }
//        return sprintf('%s %s', $n, $tr);
//    }

    /**
     * @param string $text      Text to be cropped
     * @param int    $size      Max number of symbols available
     * @param string $addColons symbol to be added if cropped, false if nothing should be added
     * @return string
     */
    public function getCroppedText($text, $size = 500, $addColons = '...')
    {
        $result = $text;
        if (mb_strlen($text) > $size) {
            $result = mb_substr($text, 0, $size, 'utf-8') . (($addColons === false) ? '' : $addColons);
        }

        return $result;
    }

    /**
     * @param        $value
     * @param string $formatFrom
     * @return string
     */
    public function getJsFormattedTime($value, $formatFrom = 'Y.m.d h:i:s')
    {
        if ($value = new \DateTime($value, new \DateTimeZone('UTC'))) {
            /**
             * return date in format 2004-02-12T15:19:21+00:00
             * @see http://php.net/manual/ru/function.date.php
             */
            return $value->format('c');
        }
        return '';
    }

    /**
     * Return participant age in years
     * @param string|\DateTime $birthdayDate
     * @param string           $formatFrom
     * @return int|string
     */
    public function getAge($birthdayDate, $formatFrom = 'Y-m-d')
    {
        $result = '';
        if (!empty($birthdayDate) && ($from = ($birthdayDate instanceof DateTime) ? $birthdayDate : \DateTime::createFromFormat($formatFrom, $birthdayDate)) !== false) {
            $result = $from->diff((new \DateTime('today')))->y;
        }

        return $result;
    }

    /**
     * Get dates range with some rules
     * @param $dateFrom
     * @param $dateTo
     * @param string $delimiter
     * @param array $params
     * @return string
     */
    public function getDatesRange($dateFrom,$dateTo, $delimiter = '-', $params = [])
    {
        //get objects of provided dates
        $dateFrom = new \DateTime($dateFrom);
        $dateTo = new \DateTime($dateTo);

        //extract params
        $dayFormat      = ArrayHelper::getValue($params, 'dayFormat', 'd');
        $monthFormat    = ArrayHelper::getValue($params, 'monthFormat', 'F');
        $yearFormat     = ArrayHelper::getValue($params, 'yearFormat', 'Y');

        $dates = '';
        if ($dateTo->format('Y') > $dateFrom->format('Y')) {
            $tmp = [];
            $tmp[] = Yii::$app->dataFormatter->getDateTime($dateFrom, sprintf('%s %s %s', $dayFormat, $monthFormat, $yearFormat));
            $tmp[] = Yii::$app->dataFormatter->getDateTime($dateTo, sprintf('%s %s %s', $dayFormat, $monthFormat, $yearFormat));
            $dates = implode($delimiter, $tmp);
        }
        elseif ($dateTo->format('m') > $dateFrom->format('m')) {
            $tmp = [];
            $tmp[] = Yii::$app->dataFormatter->getDateTime($dateFrom, sprintf('%s %s', $dayFormat, $monthFormat));
            $tmp[] = Yii::$app->dataFormatter->getDateTime($dateTo, sprintf('%s %s', $dayFormat, $monthFormat));
            $dates = implode($delimiter, $tmp).' '.$dateTo->format($yearFormat);
        }
        elseif ($dateTo->format('d') > $dateFrom->format('d')) {
            $tmp = [];
            $tmp[] = $dateFrom->format($dayFormat);
            $tmp[] = Yii::$app->dataFormatter->getDateTime($dateTo, sprintf('%s %s', $dayFormat, $monthFormat));
            $dates = implode($delimiter, $tmp).' '.$dateTo->format($yearFormat);
        }
        else {
            $dates = Yii::$app->dataFormatter->getDateTime($dateTo, sprintf('%s %s %s', $dayFormat, $monthFormat, $yearFormat));
        }

        return $dates;
    }

    /**
     * Range explanation
     * @param int $from Min allowed value
     * @param int $to Max allowed value
     * @return string
     */
    public function getRange($from,$to)
    {
        $res = '';
        if ($from > 0){
            if($from  == $to){
                $res = Yii::t('app','{min} only',['min' => $from]);
            }
            elseif ($to > 0){
                $res = Yii::t('app','{min} to {max}',['min' => $from, 'max' => $to]);
            }
            else{
                $res = Yii::t('app','from {min}',['min' => $from]);
            }
        }
        elseif($to > 0){
            $res = Yii::t('app','to {max}',['max' => $to]);
        }
        return $res;
    }

    /**
     * Build link to google map page for passed coordinates
     * @param $latitude
     * @param $longitude
     * @return string
     */
    public function getMapLink($latitude, $longitude) {
        $link = '';
        if (!empty($latitude) && !empty($longitude)) {
//            $link = sprintf('http://www.google.com/maps/place/%s,%s', $latitude, $longitude);
            $link = sprintf('https://bing.com/maps?cp=%s~%s&sp=point.%s_%s_%s', $latitude, $longitude, $latitude, $longitude,Yii::t('app','Here'));
        }

        return $link;
    }

    /**
     * Return timezone offset in hours and minutes
     * @param string $timezoneName
     * @return string
     */
    public function getTimezoneOffset($timezoneName) {

        $timezone = new \DateTimeZone($timezoneName);
        $offset   = $timezone->getOffset(new \DateTime());

        return sprintf('%+02d:%02u', $offset / 3600, ($offset % 3600) / 60);
    }
}
