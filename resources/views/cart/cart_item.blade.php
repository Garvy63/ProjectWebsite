<tr>
    <td>
        <div class="media">
            <div class="d-flex">
                <img src="{{ $item['product']->product_image
                    ? asset($item['product']->product_image)
                    : 'https://via.placeholder.com/100x100?text=No+Image' }}"
                    alt="{{ $item['product']->product_name }}">
            </div>
            <div class="media-body">
                <p>{{ $item['product']->product_name }}</p>
            </div>
        </div>
    </td>
    <td><h5>Rp{{ number_format($item['unit_cost'], 0, ',', '.') }}</h5></td>
    <td>
        <form action="{{ route('cart.add') }}" method="POST" class="d-flex">
            @csrf
            <input type="hidden" name="product_id" value="{{ $item['product']->product_id }}">
            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="input-text qty">
            <button type="submit" class="gray_btn">Update</button>
        </form>
    </td>
    <td><h5>Rp{{ number_format($item['item_total'], 0, ',', '.') }}</h5></td>
</tr>


