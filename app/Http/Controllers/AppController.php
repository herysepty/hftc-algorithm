<?php

namespace herysepty\Http\Controllers;

use Illuminate\Http\Request;

use herysepty\Http\Requests;
use DB;
use herysepty\Libraries\Combinatorics;
use Illuminate\Support\Facades\Auth;
use herysepty\Libraries\Hftc;
use herysepty\Libraries\FMeasure;
use herysepty\Libraries\tfIdf;
use Abraham\TwitterOAuth\TwitterOAuth;

class AppController extends Controller
{
	private $variable = array();
	public function __construct()
    {
        ini_set('max_execution_time', 36000);
        ini_set('memory_limit',-1);
    }
    public static function alertMessages($type_messages,$title_messages,$messages)
    {
        if($type_messages == 'success')
        {
            return '<div class="callout callout-'.$type_messages.'">
                    <h4><span class="fa fa-check-square-o"></span> '.$title_messages.'</h4>
                    <p>'.$messages.'</p>
                </div>';
        }
        else if($type_messages == 'warning')
        {
            return '<div class="callout callout-'.$type_messages.'">
                    <h4><span class="fa fa-warning"></span> '.$title_messages.'</h4>
                    <p>'.$messages.'</p>
                </div>';
        }
        else
        {
            return '<div class="callout callout-'.$type_messages.'">
                    <h4><span class="fa fa-times-circle"></span> '.$title_messages.'</h4>
                    <p>'.$messages.'</p>
                </div>';
        }
    }
   #========================================================================================
   public function test()
   {
   	// $date = date('D M d%Y',strtotime('04/16/2016'));
   	// echo $date;
   	// $tweets = DB::table('tweets')->where('date_tweet','like',$date)->get();
   	// foreach ($tweets as $key => $value) {
   	// 	echo $h++.$value->username.' '.$value->date_tweet.'<br/>';
   	// }
    // $tmp = array();
    // $test = array(1,2,1,2,2,4,6,7,4);
    // for ($i=0; $i < 24 ; $i++) {
    //     foreach ($test as $key => $value) {
    //         if($i == $value){
    //             $tmp[$i][$key] = $value;
    //         }
    //     }
    // }
    // echo "<pre>";
    // print_r($tmp);
    // echo "</pre>";

    // $this->timeAgregation();

    
    // $tfIdf = new tfIdf();
   }

    public function timeAgregation()
    {
        $time_agregation = array();
        $tweet = DB::table('tweets')->limit(5000)->get();

            foreach ($tweet as $key => $value) 
            {
                for ($i=0; $i < 24 ; $i++) 
                {
                    if($i == date("G",strtotime($value->date_tweet)))
                    {   
                        $time_agregation['Jam: '.$i][$value->id] = json_decode(json_encode($value),true);
                    }
                }
            }
        echo "<pre>";
        print_r($time_agregation);
        echo "</pre>";
    }


    public function twitter()
    {
        $consumer_key = "YyiX1I2pgTKaMAI4UbKxkFCJ0";
        $consumer_secret = "Kxw9IrUjFHz5IcVFhiBPUmjr1FxAvwSt3zveo2oPKqro1PMUni";
        $access_token = "715720484491399169-BRLVVh1oqYsy7Hq3bf1pRkWTfqCIHLc";
        $access_token_secret = "66q4RVaIq8NPHcc01mEaXXJXqPmLvqUfdtcbjuUvnBTHx";
        $twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
        $h = 1;
        $date = '2016-05-23';
        $keywords = array('bencana banjir','longsor','puting beliung','gempa','gunung meletus');
        foreach ($keywords as $value_keyword)
        {
            echo "<h1>".$value_keyword."</h1>";
            $flag = 1;
            $max_id = 0;
            for ($i=0; $i <999; $i++) 
            { 
                // GET https://api.twitter.com/1.1/search/tweets.json?q=twitterapi
                // $tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q='.$value_keyword.'&result_type=recent&until='.$date.'&count=100&max_id='.$max_id);
                 $tweets = $twitter->get("search/tweets", ["q" => $value_keyword,"until"=>$date,"count"=>100,"max_id"=>$max_id,"result_type"=>"recent"]);
                if(!empty($tweets->statuses))
                {
                    foreach ($tweets->statuses as $tweet)
                    {
                        if(date('Y-m-d',strtotime($tweet->created_at)) == date('Y-m-d',strtotime($date)-86400))
                        {
                            echo $h++.'=>';       
                            echo $tweet->user->screen_name."<br/>";
                            echo $tweet->id_str.'<br/>';
                            echo $tweet->text."<br/>";
                            echo $tweet->created_at."<br/>";
                            $max_id = $tweet->id_str;
                            // $check_tweet = DB::table('tweets')->where('id_tweet' , $tweet->id_str)->count();
                            // if($check_tweet == 0)
                            // {
                            //      $insert = DB::table('tweets')->insert(['id_tweet' => $tweet->id_str,'username' => $tweet->user->screen_name,'tweet' => $tweet->text,'date_tweet' => $tweet->created_at,'date_get_tweet' => date("Y-m-d H:i:s")]);
                            // }
                        }
                        else
                        {
                            $flag = 0;
                        }
                    }
                }
                else
                {
                    break;
                }
                if($flag == 0)
                    break;
            }
        }
        // $total_tweet = DB::table('tweets')->where('tweet','Like','%%')
    }

#===================================================================================================
   private $_pointers = array();
   public function clusters($terms)
    {
    $clusters = array();
    $set = $terms;
    $set_size = count($set);
        for($subset_size=1; $subset_size <= $set_size; $subset_size++)
        {
            if ($subset_size == 1) 
            {
                 $terms_single = array_chunk($set, 1);
                 foreach ($terms_single as $key => $value) {
                  echo "<pre>";
                   print_r($value);
                   echo "</pre>";
                 }
            }
            else
            {
                $set_keys = array_keys($set);//Array ( [0] => 0 [1] => 1 [2] => 2 [3] => 3 )

                $this->_pointers = array_slice(array_keys($set_keys), 0, $subset_size);
                //Array ( [0] => 0 [1] => 1 )
                do
                {
                    $combinations = $this->getCombination($set);
                    echo "<pre>";
                    print_r($combinations);
                    echo "</pre>";
                }
                while($this->pointers($subset_size - 1, $set_size - 1));
                // $subset_size = 2;
                // $set_size = 4;
            }
        }
    }

