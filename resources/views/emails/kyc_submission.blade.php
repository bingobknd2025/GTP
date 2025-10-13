<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f6f8;
      color: #333;
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .header {
      background-color: #4CAF50;
      padding: 20px;
      text-align: center;
      color: #fff;
    }

    .header h1 {
      margin: 0;
      font-size: 24px;
    }

    .body {
      padding: 30px 25px;
    }

    .body p {
      font-size: 16px;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    .info-box {
      background-color: #f1f1f1;
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .info-box p {
      margin: 5px 0;
    }

    .button {
      display: inline-block;
      background-color: #4CAF50;
      color: #fff;
      text-decoration: none;
      padding: 12px 25px;
      border-radius: 6px;
      font-weight: bold;
    }

    .footer {
      text-align: center;
      font-size: 13px;
      color: #777;
      padding: 15px;
    }

    @media screen and (max-width: 620px) {
      .container {
        margin: 20px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>{{ $heading }}</h1>
    </div>
    <div class="body">
      <p>{!! $bodyMessage !!}</p>

      @isset($customer)
      <div class="info-box">
        <p><strong>Customer Name:</strong> {{ $customer->fname }} {{ $customer->lname }}</p>
        <p><strong>Email:</strong> {{ $customer->email }}</p>
      </div>
      @endisset

      @isset($franchise)
      <div class="info-box">
        <p><strong>Franchise Name:</strong> {{ $franchise->name }}</p>
        <p><strong>Email:</strong> {{ $franchise->email }}</p>
      </div>
      @endisset


    </div>

    <div class="footer">
      &copy; {{ date('Y') }} Your Company. All rights reserved.
    </div>
  </div>
</body>

</html>