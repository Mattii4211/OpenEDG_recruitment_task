<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\Currency;
use App\ValueObject\DayRate;
use DateTimeInterface;
use App\Kernel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Exception;
use DateTime;

final readonly class NBPApiService 
{
    private const NBP_URL = 'https://api.nbp.pl/api/exchangerates/rates/a';
    private const DATA_API_FORMAT = '?format=json';

    public function __construct(private HttpClientInterface $client)
    {}

    public function getNBPExchangeData(
        Currency $currency,
        DateTimeInterface $dateStart,
        DateTimeInterface $dateEnd
    ): array
    {
        try {
            $data = $this->getData($this->prepareUrl($currency, $dateStart, $dateEnd));
        } catch (Exception $e) {
            return [];
        }

        $output = [];
        foreach ($data as $row) {
            $output[] = new DayRate(
                $currency,
                $row->mid,
                new DateTime($row->effectiveDate)
            );
        }
        
        return $output;
    }

    private function prepareUrl(
        Currency $currency,
        DateTimeInterface $dateStart,
        DateTimeInterface $dateEnd
    ): string
    {
        $dateStart = $dateStart->format(Kernel::DATE_FORMAT);
        $dateEnd = $dateEnd->format(Kernel::DATE_FORMAT);

        return self::NBP_URL . "/{$currency->getApiName()}/$dateStart/$dateEnd" . self::DATA_API_FORMAT;
    }
    
    private function getData(string $url): array
    {
        $response = $this->client->request(
            'GET', 
            $url
        );

        if ($response->getStatusCode() !== Response::HTTP_OK) {
            throw new NotFoundHttpException;
        }

        return json_decode($response->getContent())->rates;
    }
}