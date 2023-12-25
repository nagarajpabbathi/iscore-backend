<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request as HTTPRequest;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request as GuzzleRequest;



class APIController extends Controller
{
    public function live(HTTPRequest $request){

        if (Cache::has("live_screen")) {
            return Cache::get("live_screen");
        }
        else{
            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');

            //        $url = 'https://rest.entitysport.com/v2/matches/?status=3&token='. $apiToken;
            $url = 'https://isportindia.com/api/live-score&iscore123';
            $guzzleRequest = new GuzzleRequest('GET', $url);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $response = json_decode($res->getBody(), true);

            if (isset($response['live'])) {
                // Group events by "league_name"
                $grouped_json = [];
                foreach ($response['live'] as $event) {
                    $league_name = $event['competition']['title'];
                    if (!isset($grouped_json[$league_name])) {
                        $grouped_json[$league_name] = [
                            'match_id' => $event['match_id'],
                            'league_name' => $league_name,
                            'matches' => [],
                        ];
                    }
                    $grouped_json[$league_name]['matches'][] = $event;
                }

                $result = array_values($grouped_json);
//                Cache::put("live_screen", $result, 30);
                return json_encode($result, JSON_PRETTY_PRINT);
            } else {
                return json_encode("There is no live match", JSON_PRETTY_PRINT);
            }
        }
    }

    public function foryou(HTTPRequest $request){

        if(Cache::has("foryou_screen")){
            return Cache::get("foryou_screen");
        }
        else {

            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches?date=' . $request['fromDate'] . '_' . $request['toDate'] . '&paged=1&per_page=100&token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $response = json_decode($res->getBody(), true);

            if (isset($response['response']['items'])) {
                // Group items by competition title
                $grouped_items = [];
                $client = new Client();
                $promises = [];

                foreach ($response['response']['items'] as $item) {
                    $competition_title = $item['competition']['title'];
                    $competition_id = $item['competition']['cid'];
                    $match_id = $item['match_id'];

                    if (!isset($grouped_items[$competition_title])) {
                        $grouped_items[$competition_title] = [
                            'competition_id' => $competition_id,
                            'competition_title' => $competition_title,
                            'competition_type' => $item['competition']['type'],
                            'matches' => [],
                        ];
                    }

                    // Check if player data is already set
                    if (!isset($item['playersData'])) {
                        $url = 'https://rest.entitysport.com/v2/competitions/' . $competition_id . '/squads/' . $match_id . '?token=' . $apiToken;

                        $promises[] = $client->getAsync($url)
                            ->then(
                                function ($res) use ($item, &$grouped_items, $competition_title) {

                                    $playerResponse = json_decode($res->getBody(), true);
                                    $item['playersData'] = $playerResponse['response']['squads'];
                                    //                                dd($item['playersData']);

                                    $grouped_items[$competition_title]['matches'][] = $item;
                                }
                            );
                    } else {
                        $grouped_items[$competition_title]['matches'][] = $item;
                    }
                }

                // Wait for all asynchronous requests to complete
                Promise\settle($promises)->wait();

                $grouped_items = array_values($grouped_items);

                Cache::put("foryou_screen", $grouped_items, 180);
                return json_encode($grouped_items, JSON_PRETTY_PRINT);
            } else {
                return json_encode(['error' => 'Invalid JSON response or missing "items" key'], JSON_PRETTY_PRINT);
            }
        }
    }

    public function upcoming(HTTPRequest $request){

        if(Cache::has("upcoming_screen")){
            return Cache::get("upcoming_screen");
        }
        else {

            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
//        $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/?status=1&token='.$apiToken.'&pre_squad=true&per_page=50');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/?status=2&token=' . $apiToken . '&pre_squad=true&per_page=50&data=2022-01-01_2022-04-31');
            $res = $client->sendAsync($guzzleRequest)->wait();
            $response = json_decode($res->getBody(), true);

            if (isset($response['response']['items'])) {
                $grouped_items = [];
                foreach ($response['response']['items'] as $item) {
                    if (isset($item['date_start_ist'])) {
                        $date_start_ist = $item['date_start_ist'];
                        if (!isset($grouped_items[$date_start_ist])) {
                            $grouped_items[$date_start_ist] = [];
                        }
                        $grouped_items[$date_start_ist][] = $item;
                    }
                }
                Cache::put("upcoming_screen", $grouped_items, 180);
                return json_encode($grouped_items, JSON_PRETTY_PRINT);
            } else {
                return json_encode(['error' => 'Invalid JSON response or missing "items" key'], JSON_PRETTY_PRINT);
            }
        }
    }

