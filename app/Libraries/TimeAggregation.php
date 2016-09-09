<?php

namespace herysepty\Libraries;
use DB;

class TimeAggregation
{
     public function algorithmTimeAggregation($start_time,$end_time)
    {
        $tweets_aggregation = array();
        $length = 1800; //30 menit
        $count_slot_time = (strtotime($end_time) - strtotime($start_time))/$length;

        $tweets = DB::table('clear_tweets')
                ->join('tweets','clear_tweets.id_tweet','=','tweets.id')
                ->select('*')->orderBy('date_tweet',"asc")->get();

        foreach ($tweets as $value_tweets) 
        {
            $start_time_tweet = strtotime($start_time);
            $last_time =strtotime($start_time) + $length;
            for ($i=0; $i < ceil($count_slot_time); $i++) 
            { 
                if((strtotime($value_tweets->date_tweet)  > $start_time_tweet) && ($last_time > strtotime($value_tweets->date_tweet)))
                {   
                    $tweets_aggregation[date("H:i",$start_time_tweet)."-".date("H:i",$last_time)][$value_tweets->id] = json_decode(json_encode($value_tweets),true);
                } 
                $start_time_tweet = $start_time_tweet + $length;
                $last_time = $last_time + $length;
            }        
        }
        return $tweets_aggregation;
    }
}

