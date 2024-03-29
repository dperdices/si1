<!DOCTYPE html>
<?php
session_start();
if(!isset($_POST["index_token"]) || strcmp($_POST["index_token"], $_SESSION["index_token"]) !== 0) {
    header('Location: '.dirname(strtok($_SERVER["REQUEST_URI"],'?')));
    die;
}
date_default_timezone_set('Europe/Madrid');
if(isset($_SESSION["user"])) {
    echo '<div class="title">';
    echo '  Bet History';
    echo '</div>';
    $xml = simplexml_load_file('db.xml');
    $his = simplexml_load_file('users/'.$_SESSION["user"].'/history.xml');
    $bets = array();
    foreach($his->bet as $bet) {
        $bets[] = $bet;
    }
    unset($his);
    foreach(array_reverse($bets) as $i => $bet) {
        $game = $xml->xpath('/db/category[@*]/game[@id = "'.$bet->game.'"]')[0];
        $match = $xml->xpath('/db/category[@*]/game[@id = "'.$bet->game.'"]/matches/match[@id = "'.$bet["id"].'"]')[0];
        echo '<div class="match" onclick="showBetDetails(\''.$i.'\')">';
        if(strcmp($bet->winner, "0") == 0) {
            echo '  <div class="side0">'.$bet->amount.' €</div><div class="arrow0"></div>';
        }
        echo '  <div class="matchinfo">';
        echo '      <div class="matchdetail">'.date('D, jS F Y @ H:i',strtotime($match->date)).'</div>';
        echo '      <img src="'.$match->team[0]->icon.'" alt=""/>';
        echo '      '.$match->team[0]["name"].' vs. '.$match->team[1]["name"];
        echo '      <img src="'.$match->team[1]->icon.'" alt=""/>';
        echo '      <div class="matchdetail">'.$game["name"].'</div>';
        echo '  </div>';
        if(strcmp($bet->winner, "1") == 0) {
            echo '  <div class="side1">'.$bet->amount.' €</div><div class="arrow1"></div>';
        }
        echo '</div>';
        echo '<div class="betdetails" id="details'.$i.'">';
        echo '  Bet made on '.date('D, jS F Y \a\t H:i', intval($bet->time));
        echo '</div>';
        unset($game);
        unset($match);
    }
    unset($bets);
    unset($xml);
}
?>
