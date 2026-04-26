<?php

namespace App\Services;

use App\Http\Resources\GameHokm\GameHokmCollection;
use App\Http\Resources\GameHokm\GameHokmResource;
use App\Http\Resources\User\UserResource;
use App\Models\GameHokm;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Support\Facades\Validator;

class GameHokmService extends Service
{
    const PLAYER_1 = 'player_1';

    const PLAYER_2 = 'player_2';

    const PLAYER_3 = 'player_3';

    const PLAYER_4 = 'player_4';

    const TEAM_13 = 'team_13';

    const TEAM_24 = 'team_24';

    const SUIT_SPADE = 'spade';

    const SUIT_HEART = 'heart';

    const SUIT_DIAMOND = 'diamond';

    const SUIT_CLUB = 'club';

    const STATE_SELECT_TURN_SUIT = 'select_turn_suit';

    const STATE_SELECT_CARD = 'select_card';

    public static function new()
    {
        return app(self::class);
    }

    public function getApiCollection(User $user): ApiResponse
    {
        $gameHokms = GameHokm::where(function ($query) use ($user) {
            $query
                ->orWhere('player_1_id', $user->id)
                ->orWhere('player_2_id', $user->id)
                ->orWhere('player_3_id', $user->id)
                ->orWhere('player_4_id', $user->id)
                ->orWhere('owner_id', $user->id);
        })
            ->orderBy('created_at', 'DESC')
            ->get();

        return ApiResponse::new()->data([
            'user' => (new UserResource($user))->toArray(),
            'game_hokms' => (new GameHokmCollection($gameHokms))->withOwner()->setUserId($user->id)->toArray(),
        ]);
    }

    public function create(User $user, $player1Username, $player2Username, $player3Username, $player4Username)
    {
        $players = [
            static::PLAYER_1 => $player1Username,
            static::PLAYER_2 => $player2Username,
            static::PLAYER_3 => $player3Username,
            static::PLAYER_4 => $player4Username,
        ];

        foreach ($players as $player => $playerUsername) {
            if (! $player) {
                return ApiResponse::new(406);
            }
        }

        if (count(array_flip($players)) !== 4) {
            return ApiResponse::new(406);
        }

        $friends = User::query()
            ->whereIn('username', array_values($players))
            ->get()
            ->pluck(null, 'username')
            ->toArray();

        $currentHandLeader = array_rand($players, 1);

        $gameHokmData = [
            'owner_id' => $user->id,
            'winners' => [],
            'data' => [
                'deck' => $this->prepareDeck(),
                'state' => static::STATE_SELECT_TURN_SUIT,
                'turn' => [
                    'leader' => $currentHandLeader,
                    'suit' => null,
                    'scores' => [static::TEAM_13 => 0, static::TEAM_24 => 0],
                ],
                'hand' => [
                    'leader' => $currentHandLeader,
                    'suit' => null,
                    'scores' => [static::TEAM_13 => 0, static::TEAM_24 => 0],
                ],
                'hand_turn' => $currentHandLeader,
                'old_plays' => [
                    static::PLAYER_1 => null,
                    static::PLAYER_2 => null,
                    static::PLAYER_3 => null,
                    static::PLAYER_4 => null,
                ],
                'plays' => [
                    static::PLAYER_1 => null,
                    static::PLAYER_2 => null,
                    static::PLAYER_3 => null,
                    static::PLAYER_4 => null,
                ],
            ],
        ];

        $i = 1;
        foreach ($players as $player => $playerUsername) {
            if (array_key_exists($playerUsername, $friends)) {
                $gameHokmData[$player.'_id'] = $friends[$playerUsername]['id'];
                $gameHokmData[$player.'_token'] = $i.rand(10_000_000, 99_999_999);
                $gameHokmData[$player.'_name'] = $friends[$playerUsername]['name'];
            } else {
                return ApiResponse::new(406);
            }
            $i++;
        }

        $gameHokm = GameHokm::create($gameHokmData);

        return ApiResponse::new(201)->data([
            'game_hokm' => (new GameHokmResource($gameHokm))->setUserId($user->id)->toArray(),
        ]);
    }

    public function modification($id, $player, $token)
    {
        if (! in_array($player, [static::PLAYER_1, static::PLAYER_2, static::PLAYER_3, static::PLAYER_4])) {
            return ApiResponse::new(406);
        }
        $playerTokenColumn = $player.'_token';
        $playerIdColumn = $player.'_id';

        $gameHokm = GameHokm::query()->select([
            'id', 'winners', 'modified_at',
            'player_1_id', 'player_1_token', 'player_1_name', 'player_1_quote',
            'player_2_id', 'player_2_token', 'player_2_name', 'player_2_quote',
            'player_3_id', 'player_3_token', 'player_3_name', 'player_3_quote',
            'player_4_id', 'player_4_token', 'player_4_name', 'player_4_quote',
        ])->where($playerTokenColumn, $token)->find($id);
        if (! $gameHokm) {
            return ApiResponse::new(404);
        }

        return ApiResponse::new()->data([
            'game_hokm' => (new GameHokmResource($gameHokm))
                ->setUserId($gameHokm->$playerIdColumn)
                ->withOwner(false)
                ->withData(false)
                ->toArray(),
        ]);
    }