    private function pointers($pointer_number, $limit)
    {
        if ($pointer_number < 0) {
            return false;
        }
        if ($this->_pointers[$pointer_number] < $limit) {
            $this->_pointers[$pointer_number]++;
            // print_r($this->_pointers); //Array ( [0] => 0 [1] => 2 )
            return true;
        } else {
            if ($this->pointers($pointer_number - 1, $limit - 1)) {
                $this->_pointers[$pointer_number] = $this->_pointers[$pointer_number - 1] + 1;
                print_r($this->_pointers);
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
        // print_r($this->_pointers); Array ( [0] => 0 [1] => 2 )Array ( [0] => 0 [1] => 3 )
        foreach ($this->_pointers as $pointer)
            $combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
        return $combination;
    }
    #Math Combinatorics

#=====================================================================================
   public function create()
   {
      $total=0;
    for ($i=0; $i < 130000; $i++) {
        for ($j=0; $j <800 ; $j++) { 
               $total = $total + 1;
        }
    }
    echo $total;
    echo '<br/>';
    $set = array('test','satu','dua','tiga');
    $subset_size = 1;
    $set_keys = array_keys($set);
    $pointers = array_slice(array_keys($set_keys), 0, $subset_size);
    print_r($pointers);
    echo "<br>";
    print_r(array_keys($set_keys));
    echo "<br/>";
    // $combination[$set_keys['3']] = $set[$set_keys['3']];
    // print_r($combination);
    $pointerss = Array ( 0 => 0 ,1 => 1 );
    foreach ($pointerss as $pointer)
            $combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
    print_r($combination);
    echo "<br/>=========================Combination=====================<br/>";
    $this->clusters($set);  
   }



























#====================ngrams===========================#

  public function countClearTweets()
    {
        $display = "";
        $results = DB::table('tweets')->orderBy('id','desc')->first();
        // return number_format($results);
        return substr($results->tweet,0,99).'...';
    }


#====================Chating abaikan===========================#
    public function chat_add(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $post = $request->all();
        if (Auth::check())
        {
            $n = Auth::user()->name;
        }
        else
        {
            $n = 'Anonymouse';
        }
        
        $insert = DB::table('chats')->insert(['name'=>$n,'chat' => $post['chat'],'date' => date('Y-m-d H:i:s')]);
        return $post['chat'];
    }

    public function chat_show()
    {
        $display = "";
        $results = DB::table('chats')->orderBy('Id','desc')->get();

        $tanggal = array('senin','selasa','Rabu','kamis','Jumat','Sabtu','Minggu');
        foreach ($results as $key => $value) {
            if($value->name == "Hery Septyadi") 
            {
                    $d = 'left';
                    $f = 'active';
            }
            else
            {
                $d = 'right';
                $f = '';
            }
            $display .= '<div class="'.$d.'">
            <div class="author-name">
                '.$value->name.'
            </div>
            <div class="chat-message '.$f.'">
               '.$value->chat.'<br/><br/>
               <small class="chat-date">
                '.$tanggal[date('w')].', '.date("d M",strtotime($value->date)).' - '.date("H:i",strtotime($value->date)).'
            </small>
            </div>

        </div>';
        }
        return $display;
    }
}
