<?php

class BuyerController{

    private $sessions;
    private $token;
    private $curl;

    private $frige;
    private $total;
    private $notifyMin;

    public function __construct(Session $sessions, string $token, Curl $curl){
        $this->sessions = $sessions;
        $this->token    = $token;
        $this->curl     = $curl;
    
        self::isLoggedIn();

        $this->fridge    = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/beers/fridge");
        $this->total     = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/beers/total");
        $this->notifyMin = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/settings/read/notifymin");
    }

    /**
     * Perform a check, that the user is logged in
     * @return   Dies and redirects if false
     */
    private function isLoggedIn(){
        if (!$this->sessions->isset("loggedId")){
            header("location:index.php");
            die;
        }
    }
    
    /**
     * This method will update the total amount of beers stock-piled. 
     * For once the purchase manager have bought new beers.
     * @return string Success/fail message
     */
    public function updateTotal(){
        $value  = $_POST['value'];
        $result = $this->curl->curl("http://easj-beerinator.azurewebsites.net/Service1.svc/beers/update/total/".$this->token."/".$value);
        
        if ($result == "-1")
            $this->sessions->set('error', 'Noget gik galt. Prøv igen.');
        else
            $this->sessions->set('message', 'Lager status opdateret.');

        header("location:?buyer");
    }

    /**
     * Returns the current total amount of beer on stock for the view.
     * @return int  The amount of beers on stock
     */
    public function getTotal(){
        return $this->total;
    }

    /**
     * Returns the minimum value of beers allowed on stock before notification
     * @return int The notifyMin value from the database (stock beers before notification)
     */
    public function getNotifyMin(){
        return $this->notifyMin;
    }

    /**
     * This is our Web-Scraper function. It will look for offers
     * in the nearby supermarkets for display in the view.
     * @return array ['product', 'price', 'volPrice', 'store', 'image']
     */
    public function webScrape(){
        $url = file_get_contents('https://minetilbud.dk/Tilbudssoegning?qw=øl');
        if(!empty($url)){

            # Limit search to these supermarket (available in Roskilde)
            $supermarkets = array('Fakta', 'Føtex', 'Netto', 'Aldi', 'Irma', 'SuperBrugsen');

            # Get the HTML
            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($url);
            libxml_clear_errors();
            
            # Target data
            $xpath       = new DOMXPath($doc);
            $productName = $xpath->query("//a[@class='product-item__title']");
            $volumePrice = $xpath->query("//span[@class='product-item-price__volumeprice']");
            $price       = $xpath->query("//span[@class='product-item-price__price']");
            $shop        = $xpath->query("//a[@class='product-item__shop']");
            $image       = $xpath->query("//img[@class='product-item__imagewrap__image']");
            
            # If any results, sort em 
            if ($productName->length > 0){
                $values = array();
                for ($i=0; $i < $productName->length; $i++) { 

                    # Not all products returns with a volume price,
                    # we check against that here, or just return null
                    $volPrice = null;
                    if (!empty($volumePrice[$i]->nodeValue))
                        $volPrice = substr($volumePrice[$i]->nodeValue, 0, -9);

                    # Store and format the data from minetilbud (price, product name, store and image of product)
                    $img          = $image[$i]->getAttribute('data-original');

                    $name         = $productName[$i]->nodeValue;
                    $name         = utf8_decode($name);

                    $store        = $shop[$i]->nodeValue;
                    $store        = utf8_decode($store);
                    $store        = trim($store);
                    $store        = strtolower($store);
                    
                    $productPrice = $price[$i]->nodeValue;
                    $productPrice = trim($productPrice);
                    $productPrice = preg_replace('/\s/', '', $productPrice);
                    $productPrice = preg_replace('[,]', '.', $productPrice);
                    
                    $volPrice     = trim($volPrice);
                    $volPrice     = preg_replace('/\s/', '', $volPrice);
                    $volPrice     = preg_replace('[,]', '.', $volPrice);

                    # Clean the price result (some comes with ,- )
                    if (substr($productPrice, -1) == '-')
                        $productPrice = substr($productPrice, 0, -2);

                    if (substr($volPrice, -1) == '-')
                        $volPrice = substr($volPrice, 0, -2);

                    $productPrice .= ',-';
                    if ($volPrice != null)
                        $volPrice .= ',-';

                    # Check if it's from a supermarket nearby
                    $supermarkets = array_map('strtolower', $supermarkets);
                    if (!in_array(strtolower($store), $supermarkets))
                        continue;

                    $values[] = array('product' => $name, 'price' => $productPrice, 'volumePrice' => $volPrice, 'store' => ucfirst($store), 'image' => $img);
                }

                return  $values;
            }
        }
    }
}