    public function board($id, $player, $token)
    {
        if (! in_array($player, [static::PLAYER_1, static::PLAYER_2, static::PLAYER_3, static::PLAYER_4])) {
            return ApiResponse::new(406);
        }
        $playerTokenColumn = $player.'_token';
        $playerIdColumn = $player.'_id';

        $gameHokm = GameHokm::where($playerTokenColumn, $token)->find($id);
        if (! $gameHokm) {
            return ApiResponse::new(404);
        }

        return ApiResponse::new()->data([
            'game_hokm' => (new GameHokmResource($gameHokm))->setUserId($gameHokm->$playerIdColumn)->withData()->toArray(),
        ]);
    }

    public function quote($id, $player, $token, $quote)
    {
        if (in_array($player, [static::PLAYER_1, static::PLAYER_2, static::PLAYER_3, static::PLAYER_4])) {
            $playerTokenColumn = $player.'_token';
            $playerIdColumn = $player.'_id';
            $playerQuoteColumn = $player.'_quote';
        } else {
            return ApiResponse::new(406);
        }

        $gameHokm = GameHokm::query()->select([
            'id', 'winners', 'modified_at',
            'player_1_id', 'player_1_token', 'player_1_name', 'player_1_quote',
            'player_2_id', 'player_2_token', 'player_2_name', 'player_2_quote',
            'player_3_id', 'player_3_token', 'player_3_name', 'player_3_quote',
            'player_4_id', 'player_4_token', 'player_4_name', 'player_4_quote',
        ])->where($playerTokenColumn, $token)->find($id);
        if (! $gameHokm) {
            return ApiResponse::new(404);
        }

        if ($gameHokm->winners) {
            return ApiResponse::new(406);
        }

        $validator = Validator::make(['quote' => $quote], [
            'quote' => [
                'nullable',
                'string',
                'max:64',
            ],
        ]);

        if ($validator->fails()) {
            return ApiResponse::new(406)->errors($validator->messages());
        }

        if (! $gameHokm->update([$playerQuoteColumn => $quote])) {
            return ApiResponse::new(500);
        }

        return ApiResponse::new(200);
    }

    public function play($id, $player, $token, $cardOrSuit)
    {
        if (in_array($player, [static::PLAYER_1, static::PLAYER_2, static::PLAYER_3, static::PLAYER_4])) {
            $playerTokenColumn = $player.'_token';
            $playerIdColumn = $player.'_id';
        } else {
            return ApiResponse::new(406);
        }

        $gameHokm = GameHokm::where($playerTokenColumn, $token)->find($id);
        if (! $gameHokm) {
            return ApiResponse::new(404);
        }

        if ($gameHokm->winners) {
            return ApiResponse::new(406);
        }

        $data = $gameHokm->data;

        if ($data['hand_turn'] !== $player) {
            return ApiResponse::new(403);
        }

        if ($data['state'] === static::STATE_SELECT_CARD) {
            $playerCardsCollection = collect($data['deck'][$player]);
            $card = $playerCardsCollection->firstWhere('id', '==', $cardOrSuit);
            if (! $card) {
                return ApiResponse::new(406);
            }

            if (
                ($data['hand']['suit']) && ($data['hand']['suit'] !== $card['suit']) &&
                collect($data['deck'][$player])->firstWhere('suit', '==', $data['hand']['suit'])
            ) {
                return ApiResponse::new(406);
            }

            $data['deck'][$player] = $playerCardsCollection->reject(fn ($card) => $card['id'] == $cardOrSuit)->toArray();
            $card['hand_turn'] = $player;
            $data['plays'][$player] = $card;
            if ($data['hand']['leader'] == $player) {
                $data['hand']['suit'] = $card['suit'];
            }

            $nextPlayer = $this->getNextCirclePlayer($player);

            if ($nextPlayer == $data['hand']['leader']) {
                $nextHandPlayer = $this->calcHandWinner($data['plays'], $data['turn']['suit'], $data['hand']['suit']);
                $handWinnerTeam = $this->getTeam($nextHandPlayer);
                $data['hand']['scores'][$handWinnerTeam]++;

                $newTurnScore = $this->calcTurnWinner($data);
                if ($newTurnScore) {
                    $data['turn']['scores'][$newTurnScore['winner_team']] += $newTurnScore['score'];
                    $newGameScore = $this->calcGameWinner($data);
                    if ($newGameScore) {
                        $gameHokm->data = $data;
                        $gameHokm->winners = $newGameScore;
                        $gameHokm->save();

                        return ApiResponse::new(200);
                    } else {
                        $nextHandPlayer = ($this->getTeam($data['turn']['leader']) == $newTurnScore['winner_team'] ? $data['turn']['leader'] : $this->getNextCirclePlayer($data['turn']['leader']));
                        $data['deck'] = $this->prepareDeck();
                        $data['state'] = static::STATE_SELECT_TURN_SUIT;
                        $data['turn']['leader'] = $nextHandPlayer;
                        $data['turn']['suit'] = null;
                        $data['hand']['scores'] = [static::TEAM_13 => 0, static::TEAM_24 => 0];
                    }
                }
                $data['hand']['leader'] = $nextHandPlayer;
                $data['hand']['suit'] = null;
                $data['hand_turn'] = $nextHandPlayer;
                $data['old_plays'] = $data['plays'];
                $data['plays'] = [
                    static::PLAYER_1 => null,
                    static::PLAYER_2 => null,
                    static::PLAYER_3 => null,
                    static::PLAYER_4 => null,
                ];
                //
                $gameHokm->data = $data;
                $gameHokm->save();

                return ApiResponse::new(201);
            } else {
                $data['hand_turn'] = $nextPlayer;
                $gameHokm->data = $data;
                $gameHokm->save();

                return ApiResponse::new(201);
            }

        } elseif ($data['state'] === static::STATE_SELECT_TURN_SUIT) {
            if (! in_array($cardOrSuit, [static::SUIT_CLUB, static::SUIT_DIAMOND, static::SUIT_HEART, static::SUIT_SPADE])) {
                return ApiResponse::new(406);
            }
            //
            $data['turn']['suit'] = $cardOrSuit;
            $data['state'] = static::STATE_SELECT_CARD;
            //
            $gameHokm->data = $data;
            $gameHokm->save();

            return ApiResponse::new(201);
        }
    }

