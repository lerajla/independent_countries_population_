<?php

namespace app\Libraries;

use Exception;
use App\Dto\DispatcherDto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;

/**
 * Class CountriesDispatcherBusiness
 */
class CountriesDispatcherBusiness
{
    private $baseurl;

    /**
     * CountriesDispatcherBusiness constructor.
     */
    public function __construct()
    {
        $this->baseurl = 'https://restcountries.com/v3.1';
    }

    /**
     * @return DispatcherDto
     */
    public function listindependentCountries(): DispatcherDto
    {
        $endpoint = "$this->baseurl/independent?status=true";

        return $this->handler('GET', $endpoint);
    }

    /**
     * @return DispatcherDto
     */
    public function getRegionDetails($region): DispatcherDto
    {
        $endpoint = "$this->baseurl/region/$region";

        return $this->handler('GET', $endpoint);
    }

    /**
     * @param string $verb
     * @param string $endpoint
     * @return DispatcherDto
     */
    private function handler(string $verb, string $endpoint): DispatcherDto
    {
      $resultDto = new DispatcherDto();
      $client = new Client();

      try {
          /** @var ResponseInterface $response */
          $response = $client->$verb($endpoint);
          $resultDto->setSuccess(true);
          $resultDto->setData($response->getBody()->getContents());
      } catch (ConnectException $e) {
          Log::channel('app')->error('Failed operation:: ConnectExceptionMessage: ' . $e->getMessage() . ']');
          $resultDto->setMessage($e->getMessage());
      } catch (Exception $e) {
          Log::channel('app')->error('Failed operation:: ExceptionMessage: ' . $e->getMessage() . ']');
          $resultDto->setMessage($e->getMessage());
      }

      return $resultDto;
    }

}
