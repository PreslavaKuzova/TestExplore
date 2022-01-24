<?php

require_once 'controllers/BaseController.php';

class Dictionary extends BaseController
{
    public function __construct()
    {
        parent::__construct('views/dictionary.phtml');
    }

    public function index()
    {
        $this->render();
    }

    public function searchWord() {
        if (isset($_POST['word'])) {
            $this->decodeResponse($this->callApi("", "https://api.dictionaryapi.dev/api/v2/entries/en/".$_POST['word']));
        }
    }

    private function callApi($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
            {
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            }
            case "PUT":
            {
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            }
            default:
            {
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
            }
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    private function decodeResponse($response)
    {
        $wordDefinition = json_decode($response)[0];
        $word = $wordDefinition->word;
        $partOfSpeech = $wordDefinition->meanings[0]->partOfSpeech;
        $phonetic = $wordDefinition->phonetic;
        $definition = $wordDefinition->meanings[0]->definitions[0]->definition;
        $example = $wordDefinition->meanings[0]->definitions[0]->example;
        $audio = $wordDefinition->phonetics[0]->audio;

        $data = array($word, $partOfSpeech, $phonetic, $definition, $example, $audio);
        echo json_encode($data);
    }

}