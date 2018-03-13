<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $apiKeyPublic = "b19537ad3363e8ab947151b2fb216ed3";
        $apiKeyPrivate = "219b7835b034c47b33840a87012d8694dfc8602c";
        $ts = time();

        //hash - a md5 digest of the ts parameter,
        //your private key
        //and your public key
        //(e.g. md5(ts+privateKey+publicKey)
        $concat = (string)$ts.$apiKeyPrivate.$apiKeyPublic;
        $hash = md5($concat);

        $url = "https://gateway.marvel.com:443/v1/public/characters?orderBy=name&limit=20&offset=100"."&ts=".$ts."&apikey=".$apiKeyPublic."&hash=".$hash;

        $ch = curl_init();
        // configuration des options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        // exécution de la session
        $result = curl_exec($ch);
        // fermeture des ressources
        curl_close($ch);
        /*curl_multi_getcontent($ch);
        $xml = simplexml_load_string($test);
        $test = file_get_contents($url);*/

        $result = json_decode($result, true);
        dump($result);

        $hero = $result['data']['results'];

        dump($hero);

        return $this->render('@App/index.html.twig',[ 'heros' => $hero]);
    }

    /**
     * @param $id
     * @Route("/detailHero" , name="detail_hero")
     *
     */
    public function  detailHero(Request $id)
    {
        $apiKeyPublic = "b19537ad3363e8ab947151b2fb216ed3";
        $apiKeyPrivate = "219b7835b034c47b33840a87012d8694dfc8602c";
        $ts = time();

        $concat = (string)$ts.$apiKeyPrivate.$apiKeyPublic;
        $hash = md5($concat);

        $Id = $id->get('id');
        dump($Id);

        $url = "https://gateway.marvel.com:443/v1/public/characters/".$Id."?ts=".$ts."&apikey=".$apiKeyPublic."&hash=".$hash;

        $ch = curl_init();
        // configuration des options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        // exécution de la session
        $result = curl_exec($ch);
        // fermeture des ressources
        curl_close($ch);

        $result = json_decode($result, true);

        $detail = $result['data']['results'];

        dump($detail);

        $comic1 = $result['data']['results'][0]['comics']['items'][0]['name'];
        $comic2 = $result['data']['results'][0]['comics']['items'][1]['name'];
        $comic3 = $result['data']['results'][0]['comics']['items'][2]['name'];
//        dump($comic);

        return $this->render('@App/detailsHero.html.twig', [
            'details' => $detail,
            'comics1' => $comic1,
            'comics2' => $comic2,
            'comics3' => $comic3,
            ]);
    }

}