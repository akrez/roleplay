@extends('layouts.app')

@section('title', __('GameXo'))

@php
    $routeData = [
        'id' => $game_xo['id'],
        'player' => $game_xo['player'],
        'token' => $game_xo[$game_xo['player']]['token'],
    ];
    $params = [
        'board_url' => route('api.game_xos.board', $routeData),
        'play_url' => route('api.game_xos.play', $routeData),
    ];
@endphp

@section('content')
    <div class="container" x-data="gameData()" x-init="initializeGame({{ json_encode($game_xo) }})">
        <div class="row text-center">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                @foreach (['player_x' => '✖️', 'player_o' => '⭕'] as $player => $symbol)
                    <div class="btn btn-light border-1 border-dark w-100 position-relative mb-1">
                        @if ($player == $game_xo['player'])
                            <span
                                class="px-2 position-absolute top-0 start-0 d-flex align-items-center border-end border-dark border-1"
                                style="height: 100%;">
                                شما
                            </span>
                        @endif
                        <span x-text="players.{{ $player }}.name"></span>
                        <span
                            class="px-2 position-absolute top-0 end-0 d-flex align-items-center border-start border-dark border-1"
                            style="height: 100%;">
                            {{ $symbol }}
                        </span>
                    </div>
                @endforeach

                <div class="alert alert-light mb-1" role="alert" x-text="statusMessage">
                </div>

                <table class="table table-bordered text-center shadow-sm w-100 mb-3" id="board" dir="ltr">
                    <tbody>
                        @for ($i = 0; $i < 3; $i++)
                            <tr>
                                @for ($j = 0; $j < 3; $j++)
                                    <td class="cell align-middle" style="width: 33.33%; height: 33.33%"
                                        @click="makeMove({{ $i }}, {{ $j }})">
                                        <img class="w-100"
                                            :src="board[{{ $i }}][{{ $j }}] === 'player_x' ?
                                                '{{ url('assets/x.svg') }}' : (board[{{ $i }}][
                                                        {{ $j }}
                                                    ] === 'player_o' ?
                                                    '{{ url('assets/o.svg') }}' :
                                                    '{{ url('assets/d.svg') }}')">
                                    </td>
                                @endfor
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
            </div>
        </div>

    </div>

    <script>
        var parameters = @json($params);

        function gameData() {
            return {
                gameId: null,
                player: null,
                token: null,
                players: {},
                board: [
                    [null, null, null],
                    [null, null, null],
                    [null, null, null]
                ],
                turn: null,
                winners: [],
                statusMessage: '',
                isLoading: false,
                refreshInterval: null,

                initializeGame(initialGameData) {
                    if (!initialGameData) {
                        console.error('initialGameData is undefined');
                        return;
                    }

                    this.gameId = initialGameData.id;
                    this.player = initialGameData.player;
                    this.token = initialGameData[initialGameData.player].token;
                    this.players = {
                        player_x: {
                            name: initialGameData.player_x.name
                        },
                        player_o: initialGameData.player_o ? {
                            name: initialGameData.player_o.name
                        } : null
                    };
                    this.board = initialGameData.data.board;
                    this.turn = initialGameData.data.turn;
                    this.winners = initialGameData.winners || [];
                    this.updateStatusMessage();

                    this.refreshInterval = setInterval(() => this.fetchBoardData(), 3000);
                },

                updateStatusMessage() {
                    if (this.winners.length === 0) {
                        this.statusMessage = this.turn === this.player ?
                            '✨ نوبت شماست!' :
                            '⏳ منتظر حرکت حریف...';
                        return;
                    }

                    if (this.winners.length === 2) {
                        const xName = this.players.player_x?.name || 'X';
                        const oName = this.players.player_o?.name || 'O';
                        this.statusMessage = `🤝 بازی مساوی شد بین ${xName} و ${oName}`;
                    } else {
                        const winnerKey = this.winners[0];
                        const winnerName = this.players[winnerKey]?.name || 'ناشناخته';
                        const symbol = winnerKey === 'player_x' ? '❌' : '⭕';
                        this.statusMessage = `${symbol} ${winnerName} برنده شد! 🎉`;
                    }
                },

                async fetchBoardData() {
                    if (this.winners.length > 0) {
                        clearInterval(this.refreshInterval);
                        return;
                    }
                    if (this.isLoading) return;
                    this.isLoading = true;

                    try {
                        const res = await fetch(parameters['board_url'], {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!res.ok) {
                            console.error('خطا در دریافت وضعیت بازی');
                            return;
                        }

                        const data = await res.json();
                        const updatedGame = data.data.game_xo;

                        if (JSON.stringify(this.board) !== JSON.stringify(updatedGame.data.board) ||
                            JSON.stringify(this.winners) !== JSON.stringify(updatedGame.winners)) {
                            this.board = updatedGame.data.board;
                            this.turn = updatedGame.data.turn;
                            this.winners = updatedGame.winners || [];
                            this.updateStatusMessage();
                        }
                    } catch (err) {
                        console.error('خطا در ارتباط با سرور:', err);
                    } finally {
                        this.isLoading = false;
                    }
                },

                async makeMove(row, col) {
                    if (this.winners.length > 0 || this.turn !== this.player || this.board[row][col] !== null) {
                        return;
                    }

                    if (this.isLoading) return;
                    this.isLoading = true;

                    try {
                        const formData = new FormData();
                        formData.append('row', row);
                        formData.append('col', col);

                        const res = await fetch(parameters['play_url'], {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (res.ok) {
                            this.fetchBoardData();
                        } else {
                            res.json().then(res => {
                                alert(res.message);
                            })
                        }
                    } catch (err) {
                        alert('❗ خطا در ارسال حرکت');
                        console.error(err);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
@endsection