    protected function getTeam(string $player): string
    {
        return [
            static::PLAYER_1 => static::TEAM_13,
            static::PLAYER_2 => static::TEAM_24,
            static::PLAYER_3 => static::TEAM_13,
            static::PLAYER_4 => static::TEAM_24,
        ][$player];
    }

    protected function calcGameWinner($data)
    {
        if ($data['turn']['scores'][static::TEAM_13] >= 7) {
            return [static::PLAYER_1, static::PLAYER_3];
        }

        if ($data['turn']['scores'][static::TEAM_24] >= 7) {
            return [static::PLAYER_2, static::PLAYER_4];
        }

        return null;
    }

    protected function calcTurnWinner($data)
    {
        if ($data['hand']['scores'][static::TEAM_13] >= 7) {
            $winnerTeam = static::TEAM_13;
            $loserTeam = static::TEAM_24;
        } elseif ($data['hand']['scores'][static::TEAM_24] >= 7) {
            $winnerTeam = static::TEAM_24;
            $loserTeam = static::TEAM_13;
        } else {
            return null;
        }

        if ($data['hand']['scores'][$loserTeam] != 0) {
            $score = 1;
        } elseif ($this->getTeam($data['turn']['leader']) != $loserTeam) {
            $score = 2;
        } else {
            $score = 3;
        }

        return [
            'winner_team' => $winnerTeam,
            'loser_team' => $loserTeam,
            'score' => $score,
        ];
    }

    protected function calcHandWinner(array $cards, $turnSuit, $handSuit): string
    {
        $winner = null;
        $winnerScore = 0;

        foreach ($cards as $card) {
            if ($card['suit'] == $turnSuit) {
                $power = 200;
            } elseif ($card['suit'] == $handSuit) {
                $power = 100;
            } else {
                $power = 0;
            }

            $calculatedCardScore = $card['score'] + $power;
            if ($winnerScore < $calculatedCardScore) {
                $winner = $card['hand_turn'];
                $winnerScore = $calculatedCardScore;
            }
        }

        return $winner;
    }

    protected function prepareDeck()
    {
        $suits = [
            static::SUIT_SPADE => '♠️',
            static::SUIT_HEART => '♥️',
            static::SUIT_CLUB => '♣️',
            static::SUIT_DIAMOND => '♦️',
        ];
        $ranks = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];
        $deck = [];

        $id = 1;
        foreach ($suits as $suit => $suitSymbol) {
            $score = 1;
            foreach ($ranks as $rank) {
                $deck[] = [
                    'id' => $id, 'random_id' => null,
                    'rank' => $rank,
                    'suit' => $suit, 'suit_symbol' => $suitSymbol,
                    'score' => $score,
                    'hand_turn' => null,
                ];
                $id++;
                $score++;
            }
        }
        shuffle($deck);
        shuffle($deck);
        shuffle($deck);
        shuffle($deck);
        foreach ($deck as $deckRandomId => $deckCard) {
            $deck[$deckRandomId]['random_id'] = $deckRandomId;
        }
        $chunks = array_chunk($deck, 13);

        $result = [];
        foreach ([
            static::PLAYER_1 => 0,
            static::PLAYER_2 => 1,
            static::PLAYER_3 => 2,
            static::PLAYER_4 => 3,
        ] as $player => $index) {
            usort($chunks[$index], function ($a, $b) {
                return $a['id'] <=> $b['id'];
            });
            $result[$player] = $chunks[$index];
        }

        return $result;
    }

    protected function getNextCirclePlayer(string $player): string
    {
        return [
            static::PLAYER_1 => static::PLAYER_2,
            static::PLAYER_2 => static::PLAYER_3,
            static::PLAYER_3 => static::PLAYER_4,
            static::PLAYER_4 => static::PLAYER_1,
        ][$player];
    }
}
