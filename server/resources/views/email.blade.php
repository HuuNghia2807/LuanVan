<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="utf-8" />
    <title>Send Mail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

</head>

<body>
    <h3>{{ $mailData['greeting'] }}</h3>
    <h3>{{ $mailData['lastline'] }}</h3>

    <ul>
        @foreach ($mailData['body'] as $product)
        <li>
            Sản phẩm: {{ $product['product_name'] }}
            <br>
            Số Lượng: {{ $product['product_quantity'] }}
            <br>
            Giá: {{ $product['product_price'] }}
        </li>
        @endforeach
    </ul>

    <h3>Tổng Giá Trị Đơn Hàng: {{ number_format($mailData['total_price']) }} VNĐ</h3>
    <h3>{{ $mailData['actiontext'] }}</h3>
    <a href="{{ $mailData['actionurl'] }}">King Shoes</a>
</body>

</html>