<?php

namespace App\Controller;

use App\Enum\Currency;
use App\Exception\DateRangeException;
use App\Repository\CurrencyExchangeRateRepository;
use App\Service\ValidateMinMaxRequest;
use App\Service\ValidateCalculation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Exception\IncorrectCurrencyException;
use App\Exception\IncorrectPeriodException;
use DateTime;
use InvalidArgumentException;

#[Route('/currency_exchange_rate')]
class CurrencyExchangeRateController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('currency_exchange_rate/index.html.twig', [
            'currencies' => [Currency::EUR->getName(), Currency::USD->getName()],
        ]);
    }

    #[Route('/checkMinMax', name: 'checkMinMax', methods: ['GET', 'POST'])]
    public function checkMinMax(
        Request $request, 
        ValidateMinMaxRequest $validateMinMaxRequest, 
        CurrencyExchangeRateRepository $currencyExchangeRateRepository
    ): JsonResponse
    {
        $requestData = json_decode($request->getContent());

        try {
            $validateMinMaxRequest->validate($requestData);
        } catch (IncorrectCurrencyException|IncorrectPeriodException|InvalidArgumentException $e) {
           return new JsonResponse(
                ['message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
           );
        }

        $data = $currencyExchangeRateRepository->findMinMax(
            $requestData->period,
            $requestData->range,
            $requestData->currency
        );

        return new JsonResponse(
            $data ? [
                'min' => $data->getMin(),
                'max' => $data->getMax()    
            ] : [
                'message' => 'Empty data'
            ],
            $data ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }

    #[Route('/calculateInPln', name: 'calculateInPln', methods: ['GET', 'POST'])]
    public function calculateInPln(
        Request $request, 
        ValidateCalculation $validateCalculation,
        CurrencyExchangeRateRepository $currencyExchangeRateRepository
    ): JsonResponse
    {
        try {
            $requestData = json_decode($request->getContent());
            $validateCalculation->validate($requestData);
        } catch (IncorrectCurrencyException|DateRangeException|InvalidArgumentException $e) {
            return new JsonResponse(
                ['message' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
           );
        }

        $rate = $currencyExchangeRateRepository->getValueByDate(new DateTime($requestData->date), $requestData->currency);
        $valueInPln = $rate ? round(+$requestData->value / $rate, 5) : 0;

        return new JsonResponse(
            $rate ? ['value' => $valueInPln] : ['message' => 'Empty data'],
            $rate ? Response::HTTP_OK : Response::HTTP_NOT_FOUND
        );
    }
}
