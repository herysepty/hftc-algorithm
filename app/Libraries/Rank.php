<?php 
namespace herysepty\Libraries;
use herysepty\Libraries\TimeAggregation;
use DB;
use Storage;
/* 
	create by hs
	// $terms = array('sun','fun');
	$slot_time = array(array('sun fun', 'surf sun', 'beach sun'),array('sun fun','beach sun fun'),array('sun fun','beach sun fun'));
	t=time slots $dfidf = dfi+1/(log(dfj/t)+1)*1 atau $dfidf = ((1+1)/(log10((2/2)+1)+1))*1;
*/
class Rank 
{
    private $slot_time_current ;
	private $term = array();
	public function ranking($start_time,$end_time)
   {
        $skor = array();
        $tmp_skor = array();
        $terms = Storage::get('public/terms.json');
        foreach (json_decode($terms) as $key => $value) {
            $this->terms[] = $key;
            if(count($this->terms) == 5)
            {
                break;
            }
        }
        $slot_time = $this->slotTime($start_time,$end_time);
        for ($e = 0; $e < count($slot_time); $e++) 
        {
            foreach ($this->terms as $t) {
                $dfi = 0;$dfj = 0;
                $c=$e;
        		$dfi = $this->frequencyTerm($t,$slot_time[$e]);
                $dfj = $this->frequencyTerm($t,$slot_time[$c++]);
                $skor[$t] =  $this->algorithmRank($dfi,$dfj,count($slot_time),$this->NER($t));
            }
            arsort($skor);
            $tmp_skor[$e][$this->slot_time_current] = $skor;
        }
        ksort($tmp_skor);
        return $tmp_skor;
   }

   public function frequencyTerm($term,$slot_time)
    {
    	$h=1;
    	foreach ($slot_time as $key_slot_time => $value_slot_time) {
    		foreach ($value_slot_time as $value_tweet_term) {
	    		foreach(explode(" ",$value_tweet_term) as $value_term)
	    		{
	    			if($term==$value_term)
	    				$h++;
	    		}
	    	}
            $this->slot_time_current = $key_slot_time;
        }
	    return $h++;
    }

    public function algorithmRank($term1,$term2,$total_slot,$ner)
    {
        $dfidf = (($term1+1)/(log10(($term2/$total_slot)+1)+1))*$ner;
        return round($dfidf,2);
    }
    public function slotTime($start_time,$end_time)
    {
        $p = new TimeAggregation();
        $tweets = array();
        foreach ($p->algorithmTimeAggregation($start_time,$end_time) as $key => $value) 
        {
            $tweet = array();
            foreach ($value as $v) {
                $tweet[] = $v['clear_tweet'];
            }
            $tweets[][$key] = $tweet;
        }
        return $tweets;
    }






























    
    public function NER($term)
    {
        $organisasi = ['bnpb','sekolah'];
        $location = ['jakarta','bandung','surabaya','sulawesi','papua'];
        $kejadian = ['banjir','gempa','puting','beliung','abrasi','pasang','meletus'];
        if(in_array($term,$organisasi))
            return 1.5;
        else if(in_array($term,$location))
            return 1.5;
        else if(in_array($term,$kejadian))
            return 1.5;
        else
            return 1;
    }
}