<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <center>
        Customer Name   : {{$data->user->name}} <br><br><br>
        Customer Address: {{$data->receiver_address}} <br><br><br>
        Customer Phone  : {{$data->receiver_phone}} <br><br><br>
        Product         : {{$data->product->product_title}} <br><br><br>
        Product Price   : $ {{$data->product->product_prices}} <br><br><br>
    </center>
</body>
</html>