    public function finished(HTTPRequest $request){

        if(Cache::has("finished_screen")){
            return Cache::get("finished_screen");
        }
        else {

            $currentDate = Carbon::today()->toDateString();
            $sevenDaysAgo = Carbon::now()->subDays(7)->toDateString();

            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches?status=2&date=2022-01-01_2022-12-01&paged=1&per_page=50&token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $response = json_decode($res->getBody(), true);

            if (isset($response['response']['items'])) {
                // Group items by date
                $grouped_items = [];
                foreach ($response['response']['items'] as $item) {
                    if (isset($item['date_start_ist'])) {
                        $date_start_ist = $item['date_start_ist'];

                        if (!isset($grouped_items[$date_start_ist])) {
                            $grouped_items[$date_start_ist] = [];
                        }
                        $grouped_items[$date_start_ist][] = $item;
                    }
                }

//                $grouped_items2 = array_values($grouped_items);
                Cache::put("finished_screen", $grouped_items, 180);
                return json_encode($grouped_items, JSON_PRETTY_PRINT);
            }
            else {
                return json_encode([
                    'status' => $response['status'],
                    'response' => $response['response']
                ], JSON_PRETTY_PRINT);
            }
        }
    }

    public function infoPage(HTTPRequest $request){

        if(Cache::has("info_page_".$request['match_id'])){
           return Cache::pull("info_page_".$request['match_id']);
//           return Cache::get("info_page_".$request['match_id']);
        }
        else {

            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/' . $request['match_id'] . '/info?token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $response = json_decode($res->getBody(), true);

            if (isset($response['response'])) {
                $venueID = $response['response']['venue']['venue_id'];
                $competition_id = $response['response']['competition']['cid'];
                $teamId1 = $response['response']['teama']['team_id'];
                $teamId2 = $response['response']['teamb']['team_id'];

                $competition_team1 = null;
                $competition_team2 = null;

                $guzzleRequest3 = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/teams/'.$teamId1.'/matches?status=2&per_page=50&paged=1&token='. $apiToken);
                $res = $client->sendAsync($guzzleRequest3)->wait();
                $team1_response = json_decode($res->getBody(), true);
                $team1 = $team1_response['response']['items'];
                $team1Venues = array_filter($team1, function($match) use ($venueID) {
                    return $match['venue']['venue_id'] == $venueID;
                });
                $team1_finalVenue = array_values($team1Venues);


                $guzzleRequest4 = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/teams/'.$teamId2.'/matches?status=2&per_page=50&paged=1&token='. $apiToken);
                $res = $client->sendAsync($guzzleRequest4)->wait();
                $team2_response = json_decode($res->getBody(), true);
                $team2 = $team2_response['response']['items'];
                $team2Venues = array_filter($team2, function($match) use ($venueID) {
                    return $match['venue']['venue_id'] == $venueID;
                });
                $team2_finalVenue = array_values($team2Venues);


                $finalVenueArray = [];

                foreach ($team1_finalVenue as $match1) {
                    $matchId1 = $match1['match_id'];

                    // Find the matching element in the second array (e.g., $team2_finalVenue)
                    $matchingMatch2 = null;
                    foreach ($team2_finalVenue as $match2) {
                        if ($match2['match_id'] == $matchId1) {
                            $matchingMatch2 = $match2;
                            break;
                        }
                    }

                    // If a match is found, add it to the final array
                    if ($matchingMatch2 !== null) {
                        $finalVenueArray[] = [
                            'team1_match' => $match1,
                            'team2_match' => $matchingMatch2,
                        ];
                    }
                }
//                return $finalVenueArray;
                // Make the second request with the extracted Competition Id
                $guzzleRequest2 = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/competitions/' . $competition_id . '/standings/?token=' . $apiToken);
                $res = $client->sendAsync($guzzleRequest2)->wait();
                $standing_response = json_decode($res->getBody(), true);

                $teamsData = $standing_response['response']['standings'][0]['standings'];
                // To filter the teams according to the team_id
                foreach ($teamsData as $teamData) {

                    if ($teamData['team_id'] == $teamId1) {
                        $competition_team1 = $teamData;
                    } elseif ($teamData['team_id'] == $teamId2) {
                        $competition_team2 = $teamData;
                    }

                    // Break the loop if both teams are found
                    if ($competition_team1 !== null && $competition_team2 !== null) {
                        break;
                    }
                }

                //making the response to send only those two teams
                if ($competition_team1 !== null && $competition_team2 !== null) {
                    $comparisonResult = [
                        'teama' => $competition_team1,
                        'teamb' => $competition_team2,
                    ];

                    $response['response']['previous_statistics'] = $comparisonResult;
                    $response['response']['last5matches'] = $finalVenueArray;
//                    Cache::put("info_page_".$request['match_id'], $response['response'], 10800);
                    return json_encode($response['response'], JSON_PRETTY_PRINT);
                } else {
                    return json_encode(['error' => 'One or both teams not found'], JSON_PRETTY_PRINT);
                }
                //
            } else {
                return json_encode(['error' => 'Invalid JSON response'], JSON_PRETTY_PRINT);
            }
        }

    }

