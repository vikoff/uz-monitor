<?php

namespace app\components\UzMonitor;

use app\components\log\OptionalLoggerTrait;
use app\components\curl\CurlRequest;
use Psr\Log\LoggerInterface;

class UzApiAdapter
{
    use OptionalLoggerTrait;

    /**
     * @var string
     */
    private $stationFrom;
    /**
     * @var string
     */
    private $stationTo;
    /**
     * @var string
     */
    private $date;
    /**
     * @var array
     */
    private $allowedTrains;
    /**
     * @var string
     */
    private $time;
    /**
     * @var array|null
     */
    private $previousPlaces;

    /**
     * @param string $stationFrom
     * @param string $stationTo
     * @param string $date in format YYYY-MM-DD
     * @param array $allowedTrains
     * @param string $time
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        $stationFrom,
        $stationTo,
        $date,
        array $allowedTrains = [],
        $time = '00:00',
        LoggerInterface $logger = null
    ) {
        if ((new \DateTime($date))->format('Y-m-d') !== $date) {
            throw new \InvalidArgumentException('Invalid date format. Expecting Y-m-d');
        }

        $this->stationFrom = $stationFrom;
        $this->stationTo = $stationTo;
        $this->date = $date;
        $this->allowedTrains = $allowedTrains;
        $this->time = $time;
        $this->setLoggerOptional($logger);
    }

    /**
     * @return array
     */
    public function dumpParams()
    {
        return [
            'stationFrom' => $this->stationFrom,
            'stationTo' => $this->stationTo,
            'date' => $this->date,
            'allowedTrains' => $this->allowedTrains,
            'time' => $this->time,
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function parse()
    {
        $data = $this->requestData();
        return $this->processData($data);
    }

    /**
     * test parsing
     *
     * @return array
     */
    public function parseTest()
    {
        $this->processData(json_decode('{"data":{"list":[{"num":"002Д","category":0,"isTransformer":0,"travelTime":"9:08","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Константиновка","date":"пятница, 14.09.2018","time":"04:00","sortTime":1536886800,"srcDate":"2018-09-14"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"пятница, 14.09.2018","time":"13:08","sortTime":1536919680},"types":[{"id":"Л","title":"Люкс","letter":"Л","places":8},{"id":"К","title":"Купе","letter":"К","places":1}],"child":{"minDate":"2004-09-15","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"749К","category":2,"isTransformer":1,"travelTime":"8:03","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Киев-Пассажирский","date":"пятница, 14.09.2018","time":"14:07","sortTime":1536923220,"srcDate":"2018-09-14"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"пятница, 14.09.2018","time":"22:10","sortTime":1536952200},"types":[{"id":"С1","title":"Сидячий первого класса","letter":"С1","places":30},{"id":"С2","title":"Сидячий второго класса","letter":"С2","places":62}],"child":{"minDate":"2004-09-15","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"043К","category":0,"isTransformer":0,"travelTime":"10:53","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Киев-Пассажирский","date":"пятница, 14.09.2018","time":"18:50","sortTime":1536940200,"srcDate":"2018-09-14"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"суббота, 15.09.2018","time":"05:43","sortTime":1536979380},"types":[],"child":{"minDate":"2004-09-15","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"144О","category":0,"isTransformer":0,"travelTime":"11:01","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Кременчуг","date":"пятница, 14.09.2018","time":"20:19","sortTime":1536945540,"srcDate":"2018-09-14"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ворохта","date":"суббота, 15.09.2018","time":"07:20","sortTime":1536985200},"types":[],"child":{"minDate":"2004-09-15","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"007К","category":0,"isTransformer":1,"travelTime":"9:33","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Киев-Пассажирский","date":"пятница, 14.09.2018","time":"22:27","sortTime":1536953220,"srcDate":"2018-09-14"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"суббота, 15.09.2018","time":"08:00","sortTime":1536987600},"types":[{"id":"Л","title":"Люкс","letter":"Л","places":2}],"child":{"minDate":"2004-09-15","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0}]}}', true));

        return $this->processData(json_decode('{"data":{"list":[{"num":"002Д","category":0,"isTransformer":0,"travelTime":"9:08","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Константиновка","date":"пятница, 28.09.2018","time":"04:00","sortTime":1538096400,"srcDate":"2018-09-28"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"пятница, 28.09.2018","time":"13:08","sortTime":1538129280},"types":[{"id":"Л","title":"Люкс","letter":"Л","places":18},{"id":"К","title":"Купе","letter":"К","places":9}],"child":{"minDate":"2004-09-29","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"749К","category":2,"isTransformer":1,"travelTime":"8:10","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Киев-Пассажирский","date":"пятница, 28.09.2018","time":"14:00","sortTime":1538132400,"srcDate":"2018-09-28"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"пятница, 28.09.2018","time":"22:10","sortTime":1538161800},"types":[{"id":"С1","title":"Сидячий первого класса","letter":"С1","places":14},{"id":"С2","title":"Сидячий второго класса","letter":"С2","places":25}],"child":{"minDate":"2004-09-29","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"043К","category":0,"isTransformer":0,"travelTime":"10:53","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Киев-Пассажирский","date":"пятница, 28.09.2018","time":"18:50","sortTime":1538149800,"srcDate":"2018-09-28"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"суббота, 29.09.2018","time":"05:43","sortTime":1538188980},"types":[{"id":"Л","title":"Люкс","letter":"Л","places":1},{"id":"К","title":"Купе","letter":"К","places":2},{"id":"П","title":"Плацкарт","letter":"П","places":11}],"child":{"minDate":"2004-09-29","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"144О","category":0,"isTransformer":0,"travelTime":"11:01","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Кременчуг","date":"пятница, 28.09.2018","time":"20:19","sortTime":1538155140,"srcDate":"2018-09-28"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ворохта","date":"суббота, 29.09.2018","time":"07:20","sortTime":1538194800},"types":[{"id":"К","title":"Купе","letter":"К","places":2},{"id":"П","title":"Плацкарт","letter":"П","places":23}],"child":{"minDate":"2004-09-29","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0},{"num":"007К","category":0,"isTransformer":1,"travelTime":"9:03","from":{"code":"2200001","station":"Киев-Пассажирский","stationTrain":"Киев-Пассажирский","date":"пятница, 28.09.2018","time":"22:27","sortTime":1538162820,"srcDate":"2018-09-28"},"to":{"code":"2218200","station":"Ивано-Франковск","stationTrain":"Ивано-Франковск","date":"суббота, 29.09.2018","time":"07:30","sortTime":1538195400},"types":[{"id":"Л","title":"Люкс","letter":"Л","places":10},{"id":"К","title":"Купе","letter":"К","places":18}],"child":{"minDate":"2004-09-29","maxDate":"2018-09-06"},"allowStudent":1,"allowBooking":1,"isCis":0,"isEurope":0,"allowPrivilege":0,"disabledPrivilegeByDate":0}]}}', true));
    }

    /**
     * @return string
     */
    public function buildViewUrl()
    {
//        'https://booking.uz.gov.ua/ru/?from=2200001&to=2218200&date=2018-09-28&time=00%3A00&url=train-list';

        return 'https://booking.uz.gov.ua/ru/?'
            . http_build_query([
                'from' => $this->stationFrom,
                'to' => $this->stationTo,
                'date' => $this->date,
                'time' => $this->time,
                'url' => 'train-list',
            ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function requestData()
    {
        $response = CurlRequest::init('https://booking.uz.gov.ua/ru/train_search/')
            ->setPostFields([
                'from' => $this->stationFrom,
                'to' => $this->stationTo,
                'date' => $this->date,
                'time' => $this->time,
            ])
            ->exec();

        if ($response->getHttpCode() !== 200) {
            throw new \Exception('Invalid response http code. ' . print_r($response, true));
        }

        $data = json_decode($response->getBody(), true);

        if (!isset($data['data']['list'])) {
            throw new \Exception('Invalid response. ' . print_r($response, true));
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function processData($data)
    {
        $trains = array_filter($data['data']['list'], function($train) {
            return in_array($train['num'], $this->allowedTrains);
        });

        $log = $this->getLogger();

        if (count($trains) !== count($this->allowedTrains)) {
            $log->warning(count($trains) . ' found, expected ' . count($this->allowedTrains));
        }

        $places = [];

        foreach ($trains as $train) {
            $places[$train['num']] = [];
            foreach ($train['types'] as $type) {
                if (in_array($type['id'], ['К', 'П'])) {
                    $places[$train['num']][$type['id']] = $type['places'];
                }
            }
            $log->info("    {$train['num']}: " . json_encode($places[$train['num']], JSON_UNESCAPED_UNICODE));
        }

        $newPlaces = [];

        if ($this->previousPlaces !== null) {
            foreach ($places as $train => $types) {
                foreach ($types as $type => $cnt) {
                    $prevCnt = isset($this->previousPlaces[$train][$type]) ? $this->previousPlaces[$train][$type] : 0;
                    if ($cnt > $prevCnt) {
                        $msg = "new places on $train [$type]: $prevCnt => $cnt";
                        $log->notice($msg);
                        $newPlaces[] = $msg;
                    }
                }
            }
        } else {
            $log->info('previous data is not stored yet');
        }

        $this->previousPlaces = $places;

        return $newPlaces;
    }
}
