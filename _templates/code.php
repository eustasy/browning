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
    echo '&lt;h2&gt;Success! We managed to send the E-Mail.&lt;/h2&gt;'.PHP_EOL;
    echo '&lt;p class="sub-title"&gt;Thanks for believeing in us.&lt;/p&gt;'.PHP_EOL;
} else {
    echo '&lt;h2&gt;Sorry, we failed to send the E-Mail.&lt;/h2&gt;'.PHP_EOL;
    echo '&lt;p class="sub-title error"&gt;'.$Mail['Error'].'&lt;/p&gt;'.PHP_EOL;
    echo '&lt;!--'.PHP_EOL;
    var_dump($Mail);
    echo PHP_EOL.'--&gt;'.;
}
</code>
