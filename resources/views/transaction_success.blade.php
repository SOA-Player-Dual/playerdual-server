<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title></title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: rgb(241, 242, 245);
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div style="
                width: 100%;
                height: 8px;
                background-color: rgb(100, 207, 161);
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            "></div>
    <div style="
                font-family: Helvetica, Arial, sans-serif;
                width: 100%;
                height: 100vh;
                line-height: 2;
                position: relative;
            ">
        <div style="
                    position: fixed;
                    margin: auto;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    width: 80%;
                    height: fit-content;
                    padding: 0 12px;
                    border: 1px solid rgba(22, 24, 35, 0.09);
                    border-radius: 8px;
                    background-color: rgb(255, 255, 255);
                ">
            <div style="border-bottom: 1px solid #eee">
                <img src="{{asset('/logo.png')}}" style="width: 110px; height: 110px; display: block" alt="" />
            </div>

            <div style="text-align: center; padding: 8px">
                <div style="
                            width: 110px;
                            height: 110px;
                            border-radius: 50%;
                            text-align: center;
                            background-color: rgb(100, 207, 161);
                            display: inline-block;
                        ">
                    <div style="
                                display: inline-block;
                                line-height: 110px;
                                font-size: 80px;
                                color: #fff;
                            ">
                        &#10003;
                    </div>
                </div>

                <div style="font-size: 1.5rem; color: rgb(100, 207, 161)">
                    {{$mailData['type']}} thành công!
                </div>
            </div>

            <div style="
                        font-size: 1.1rem;
                        font-weight: 500;
                        color: rgb(100, 207, 161);
                    ">
                Thông tin {{$mailData['type']}}
            </div>
            <div style="margin-left: 32px">
                <div>Họ và tên: {{$mailData['name']}}</div>
                <div>Số tiền bằng số: {{$mailData['amountInNumber']}}</div>
                <div>Số tiền bằng chữ: {{$mailData['amountInWord']}}</div>
                <div>Phí: {{$mailData['fee']}}</div>
            </div>
            <hr style="width: 20%; opacity: 0.4" />
            <div style="padding-top: 16px">
                <p style="font-size: 0.9em">
                    Trân trọng,<br />Playerdual Team
                </p>
                <hr style="border: none; border-top: 1px solid #eee" />
                <div style="
                            float: center;
                            padding: 8px 0;
                            color: #aaa;
                            font-size: 0.8em;
                            line-height: 1;
                            font-weight: 300;
                            text-align: center;
                        ">
                    <p style="margin: 0; padding-bottom: 8px">
                        Copyright © 2022 Dev Team
                    </p>
                    <p style="margin: 0; padding-bottom: 8px">
                        19, Nguyễn Hữu Thọ, phường Tân Phong, quận 7
                    </p>
                    <p style="margin: 0; padding-bottom: 8px">
                        Thành phố Hồ Chí Minh
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="./main.js"></script>
</body>

</html>