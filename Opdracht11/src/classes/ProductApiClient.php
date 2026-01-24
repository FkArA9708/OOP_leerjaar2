<?php

require_once 'Product.php'; 

class ProductApiClient {
    
    private const API_BASE_URL = 'https://st1738846851.splsites.nl/api.php';
    private $user_agent;
    
    public function __construct() {
        $this->user_agent = 'MijnProductApp/1.0 (Student SD2)';
    }

    
    private function setupCurl($url, $method = 'GET', $data = null) {
        $ch = curl_init($url);
        
        // Basis instellingen voor elke request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_FAILONERROR, false); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json'
        ]);

        // Instellingen voor POST-verzoek
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        return $ch;
    }

    
    public function getProducts($zoekterm = '') {
        $url = self::API_BASE_URL;
        // Als er een zoekterm is, voeg die toe als query parameter
        if (!empty($zoekterm)) {
            $url .= '?naam=' . urlencode($zoekterm);
        }

        $ch = $this->setupCurl($url, 'GET');
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        
        if (curl_errno($ch)) {
            throw new Exception('CURL fout: ' . curl_error($ch));
        }
        curl_close($ch);

        // Controleer HTTP status code
        if ($http_code != 200) {
            $error_data = json_decode($response, true);
            $error_msg = $error_data['error'] ?? 'Onbekende API fout';
            throw new Exception("API fout ($http_code): " . $error_msg);
        }

        
        $productenData = json_decode($response, true);
        $producten = [];
        foreach ($productenData as $data) {
            $producten[] = new Product(
                $data['naam'],
                $data['prijs'],
                $data['id'],
                $data['created_at']
            );
        }
        return $producten;
    }

    
    public function addProduct($naam, $prijs) {
    

        $url = self::API_BASE_URL;
        
        $data = ['naam' => $naam, 'prijs' => (float)$prijs];

        $ch = $this->setupCurl($url, 'POST', $data);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        
        if (curl_errno($ch)) {
            throw new Exception('CURL fout bij toevoegen: ' . curl_error($ch));
        }
        curl_close($ch);

        
        if ($http_code == 200 || $http_code == 201) {
            return true;
        } else {
            $error_data = json_decode($response, true);
            $error_msg = $error_data['error'] ?? 'Kon product niet toevoegen';
            throw new Exception("API fout ($http_code): " . $error_msg);
        }
    }

    
    public function deleteProduct($id) {
        $url = self::API_BASE_URL . '/' . $id;
        $ch = curl_init($url);
        
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => $this->user_agent,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CUSTOMREQUEST => 'DELETE', 
            CURLOPT_HTTPHEADER => ['Accept: application/json']
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            throw new Exception('CURL fout bij verwijderen: ' . curl_error($ch));
        }
        curl_close($ch);

        return ($http_code == 200);
    }
}
?>