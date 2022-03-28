<?php
$number = '232323232323';
$body = 'Testing';

header('Content-Type: text/xml');
?>

<Response>
    <Message>
        Hello <?php echo $number ?>.
        You said <?php echo $body ?>
    </Message>
</Response>
