@extends('layouts.game')

@section('title', __('GameHokm'))

@php
    $routeData = [
        'id' => $game_hokm['id'],
        'player' => $game_hokm['player'],
        'token' => $game_hokm['players'][$game_hokm['player']]['token'],
    ];
    $urls = [
        'modification' => route('api.game_hokms.modification', $routeData),
        'board' => route('api.game_hokms.board', $routeData),
        'play' => route('api.game_hokms.play', $routeData),
        'quote' => route('api.game_hokms.quote', $routeData),
        'public' => url('/'),
        'bg' => url('assets/bg-green.jpg'),
    ];
@endphp

@section('content')
    <div class="container-fluid user-select-none flex-fill bg-black" x-data="gameData()" x-init="initGameData()"
        x-show="gameId" x-transition>
        <div class="row h-100">
            <div style="background-image: url('{{ $urls['bg'] }}'); background-repeat: no-repeat; background-size: cover; background-position: center;"
                class="col-xl-4 offset-xl-4 col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12
                    d-flex flex-column justify-content-between
                    text-center p-3">
                <div class="row g-1">
                    <div
                        class="w-100 bg-tertiary rounded mb-1 p-2 d-flex flex-row justify-content-between align-items-center text-center">
                        <div class="d-flex flex-row col"></div>
                        <div class="d-flex flex-row col justify-content-center fs-7">
                            <div class="" x-text="getCirclePlayerAttr(0, 'name')"></div>
                        </div>
                        <div class="d-flex flex-row col justify-content-end">
                            <a href="{{ route('game_hokms.index') }}" class="fs-8 text-body-tertiary rounded text-muted text-decoration-none text-dark">
                                بازگشت
                            </a>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-success mb-1 fs-8">
                            <div class="card-header text-bg-success p-1">
                                <div class="text-truncate" x-text="players?.player_1.name"></div>
                            </div>
                            <div class="card-header text-bg-success p-1">
                                <div class="text-truncate" x-text="players?.player_3.name"></div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-borderless m-0">
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td class="border-end">امتیاز کلی</td>
                                            <td x-text="turn?.scores.team_13"></td>
                                        </tr>
                                        <tr>
                                            <td class="rounded border-end">امتیاز دست</td>
                                            <td class="rounded" x-text="hand?.scores.team_13"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 d-flex flex-column justify-content-between">
                        <div style="text-shadow: 0 0 20px yellow;"
                            class="m-0 p-0 pt-2 fs-1 rounded my-1 flex-fill d-flex justify-content-center align-items-center"
                            x-text="(turn && turn['suit']) ? suits[turn['suit']]['suit_symbol'] : 'ㅤ'">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card border-success mb-1 fs-8">
                            <div class="card-header text-bg-success p-1">
                                <div class="text-truncate" x-text="players?.player_2.name"></div>
                            </div>
                            <div class="card-header text-bg-success p-1">
                                <div class="text-truncate" x-text="players?.player_4.name"></div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm table-borderless m-0">
                                    <tbody>
                                        <tr class="border-bottom">
                                            <td class="border-end">امتیاز کلی</td>
                                            <td x-text="turn?.scores.team_24"></td>
                                        </tr>
                                        <tr>
                                            <td class="rounded border-end">امتیاز دست</td>
                                            <td class="rounded" x-text="hand?.scores.team_24"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1">
                    </div>
                    <div class="col-10">
                        <div class="row g-1 d-flex flex-row justify-content-center">
                            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="carousel-inner mb-1">
                                    <div class="carousel-item active">
                                        <div class="carousel-caption rounded p-0 top-0 start-0 end-0 w-100"
                                            x-show="hasWinners()">
                                            <div x-text="isCirclePlayerWinner(1) ? '🏅' : ''"
                                                class="d-flex justify-content-center align-items-center fs-1 position-absolute top-0 start-0 end-0 bottom-0 z-3">
                                            </div>
                                            <div :class="isCirclePlayerWinner(1) ? 'bg-success border-success' :
                                                'bg-dark border-dark'"
                                                class="border border-1 opacity-75 rounded position-absolute top-0 start-0 end-0 bottom-0">
                                            </div>
                                        </div>
                                        <img x-bind:src="renderPlays(1)" class="bd-placeholder-img w-100 rounded"
                                            :class="(isHandSuitEmpty() ? 'opacity-25' : '')">
                                        <div
                                            class="carousel-caption rounded-bottom p-0 bottom-0 start-0 end-0 w-100 cursor-pointer bg-gradient">
                                            <div class="m-0 p-1 pb-2 text-center fs-8 text-bg-dark border border-1 border-dark opacity-75"
                                                x-text="getCirclePlayerAttr(1, 'quote')"
                                                x-show="!hasWinners() && getCirclePlayerAttr(1, 'quote')">
                                            </div>
                                            <div class="m-0 p-1 pt-2 position-relative border border-1 rounded-bottom"
                                                :class="isCircleHandTurn(1) ? 'text-bg-success border-success' :
                                                    'text-bg-dark border-dark'">
                                                <span
                                                    class="position-absolute top-0 start-50 translate-middle d-flex align-items-center justify-content-around w-100">
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"
                                                        x-text="renderTurnLeader(1)"> </div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                </span>
                                                <div class="m-0 p-0 text-truncate text-center fs-7"
                                                    x-text="getCirclePlayerAttr(1, 'name')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="carousel-inner mb-1">
                                    <div class="carousel-item active">
                                        <div class="carousel-caption rounded p-0 top-0 start-0 end-0 w-100"
                                            x-show="hasWinners()">
                                            <div x-text="isCirclePlayerWinner(2) ? '🏅' : ''"
                                                class="d-flex justify-content-center align-items-center fs-1 position-absolute top-0 start-0 end-0 bottom-0 z-3">
                                            </div>
                                            <div :class="isCirclePlayerWinner(2) ? 'bg-success border-success' :
                                                'bg-dark border-dark'"
                                                class="border border-1 opacity-75 rounded position-absolute top-0 start-0 end-0 bottom-0">
                                            </div>
                                        </div>
                                        <img x-bind:src="renderPlays(2)" class="bd-placeholder-img w-100 rounded"
                                            :class="(isHandSuitEmpty() ? 'opacity-25' : '')">
                                        <div
                                            class="carousel-caption rounded-bottom p-0 bottom-0 start-0 end-0 w-100 cursor-pointer bg-gradient">
                                            <div class="m-0 p-1 pb-2 text-center fs-8 text-bg-dark border border-1 border-dark opacity-75"
                                                x-text="getCirclePlayerAttr(2, 'quote')"
                                                x-show="!hasWinners() && getCirclePlayerAttr(2, 'quote')">
                                            </div>
                                            <div class="m-0 p-1 pt-2 position-relative border border-1 rounded-bottom"
                                                :class="isCircleHandTurn(2) ? 'text-bg-success border-success' :
                                                    'text-bg-dark border-dark'">
                                                <span
                                                    class="position-absolute top-0 start-50 translate-middle d-flex align-items-center justify-content-around w-100">
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"
                                                        x-text="renderTurnLeader(2)"> </div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                </span>
                                                <div class="m-0 p-0 text-truncate text-center fs-7"
                                                    x-text="getCirclePlayerAttr(2, 'name')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="carousel-inner mb-1">
                                    <div class="carousel-item active">
                                        <div class="carousel-caption rounded p-0 top-0 start-0 end-0 w-100"
                                            x-show="hasWinners()">
                                            <div x-text="isCirclePlayerWinner(0) ? '🏅' : ''"
                                                class="d-flex justify-content-center align-items-center fs-0 position-absolute top-0 start-0 end-0 bottom-0 z-3">
                                            </div>
                                            <div :class="isCirclePlayerWinner(0) ? 'bg-success border-success' :
                                                'bg-dark border-dark'"
                                                class="border border-1 opacity-75 rounded position-absolute top-0 start-0 end-0 bottom-0">
                                            </div>
                                        </div>
                                        <img x-bind:src="renderPlays(0)" class="bd-placeholder-img w-100 rounded"
                                            :class="(isHandSuitEmpty() ? 'opacity-25' : '')">
                                        <div class="carousel-caption rounded-bottom p-0 bottom-0 start-0 end-0 w-100 cursor-pointer bg-gradient"
                                            @click="if(!hasWinners()) { $dispatch('open-quote-modal') }">
                                            <div class="m-0 p-1 pb-2 text-center fs-8 text-bg-dark border border-1 border-dark opacity-75"
                                                x-text="getCirclePlayerAttr(0, 'quote')"
                                                x-show="!hasWinners() && getCirclePlayerAttr(0, 'quote')">
                                            </div>
                                            <div class="m-0 p-1 pt-2 position-relative border border-1 rounded-bottom"
                                                :class="isCircleHandTurn(0) ? 'text-bg-success border-success' :
                                                    'text-bg-dark border-dark'">
                                                <span
                                                    class="position-absolute top-0 start-50 translate-middle d-flex align-items-center justify-content-around w-100">
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"
                                                        x-text="renderFetchStatus(0)"></div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"
                                                        x-text="renderTurnLeader(0)"> </div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                </span>
                                                <div class="m-0 p-0 text-truncate text-center fs-7"
                                                    x-text="getCirclePlayerAttr(0, 'name')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4 d-flex flex-column align-items-center justify-content-center">
                                <div class="carousel-inner mb-1">
                                    <div class="carousel-item active">
                                        <div class="carousel-caption rounded p-0 top-0 start-0 end-0 w-100"
                                            x-show="hasWinners()">
                                            <div x-text="isCirclePlayerWinner(3) ? '🏅' : ''"
                                                class="d-flex justify-content-center align-items-center fs-1 position-absolute top-0 start-0 end-0 bottom-0 z-3">
                                            </div>
                                            <div :class="isCirclePlayerWinner(3) ? 'bg-success border-success' :
                                                'bg-dark border-dark'"
                                                class="border border-1 opacity-75 rounded position-absolute top-0 start-0 end-0 bottom-0">
                                            </div>
                                        </div>
                                        <img x-bind:src="renderPlays(3)" class="bd-placeholder-img w-100 rounded"
                                            :class="(isHandSuitEmpty() ? 'opacity-25' : '')">
                                        <div
                                            class="carousel-caption rounded-bottom p-0 bottom-0 start-0 end-0 w-100 cursor-pointer bg-gradient">
                                            <div class="m-0 p-1 pb-2 text-center fs-8 text-bg-dark border border-1 border-dark opacity-75"
                                                x-text="getCirclePlayerAttr(3, 'quote')"
                                                x-show="!hasWinners() && getCirclePlayerAttr(3, 'quote')">
                                            </div>
                                            <div class="m-0 p-1 pt-2 position-relative border border-1 rounded-bottom"
                                                :class="isCircleHandTurn(3) ? 'text-bg-success border-success' :
                                                    'text-bg-dark border-dark'">
                                                <span
                                                    class="position-absolute top-0 start-50 translate-middle d-flex align-items-center justify-content-around w-100">
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"
                                                        x-text="renderTurnLeader(3)"> </div>
                                                    <div class="m-0 p-0 flex-fill flex-grow-0 w-32"></div>
                                                </span>
                                                <div class="m-0 p-0 text-truncate text-center fs-7"
                                                    x-text="getCirclePlayerAttr(3, 'name')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-1">
                    <div class="col-12">
                        <div class="row g-1 d-flex flex-wrap justify-content-center mb-1"
                            :class="(isMyHandTurn() && state === 'select_turn_suit') ? 'd-flex' : 'd-none'" x-transition>
                            <template x-for="suit in suits" :key="suit.id">
                                <div class="col-2">
                                    <div style="text-shadow: 0 0 20px yellow;"
                                        class="m-0 p-0 pt-2 fs-1 rounded my-2 flex-fill d-flex justify-content-center align-items-center cursor-pointer"
                                        @click="playGame(suit.id)" x-text="suit.suit_symbol">
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="row g-0 d-flex flex-wrap justify-content-center mb-1">
                            <template x-for="card in player_deck" :key="card.id">
                                <img x-bind:src="getCardSrc(card)" class="col-card"
                                    x-bind:style="'flex: 0 0 auto; width: ' + (player_deck.length > 12 ? '14.2857143' :
                                        '16.6666666') + '%;'";
                                    :class="(isMyHandTurn() && state !== 'select_turn_suit') ? 'cursor-pointer' : 'opacity-25'"
                                    @click="playGame(card.id)">
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" x-data
            @open-quote-modal.window=" const m = bootstrap.Modal.getOrCreateInstance($el); m.show(); "
            @close-quote-modal.window=" const m = bootstrap.Modal.getOrCreateInstance($el); m.hide(); ">
            <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-1">
                            <div class="col-12 d-flex">
                                <div class="input-group flex-fill me-1">
                                    <button class="btn btn-success p-2" @click="sendQuote(quote);"
                                        :disabled="isSendingQuote">
                                        <i class="bi-chevron-right"></i>
                                    </button>
                                    <input type="text" class="form-control text-center p-2 pb-1" x-model="quote">
                                </div>
                                <button class="btn btn-danger p-1 px-2" @click="setQuote('');"
                                    :disabled="isSendingQuote">
                                    <i class="bi-eraser"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <template x-for="oldQuote in oldQuotes">
                                    <div class="d-inline-block rounded fs-6 small text-bg-secondary cursor-pointer m-1 p-1"
                                        x-text="oldQuote" @click="setQuote(oldQuote);"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var urls = @json($urls);

        function gameData() {
            return {
                gameId: null,
                winners: [],
                player: null,
                players: null,
                player_deck: [],
                hand_turn: null,
                plays: [],
                old_plays: [],
                state: null,
                turn: null,
                hand: null,
                modified_at: null,
                //
                refreshInterval: null,
                fetchStatus: 'OFF',
                isPlaying: false,
                confettiShowed: false,
                isSendingQuote: false,
                quote: null,
                oldQuotes: [],
                //
                suits: {
                    'spade': {
                        id: 'spade',
                        suit_symbol: '♠️',
                    },
                    'heart': {
                        id: 'heart',
                        suit_symbol: '♥️',
                    },
                    'club': {
                        id: 'club',
                        suit_symbol: '♣️',
                    },
                    'diamond': {
                        id: 'diamond',
                        suit_symbol: '♦️',
                    },
                },
                //
                initGameData() {
                    this.fetchGameData();
                    this.refreshInterval = setInterval(() => this.fetchGameData(), 3000);
                },
                updateGameModification(gameData) {
                    this.gameId = gameData.id;
                    this.winners = gameData.winners || [];
                    this.player = gameData.player;
                    this.players = gameData.players || [];
                    this.modified_at = gameData.modified_at;
                    if (this.quote === null) {
                        this.quote = this.getCirclePlayerAttr(0, 'quote');
                    }
                },
                updateGameData(gameData) {
                    this.player_deck = gameData.player_deck || [];
                    this.hand_turn = gameData.hand_turn;
                    this.plays = gameData.plays || [];
                    this.old_plays = gameData.old_plays || [];
                    this.state = gameData.state;
                    this.turn = gameData.turn;
                    this.hand = gameData.hand;
                    this.updateGameModification(gameData);
                },
                isCircleHandTurn(circleIndex) {
                    player = this.circleIndexToPlayer(circleIndex);
                    return (
                        (this.hand_turn && player) &&
                        (this.hand_turn === player)
                    );
                },
                hasWinners() {
                    return this.winners.length > 0;
                },
                isMyHandTurn() {
                    return (this.isCircleHandTurn(0) && !this.hasWinners());
                },
                isHandSuitEmpty() {
                    return !(this.hand && this.hand['suit']);
                },
                renderTurnLeader(circleIndex) {
                    if (this.turn && (this.turn['leader'] == this.circleIndexToPlayer(circleIndex))) {
                        return '👑';
                    }
                    return 'ㅤ';
                },
                renderFetchStatus(circleIndex) {
                    if (this.fetchStatus == 'OFF') {
                        return '⚪';
                    }
                    if (this.fetchStatus == 'CONNECTED') {
                        return '🟢';
                    }
                    if (this.fetchStatus == 'CONNECTING') {
                        return '🟡';
                    }
                    return '🔴';
                },
                isCirclePlayerWinner(index) {
                    player = this.circleIndexToPlayer(index);
                    return this.winners && this.winners.includes(player);
                },
                renderPlays(index) {
                    player = this.circleIndexToPlayer(index);
                    //
                    card = null;
                    if (this.hand) {
                        if (this.hand['suit']) {
                            card = this.plays[player];
                        } else {
                            card = this.old_plays[player];
                        }
                    }
                    return this.getCardSrc(card);
                },
                getCardSrc(card) {
                    ext = '.svg';
                    if (card) {
                        path = card['suit'] + '/' + card['rank'] + ext;
                    } else {
                        path = 'null' + ext;
                    }
                    return this.getPublicUrl('assets/cards/' + path);
                },
                getPublicUrl(path) {
                    return urls['public'] + '/' + path;
                },
                async fetchGameData() {
                    try {
                        if (this.fetchStatus == 'CONNECTING') return;
                        this.fetchStatus = 'CONNECTING';

                        const modificationRes = await fetch(urls['modification'], {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!modificationRes.ok) {
                            this.fetchStatus = 'ERROR';
                            return;
                        }

                        const modificationData = await modificationRes.json();
                        if (modificationData.data.game_hokm.modified_at == this.modified_at) {
                            this.updateGameModification(modificationData.data.game_hokm);
                            this.fetchStatus = 'CONNECTED';
                            return;
                        }

                        const boardRes = await fetch(urls['board'], {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!boardRes.ok) {
                            this.fetchStatus = 'ERROR';
                            return;
                        }

                        const data = await boardRes.json();
                        if (data.data.game_hokm.winners.length > 0) {
                            clearInterval(this.refreshInterval);
                            this.fetchStatus = 'OFF';
                        } else {
                            this.fetchStatus = 'CONNECTED';
                        }

                        this.updateGameData(data.data.game_hokm);

                        isNewTurn = (this.hasWinners() || (this.turn &&
                            !this.turn['suit'] &&
                            (this.turn['scores']['team_13'] || this.turn['scores']['team_24']) &&
                            (this.state === 'select_turn_suit')
                        ));

                        if (isNewTurn) {
                            if (!this.confettiShowed) {
                                confetti({
                                    particleCount: 275,
                                    spread: 75,
                                    zIndex: 100
                                });
                                this.confettiShowed = true;
                            }
                        } else {
                            this.confettiShowed = false;
                        }

                    } catch (err) {
                        this.fetchStatus = 'ERROR';
                    }
                },
                getCirclePlayerAttr(circleIndex, attr, defaultValue) {
                    if (
                        this.players &&
                        (player = this.circleIndexToPlayer(circleIndex))
                    ) {
                        return this.players[player][attr];
                    }
                    return defaultValue;
                },
                circleIndexToPlayer(circleIndex, prefix = "player_") {
                    if (this.player === null) {
                        return null;
                    }
                    playerIndex = parseInt(this.player.replace("player_", ""), 10);
                    player = 1 + ((3 + playerIndex + circleIndex) % 4);
                    return prefix + player;
                },
                alertError(text) {
                    Swal.fire({
                        text: text,
                        icon: 'error',
                        timer: 1500,
                        showCloseButton: true,
                        showConfirmButton: false,
                        timerProgressBar: true,
                        toast: true,
                        position: 'center',
                    });
                },
                async playGame(cardId) {

                    try {

                        if (!this.isMyHandTurn()) {
                            return;
                        }

                        if (this.isPlaying) return;
                        this.isPlaying = true;

                        const formData = new FormData();
                        formData.append('card', cardId);

                        const res = await fetch(urls['play'], {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (res.ok) {
                            this.fetchGameData();
                        } else {
                            res.json().then(res => {
                                this.alertError(res.message);
                            });
                        }
                    } catch (err) {
                        this.alertError('خطا');
                    } finally {
                        this.isPlaying = false;
                    }
                },
                setQuote(quote) {
                    this.quote = quote;
                },
                async sendQuote(quote) {

                    oldQuote = this.quote;

                    try {

                        if (this.isSendingQuote) return;
                        this.isSendingQuote = true;

                        const formData = new FormData();
                        if (quote) {
                            formData.append('quote', quote);
                        }

                        const res = await fetch(urls['quote'], {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });

                        if (res.ok) {
                            this.players[this.circleIndexToPlayer(0)]['quote'] = quote;
                            this.$dispatch('close-quote-modal');
                            this.quote = quote;
                            if (quote) {
                                this.oldQuotes = this.oldQuotes.filter(x => x !== quote);
                                this.oldQuotes.unshift(quote);
                                this.oldQuotes = this.oldQuotes.slice(0, 10);
                            }
                        } else {
                            res.json().then(res => {
                                this.alertError(res.message);
                                this.quote = oldQuote;
                            });
                        }
                    } catch (err) {
                        this.alertError('خطا');
                        this.quote = oldQuote;
                    } finally {
                        this.isSendingQuote = false;
                    }
                }
            }
        }
    </script>
@endsection
