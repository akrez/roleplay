@extends('layouts.app')

@section('header', __('GameHokm'))

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="POST" action="{{ route('game_hokms.store') }}">
                        @csrf
                        @foreach (['player_1_username', 'player_2_username', 'player_3_username', 'player_4_username'] as $playerUsername)
                            <div class="mb-3">
                                <div x-data="initFriends({{ json_encode($friends) }})" class="position-relative w-full" @click.outside="closeList()">
                                    <div class="input-group">
                                        <span class="input-group-text">نام کاربری کاربر</span>
                                        <input type="text" x-model="search" @click.stop="isVisible = true"
                                            name="{{ $playerUsername }}" @focus="isVisible = true" class="form-control"
                                            autocomplete="off">
                                    </div>
                                    <div class="list-group position-absolute w-100 z-3"
                                        x-show="isVisible && filteredFriends.length > 0" x-transition:enter.duration.200ms
                                        x-transition:leave.duration.200ms>
                                        <template x-for="filteredFriend in filteredFriends"
                                            :key="'filteredFriend' + filteredFriend.id">
                                            <button type="button" @click="setValue(filteredFriend.username)"
                                                class="list-group-item">
                                                <span x-text="filteredFriend.username"></span>
                                                <small class="text-muted ps-1 pe-1" x-text="filteredFriend.name"></small>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-success w-100">
                            ایجاد بازی جدید
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center m-0">
                    <thead class="table-dark">
                        <tr>
                            <th>صاحب بازی</th>
                            <th>Player 1</th>
                            <th>Player 2</th>
                            <th>Player 3</th>
                            <th>Player 4</th>
                            <th>برنده</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($game_hokms as $game_hokm)
                            <tr>
                                <td class="table-secondary">
                                    {{ $game_hokm['owner']['name'] }}
                                    <div class="text-sm text-muted">{{ $game_hokm['owner']['username'] }}</div>
                                </td>
                                @foreach (['player_1' => '', 'player_2' => 'table-secondary', 'player_3' => '', 'player_4' => 'table-secondary'] as $player => $playerClass)
                                    <td class="{{ $playerClass }}">
                                        {{ $game_hokm['players'][$player]['name'] }}
                                        @if ($game_hokm['players'][$player]['token'])
                                            <a class="btn btn-success btn-sm w-100"
                                                href="{{ route('game_hokms.board', [
                                                    'id' => $game_hokm['id'],
                                                    'player' => $player,
                                                    'token' => $game_hokm['players'][$player]['token'],
                                                ]) }}">
                                                ورود به بازی
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                                <td>
                                    @foreach ($game_hokm['winners'] as $winner)
                                        <div class="w-100">{{ $game_hokm['players'][$winner]['name'] }}</div>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function initFriends(friends) {
            return {
                search: '',
                friends: friends,
                isVisible: false,
                setValue(buttonText) {
                    this.search = buttonText;
                    this.isVisible = false;
                },
                closeList() {
                    this.isVisible = false;
                },
                get filteredFriends() {
                    const searchTerm = this.search.toLowerCase();
                    return this.friends.filter(friend => friend.name.toLowerCase().includes(searchTerm) || friend
                        .username.toLowerCase().includes(searchTerm));
                }
            }
        }
    </script>
@endsection
