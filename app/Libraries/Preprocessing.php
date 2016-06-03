<?php
namespace herysepty\Libraries;
use herysepty\Libraries\Stemmer;
use DB;
// preprocesing yaitu tokenization, filtering, stopterm removal dan stemming.
// Processing
// Steaming,Stopterm, penghilangan tanda baca, pemisahan term

class Preprocessing extends Stemmer
{
	public function preprocesing($date,$total_tweet){
        $terms = array();
        $clear_tweet = "";
        $clear_tweets = array();
    	$Stemmer = new Stemmer();

        $date = date('D M d%Y',strtotime($date));
    	$tweets = DB::table('tweets')
                        // ->orderBy('id','desc')
                        ->limit($total_tweet)
                        ->where('date_tweet','like',$date)->get();
 		foreach ($tweets as $tweet) {
 			foreach ($this->parsing($tweet->tweet) as $term)
 			{ 
                $tokenizing = $this->tokenizing($term);
                if($tokenizing)
                {
                    if($this->stopword($tokenizing))
                    {
                        $data_term = $this->NAZIEF($tokenizing);
                        $clear_tweet .= $data_term." ";
                        if(strlen($data_term) != 0)
                        {
                            if(!array_key_exists($data_term, $terms))
                                $terms[$data_term] = 0;
                            $terms[$data_term] = $terms[$data_term] + 1;
                        }
                    }
                }
 			}
            $insert_clear_tweet = DB::table('clear_tweets')->insert(['id_tweet' => $tweet->id,'clear_tweet' => rtrim($clear_tweet,' ')]);
            // $clear_tweets[$tweet->id] = rtrim($clear_tweet,' ');
            $clear_tweet = "";
 		}
 		foreach ($terms as $key_term => $value_term) {
            DB::table('terms')->insert(['term'=>$key_term,'frequency'=>$value_term]);
        }
        return $terms;
    }
    public function tokenizing($term)
    {
    	// return $this->tokenizing = preg_replace('/[^a-z0-9]/i','',$tweet);
        if((substr($term, 0,4) == 'http') || (substr($term, 0,1) == '@'))
           return false;
        return preg_replace('/[^a-z]/i','', $this->caseFolding($term));
    }

    public function caseFolding($term)
    {
    	return strtolower($term);
    }

    public function parsing($tweet)
    {
    	return $parsing = explode(' ',$tweet);
    }
    public function stopword($term)
    {
        $stop_term = DB::table('stopwords')->where('stopword',$term)->count();
        if($stop_term < 1)
            return true;
        return false;
    }
}