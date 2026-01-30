<?php
require_once 'Product.php';

class ProductApiClient {
    
    
    private const API_BASE_URL = 'http://localhost/APIopdracht/api.php';
    private $user_agent;
    
    public function __construct() {
        $this->user_agent = 'MijnProductApp/1.0 (Student SD2)';
    }
    
    // curl instellingen
    private function executeCurl($ch) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($curl_error) {
            throw new Exception('CURL fout: ' . $curl_error);
        }
        
        return [$response, $http_code];
    }
    
    // alle producten uit de database ophalen
    public function getProducts($zoekterm = '') {
        $url = self::API_BASE_URL;
        if (!empty($zoekterm)) { 
            $url .= '?naam=' . urlencode($zoekterm);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']); 
        
        list($response, $http_code) = $this->executeCurl($ch);

        if ($http_code != 200) {
            $error_data = json_decode($response, true);
            $error_msg = $error_data['error'] ?? 'Onbekende API fout';
            throw new Exception("API fout ($http_code): " . $error_msg);
        }
        
        $productenData = json_decode($response, true);
        $producten = [];
        
        if (is_array($productenData)) {
            foreach ($productenData as $data) {
                $producten[] = new Product(
                    $data['naam'],
                    $data['prijs'],
                    $data['id'],
                    $data['created_at'] ?? null
                );
            }
        }
        
        return $producten;
    }
    
    // product toevoegen in de tabel (database)
    public function addProduct($naam, $prijs) {
        $url = self::API_BASE_URL;
        $data = ['naam' => trim($naam), 'prijs' => (float)$prijs];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        list($response, $http_code) = $this->executeCurl($ch);

        if ($http_code == 200 || $http_code == 201) {
            $response_data = json_decode($response, true);
            return [
                'success' => true,
                'product' => new Product(
                    $response_data['product']['naam'] ?? $naam,
                    $response_data['product']['prijs'] ?? $prijs,
                    $response_data['product']['id'] ?? null,
                    $response_data['product']['created_at'] ?? null
                )
            ];
        } else {
            $error_data = json_decode($response, true);
            $error_msg = $error_data['error'] ?? 'Kon product niet toevoegen';
            throw new Exception("API fout ($http_code): " . $error_msg);
        }
    }
    
    // verwijder producten uit de database of tabel
    public function deleteProduct($id) {
        $url = self::API_BASE_URL . '/' . $id;
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
        
        list($response, $http_code) = $this->executeCurl($ch);

        if ($http_code == 200 || $http_code == 204) {
            return true;
        } else {
            $error_data = json_decode($response, true);
            $error_msg = $error_data['error'] ?? 'Kon product niet verwijderen';
            throw new Exception("API fout ($http_code): " . $error_msg);
        }
    }
    
    // producten bewerken 
    public function updateProduct($id, $naam, $prijs) {
        $url = self::API_BASE_URL . '/' . $id;
        $data = ['naam' => trim($naam), 'prijs' => (float)$prijs];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        list($response, $http_code) = $this->executeCurl($ch);

        if ($http_code == 200) {
            $response_data = json_decode($response, true);
            return [
                'success' => true,
                'product' => new Product(
                    $response_data['product']['naam'] ?? $naam,
                    $response_data['product']['prijs'] ?? $prijs,
                    $response_data['product']['id'] ?? $id,
                    $response_data['product']['created_at'] ?? null
                )
            ];
        } else {
            $error_data = json_decode($response, true);
            $error_msg = $error_data['error'] ?? 'Kon product niet bijwerken';
            throw new Exception("API fout ($http_code): " . $error_msg);
        }
    }
}
?>