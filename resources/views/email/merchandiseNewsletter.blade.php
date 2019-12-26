<h1>Hi {{ $User->nickname }}，以下是最新商品</h1>

<table border="1">
    <tr>
        <th>名稱</th>
        <th>價格</th>
    </tr>
    @foreach($MerchandiseCollection as $Merchandise)
        <tr>
            <td>
                <a href="{{ url('/merchandise/' . $Merchandise->id) }}">
                    {{ $Merchandise->name }}
                </a>
            </td>
            <td>
                {{ $Merchandise->price }}
            </td>
        </tr>
        @endforeach
</table>
