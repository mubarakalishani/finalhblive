<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="myForm"  action="https://perfectmoney.com/api/step1.asp" method="POST">
        <p>
            <input type="hidden" name="PAYEE_ACCOUNT" value="U43626532">
            <input type="hidden" name="PAYEE_NAME" value="Handbucks.com">
            <input type="hidden" name="PAYMENT_AMOUNT" value="{{ $amount }}">
            <input type="hidden" name="PAYMENT_ID" value="{{ $transactionId }}">
            <input type="hidden" name="PAYMENT_UNITS" value="USD">
            <input type="hidden" name="STATUS_URL" value="https://handbucks.com/webhook/perfectmoney">
            <input type="hidden" name="PAYMENT_URL_METHOD" value="GET"> 
            <input type="hidden" name="PAYMENT_URL" value="https://handbucks.com/advertiser/deposit">
            <input type="hidden" name="NOPAYMENT_URL_METHOD" value="GET"> 
            <input type="hidden" name="NOPAYMENT_URL" value="https://handbucks.com/advertiser/deposit">
            <input type="hidden" name="BAGGAGE_FIELDS" value="USER_ID">
            <input type="hidden" name="USER_ID" value="{{ auth()->user()->unique_user_id }}">
            <input type="submit" name="PAYMENT_METHOD" value="PerfectMoney account">
        </p>
        </form>

        <script>
            // Automatically submit the form when the page loads
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('myForm').submit();
            });
        </script>
        
</body>
</html>