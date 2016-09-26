<h2>Example Code</h2>
<br>
<code class="background-midnight-blue color-white rounded whole">
require '_settings/browning.default.php';
include '_settings/browning.custom.php';
require '_functions/browning/function.browning.php';

$Mail = Browning(
    'recepient@example.com',
    'Message Subject',
    'Text or HTML Body',
    'Sender Name',
    'reply-to@example.com',
    true
);

if ( $Mail['Success'] ) {
    echo '<h2>Success! We managed to send the E-Mail.</h2>'.PHP_EOL;
    echo '<p class="sub-title">Thanks for believeing in us.</p>'.PHP_EOL;
} else {
    echo '<h2>Sorry, we failed to send the E-Mail.</h2>'.PHP_EOL;
    echo '<p class="sub-title error">'.$Mail['Error'].'</p>'.PHP_EOL;
    echo '<!--'.PHP_EOL;
    var_dump($Mail);
    echo PHP_EOL.'-->'.;
}
</code>
