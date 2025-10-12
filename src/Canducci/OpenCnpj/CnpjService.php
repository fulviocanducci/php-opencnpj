<?php 

namespace Canducci\OpenCnpj;

use Canducci\OpenCnpj\Models\Company;
use Canducci\OpenCnpj\Utils\ApiResponse;
use Canducci\OpenCnpj\Utils\Valid;
use Canducci\OpenCnpj\ICnpjService;
use Exception;

class CnpjService implements ICnpjService
{

    public function __construct() { }

    public static function create(): CnpjService
    {
        return new CnpjService();
    }

    public function getCnpj(string $cnpj): ApiResponse
    {
        $value = $this->normalizeCnpj($cnpj);
        if (Valid::isCnpj($value)){
            try {            
                $ch = $this->getCh($value);
                if ($ch) {
                    $response = $this->getExec($ch);                
                    if ($response['error']) {
                        return $this->getApiResponseError($response);
                    }                
                    $data = $this->getData($response['value']);
                    if ($data['valid'] === false) {
                        return $this->getApiResponseError($data);
                    }
                    return $this->getApiResponseOk($data['value']);                
                }
                return $this->getApiResponseExceptionError(new Exception("CurHandle invalid"));
            } 
            catch(Exception $ex) {
                return $this->getApiResponseExceptionError($ex);
            }
        }
        return $this->getApiResponseExceptionError(new Exception("Invalid CNPJ format"));
    }

    private function getCh(string $cnpj): \CurlHandle|false
    {
        $ch = curl_init("https://api.opencnpj.org/{$cnpj}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [            
            'Accept: application/json'
        ]);
        return $ch;
    }

    private function getData(string $json): array 
    {
        if (empty($json)) {
            return [
                'value' => null,
                'valid' => false,
                'error' => 1,
                'errorMsg' => 'Empty response'
            ];
        }
        $value = json_decode($json, true);
        $error = json_last_error();
        $errorMsg = json_last_error_msg() ?? "";
        return [
            'value' => $value,
            'valid' => json_last_error() === JSON_ERROR_NONE,
            'error' => $error,
            'errorMsg' => "Invalid JSON response: {$errorMsg}"
        ];

    }

    private function getExec(\CurlHandle $ch): array 
    {       
        $value = curl_exec($ch);
        $error = curl_errno($ch);
        $errorMsg = curl_error($ch);
        curl_close($ch);
        return [
            'value' => $value,
            'error' => $error,
            'errorMsg' => $errorMsg
        ];
    }

    private function getApiResponseError(array $response): ApiResponse
    {
        return ApiResponse::error(new Exception($response['errorMsg'], $response['error']));        
    }

    private function getApiResponseExceptionError(Exception $ex): ApiResponse
    {      
        return ApiResponse::error($ex);
    }

    private function getApiResponseOk(array $data): ApiResponse
    {
        return ApiResponse::ok(new Company($data));
    }

    private function normalizeCnpj(string $cnpj): string
    {
        return preg_replace('/\D/', '', $cnpj);
    }    
}