    public function fantasypage(HTTPRequest $request){
        if(Cache::has("fantasy_page_".$request['match_id'])){
            return Cache::get("fantasy_page_".$request['match_id']);
        }
        else {
            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
//        $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/competitions/'.$request['competition_id'].'/squads/'.$request['match_id'].'?token='.$apiToken);
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/' . $request['match_id'] . '/newpoint2?token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $response = json_decode($res->getBody(), true);
            Cache::put("fantasy_page_".$request['match_id'], $response['response']['points'], 180);
            return $response['response']['points'];
        }
    }

    public function commenterypage(HTTPRequest $request){
        if(Cache::has("commentery_".$request['match_id'])){
            return Cache::get("commentery_".$request['match_id']);
        }
        else {
            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/' . $request['match_id'] . '/live?token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $json_response = json_decode($res->getBody(), true);
            if (isset($json_response['response']['items'])) {
                Cache::get("commentery_".$request['match_id'], $json_response['response']['items'], 30);
                return json_encode($json_response['response']['items'], JSON_PRETTY_PRINT);
            } else {
                return json_encode("This event has ended");
            }
        }
    }

    public function livepage(HTTPRequest $request){
        if(Cache::has("live_page_".$request['match_id'])){
            return Cache::get("live_page_".$request['match_id']);
        }
        else {
            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/' . $request['match_id'] . '/live?token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $json_response = json_decode($res->getBody(), true);
            if (isset($json_response['response']['items'])) {
                Cache::get("live_page_".$request['match_id'], $json_response['response']['items'], 30);
                return json_encode($json_response['response']['items'], JSON_PRETTY_PRINT);
            } else {
                return json_encode("This event has ended");
            }
        }
    }

    public function scorecardpage(HTTPRequest $request){

        if(Cache::has("scorecard_".$request['competition_id'])){
            return Cache::get("scorecard_".$request['competition_id']);
        }
        else{

            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/matches/' . $request['match_id'] . '/scorecard?token=' . $apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $json_response = json_decode($res->getBody(), true);
            $response = json_encode($json_response['response'], JSON_PRETTY_PRINT);
            
            Cache::put("scorecard_".$request['competition_id'], $response, 180);
            return $response;
        }
    }

    public function pointstable(HTTPRequest $request){


        if(Cache::has("points_table_".$request['competition_id'])){
            return Cache::get("points_table_".$request['competition_id']);
        }
        else{
            $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://rest.entitysport.com/v2/competitions/'.$request['competition_id'].'/standings/?token='.$apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $json_response =  json_decode($res->getBody(), true);
            $response = json_encode($json_response['response'],JSON_PRETTY_PRINT);

            Cache::put("points_table_".$request['competition_id'], $response, 180);
            return $response;
        }
    }
    public function testing()
    {
        $apiToken = env('ENTITYSPORT_API_KEY');
        $client = new Client();
            $apiToken = env('ENTITYSPORT_API_KEY');
            $guzzleRequest = new GuzzleRequest('GET', 'https://isportindia.com/api/live-score&'.$apiToken);
            $res = $client->sendAsync($guzzleRequest)->wait();
            $request =  json_decode($res->getBody(), true);
            

            dd(  $request);
            Cache::put("points_table_".$request['competition_id'], $response, 180);
            return $response;
      
        
    }
}
