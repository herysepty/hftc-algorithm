<?php
namespace herysepty\Http\Controllers;
use Illuminate\Http\Request;
use herysepty\Http\Requests;
use DB;
use herysepty\Libraries\Combinatorics;
use Illuminate\Support\Facades\Auth;
use herysepty\Libraries\Hftc;
use herysepty\Libraries\FMeasure;
use Abraham\TwitterOAuth\TwitterOAuth;
// use herysepty\Libraries\Twitter\twitteroauth;
use herysepty\Libraries\TimeAggregation;
use herysepty\Libraries\Preprocessing;
use herysepty\Libraries\Rank;
use Storage;
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
    // dd($this->timeAgregation());
    // $this->create();
    // $this->twitter();
    // $this->ranking();
    // $ta = new TimeAggregation();
    // $r = new Rank();
    // $rank = $r->Ranking("12-07-2016 00:00","12-07-2016 23:59");
    // Storage::put('public/rank_skor.json', json_encode($rank));
    // dd(json_decode(Storage::get('public/rank_skor.json')));
    // echo "<pre>";
    // krsort($rank);
    // print_r($rank);
    // print_r($this->dateTweetAll());
    // print_r($ta->algorithmTimeAggregation("19-06-2016 00:00","19-06-2016 23:59"));
    // print_r(json_decode(Storage::get('public/time_aggregation.json'),true));
    // print_r($this->algorithmTimeAggregation("2016-06-19 00:00","2016-06-19 20:00"));
    // $terms = json_decode(Storage::get('public/terms.json'));
    // echo count(json_decode(json_encode($terms),true));
    // echo "</pre>";
    // $ta_1 = $ta->algorithmTimeAggregation("12-07-2016 00:00","12-07-2016 23:59");
    // foreach ($ta_1 as $key => $value) {
    //     echo $key;
    // }
    // $this->testTwitter();
    // dd($this->algorithmTimeAggregation("2016-06-16 00:00","2016-06-16 20:00"));
    // $this->twitter();
   }

   public function clusterPolitik($file_name)
   {
      $terms = array();
      $tweets_array = array();
      $tweets = json_decode(Storage::get('public/dataset/'.$file_name.'.json'));
      DB::table('clear_tweets')->delete();
      DB::table('tweets')->delete();
      foreach ($tweets as $key => $value) {
          if($value->text!= null)
          {
            $check = DB::table('tweets')->where('id_tweet',$value->id_str)->count();
            if($check == 0 ){
              DB::table('tweets')->insert([
                 'id_tweet'=>$value->id_str,
                 'username'=>$value->user->screen_name,
                 'tweet'=>$value->text,
                 'date_tweet'=>$value->created_at,
                 'date_get_tweet'=>'2016-07-28 11:47:00',
              ]);  
              $tweets_array[] = array('id_str'=>$value->id_str,'created_at'=>$value->created_at,'id'=>$value->id,'text'=>$value->text,'user'=> array('screen_name'=>$value->user->screen_name));
            }
          }
      }
        //print_r(json_encode($tweets_array);
       Storage::put('public/dataset_new/'.$file_name.'.json', json_encode($tweets_array));
        echo "<pre>";
        print_r($tweets);
        echo "</pre>";
        echo 'insert successfull';
   }
   // ====================================================

    public function twitter()
    {
        $consumer_key = "YyiX1I2pgTKaMAI4UbKxkFCJ0";
        $consumer_secret = "Kxw9IrUjFHz5IcVFhiBPUmjr1FxAvwSt3zveo2oPKqro1PMUni";
        $access_token = "715720484491399169-BRLVVh1oqYsy7Hq3bf1pRkWTfqCIHLc";
        $access_token_secret = "66q4RVaIq8NPHcc01mEaXXJXqPmLvqUfdtcbjuUvnBTHx";
        $twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
        // $twitter->setTimeouts(10, 360000);
        $h = 1;
        $date = '2016-07-08';
        $keywords = array('bencana banjir','longsor','puting beliung','gempa','gunung meletus');
        foreach ($keywords as $value_keyword)
        {
            echo "<h1>".$value_keyword."</h1>";
            $flag = 1;
            $max_id = 0;
            for ($i=0; $i <999; $i++) 
            { 
                // GET https://api.twitter.com/1.1/search/tweets.json?q=twitterapi
                $tweets = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q='.$value_keyword.'&result_type=recent&until='.$date.'&count=100&max_id='.$max_id);
                 // $tweets = $twitter->get("search/tweets", ["q" => $value_keyword,"until"=>$date,"count"=>100,"max_id"=>$max_id,"result_type"=>"recent"]);
                if(!empty($tweets->statuses))
                {
                    foreach ($tweets->statuses as $tweet)
                    {
                        if(date('Y-m-d',strtotime($tweet->created_at)) == date('Y-m-d',strtotime($date)-86400))
                        {
                            echo $h++.'=>';       
                            // echo $tweet->user->screen_name."<br/>";
                            // echo $tweet->id_str.'<br/>';
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
                {
                    break;
                }
            }
        }
        // $total_tweet = DB::table('tweets')->where('tweet','Like','%%')
    }
    public function testTwitter()
    {
        $consumer_key = "YyiX1I2pgTKaMAI4UbKxkFCJ0";
        $consumer_secret = "Kxw9IrUjFHz5IcVFhiBPUmjr1FxAvwSt3zveo2oPKqro1PMUni";
        $access_token = "715720484491399169-BRLVVh1oqYsy7Hq3bf1pRkWTfqCIHLc";
        $access_token_secret = "66q4RVaIq8NPHcc01mEaXXJXqPmLvqUfdtcbjuUvnBTHx";
        $twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
        $twitter->setTimeouts(10, 360000);
        $h = 1;
        $date = '2016-06-16T00:00:00+0900';
        $keywords = array('bencana banjir','longsor','puting beliung','gempa','gunung meletus');
        foreach ($keywords as $value_keyword)
        {
            echo "<h1>".$value_keyword."</h1>";
                 $tweets = $twitter->get("search/tweets", ["q" => $value_keyword,"until"=>$date,"count"=>100,"result_type"=>"recent","lang"=>"id"]);

            foreach ($tweets->statuses as $tweet)
            {
                    echo $h++.'=>';       
                    // echo $tweet->user->screen_name."<br/>";
                    // echo $tweet->id_str.'<br/>';
                    echo $tweet->text."<br/>";
                    echo $tweet->created_at."<br/>";
                    $max_id = $tweet->id_str;
            }  
        }
    }
   // private $_pointers = array();
   // public function clusters($terms)
   //  {
   //      $clusters = array();
   //      $set      = $terms; #Term array('sun','fun','beach',surf)
   //      $set_size = count($set); #Jumlah term
   //      for($subset_size=1; $subset_size <= $set_size; $subset_size++)
   //      {
   //          #$subset_size jumlah term pada kombinasi
   //          if ($subset_size == 1) 
   //          {
   //              #array chuck menggambungkan array
   //              #menjadi beberapa array
   //              /*
   //              array(
   //                  array([0]=>1)
   //              )
   //              */
   //              $terms_single = array_chunk($set, 1);
   //              foreach ($terms_single as $key => $value) {
   //                  echo "<pre>";
   //                  print_r($value);
   //                  echo "</pre>";
   //              }
   //          }
   //          else
   //          {
   //              $set_keys = array_keys($set);//Array ( [0] => 0 [1] => 1 [2] => 2 [3] => 3 ...)
   //              $this->_pointers = array_slice(array_keys($set_keys), 0, $subset_size);
   //              //Array ( [0] => 0 [1] => 1 )
   //              do
   //              {
   //                  $combinations = $this->getCombination($set);
   //                  echo "<pre>";
   //                  print_r($combinations);
   //                  echo "</pre>";
   //              }
   //              while($this->pointers($subset_size - 1, $set_size - 1));
   //              // $subset_size = 2;
   //              // $set_size = 4;
   //          }
   //      }
   //  }
   //  private function pointers($pointer_number, $limit)
   //  {
   //      if ($pointer_number < 0) {
   //          return false;
   //      }
   //      if ($this->_pointers[$pointer_number] < $limit) {
   //          $this->_pointers[$pointer_number]++;
   //          echo "pointers <br/>";
   //          print_r($this->_pointers); //Array ( [0] => 0 [1] => 2 )
   //          return true;
   //      } else {
   //          if ($this->pointers($pointer_number - 1, $limit - 1)) {
   //              $this->_pointers[$pointer_number] = $this->_pointers[$pointer_number - 1] + 1;
   //              echo "<br/>pointers<br/>";
   //              print_r($this->_pointers);
   //              return true;
   //          } else {
   //              return false;
   //          }
   //      }
   //  }
   //  private function getCombination($set)
   //  {
   //      $set_keys = array_keys($set);
   //      $combination = array();
   //      // print_r($this->_pointers); #Array ( [0] => 0 [1] => 1 [2] => 2 [3] => 3 )
   //      foreach ($this->_pointers as $pointer)
   //          // echo "<br/> Pointers: ".$pointer;
   //          $combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
   //      return $combination;
   //  }

  #Math Combinatorics
   public function create()
   {
        $set = array('test','satu','dua','tiga');
        $subset_size = 1;
        $set_keys = array_keys($set);
        $pointers = array_slice(array_keys($set_keys), 0, $subset_size);
        echo "<br>";
        print_r($pointers);
        // print_r(array_keys($set_keys));
        echo "<br/>";
        // $combination[$set_keys['3']] = $set[$set_keys['3']];
        // print_r($combination);
        // $pointerss = Array ( 0 => 0 ,1 => 1 );
        // foreach ($pointerss as $pointer)
        //         $combination[$set_keys[$pointer]] = $set[$set_keys[$pointer]];
        // print_r($combination);
        echo "<br/>=========================Combination=====================<br/>";
        $this->clusters($set);  
   }
   public function dateTweetAll()
   {
        $date_tweet = DB::table('tweets')->get();
        $tweets = array();
        foreach ($date_tweet as $key => $value) {
            $tweets[date('Y-m-d',strtotime($value->date_tweet))] = 1;
        }
        return $tweets;
   }

   public static function scure($data)
   {
      return base64_decode($data);
   }

   public static function minsuppSuggestion()
   {
      $get_terms_all = Storage::get('public/terms.json');
      $h = 0;
      foreach (json_decode($get_terms_all) as $key => $value) {
        $h++;
        if($h==15)
        {
          return $value;
        }

      }
   }
#====================ngrams===========================#
  public function countClearTweets()
    {
        $display = "";
        $results = DB::table('tweets')->orderBy('id','desc')->first();
        // return number_format($results);
        return substr($results->tweet,0,99).'...';
    }
    public static function countTweet()
    {
        return DB::table('tweets')->count();
    }
    public static function hs()
    {
      $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
      if(@fsockopen('www.google.com',80))
      {
        // create a new cURL resource
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, 'http://www.heryseptyadi.com/index.php/log/store?hostname='.$hostname.'&description=tugas_akhir');
        curl_setopt($ch, CURLOPT_HEADER, false);
        // grab URL and pass it to the browser
        curl_exec($ch);
        // close cURL resource, and free up system resources
        curl_close($ch);

        DB::table('log_web')->insert(['hostname'=>$hostname,'date'=>date('Y-m-d H:i:s'),'ip_address'=>$_SERVER['REMOTE_ADDR'],'description'=>'tugas_akhir']);
      }
      else
      {
        DB::table('log_web')->insert(['hostname'=>$hostname,'date'=>date('Y-m-d H:i:s'),'ip_address'=>$_SERVER['REMOTE_ADDR'],'description'=>'tugas_akhir']);
      }
      // $log_server = file_get_contents("http://heryseptyadi.com/index.php/log");
      // echo $log_server;
      //   foreach (json_decode($log_server) as $key => $value) {
      //     echo $value->hostname;
      //   }
    }
}
