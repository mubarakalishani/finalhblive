<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form id="myForm" method="post" action="https://payeer.com/merchant/">
        <input type="hidden" name="m_shop" value="815329765">
        <input type="hidden" name="m_orderid" value="{{ $transactionId }}">
        <input type="hidden" name="m_amount" value="{{ $amount }}">
        <input type="hidden" name="m_curr" value="USD">
        <input type="hidden" name="m_desc" value="Deposit to handbucks.com for advertisement">
        <input type="hidden" name="m_sign" value="<?=$sign?>">
        <input type="submit" name="m_process" value="send" />
    </form>


    <script>
        // Automatically submit the form when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('myForm').submit();
        });
    </script>
</body>
</html>