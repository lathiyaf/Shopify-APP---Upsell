<!DOCTYPE html>
<html>
<head>
    <title>Email-Automations</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            font-size: 0.875rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 300;
        }

        .text-blue {
            color: #5590F5;
        }

        .font-400 {
            font-weight: 400;
        }

        p {
            margin-top: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .box_shadow_reg {
            -webkit-box-shadow: 0px 4px 10px rgba(85, 144, 245, 0.16);
            box-shadow: 0px 4px 10px rgba(85, 144, 245, 0.16);
        }

        .border-radius-reg {
            border-radius: 20px;
        }

        .text-center {
            text-align: center !important;
        }

        .text-right {
            text-align: right !important;
        }

        .content_div_main {
            max-width: 590px;
            margin: auto;
        }

        table {
            border-spacing: 0;
            border-collapse: separate;
        }

        .sms-automation-on .middle_div .form_inner_div {
            background: #fff;
            margin: 30px 0;
        }

        .sms-automation-on .middle_div .form_inner_div .head_div {
            padding: 15px;
            color: #fff;
            background: #5590F5;
            border-radius: 20px 20px 0 0;
        }

        .email-automations .right_main_div .form_inner_div .body_div {
            padding: 0;
        }

        .sms-automation-on .middle_div .form_inner_div {
            background: #fff;
            margin: 30px 0;
        }

        .email-automations .right_main_div .form_inner_div .body_div .content_div {
            padding: 13px 15px;
        }

        .email-automations .right_main_div .form_inner_div .body_div .discount_div {
            padding: 12px 15px;
            background: #F5F6FA;
        }

        .email-automations .right_main_div .form_inner_div .body_div .cart_main_div {
            padding: 12px 15px;
        }

        .email-automations .right_main_div .form_inner_div .body_div .cart_main_div .table_div_inner .table tbody td {
            text-align: center;
            font-size: 14px;
        }
        table{
            width: 100%;
        }
        .table_div_inner {
            background: #fff;
            overflow: hidden;
            margin-top: 15px;
        }

        .table_div_inner .table .thead-blue th {
            color: #fff;
            background: #5590F5;
            border-color: #5590F5;
            font-size: 16px;
            font-weight: 400;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .btn_all {
            background: #5590F5;
            color: #fff;
            border-radius: 30px;
            padding: 8px 15px;
            min-width: 130px;
            cursor: pointer;
            border: none;
            outline: none;
            -webkit-transition: all .5s;
            transition: all .5s;
        }

        .text-gray {
            color: #707070;
        }

        .mx-auto {
            margin-left: auto !important;
            margin-right: auto !important;
        }

        .w-75 {
            width: 75% !important;
        }

        .email-automations .right_main_div .form_inner_div .body_div .discount_div h3 {
            margin: 0;
            font-size: 20px;
        }

        .table_div_inner .table tbody td {
            padding: 15px 10px;
            vertical-align: middle;
            font-size: 12px;
        }

        a {
            color: #5590F5;
            display: block;
        }

        .email-automations .right_main_div .form_inner_div .body_div .content_div .content_txt {
            line-height: 30px;
        }
        .after-cart-body td{
            text-align: left !important;
        }
        .campaign-btn{
            margin-bottom: 20px;
            text-align: center;
        }
        /*# sourceMappingURL=theme.css.map */
    </style>
</head>
<body>
<div class="content_div_main email-automations">
    <div class="container-mian sms-automation-on ml-auto">
        <div class="middle_div">
            <div class="right_main_div mt-4">
                <div class="form_inner_div border-radius-reg box_shadow_reg mt-1">
                    <div class="body_div pb-4">
                        <div class="content_div">
                            <h2 class="text-center mb-3 mt-2">{{$data['body_text']['header']}}</h2>
                            <p class="content_txt font-400 mb-0">{!! nl2br($data['body_text']['before_cart_body']) !!}</p>
                        </div>
                        <div class="discount_div">
                            <h3 class="text-center text-blue mb-0">{{ $data['body_text']['discount_banner_text'] }}</h3>
                        </div>
                        @if($data['automation_type'] == 'automation')
                            <div class="cart_main_div">
                            <p class="font-400">{{$data['body_text']['cart_title']}}</p>
                            <div class="table_div_inner box_shadow_reg border-radius-reg">
                                <table class="table mb-0 ">
                                    <thead class="thead-blue">
                                    <tr>
                                        <th>Image</th>
                                        <th>{{$data['body_text']['product_description']}}</th>
                                        <th>{{$data['body_text']['product_price']}}</th>
                                        <th>{{$data['body_text']['product_qty']}}</th>
                                        <th>{{$data['body_text']['product_total']}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $totalP = 0; ?>

                                    @foreach($data['line_items'] as $lineitem)
                                        <tr>
                                            <td>
                                                <div class="img_div_td">
                                                    @if($lineitem['image'] != null || $lineitem['image'] != '')
                                                            <img src="{{$lineitem['image']}}" id="img" width="150px;">
                                                    @else
                                                        <img src="{{ asset('images/place-img.png') }}" alt="{{$lineitem['title']}}"  width="150px;"/>
                                                    @endif
                                                </div>
                                            </td>
                                            <td><a href="#">{{$lineitem['title']}}</a></td>
                                            <td>{{$data['checkout']['currency_symbol']}}{{$lineitem['price']}}</td>
                                            <td>{{$lineitem['quantity']}}</td>
                                            <td>{{$data['checkout']['currency_symbol']}}{{$lineitem['total_price']}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5" class="pr-3"><p class="mb-0 font-400 text-right pr-1">Total : {{$data['checkout']['currency_symbol']}}{{$data['checkout']['total_line_items_price']}}</p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right pr-3">
                                            <a href="{{$data['cart_link']}}" target="_blank" class="btn_all">{{$data['body_text']['button_text']}}</a></td>
                                    </tr>
                                    <tr class="after-cart-body">
                                        <td colspan="5" class="text-left pr-3"><p>{!! nl2br($data['body_text']['after_cart_body']) !!}</p></td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="">
                                            <p class="w-75 mx-auto text-gray font-400">{!! nl2br($data['body_text']['footer']) !!}</p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @else
                        <div class="content_div">
                            <h2 class="text-center mb-3 mt-2">{!! nl2br($data['body_text']['after_cart_body']) !!}</h2>
                            <div class="campaign-btn mb-3">
                                <a href="{{$data['shop']['domain']}}" target="_blank" class="btn_all">{{$data['body_text']['button_text']}}</a>
                            </div>
                            <div class="content_txt font-400 mb-0 text-gray handle-content">{!! nl2br($data['body_text']['footer']) !!}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
