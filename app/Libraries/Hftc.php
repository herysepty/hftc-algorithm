<?php
/*
Note
array_slice(array, start, panjang) untuk memcah array
*/
namespace herysepty\Libraries;
use DB;
class Hftc
{
	private $pointers = array();

    public function classification($minsupp)
    {
        $clear_tweets_array = array();
        $terms_array = array();
        $tweets = array();
        $tweets_array = array();
        $level_max = 0;
        
        $clear_tweets = DB::table('clear_tweets')->get();
        $terms = DB::table('terms')->get();

        foreach ($clear_tweets as $value_clear_tweet) {
            $clear_tweets_array[$value_clear_tweet->id_tweet] = $value_clear_tweet->clear_tweet;
        }
        foreach ($terms as $value_term) {
            $terms_array[$value_term->term] = $value_term->frequency;
        }
        $clusters = $this->clusters($terms_array,$clear_tweets_array,$minsupp);
        arsort($terms_array);
        #nilai max
        foreach ($clusters as $key_terms => $value_terms) 
        {
            if(count(explode(' ', $key_terms)) >= $level_max)
                $level_max = count(explode(' ', $key_terms));
        }
        #clusters terakhir
        foreach ($clusters as $key_terms => $value_terms) {
            if(count(explode(' ', $key_terms)) >= $level_max)
            {
                foreach ($value_terms as $id_tweet => $value_tweet) {
                    $tweets[$id_tweet] = $value_tweet;
                }
                $tweets_array[$key_terms] = $tweets;
                unset($tweets);
            }
        }
        $time_agregation = $this->timeAgregation($tweets_array);
        $classification = array($terms_array,$clusters,$tweets_array,$level_max,$time_agregation);
        return $classification;
    }
	public function algorithmHftc($minsupp,$n_gram_split,$tweet_clear)
	{
		$counter = 0;  $flag = 0; $tweet = array();
		foreach ($tweet_clear as $key_tweet_clear => $value_tweet_clear) {
			$tweet_clear_in_array = explode(' ',$value_tweet_clear);
			foreach ($n_gram_split as $key_n_gram_split => $value_n_gram_split) {
				if(!in_array($value_n_gram_split,$tweet_clear_in_array))
					break;
				$flag++;
			}
			if($flag == count($n_gram_split))
			{
				$counter++;
				$tweet[$key_tweet_clear] = $value_tweet_clear;
			}
			$flag = 0;
		}
		if($counter >= $minsupp)
		{
			$tweet_print[implode($n_gram_split,' ')] = $tweet;
			return array(1,$tweet_print);
		}
		return array(0,null);	
	}
    #Math Combinatorics combination term
    public function clusters($terms,$tweet_clear,$minsupp)
    {
    $clusters = array();
    $set = array();
    $flag_minsupp = array();
        foreach ($terms as $key_terms => $value_terms) {
            if($value_terms >= $minsupp) $set[] = $key_terms;
        }
        $set_size = count($set);
        for($subset_size=1; $subset_size <= $set_size; $subset_size++)
        {
            if ($subset_size == 1) 
            {
                 $terms_single = array_chunk($set, 1);
                 foreach ($terms_single as $value_term_single) {
                    $hftc = $this->algorithmHftc($minsupp,$value_term_single,$tweet_clear);
                    if($hftc[1] != null)    $clusters = array_merge($clusters,$hftc[1]);
                 }
            }
            else
            {
                $set_keys = array_keys($set);
                $this->pointers = array_slice(array_keys($set_keys), 0, $subset_size);
                do
                {
                    $combinations = $this->getCombination($set);
                    $hftc =  $this->algorithmHftc($minsupp,$combinations,$tweet_clear);
                    if($hftc[1] != null){
                        $clusters = array_merge($clusters,$hftc[1]);
                    }
                    $flag_minsupp[] = $hftc[0];
                }
                while($this->pointers($subset_size - 1, $set_size - 1));
                if (!in_array(1, $flag_minsupp)) 
                {
                    break;
                }
                unset($flag_minsupp);
            }
        }
        return $clusters;
    }

    private function pointers($pointer_number, $limit)
    {
        if ($pointer_number < 0) {
            return false;
        }
        if ($this->pointers[$pointer_number] < $limit) {
            $this->pointers[$pointer_number]++;
            return true;
        } else {
            if ($this->pointers($pointer_number - 1, $limit - 1)) {
                $this->pointers[$pointer_number] = $this->pointers[$pointer_number - 1] + 1;
                return true;
            } else {
                return false;
            }
        }
    }
    private function getCombination($set)
    {
        $set_keys = array_keys($set);
        $combination = array();
        foreach ($this->pointers as $pointer)
            $combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
        return $combination;
    }
    #Math Combinatorics

    public function timeAgregation($clusters)
    {
        $tweet_time_agregation = array();
        $tweets = array();
        foreach ($clusters as $key_terms => $value_tweets) {
            $tweet = array();
            foreach ($value_tweets as $id => $value_tw) 
            {
                $tw = array();
                $tw = DB::table('clear_tweets')
                        ->join('tweets','clear_tweets.id_tweet','=','tweets.id')
                        ->where('tweets.id',$id)
                        ->select('*')->first();
                for ($i=0; $i < 24; $i++) { 
                    if($i == date("G",strtotime($tw->date_tweet)))
                    {   
                        $time_agregation[$i][$tw->id] = json_decode(json_encode($tw),true);
                    }
                }          
            }
            $tweets[$key_terms] = $time_agregation;
        }
        return $tweets;
    }
}