@extends('layouts.game')

@section('header', __('GameHokm'))

@php
    $initData = [
        'urls' => [
            'users' => [
                'filter' => route('users.filter'),
            ],
            'game_hokms' => [
                'index' => route('game_hokms.index'),
                'store' => route('game_hokms.store'),
            ],
            'assets' => [
                'bg' => url('assets/bg.jpg'),
            ],
        ],
    ];
@endphp

@section('content')
    <div class="container-fluid user-select-none flex-fill bg-black" x-data="gameData()" x-init="initData({{ json_encode($initData) }})"
        x-transition>
        <div class="row h-100">
            <div style="background-image: url('{{ $initData['urls']['assets']['bg'] }}'); background-repeat: repeat; background-position: center;"
                class="col-xl-4 offset-xl-4 col-lg-6 offset-lg-3 col-md-6 offset-md-3 col-sm-8 offset-sm-2 col-12
                    d-flex flex-column justify-content-between
                    text-center p-3">
                <div class="row">

                    <div class="col-12">
                        <div
                            class="border border-dark border-1 w-100 bg-success-subtle rounded mb-3 px-2 py-1 d-flex flex-row justify-content-between align-items-stretch text-center">
                            <div class="d-flex flex-row col justify-content-end align-items-center"></div>
                            <div class="d-flex flex-column col justify-content-end align-items-center">
                                <div class="fs-7" x-text="user?.name"></div>
                                <div class="fs-8" x-text="user?.username"></div>
                            </div>
                            <div class="d-flex flex-row col justify-content-end align-items-center">
                                <a class="fs-8 rounded text-decoration-none text-dark" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-1">
                        <div class="row g-0">
                            <div class="col-2">
                            </div>
                            <div class="col-8">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        نام یا نام کاربری
                                    </span>
                                    <input type="text" class="form-control " x-model="indexUsersData.filter">
                                    <button class="btn text-bg-light" @click="indexUsers()"
                                        :disabled="indexUsersData.isIndexingUsers">
                                        <i :class="indexUsersData.isIndexingUsers ? 'bi-clock' : 'bi-search'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-1">
                        <div class="row g-0">
                            <div class="col-3">
                            </div>
                            <div class="col-6 mb-1">
                                <div class="input-group cursor-pointer" @click.outside="createData.show.player_1=false">
                                    <span class="input-group-text p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_1 }"
                                        @click="createData.show.player_1=!createData.show.player_1; createData.lastShow='player_1';"
                                        :disabled="!createData.player_1">
                                        <i :class="createData.show.player_1 ? 'bi-chevron-down' : 'bi-chevron-left'"></i>
                                    </span>
                                    <span class="input-group-text flex-column text-center flex-grow-1 p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_1 }"
                                        @click="createData.show.player_1=!createData.show.player_1; createData.lastShow='player_1';">
                                        <div class="m-0 p-0 text-truncate fs-7"
                                            x-text="createData.player_1 ? createData.player_1.name : 'ㅤ'">
                                        </div>
                                        <div class="m-0 p-0 text-truncate fs-8"
                                            x-text="createData.player_1 ? createData.player_1.username : 'ㅤ'">
                                        </div>
                                    </span>
                                    <button class="btn btn-danger p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_1 }"
                                        :disabled="!createData.player_1" @click="createData.player_1 = null">
                                        <i class="bi-trash"></i>
                                    </button>
                                </div>
                                <div class="position-relative">
                                    <ul class="list-group position-absolute right-0 left-0 w-100 z-3"
                                        x-show="createData.show.player_1">
                                        <template x-for="user in indexUsersData.users">
                                            <li class="list-group-item p-1 cursor-pointer"
                                                @click="addPlayer('player_1', user)">
                                                <div class="m-0 p-0 text-truncate fs-7" x-text="user?.name"></div>
                                                <div class="m-0 p-0 text-truncate fs-8" x-text="user?.username"></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row g-1">
                            <div class="col-6 mb-1">
                                <div class="input-group cursor-pointer" @click.outside="createData.show.player_4=false">
                                    <span class="input-group-text p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_4 }"
                                        @click="createData.show.player_4=!createData.show.player_4; createData.lastShow='player_4';"
                                        :disabled="!createData.player_4">
                                        <i :class="createData.show.player_4 ? 'bi-chevron-down' : 'bi-chevron-left'"></i>
                                    </span>
                                    <span class="input-group-text flex-column text-center flex-grow-1 p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_4 }"
                                        @click="createData.show.player_4=!createData.show.player_4; createData.lastShow='player_4';">
                                        <div class="m-0 p-0 text-truncate fs-7"
                                            x-text="createData.player_4 ? createData.player_4.name : 'ㅤ'">
                                        </div>
                                        <div class="m-0 p-0 text-truncate fs-8"
                                            x-text="createData.player_4 ? createData.player_4.username : 'ㅤ'">
                                        </div>
                                    </span>
                                    <button class="btn btn-danger p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_4 }"
                                        :disabled="!createData.player_4" @click="createData.player_4 = null">
                                        <i class="bi-trash"></i>
                                    </button>
                                </div>
                                <div class="position-relative">
                                    <ul class="list-group position-absolute right-0 left-0 w-100 z-3"
                                        x-show="createData.show.player_4">
                                        <template x-for="user in indexUsersData.users">
                                            <li class="list-group-item p-1 cursor-pointer"
                                                @click="addPlayer('player_4', user)">
                                                <div class="m-0 p-0 text-truncate fs-7" x-text="user?.name"></div>
                                                <div class="m-0 p-0 text-truncate fs-8" x-text="user?.username"></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-6 mb-1">
                                <div class="input-group cursor-pointer" @click.outside="createData.show.player_2=false">
                                    <span class="input-group-text p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_2 }"
                                        @click="createData.show.player_2=!createData.show.player_2; createData.lastShow='player_2';"
                                        :disabled="!createData.player_2">
                                        <i :class="createData.show.player_2 ? 'bi-chevron-down' : 'bi-chevron-left'"></i>
                                    </span>
                                    <span class="input-group-text flex-column text-center flex-grow-1 p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_2 }"
                                        @click="createData.show.player_2=!createData.show.player_2; createData.lastShow='player_2';">
                                        <div class="m-0 p-0 text-truncate fs-7"
                                            x-text="createData.player_2 ? createData.player_2.name : 'ㅤ'">
                                        </div>
                                        <div class="m-0 p-0 text-truncate fs-8"
                                            x-text="createData.player_2 ? createData.player_2.username : 'ㅤ'">
                                        </div>
                                    </span>
                                    <button class="btn btn-danger p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_2 }"
                                        :disabled="!createData.player_2" @click="createData.player_2 = null">
                                        <i class="bi-trash"></i>
                                    </button>
                                </div>
                                <div class="position-relative">
                                    <ul class="list-group position-absolute right-0 left-0 w-100 z-3"
                                        x-show="createData.show.player_2">
                                        <template x-for="user in indexUsersData.users">
                                            <li class="list-group-item p-1 cursor-pointer"
                                                @click="addPlayer('player_2', user)">
                                                <div class="m-0 p-0 text-truncate fs-7" x-text="user?.name"></div>
                                                <div class="m-0 p-0 text-truncate fs-8" x-text="user?.username"></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row g-0">
                            <div class="col-3">
                            </div>
                            <div class="col-6">
                                <div class="input-group cursor-pointer" @click.outside="createData.show.player_3=false">
                                    <span class="input-group-text p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_3 }"
                                        @click="createData.show.player_3=!createData.show.player_3; createData.lastShow='player_3';"
                                        :disabled="!createData.player_3">
                                        <i :class="createData.show.player_3 ? 'bi-chevron-down' : 'bi-chevron-left'"></i>
                                    </span>
                                    <span class="input-group-text flex-column text-center flex-grow-1 p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_3 }"
                                        @click="createData.show.player_3=!createData.show.player_3; createData.lastShow='player_3';">
                                        <div class="m-0 p-0 text-truncate fs-7"
                                            x-text="createData.player_3 ? createData.player_3.name : 'ㅤ'">
                                        </div>
                                        <div class="m-0 p-0 text-truncate fs-8"
                                            x-text="createData.player_3 ? createData.player_3.username : 'ㅤ'">
                                        </div>
                                    </span>
                                    <button class="btn btn-danger p-1"
                                        :class="{ 'rounded-bottom-0': createData.show.player_3 }"
                                        :disabled="!createData.player_3" @click="createData.player_3 = null">
                                        <i class="bi-trash"></i>
                                    </button>
                                </div>
                                <div class="position-relative">
                                    <ul class="list-group position-absolute right-0 left-0 w-100 z-3"
                                        x-show="createData.show.player_3">
                                        <template x-for="user in indexUsersData.users">
                                            <li class="list-group-item p-1 cursor-pointer"
                                                @click="addPlayer('player_3', user)">
                                                <div class="m-0 p-0 text-truncate fs-7" x-text="user?.name"></div>
                                                <div class="m-0 p-0 text-truncate fs-8" x-text="user?.username"></div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <div class="row g-0">
                            <div class="col-3">
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-success w-100 d-flex align-items-center"
                                    @click="createGame()"
                                    :disabled="!(createData.player_1 && createData.player_2 && createData.player_3 && createData
                                        .player_4)">
                                    <span class="flex-grow-1">
                                        ایجاد بازی
                                    </span>
                                    <i
                                        :class="'flex-grow-0 ' + (createData.isCreatingGame ? 'bi-clock' : 'bi-plus-lg')"></i>
                                </button>

                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-1">

                        <template x-for="game in indexGamesData.games">
                            <div class="row d-flex justify-content-center fs-6 mb-3">
                                <div class="col-10">
                                    <div class="row g-1">
                                        <div class="col-4">
                                        </div>
                                        <div class="col-4">
                                            <div class="text-bg-light rounded w-100 p-1"
                                                x-text="game.players['player_1'].name">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                        </div>

                                        <div class="col-4">
                                            <div class="text-bg-light rounded w-100 p-1"
                                                x-text="game.players['player_4'].name">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <a class="btn btn-info w-100 p-1 fs-6" x-show="getGameLink(game)"
                                                x-bind:href="getGameLink(game)">
                                                <i class="bi-door-open"></i>
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-bg-light rounded w-100 p-1"
                                                x-text="game.players['player_2'].name">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                        </div>
                                        <div class="col-4">
                                            <div class="text-bg-light rounded w-100 p-1"
                                                x-text="game.players['player_3'].name">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                </div>
            </div>
        </div>
        <script>
            function gameData() {
                return {
                    indexUsersData: null,
                    indexGamesData: null,
                    createData: null,
                    gameData: [],
                    user: null,
                    urls: null,
                    resetIndexUsersData() {
                        this.indexUsersData = {
                            isIndexingUsers: false,
                            users: [this.user],
                            filter: '',
                        }
                    },
                    resetIndexGamesData() {
                        this.indexGamesData = {
                            isIndexingGames: false,
                            games: []
                        }
                    },
                    resetCreateData() {
                        this.createData = {
                            players: {
                                player_1: null,
                                player_2: null,
                                player_3: null,
                                player_4: null,
                            },
                            show: {
                                player_1: null,
                                player_2: null,
                                player_3: null,
                                player_4: null
                            },
                            lastShow: null,
                            isCreatingGame: false,
                        };
                    },
                    async initData(initData) {
                        this.urls = initData.urls;
                        //
                        this.resetIndexGamesData();
                        this.resetIndexUsersData();
                        this.resetCreateData();
                        //
                        await this.indexGames();
                        await this.indexUsers();
                    },
                    getGameLink(game, playerIndex) {
                        url = null;
                        ['player_1', 'player_2', 'player_3', 'player_4'].forEach((playerIndex) => {
                            if (game.player_index == playerIndex) {
                                url = this.urls['game_hokms']['index'] + '/' +
                                    game.id + '/board/' + playerIndex + '/' + game.token;
                            }
                        });
                        return url;
                    },
                    addPlayer(player, user) {
                        this.createData[player] = user;
                    },
                    async indexGames() {
                        try {
                            if (this.indexGamesData.isIndexingGames) return;
                            this.indexGamesData.isIndexingGames = true;

                            const gamesRes = await fetch(this.urls['game_hokms']['index'], {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const gamesResJson = await gamesRes.json();

                            if (gamesRes.ok) {
                                this.indexGamesData.games = gamesResJson.data.game_hokms;
                                this.user = gamesResJson.data.user;
                            } else {
                                this.alertError(gamesResJson.message);
                            }

                        } catch (err) {
                            console.log(err);
                            this.alertError('خطا');
                        } finally {
                            this.indexGamesData.isIndexingGames = false;
                        }
                    },
                    async indexUsers() {
                        try {
                            if (this.indexUsersData.isIndexingUsers) return;
                            this.indexUsersData.isIndexingUsers = true;

                            const params = new URLSearchParams({
                                username: this.user.username,
                                filter: this.indexUsersData.filter
                            });

                            const usersRes = await fetch(this.urls['users']['filter'] + '?' + params.toString(), {
                                method: 'GET',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            const usersResJson = await usersRes.json();

                            if (usersRes.ok) {
                                this.indexUsersData.users = usersResJson.data.users;
                                this.indexUsersData.users.unshift(this.user);
                                if (this.createData.lastShow) {
                                    this.createData.show[this.createData.lastShow] = true;
                                }
                            } else {
                                this.alertError(usersResJson.message);
                            }

                        } catch (err) {
                            console.log(err);
                            this.alertError('خطا');
                        } finally {
                            this.indexUsersData.isIndexingUsers = false;
                        }
                    },
                    async createGame() {
                        try {
                            if (this.createData.isCreatingGame) return;
                            this.createData.isCreatingGame = true;

                            const formData = new FormData();
                            formData.append('username', this.user.username);
                            formData.append('player_1_username', this.createData.player_1.username);
                            formData.append('player_2_username', this.createData.player_2.username);
                            formData.append('player_3_username', this.createData.player_3.username);
                            formData.append('player_4_username', this.createData.player_4.username);

                            const gameRes = await fetch(this.urls['game_hokms']['store'], {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            });

                            const gameResJson = await gameRes.json();

                            if (gameRes.ok) {
                                this.alertSuccess(gameResJson.message);
                                this.resetCreateData();
                                this.indexGames();
                            } else {
                                this.alertError(gameResJson.message);
                            }

                        } catch (err) {
                            console.log(err);
                            this.alertError('خطا');
                        } finally {
                            this.createData.isCreatingGame = false;
                        }
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
                    alertSuccess(text) {
                        Swal.fire({
                            text: text,
                            icon: 'success',
                            timer: 1500,
                            showCloseButton: true,
                            showConfirmButton: false,
                            timerProgressBar: true,
                            toast: true,
                            position: 'center',
                        });
                    },
                };
            }
        </script>
    @endsection
