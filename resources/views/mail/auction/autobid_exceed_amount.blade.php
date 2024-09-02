<pre>
-----------------------------------------------------
                   AuctionApp
-----------------------------------------------------
                 AUTOBID WARNING
-----------------------------------------------------
You have insufficient balance to place an autobid
on the item {{ $presenter->getProductName() }}.

-----------------------------------------------------
Current autobid balance: {{ $presenter->getAutoBidBalance() }}
Required bid: {{ $presenter->getBidAmount() }}
-----------------------------------------------------

Contact Us:
Customer Service: support@auctionapp.com
Terms and Conditions Apply.

Thank you for your participation!

-----------------------------------------------------
</pre>

<a href="{{$presenter->getBidUrl()}}">Review the auction</a>
<a href="{{$presenter->getAutoBidConfigurationUrl()}}">Configure autobid</a>

Thanks,<br>
{{ config('app.name') }}
