<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        return response()->json(Game::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'home_team_goals' => 'required|integer|min:0',
            'away_team_goals' => 'required|integer|min:0',
        ]);

        $game = Game::create($validated);
        $this->updateTeamsStats($game);

        return response()->json($game, 201);
    }

    public function show(Game $game)
    {
        return response()->json($game, 200);
    }

    public function destroy(Game $game)
    {
        $game->delete();
        return response()->json(null, 204);
    }

    private function updateTeamsStats(Game $game)
    {
        $homeTeam = Team::find($game->home_team_id);
        $awayTeam = Team::find($game->away_team_id);

        $homeTeam->matches_played++;
        $awayTeam->matches_played++;

        if ($game->home_team_goals > $game->away_team_goals) {
            $homeTeam->wins++;
            $awayTeam->losses++;
            $homeTeam->points += 3;
        } elseif ($game->home_team_goals < $game->away_team_goals) {
            $awayTeam->wins++;
            $homeTeam->losses++;
            $awayTeam->points += 3;
        } else {
            $homeTeam->draws++;
            $awayTeam->draws++;
            $homeTeam->points += 1;
            $awayTeam->points += 1;
        }

        $homeTeam->goals_for += $game->home_team_goals;
        $homeTeam->goals_against += $game->away_team_goals;
        $awayTeam->goals_for += $game->away_team_goals;
        $awayTeam->goals_against += $game->home_team_goals;

        $homeTeam->save();
        $awayTeam->save();
    }
}
