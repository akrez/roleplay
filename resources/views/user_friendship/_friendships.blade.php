<thead class="table-dark">
    <tr>
        <th colspan="2">{{ $first_column_title }}</th>
        <th>وضعیت</th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
</thead>
<tbody>
    @if (!$friendships)
        <tr>
            <td colspan="99">
                -
            </td>
        </tr>
    @else
        @foreach ($friendships as $friendship)
            <tr>
                <td>
                    {{ $friendship[$first_column_key]['name'] }}
                </td>
                <td>
                    <small class="text-muted">{{ $friendship[$first_column_key]['username'] }}</small>
                </td>
                <td>
                    {{ $friendship['status']['trans'] }}
                </td>
                <td>
                    @if ($friendship['can_accept'])
                        <form
                            action="{{ route('user_friendships.status', ['id' => $friendship['id'], 'status' => 'ACCEPTED']) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                قبول
                            </button>
                        </form>
                    @endif
                </td>
                <td>
                    @if ($friendship['can_block'])
                        <form
                            action="{{ route('user_friendships.status', ['id' => $friendship['id'], 'status' => 'BLOCKED']) }}"
                            method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                بلاک
                            </button>
                        </form>
                    @endif
                </td>
                <td>
                    @if ($friendship['can_delete'])
                        <form action="{{ route('user_friendships.destroy', ['id' => $friendship['id']]) }}"
                            method="POST">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm w-100">
                                حذف
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
</tbody>
